<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Enums\VisitorFilterEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ip_address', 'url', 'user_agent', 'visited_at', 'page_views'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /* Query scope filter date */
    public function scopeFilter($query, $filter = null)
    {
        switch ($filter) {
            case VisitorFilterEnum::YESTERDAY:
                $query->whereDate('visited_at', Carbon::yesterday());
                break;
            case VisitorFilterEnum::THIS_WEEK:
                $query->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case VisitorFilterEnum::THIS_MONTH:
                $query->whereMonth('visited_at', now()->month)
                    ->whereYear('visited_at', now()->year);
                break;
            case VisitorFilterEnum::THIS_YEAR:
                $query->whereYear('visited_at', now()->year);
                break;
            case VisitorFilterEnum::ALL:
                // tidak ada filter
                break;
            default:
                $query->whereDate('visited_at', Carbon::today());
        }

        return $query;
    }

    public static function stats($filter = null)
    {
        return self::filter($filter)
            ->selectRaw('COUNT(DISTINCT ip_address) as unique_visitors')
            ->selectRaw('SUM(page_views) as page_views')
            ->first();
    }

    public static function groupedStats($filter)
    {
        if ($filter === VisitorFilterEnum::THIS_YEAR) {
            $format = '%Y-%m';
        } elseif ($filter === VisitorFilterEnum::ALL) {
            $format = '%Y';
        } else {
            $format = '%Y-%m-%d';
        }

        return self::filter($filter)
            ->selectRaw("DATE_FORMAT(visited_at, '$format') as date")
            ->selectRaw("SUM(page_views) as page_views")
            ->selectRaw("COUNT(DISTINCT ip_address) as unique_visitors")
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /* data untuk halaman populer */
    public static function getTopPagesVisited()
    {
        return self::select('url')
            ->selectRaw('SUM(page_views) as total_views')
            ->selectRaw('COUNT(DISTINCT ip_address) as unique_visitors')
            ->selectRaw('SUM(CASE WHEN page_views = 1 THEN 1 ELSE 0 END) as bounces')
            ->groupBy('url')
            ->orderByDesc('total_views')
            ->limit(10)
            ->get();
    }

    public static function countAllVisitors()
    {
        return \Illuminate\Support\Facades\DB::table('visitors')->distinct('ip_address')->count('ip_address');
    }
}
