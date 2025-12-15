<?php

namespace App\Http\Controllers;

use App\Http\Requests\DuplikasiDataRequest;
use App\Models\Tenant;
use App\Services\TenantQueryGeneratorService;
use Database\Seeders\DefautTenantTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * DuplikasiController
 * 
 * Controller untuk menduplikasi data dalam aplikasi yang sama berdasarkan tenant.
 * Fitur ini memungkinkan pengguna untuk:
 * 1. Menentukan kode tenant tujuan
 * 2. Menentukan rentang ID data yang akan diduplikasi
 * 3. Menduplikasi data ke tenant baru
 * 4. Menyesuaikan ID dan foreign key menggunakan rumus: original_id + id_start_range
 */
class DuplikasiController extends Controller
{
    private $tableWithTenantId = [];

    private $tableWithoutIdColumn = [
        'model_has_roles',
        'model_has_permissions',
        'role_has_permissions',
        'password_resets',
    ];  
    private $tenantQueryGenerator;
    public function __construct(){
        $this->tenantQueryGenerator = new TenantQueryGeneratorService();
        $this->tableWithTenantId = array_diff(DefautTenantTable::HAS_TENANT_COLUMN, $this->tableWithoutIdColumn);
    }
    /**
     * Show the duplication form
     */
    public function showForm()
    {
        return view('duplikasi.index');
    }

    /**
     * Handle the duplication request
     */
    public function duplicate(DuplikasiDataRequest $request)
    {
        $tenantCode = $request->input('tenant_code');
        $idStartRange = $request->input('id_start_range');
        $idEndRange = $request->input('id_end_range');
        $id = $request->input('id');
        // Get the current tenant to make sure we're getting the right data
        $currentTenant = app('current_tenant');
        if (!$currentTenant) {
            return redirect()->back()->withErrors(['tenant_code' => 'No current tenant found']);
        }
        
        // Get or create the new tenant based on the provided code
        $newTenant = DB::table('tenants')->where('code', $tenantCode)->when(!empty($id), static fn($q) => $q->orWhere('id', $id))->first();
        if ($newTenant) {
            return redirect()->back()->withErrors(['error' => 'Kode desa sudah ada, jadi tidak bisa dilakukan duplikasi']);
        }

        // Perform duplication
        try {
            // Start transaction to ensure data consistency
            DB::beginTransaction();
            // Create a new tenant with default values
            $tenantData = [
                'code' => $tenantCode,
                'name' => 'Tenant ' . $tenantCode,
                'id_start_range' => $idStartRange,
                'id_end_range' => $idEndRange,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if($id){
                $tenantData['id'] = $id;
            }
            $newTenantId = DB::table('tenants')->insertGetId($tenantData);
            // Get the newly created tenant
            $newTenant = DB::table('tenants')->where('id', $newTenantId)->first();
            $this->performDuplication($currentTenant, $newTenant);
            // generate sql backup untuk digabungkan pada sistem PBB lain
            $sqlContent = $this->generateSqlBackup($newTenant);
            // bersihkan data kembali
            Tenant::where('id', $newTenant->id)->delete();            
            DB::commit();
            $filename = 'tenant_' . $newTenant->id . '_queries_insert_' . now()->format('Y-m-d_H-i-s') . '.sql';
            return response()->streamDownload(function() use($sqlContent){
                echo $sqlContent;
            }, $filename, ['Content-Type' => 'application/sql']);
        } catch (\Exception $e) {
            Log::error('Duplikasi data gagal: ' . $e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Gagal menduplikasi data: ' . $e->getMessage()]);
        }
    }

    /**
     * Duplicate a specific table with the given parameters
     */
    private function duplicateTable($table, $currentTenantId, $newTenantId, $selisihIdStartRange)
    {
        // Get the list of columns for the table
        $columns = Schema::getColumnListing($table);

        // Remove tenant_id from columns if it exists to avoid conflicts during duplication
        $columns = array_filter($columns, function ($column) {
            return !in_array($column, ['id', 'tenant_id']);
        });

        // Convert back to indexed array
        $columns = array_values($columns);

        if (!empty($columns)) {
            // Build the column list for the INSERT statement
            $columnList = implode('`, `', $columns);
            $columnListWithTenant = '`id`, `' . $columnList . '`, `tenant_id`';

            // Build the SELECT statement
            $selectList = implode('`, `', $columns);
            $selectWithTenant = '(id + ' . $selisihIdStartRange . ') as `id`, `' . $selectList . '`, ' . $newTenantId . ' as `tenant_id`';

            // Execute the INSERT ... SELECT query to duplicate data
            $query = "INSERT INTO `{$table}` ({$columnListWithTenant})
                   SELECT {$selectWithTenant}
                   FROM `{$table}`
                   WHERE `tenant_id` = ?";

            DB::statement($query, [$currentTenantId]);
            Log::info('duplikasi tabel '.$table,['query' => $query, 'bindings' => [$currentTenantId]]);
            
        }
    }

    /**
     * Perform the actual duplication with foreign key handling
     */
    private function performDuplication($currentTenant, $newTenant)
    {
        try {
            $selisihIdStartRange = ($newTenant->id_start_range - $currentTenant->id_start_range);
            foreach ($this->tableWithTenantId as $table) {
                $this->duplicateTable($table, $currentTenant->id, $newTenant->id,$selisihIdStartRange);
            }

            foreach (array_merge($this->tableWithTenantId, $this->tableWithoutIdColumn) as $table) {
                $this->updateForeignKey($table, $newTenant->id,$selisihIdStartRange);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function updateForeignKey($table, $newTenantId, $selisihIdStartRange){
        // Get the list of columns for the table
        $columns = Schema::getColumnListing($table);

        // Remove tenant_id from columns if it exists to avoid conflicts during duplication
        $columns = array_filter($columns, function ($column) {
            return !in_array($column, ['id', 'tenant_id']);
        });

        // Convert back to indexed array
        $columns = array_values($columns);        
        try {
            $foreignKey = array_filter($columns, function ($column) {
                return \Illuminate\Support\Str::endsWith($column,'_id');
            });
            if($foreignKey){
                foreach ($foreignKey as $key => $column) {
                    $query = 'update '.$table.' set '.$column.'= ('.$column.' + ?) where tenant_id = ?';
                    DB::statement($query, [$selisihIdStartRange, $newTenantId]);
                    Log::info('update  kolom '.$column.' tabel '.$table,['query' => $query, 'bindings' => [$newTenantId]]);
                }    
            }
        }catch (\Exception $e) {

        }
    }

    private function generateSqlBackup($tenant){                     
        return $this->tenantQueryGenerator->generateSqlFileContent($tenant->id, null, 'insert');    
    }
}

