<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
    private static ?bool $activityLogAvailable = null;

    public function getFilteredActivities(?string $dateFrom, ?string $dateTo, ?string $userId, ?string $event, ?string $keyword)
    {
        $query = Activity::query();

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($userId) {
            $query->where('causer_id', (int) $userId);
        }

        if ($event) {
            $query->where('event', $event);
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('description', 'like', "%{$keyword}%")
                  ->orWhere('properties->url_slug', 'like', "%{$keyword}%");
            });
        }

        return $query;
    }

    public static function log(string $event, ?string $description = null, ?array $properties = []): ?Activity
    {
        if (! self::isActivityLogAvailable()) {
            return null;
        }

        $request = request();
        $properties = array_merge($properties ?? [], [
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
            'url_slug' => $request ? $request->path() : null,
        ]);

        return activity()
            ->event($event)
            ->withProperties($properties)
            ->log($description ?? $event);
    }

    public static function logFailed(string $event, ?string $description = null, ?array $properties = [], ?int $userId = null): ?Activity
    {
        if (! self::isActivityLogAvailable()) {
            return null;
        }

        $request = request();
        $properties = array_merge($properties ?? [], [
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
            'url_slug' => $request ? $request->path() : null,
        ]);

        if ($userId === null) {
            $userId = $request ? ($request->user() ? $request->user()->id : null) : null;
        }

        $properties['failed'] = true;

        return activity()
            ->event($event)
            ->withProperties($properties)
            ->log($description ?? $event);
    }

    public static function logAttributeChange(string $event, string $action, ?int $userId = null, array $changedAttributes = []): ?Activity
    {
        if (! self::isActivityLogAvailable()) {
            return null;
        }

        $request = request();
        $properties = [
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
            'url_slug' => $request ? $request->path() : null,
            'action' => $action,
            'changed_attributes' => $changedAttributes,
        ];

        if ($userId === null) {
            $userId = $request ? ($request->user() ? $request->user()->id : null) : null;
        }

        $properties['user_id'] = $userId;

        return activity()
            ->event($event)
            ->withProperties($properties)
            ->log($event);
    }

    private static function isActivityLogAvailable(): bool
    {
        if (self::$activityLogAvailable !== null) {
            return self::$activityLogAvailable;
        }

        self::$activityLogAvailable = Schema::hasTable('activity_log');

        return self::$activityLogAvailable;
    }
}
