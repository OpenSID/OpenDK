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
        // Handle tables that might have different constraint naming patterns
        $additionalTables = [
            // Tables with potential different constraint names
            'das_penduduk' => [
                'columns' => ['nik'],
                'possible_constraints' => ['das_penduduk_nik_unique', 'nik_unique']
            ],
            'das_pengurus' => [
                'columns' => ['nik'],
                'possible_constraints' => ['das_pengurus_nik_unique', 'nik_unique']
            ],            
            'users' => [
                'columns' => ['email'],
                'possible_constraints' => ['users_email_unique', 'email_unique']
            ],            
        ];

        // Process additional tables
        foreach ($additionalTables as $tableName => $config) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $config) {
                    $columns = $config['columns'];
                    $possibleConstraints = $config['possible_constraints'];
                    
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($tableName, $column)) {
                            // Try to drop any existing unique constraint
                            foreach ($possibleConstraints as $constraintName) {
                                if ($this->uniqueIndexExists($tableName, $constraintName)) {
                                    try {
                                        $table->dropUnique($constraintName);
                                        break; // Stop after finding and dropping one
                                    } catch (\Exception $e) {
                                        // Continue to next constraint name
                                    }
                                }
                            }
                            
                            // Add new unique constraint with tenant_id (shortened name)
                            $newConstraintName = substr($tableName, 0, 20) . '_' . substr($column, 0, 15) . '_tid_unique';
                            // Check if new constraint doesn't already exist
                            if (!$this->uniqueIndexExists($tableName, $newConstraintName)) {
                                $table->unique([$column, 'tenant_id'], $newConstraintName);
                            }
                        }
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
        // Handle tables that might have different constraint naming patterns
        $additionalTables = [
            'das_penduduk' => ['nik'],
            'das_pengurus' => ['nik', 'nip'],
            'users' => ['email', 'mobile'],
        ];

        foreach ($additionalTables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($tableName, $column)) {
                            // Drop unique constraint with tenant_id
                            $constraintName = $tableName . '_' . $column . '_tenant_id_unique';
                            if ($this->uniqueIndexExists($tableName, $constraintName)) {
                                try {
                                    $table->dropUnique($constraintName);
                                } catch (\Exception $e) {
                                    // Continue if constraint doesn't exist
                                }
                            }
                            
                            // Restore original unique constraint (shortened name)
                            $originalConstraintName = substr($tableName, 0, 30) . '_' . substr($column, 0, 20) . '_unique';
                            if (!$this->uniqueIndexExists($tableName, $originalConstraintName)) {
                                $table->unique($column, $originalConstraintName);
                            }
                        }
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