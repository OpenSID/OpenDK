<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Single column unique constraints to update with tenant_id
        $singleColumnTables = [
            'das_penduduk' => ['nik'],
            'das_pengurus' => ['nik', 'nip'],
            'das_artikel' => ['slug'],
            'das_artikel_kategori' => ['slug'],
            'das_lembaga' => ['slug', 'kode'],
            'das_log_surat' => ['nomor'],
            'das_suplemen' => ['slug'],
            'users' => ['email', 'mobile'],
            'albums' => ['slug'],
            'galeris' => ['slug'],
            'nav_menus' => ['slug'],
            'navigations' => ['slug'],
            // Additional tables from user feedback
            'das_counter_page' => ['page'],
            'das_counter_visitor' => ['visitor'],
            'das_events' => ['slug'],
            'das_navigation' => ['slug'],
            'das_setting' => ['key'],
            'log_penduduk' => ['nik'],
        ];

        // Process single column constraints
        foreach ($singleColumnTables as $tableName => $columns) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($tableName, $column)) {
                            // Check if unique constraint exists before dropping
                            $constraintName = $tableName . '_' . $column . '_unique';
                            if ($this->uniqueIndexExists($tableName, $constraintName)) {
                                $table->dropUnique($constraintName);
                            }
                            
                            // Add new unique constraint with tenant_id (shortened name)
                            $newConstraintName = substr($tableName, 0, 20) . '_' . substr($column, 0, 15) . '_tid_unique';
                            $table->unique([$column, 'tenant_id'], $newConstraintName);
                        }
                    }
                });
            }
        }

        // Multi-column unique constraints to update with tenant_id
        $multiColumnTables = [
            'das_pembangunan_dokumentasi' => ['id', 'desa_id', 'id_pembangunan'],
            'das_program' => ['id', 'desa_id'],
            // Additional table from user feedback
            'das_pembangunan' => ['id', 'desa_id'],
        ];

        // Process multi-column constraints
        foreach ($multiColumnTables as $tableName => $columns) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    // Check if all columns exist
                    $allColumnsExist = true;
                    foreach ($columns as $column) {
                        if (!Schema::hasColumn($tableName, $column)) {
                            $allColumnsExist = false;
                            break;
                        }
                    }
                    
                    if ($allColumnsExist) {
                        // Check if unique constraint exists before dropping
                        $constraintName = $tableName . '_' . implode('_', $columns) . '_unique';
                        if ($this->uniqueIndexExists($tableName, $constraintName)) {
                            $table->dropUnique($constraintName);
                        }
                        
                        // Add new unique constraint with tenant_id (shortened name)
                        $newColumns = array_merge($columns, ['tenant_id']);
                        $shortTableName = substr($tableName, 0, 15);
                        $columnPrefix = substr(implode('_', $columns), 0, 25);
                        $newConstraintName = $shortTableName . '_' . $columnPrefix . '_tid_unique';
                        $table->unique($newColumns, $newConstraintName);
                    }
                });
            }
        }

        // Special handling for permission tables if they have tenant_id
        $permissionTables = [
            'roles' => ['name', 'guard_name'],
            'permissions' => ['name', 'guard_name'],
        ];

        foreach ($permissionTables as $tableName => $columns) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    // Check if all columns exist
                    $allColumnsExist = true;
                    foreach ($columns as $column) {
                        if (!Schema::hasColumn($tableName, $column)) {
                            $allColumnsExist = false;
                            break;
                        }
                    }
                    
                    if ($allColumnsExist) {
                        // Check if unique constraint exists before dropping
                        $constraintName = $tableName . '_' . implode('_', $columns) . '_unique';
                        if ($this->uniqueIndexExists($tableName, $constraintName)) {
                            $table->dropUnique($constraintName);
                        }
                        
                        // Add new unique constraint with tenant_id (shortened name)
                        $newColumns = array_merge($columns, ['tenant_id']);
                        $shortTableName = substr($tableName, 0, 15);
                        $columnPrefix = substr(implode('_', $columns), 0, 25);
                        $newConstraintName = $shortTableName . '_' . $columnPrefix . '_tid_unique';
                        $table->unique($newColumns, $newConstraintName);
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Single column unique constraints to revert
        $singleColumnTables = [
            'das_penduduk' => ['nik'],
            'das_pengurus' => ['nik', 'nip'],
            'das_artikel' => ['slug'],
            'das_artikel_kategori' => ['slug'],
            'das_lembaga' => ['slug', 'kode'],
            'das_log_surat' => ['nomor'],
            'das_suplemen' => ['slug'],
            'users' => ['email', 'mobile'],
            'albums' => ['slug'],
            'galeris' => ['slug'],
            'nav_menus' => ['slug'],
            'navigations' => ['slug'],
            // Additional tables from user feedback
            'das_counter_page' => ['page'],
            'das_counter_visitor' => ['visitor'],
            'das_events' => ['slug'],
            'das_navigation' => ['slug'],
            'das_setting' => ['key'],
            'log_penduduk' => ['nik'],
        ];

        // Process single column constraints
        foreach ($singleColumnTables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($tableName, $column)) {
                            // Drop unique constraint with tenant_id
                            $constraintName = $tableName . '_' . $column . '_tenant_id_unique';
                            if ($this->uniqueIndexExists($tableName, $constraintName)) {
                                $table->dropUnique($constraintName);
                            }
                            
                            // Restore original unique constraint (shortened name)
                            $originalConstraintName = substr($tableName, 0, 30) . '_' . substr($column, 0, 20) . '_unique';
                            $table->unique($column, $originalConstraintName);
                        }
                    }
                });
            }
        }

        // Multi-column unique constraints to revert
        $multiColumnTables = [
            'das_pembangunan_dokumentasi' => ['id', 'desa_id', 'id_pembangunan'],
            'das_program' => ['id', 'desa_id'],
            // Additional table from user feedback
            'das_pembangunan' => ['id', 'desa_id'],
        ];

        // Process multi-column constraints
        foreach ($multiColumnTables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    // Check if all columns exist
                    $allColumnsExist = true;
                    foreach ($columns as $column) {
                        if (!Schema::hasColumn($tableName, $column)) {
                            $allColumnsExist = false;
                            break;
                        }
                    }
                    
                    if ($allColumnsExist) {
                        // Drop unique constraint with tenant_id
                        $constraintName = $tableName . '_' . implode('_', $columns) . '_tenant_id_unique';
                        if ($this->uniqueIndexExists($tableName, $constraintName)) {
                            $table->dropUnique($constraintName);
                        }
                        
                        // Restore original unique constraint (shortened name)
                        $shortTableName = substr($tableName, 0, 20);
                        $columnPrefix = substr(implode('_', $columns), 0, 30);
                        $originalConstraintName = $shortTableName . '_' . $columnPrefix . '_unique';
                        $table->unique($columns, $originalConstraintName);
                    }
                });
            }
        }

        // Special handling for permission tables
        $permissionTables = [
            'roles' => ['name', 'guard_name'],
            'permissions' => ['name', 'guard_name'],
        ];

        foreach ($permissionTables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    // Check if all columns exist
                    $allColumnsExist = true;
                    foreach ($columns as $column) {
                        if (!Schema::hasColumn($tableName, $column)) {
                            $allColumnsExist = false;
                            break;
                        }
                    }
                    
                    if ($allColumnsExist) {
                        // Drop unique constraint with tenant_id
                        $constraintName = $tableName . '_' . implode('_', $columns) . '_tenant_id_unique';
                        if ($this->uniqueIndexExists($tableName, $constraintName)) {
                            $table->dropUnique($constraintName);
                        }
                        
                        // Restore original unique constraint (shortened name)
                        $shortTableName = substr($tableName, 0, 20);
                        $columnPrefix = substr(implode('_', $columns), 0, 30);
                        $originalConstraintName = $shortTableName . '_' . $columnPrefix . '_unique';
                        $table->unique($columns, $originalConstraintName);
                    }
                });
            }
        }
    }

    /**
     * Check if a unique index exists on a table
     */
    private function uniqueIndexExists(string $tableName, string $indexName): bool
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM `{$tableName}` WHERE Key_name = ?", [$indexName]);
            return !empty($indexes);
        } catch (\Exception $e) {
            return false;
        }
    }
};