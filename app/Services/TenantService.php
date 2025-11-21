<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class TenantService
{
    /**
     * Get the current tenant ID
     *
     * @return string|null
     */
    public static function getCurrentTenantId()
    {
        // Check if we have an authenticated user first
        if (Auth::check()) {
            $user = Auth::user();

            // If user has a kecamatan_id (typically through pengurus relationship)
            if ($user->pengurus && $user->pengurus->kecamatan_id) {
                return $user->pengurus->kecamatan_id;
            }
        }

        // Fallback to config value
        return Config::get('tenant.current_tenant_id');
    }

    /**
     * Get the offset range for a tenant
     *
     * @param string $tenantId
     * @return array|null
     */
    public static function getTenantOffsetRange($tenantId)
    {
        $offsetRanges = Config::get('tenant.offset_ranges');

        return $offsetRanges[$tenantId] ?? null;
    }

    /**
     * Calculate the offset ID for a record
     *
     * @param string $tenantId
     * @param int $baseId
     * @return int
     */
    public static function calculateOffsetId($tenantId, $baseId)
    {
        $range = self::getTenantOffsetRange($tenantId);

        if ($range) {
            // Calculate offset using the range start
            // For example: if range starts at 1000001, then offset = 1000000
            $offset = $range['start'] - 1;
            return $offset + $baseId;
        }

        // If no range defined, return original ID
        return $baseId;
    }

    /**
     * Convert an offset ID back to its base ID
     *
     * @param string $tenantId
     * @param int $offsetId
     * @return int
     */
    public static function convertOffsetToBaseId($tenantId, $offsetId)
    {
        $range = self::getTenantOffsetRange($tenantId);

        if ($range) {
            $offset = $range['start'] - 1;
            // Ensure that the offsetId is within the range of this tenant
            if ($offsetId >= $range['start'] && $offsetId <= $range['end']) {
                return $offsetId - $offset;
            }
        }

        // If not in range or no range defined, return original ID
        return $offsetId;
    }

    /**
     * Check if a given offset ID belongs to the current tenant
     *
     * @param int $offsetId
     * @return bool
     */
    public static function isOffsetIdForCurrentTenant($offsetId)
    {
        $currentTenantId = self::getCurrentTenantId();

        if (!$currentTenantId) {
            return true; // If no tenant is set, allow access (for initial setup)
        }

        $range = self::getTenantOffsetRange($currentTenantId);

        if ($range) {
            return $offsetId >= $range['start'] && $offsetId <= $range['end'];
        }

        return true; // If no range is defined, allow access
    }

    /**
     * Get tenant ID from offset ID
     *
     * @param int $offsetId
     * @return string|null
     */
    public static function getTenantIdFromOffsetId($offsetId)
    {
        $offsetRanges = Config::get('tenant.offset_ranges');

        foreach ($offsetRanges as $tenantId => $range) {
            if ($offsetId >= $range['start'] && $offsetId <= $range['end']) {
                return $tenantId;
            }
        }

        return null;
    }

}