<?php

/**
 * Time: 5:50 AM
 */

namespace App\Helpers;

use App\Models\CounterPage;
use App\Models\CounterVisitor;
use Carbon\Carbon;
use Cookie;
use DB;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

use function config;
use function env;
use function hash;
use function number_format;

class Counter
{
    public function __construct(CrawlerDetect $visitor)
    {
        $this->visitor = $visitor;
        $this->hasDnt  = isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1 ? true : false;
    }

    /**
     * Check to determine if bots/crawlers will be
     * counted as hits.
     *
     * @var bool
     */
    private static $ignore_bots = true;
/**
 * Check to determine if we will count hits
 * from visitors that send a DO NOT TRACK header.
 *
 * @var bool
 */
    private static $honor_do_not_track = false;
/**
 * Singleton for the $page in question
 *
 * @var null|object
 */
    private static $current_page;
/**
 * Show view count for the requested page.
 *
 * Use this when you just want to show the current view count
 * for the page in question. Does not add counts.
 *
 * @param string $identifier A unique string to the page you are tracking
 * @param null|integer $id A unique identifier for dynamic page tracking
 * @return string Unique view count for requested resource
 */
    public function show($identifier, $id = null)
    {
        $page = self::pageId($identifier, $id);
        return self::countHits($page);
    }

    /**
     * Show view count for the requested page while adding
     * count if it does not already exist for the current user
     * and page.
     *
     * Use this for pages that you want to show the count, while
     * also incrementing the count.
     *
     * @param string $identifier
     * @param null|integer $id
     * @return string Unique view count for requested resource
     */
    public function showAndCount($identifier, $id = null)
    {
        $page = self::pageId($identifier, $id);
        self::processHit($page);
        return self::countHits($page);
    }

    /**
     * Increase count for page without returning count.
     *
     * This can be used for a number of things. To count pages
     * if you intend to show the count elsewhere or show the count
     * if conditions are met. Ex: Count profile views, but only
     * show the view count to the profile user with show()
     *
     * @param string $identifier
     * @param null|integer $id
     * @return null
     */
    public function count($identifier, $id = null)
    {
        $page = self::pageId($identifier, $id);
        self::processHit($page);
    }

    /**
     * Return view count for all pages on the site.
     * You may supply an integer if you would like to
     * restrict counted views to a certain amount of days.
     *
     * Example: Show total views for the last 30 days
     * Counter::allHits(30)
     *
     * @param null|integer $days
     * @return string Unique view count for all pages
     */
    public function allHits($days = null)
    {
        $prefix = config('database.connections.' . config('database.default') . '.prefix');
        if ($days) {
            $hits = DB::table($prefix . 'das_counter_page_visitor')->where('created_at', '>=', Carbon::now()->subDays($days))->count();
        } else {
            $hits = DB::table($prefix . 'das_counter_page_visitor')->count();
        }

        return number_format($hits);
    }

    /**
     * Return visitor count for all pages on the site.
     * You may supply an integer if you would like to
     * restrict counted visitors to a certain amount of days.
     *
     * Example: Show total visitors for the last 30 days
     * Counter::allVisitors(30)
     *
     * @param null|integer $days
     * @return string Unique visitor count for all pages
     */
    public function allVisitors($days = null)
    {
        $prefix = config('database.connections.' . config('database.default') . '.prefix');
        if ($days) {
            $hits = DB::table($prefix . 'das_counter_page_visitor')->groupBy('visitor_id')->where('created_at', '>=', Carbon::now()->subDays($days))->count();
        } else {
            $hits = DB::table($prefix . 'das_counter_page_visitor')->groupBy('visitor_id')->count();
        }

        return number_format($hits);
    }

    /*====================== PRIVATE METHODS =============================*/
    /**
     * Processes the hit request for the page in question.
     *
     * @param string $page
     * @return null
     */
    private function processHit($page)
    {
        $addHit = true;
        if (self::$ignore_bots && $this->visitor->isCrawler()) {
            $addHit = false;
        }
        if (self::$honor_do_not_track && $this->hasDnt) {
            $addHit = false;
        }
        if ($addHit) {
            self::createCountIfNotPresent($page);
        }
    }

    /**
     * Generates a hash based on APP_KEY and current visitors set cookie
     * If route caching is enabled and our route filter has not been loaded
     * then fall back to IP Address.
     *
     * @return string
     */
    private static function hashVisitor()
    {
        $cookie  = Cookie::get(env('COUNTER_COOKIE', 'kryptonit3-counter'));
        $visitor = $cookie !== false ? $cookie : $_SERVER['REMOTE_ADDR'];
        return hash("SHA256", env('APP_KEY') . $visitor);
    }

    /**
     * Generates a hash based on page identifier
     *
     * @param string $identifier
     * @param null|integer $id
     * @return string
     */
    private static function pageId($identifier, $id = null)
    {
        /*$uuid5 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $identifier);
        if ($id) {
            $uuid5 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $identifier . '-' . $id);
        }

        return $uuid5;*/

        $uuid5 = $identifier;
        if ($id) {
            $uuid5 = $identifier . '|' . $id;
        }

        return $uuid5;
    }

    /**
     * Create and/or grab the visitor record
     *
     * @param string $visitor hash provided by hashVisitor()
     * @return object Visitor eloquent object.
     */
    private static function createVisitorRecordIfNotPresent($visitor)
    {
        return CounterVisitor::firstOrCreate(['visitor' => $visitor]);
    }

    /**
     * Create and/or grab the page record
     *
     * @param string $page hash provided by pageId()
     * @return object Page eloquent object.
     */
    private static function createPageIfNotPresent($page)
    {
        return self::$current_page = CounterPage::firstOrCreate(['page' => $page]);
    }

    /**
     * Create the count record if it does not exist.
     *
     * @param string $page hash provided by pageId()
     * @return null
     */
    private static function createCountIfNotPresent($page)
    {
        $page_record    = self::createPageIfNotPresent($page);
        $visitor        = self::hashVisitor();
        $visitor_record = self::createVisitorRecordIfNotPresent($visitor);
        $page_record->visitors()->sync([$visitor_record->id => ['created_at' => Carbon::now()]], false);
    }

    /**
     * Returns hit count for the requested resource.
     *
     * @param string $page hash provided by pageId()
     * @return string Unique view count for requested resource
     */
    private static function countHits($page)
    {
        $page_record = self::createPageIfNotPresent($page);
        return number_format($page_record->visitors->count());
    }
}
