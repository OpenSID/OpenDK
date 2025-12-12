<?php

namespace App\Services;

use Database\Seeders\DefautTenantTable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantQueryGeneratorService
{
    /**
     * List of tables that have tenant_id column
     */
    private $tablesWithTenantId = [];
    
    public function __construct()
    {
        $this->tablesWithTenantId = DefautTenantTable::HAS_TENANT_COLUMN;
    }

    /**
     * Generate SELECT queries for tables with tenant_id column
     * 
     * @param int $tenantId
     * @param array|null $tables Optional list of tables to include, if null use all tables with tenant_id
     * @return array
     */
    public function generateSelectQueries($tenantId, $tables = null)
    {
        $queries = [];
        
        // Filter tables if specific tables are provided
        $filteredTables = $this->filterTables($tables);
        
        foreach ($filteredTables as $table) {
            if ($this->hasTenantIdColumn($table)) {
                $columns = Schema::getColumnListing($table);
                
                // Remove tenant_id from columns to avoid conflicts during duplication
                $columns = array_filter($columns, function ($column) {
                    return !in_array($column, ['id', 'tenant_id']);
                });
                
                $columns = array_values($columns);
                
                if (!empty($columns)) {
                    $columnList = implode('`, `', $columns);
                    $columnListWithTenant = '`id`, `' . $columnList . '`, `tenant_id`';
                    
                    $query = "SELECT {$columnListWithTenant} FROM `{$table}` WHERE `tenant_id` = {$tenantId};";
                    $queries[$table] = $query;
                } else {
                    // If only id and tenant_id columns exist, select them
                    $query = "SELECT `id`, `tenant_id` FROM `{$table}` WHERE `tenant_id` = {$tenantId};";
                    $queries[$table] = $query;
                }
            }
        }
        
        return $queries;
    }

    /**
     * Generate INSERT queries for tables with tenant_id column
     *
     * @param int $tenantId
     * @param array|null $tables Optional list of tables to include, if null use all tables with tenant_id
     * @return array
     */
    public function generateInsertQueries($tenantId, $tables = null)
    {
        $queries = [];
        
        // Filter tables if specific tables are provided
        $filteredTables = $this->filterTables($tables);
        
        foreach ($filteredTables as $table) {
            if ($this->hasTenantIdColumn($table)) {
                // Get all records for the specified tenant
                $records = DB::table($table)->where('tenant_id', $tenantId)->get();
                
                if ($records->count() > 0) {
                    // Get column names
                    $columns = Schema::getColumnListing($table);                                        
                    
                    $columns = array_values($columns);
                    
                    // Build the INSERT query
                    if (!empty($columns)) {
                        $columnList = implode('`, `', $columns);                        
                        
                        $query = "INSERT INTO `{$table}` (`{$columnList}`) VALUES ";
                        $values = [];
                        
                        foreach ($records as $record) {
                            $recordValues = [];
                                                        
                            // Add other column values
                            foreach ($columns as $column) {
                                $recordValues[] = $this->formatValue($record->$column);
                            }
                                                        
                            $values[] = '(' . implode(', ', $recordValues) . ')';
                        }
                        
                        $query .= implode(', ', $values) . ';';
                        $queries[$table] = $query;
                    } 
                }
            }
        }
        
        return $queries;
    }

    /**
     * Get all data for tables with tenant_id column
     *
     * @param int $tenantId
     * @param array|null $tables Optional list of tables to include, if null use all tables with tenant_id
     * @return array
     */
    public function getTableData($tenantId, $tables = null)
    {
        $data = [];
        
        // Filter tables if specific tables are provided
        $filteredTables = $this->filterTables($tables);
        
        foreach ($filteredTables as $table) {
            if ($this->hasTenantIdColumn($table)) {
                $data[$table] = DB::table($table)->where('tenant_id', $tenantId)->get()->toArray();
            }
        }
        
        return $data;
    }

    /**
     * Generate SQL file content with queries for tables with tenant_id column
     *
     * @param int $tenantId
     * @param array|null $tables Optional list of tables to include, if null use all tables with tenant_id
     * @param string $queryType Type of query to generate ('select' or 'insert')
     * @return string
     */
    public function generateSqlFileContent($tenantId, $tables = null, $queryType = 'insert')
    {
        $queries = [];
        
        if ($queryType === 'select') {
            $queries = $this->generateSelectQueries($tenantId, $tables);
        } else {
            $queries = $this->generateInsertQueries($tenantId, $tables);
        }
        
        $sqlContent = "-- SQL Queries for Tenant ID: {$tenantId}\n";
        $sqlContent .= "-- Generated at: " . now()->format('Y-m-d H:i:s') . "\n\n";
        
        foreach ($queries as $table => $query) {
            $sqlContent .= "-- Table: {$table}\n";
            $sqlContent .= $query . "\n\n";
        }
        
        return $sqlContent;
    }

    /**
     * Download SQL file with queries for tables with tenant_id column
     *
     * @param int $tenantId
     * @param array|null $tables Optional list of tables to include, if null use all tables with tenant_id
     * @param string $queryType Type of query to generate ('select' or 'insert')
     * @param string|null $filename Optional filename for the download
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadSqlFile($tenantId, $tables = null, $queryType = 'insert', $filename = null)
    {
        $sqlContent = $this->generateSqlFileContent($tenantId, $tables, $queryType);
        
        if (!$filename) {
            $filename = 'tenant_' . $tenantId . '_queries_' . $queryType . '_' . now()->format('Y-m-d_H-i-s') . '.sql';
        }
        
        $response = response()->streamDownload(function () use ($sqlContent) {
            echo $sqlContent;
        }, $filename);
        
        $response->headers->set('Content-Type', 'application/sql');
        
        return $response;
    }

    /**
     * Format value for SQL query
     *
     * @param mixed $value
     * @return string
     */
    private function formatValue($value)
    {
        if (is_null($value)) {
            return 'NULL';
        } elseif (is_numeric($value)) {
            return $value;
        } else {
            // Escape single quotes and wrap in quotes
            return "'" . addslashes($value) . "'";
        }
    }

    /**
     * Check if a table has tenant_id column
     *
     * @param string $table
     * @return bool
     */
    private function hasTenantIdColumn($table)
    {
        return Schema::hasColumn($table, 'tenant_id');
    }

    /**
     * Filter tables based on provided list
     *
     * @param array|null $tables
     * @return array
     */
    private function filterTables($tables)
    {
        if (is_null($tables) || empty($tables)) {
            return $this->tablesWithTenantId;
        }
        
        // Ensure only tables that have tenant_id column are included
        return array_filter($tables, function ($table) {
            return in_array($table, $this->tablesWithTenantId) && $this->hasTenantIdColumn($table);
        });
    }

    /**
     * Get list of tables that have tenant_id column
     *
     * @return array
     */
    public function getTablesWithTenantId()
    {
        return array_filter($this->tablesWithTenantId, function ($table) {
            return $this->hasTenantIdColumn($table);
        });
    }
}