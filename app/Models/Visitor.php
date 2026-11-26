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

    protected static function booted(): void
    {
        // url_hash menjadi kunci pengelompokan dan lookup, karena kolom url berupa TEXT (tidak bisa di-index penuh).
        static::saving(function (Visitor $visitor) {
            if ($visitor->isDirty('url') && $visitor->url !== null) {
                $visitor->url_hash = hash('sha256', $visitor->url);
            }
        });
    }

    /* Query scope filter date. Memakai rentang [awal, akhir) agar index visited_at bisa dipakai. */
    public function scopeFilter($query, $filter = null)
    {
        switch ($filter) {
            case VisitorFilterEnum::YESTERDAY:
                $start = Carbon::yesterday()->startOfDay();
                return self::applyRange($query, $start, $start->copy()->addDay());
            case VisitorFilterEnum::THIS_WEEK:
                $start = now()->startOfWeek();
                return self::applyRange($query, $start, $start->copy()->addWeek());
            case VisitorFilterEnum::THIS_MONTH:
                $start = now()->startOfMonth();
                return self::applyRange($query, $start, $start->copy()->addMonth());
            case VisitorFilterEnum::THIS_YEAR:
                $start = now()->startOfYear();
                return self::applyRange($query, $start, $start->copy()->addYear());
            case VisitorFilterEnum::ALL:
                // tidak ada filter
                break;
            default:
                $start = Carbon::today();
                return self::applyRange($query, $start, $start->copy()->addDay());
        }

        return $query;
    }

    private static function applyRange($query, Carbon $start, Carbon $end)
    {
        return $query->where('visited_at', '>=', $start)->where('visited_at', '<', $end);
    }

    /**
     * Ringkasan enam periode dalam satu kali pemindaian tabel.
     * Mengembalikan array dengan kunci: today, yesterday, thisWeek, thisMonth, thisYear, all.
     * Setiap nilai berupa object dengan properti page_views dan unique_visitors.
     */
    public static function summaryStats(): array
    {
        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $weekStart = $now->copy()->startOfWeek();
        $monthStart = $now->copy()->startOfMonth();
        $yearStart = $now->copy()->startOfYear();

        $periods = [
            'today' => [$todayStart, $todayStart->copy()->addDay()],
            'yesterday' => [$todayStart->copy()->subDay(), $todayStart],
            'thisWeek' => [$weekStart, $weekStart->copy()->addWeek()],
            'thisMonth' => [$monthStart, $monthStart->copy()->addMonth()],
            'thisYear' => [$yearStart, $yearStart->copy()->addYear()],
            'all' => null,
        ];

        $columns = [];
        $bindings = [];
        foreach ($periods as $key => $range) {
            if ($range === null) {
                $columns[] = "SUM(page_views) AS {$key}_page_views";
                $columns[] = "COUNT(DISTINCT ip_address) AS {$key}_unique_visitors";

                continue;
            }

            [$start, $end] = $range;
            $condition = 'visited_at >= ? AND visited_at < ?';
            $columns[] = "SUM(CASE WHEN {$condition} THEN page_views ELSE 0 END) AS {$key}_page_views";
            $columns[] = "COUNT(DISTINCT CASE WHEN {$condition} THEN ip_address END) AS {$key}_unique_visitors";
            array_push($bindings, $start, $end, $start, $end);
        }

        $row = self::query()->selectRaw(implode(', ', $columns), $bindings)->first();

        $summary = [];
        foreach (array_keys($periods) as $key) {
            $summary[$key] = (object) [
                'page_views' => (int) $row->{"{$key}_page_views"},
                'unique_visitors' => (int) $row->{"{$key}_unique_visitors"},
            ];
        }

        return $summary;
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

    /* data untuk halaman populer. Dikelompokkan per url_hash (indexed), bukan url (TEXT). */
    public static function getTopPagesVisited()
    {
        return self::select('url_hash')
            ->selectRaw('MAX(url) as url')
            ->selectRaw('SUM(page_views) as total_views')
            ->selectRaw('COUNT(DISTINCT ip_address) as unique_visitors')
            ->selectRaw('SUM(CASE WHEN page_views = 1 THEN 1 ELSE 0 END) as bounces')
            ->groupBy('url_hash')
            ->orderByDesc('total_views')
            ->limit(10)
            ->get();
    }

    /* Ringkasan di-cache bersama, sehingga sidebar (dipanggil di setiap halaman admin) tidak memindai tabel lagi. */
    public static function cachedSummaryStats(): array
    {
        return \Illuminate\Support\Facades\Cache::remember(
            'counter.summary',
            now()->addMinutes(5),
            fn () => self::summaryStats()
        );
    }

    public static function countAllVisitors()
    {
        return self::cachedSummaryStats()['all']->unique_visitors;
    }
}
