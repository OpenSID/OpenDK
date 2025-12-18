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
        $idStartRange = $request->input('id_start_range');
        $idEndRange = $request->input('id_end_range');
        $id = $request->input('id');
        // Get the current tenant to make sure we're getting the right data
        $currentTenant = app('current_tenant');
        if (!$currentTenant) {
            return redirect()->back()->withErrors(['kode_kecamatan' => 'No current tenant found']);
        }                

        // Perform duplication
        try {
            // Start transaction to ensure data consistency
            DB::beginTransaction();
            // Create a new tenant with default values
            $currentTenant = app('current_tenant');
            $tenantData = [
                'kode_kecamatan' => 'temp-'.$currentTenant->kode_kecamatan,
                'name' => $currentTenant->name,
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
        $primaryKey = 'id';
        switch($table){
            case 'das_artikel_kategori':
                $primaryKey = 'id_kategori';
                break;
            case 'das_counter_page_visitor':
                $primaryKey = '';
                break;
            default:
        }
        
        // Remove tenant_id from columns if it exists to avoid conflicts during duplication
        $columns = array_filter($columns, function ($column)use($primaryKey) {
            return !in_array($column, [$primaryKey, 'tenant_id']);
        });

        // Convert back to indexed array
        $columns = array_values($columns);
        Log::info('columns tabel '.$table,['columns' => $columns]);
        if (!empty($columns)) {
            // Build the column list for the INSERT statement
            $columnList = implode('`, `', $columns);
            $columnListWithTenant = '`'.$primaryKey.'`, `' . $columnList . '`, `tenant_id`';

            // Build the SELECT statement
            $selectList = implode('`, `', $columns);
            $selectWithTenant = '('.$primaryKey.' + ' . $selisihIdStartRange . ') as `'.$primaryKey.'`, `' . $selectList . '`, ' . $newTenantId . ' as `tenant_id`';
            if(!$primaryKey){
                $columnListWithTenant = '`' . $columnList . '`, `tenant_id`';
                $selectWithTenant = '`' . $selectList . '`, ' . $newTenantId . ' as `tenant_id`';
            }
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
                    // Update foreign key values with the offset
                    $query = 'update '.$table.' set '.$column.'= ('.$column.' + ?) where tenant_id = ?';
                    DB::statement($query, [$selisihIdStartRange, $newTenantId]);
                    Log::info('update  kolom '.$column.' tabel '.$table,['query' => $query, 'bindings' => [$newTenantId]]);
                    
                    // Update tenant_id for related data if the column references a table with tenant_id
                    $this->updateRelatedTenantId($table, $column, $newTenantId, $selisihIdStartRange);
                }
            }
        }catch (\Exception $e) {
            Log::error('Error updating foreign keys for table '.$table.': '.$e->getMessage());
        }
    }
    
    /**
     * Update tenant_id for related data to ensure data consistency
     */
    private function updateRelatedTenantId($table, $foreignKeyColumn, $newTenantId, $selisihIdStartRange)
    {
        try {
            // Get the referenced table name from the foreign key column
            $referencedTable = $this->getReferencedTableFromForeignKey($table, $foreignKeyColumn);
            
            if ($referencedTable && in_array($referencedTable, $this->tableWithTenantId)) {
                // Update tenant_id for records in the referenced table that match the foreign keys
                $query = "UPDATE `{$referencedTable}` SET tenant_id = ? WHERE id IN (
                    SELECT {$foreignKeyColumn} FROM `{$table}` WHERE tenant_id = ?
                )";
                
                DB::statement($query, [$newTenantId, $newTenantId]);
                Log::info('update tenant_id tabel '.$referencedTable.' berdasarkan '.$table.'.'.$foreignKeyColumn,[
                    'query' => $query,
                    'bindings' => [$newTenantId, $newTenantId]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating related tenant_id for '.$table.'.'.$foreignKeyColumn.': '.$e->getMessage());
        }
    }
    
    /**
     * Get the referenced table name from a foreign key column
     */
    private function getReferencedTableFromForeignKey($table, $foreignKeyColumn)
    {
        // Common foreign key to table mappings
        $mappings = [
            'user_id' => 'users',
            'penduduk_id' => 'das_penduduk',
            'kategori_id' => 'das_artikel_kategori',
            'id_kategori' => 'das_artikel_kategori',
            'id_pembangunan' => 'das_pembangunan',
            'id_suplemen' => 'das_suplemen',
            'id_lembaga' => 'das_lembaga',
            'id_claster' => 'das_claster',
            'id_penduduk' => 'das_penduduk',
            'id_keluarga' => 'das_keluarga',
            'id_desa' => 'config',
            'id_kecamatan' => 'ref_kecamatan',
            'id_kabupaten' => 'ref_kabupaten',
            'id_provinsi' => 'ref_provinsi',
            'role_id' => 'roles',
            'permission_id' => 'permissions',
            'album_id' => 'albums',
            'parent_id' => $table, // Self-referencing
        ];
        
        // Remove _id suffix to get the base name
        $baseName = str_replace('_id', '', $foreignKeyColumn);
        
        // Check if we have a direct mapping
        if (isset($mappings[$foreignKeyColumn])) {
            return $mappings[$foreignKeyColumn];
        }
        
        // Try to infer the table name from the column name
        if (Schema::hasTable('das_' . $baseName)) {
            return 'das_' . $baseName;
        }
        
        if (Schema::hasTable($baseName)) {
            return $baseName;
        }
        
        // Special cases for common patterns
        if ($baseName === 'artikel') return 'das_artikel';
        if ($baseName === 'galeri') return 'galeris';
        if ($baseName === 'navigation') return 'das_navigation';
        if ($baseName === 'menu') return 'nav_menus';
        
        return null;
    }

    private function generateSqlBackup($tenant){                     
        return $this->tenantQueryGenerator->generateSqlFileContent($tenant->id, null, 'insert');    
    }
}

