<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Spatie\Activitylog\Models\Activity as ActivityModel;

class ActivityLogger
{
    /**
     * Catat aktivitas dengan metadata standar.
     */
    public static function log(
        string $category,
        string $event,
        string $message,
        ?Model $subject = null,
        ?Model $causer = null,
        array $additionalProperties = []
    ): ActivityModel {
        $request = request();

        $metadata = [
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'url' => $request?->fullUrl(),
            'slug' => $request?->path(),
            'method' => $request?->method(),
            'referer' => $request?->headers->get('referer'),
        ];

        if ($request && $request->userAgent()) {
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());

            $metadata['browser'] = $agent->browser();
            $metadata['platform'] = $agent->platform();
            $metadata['device'] = $agent->device();
        }

        $ipLocation = [];
        if (! empty($metadata['ip_address'])) {
            $ipLocation = IpLocationResolver::resolve($metadata['ip_address']);
        }

        $properties = array_filter(
            array_merge($metadata, $ipLocation, $additionalProperties),
            static fn ($value) => ! is_null($value) && $value !== ''
        );

        $logger = activity()
            ->useLog($category)
            ->event($event);

        $causer = $causer ?? Auth::user();
        if ($causer) {
            $logger->causedBy($causer);
        }

        if ($subject) {
            $logger->performedOn($subject);
        }

        return $logger
            ->withProperties($properties)
            ->log($message);
    }

    /**
     * Helper untuk menambahkan properti tambahan setelah log dibuat.
     */
    public static function appendProperties(ActivityModel $activity, array $properties): ActivityModel
    {
        $merged = array_merge($activity->properties?->toArray() ?? [], $properties);
        $activity->properties = Arr::except($merged, [null, '']);
        $activity->save();

        return $activity;
    }
}
