<?php

namespace App\Services;

use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
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

    public static function log(string $event, ?string $description = null, ?array $properties = []): Activity
    {
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

    public static function logFailed(string $event, ?string $description = null, ?array $properties = [], ?int $userId = null): Activity
    {
        $request = request();
        $properties = array_merge($properties ?? [], [
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
            'url_slug' => $request ? $request->path() : null,
        ]);

        // Ensure userId is set if not provided
        if ($userId === null) {
            $userId = $request ? ($request->user() ? $request->user()->id : null) : null;
        }

        $properties['failed'] = true;

        return activity()
            ->event($event)
            ->withProperties($properties)
            ->log($description ?? $event);
    }

    public static function logAttributeChange(string $event, string $action, ?int $userId = null, array $changedAttributes = []): Activity
    {
        $request = request();
        $properties = [
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
            'url_slug' => $request ? $request->path() : null,
            'action' => $action,
            'changed_attributes' => $changedAttributes,
        ];

        // Ensure userId is set if not provided
        if ($userId === null) {
            $userId = $request ? ($request->user() ? $request->user()->id : null) : null;
        }

        $properties['user_id'] = $userId;

        return activity()
            ->event($event)
            ->withProperties($properties)
            ->log($event);
    }
}
