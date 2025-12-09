<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Repositories;

use App\Facades\Counter;
use App\Models\Event;
use App\Models\MediaSosial;
use App\Models\MediaTerkait;
use App\Models\Navigation;
use App\Models\NavMenu;
use App\Models\Pengurus;
use App\Models\SinergiProgram;
use App\Models\Slide;

class WebsiteApiRepository extends BaseApiRepository
{
    protected $eventModel;
    protected $mediaSosialModel;
    protected $mediaTerkaitModel;
    protected $navigationModel;
    protected $navMenuModel;
    protected $pengurusModel;
    protected $sinergiProgramModel;
    protected $slideModel;

    /**
     * Constructor
     */
    public function __construct(
        Event $eventModel,
        MediaSosial $mediaSosialModel,
        MediaTerkait $mediaTerkaitModel,
        Navigation $navigationModel,
        NavMenu $navMenuModel,
        Pengurus $pengurusModel,
        SinergiProgram $sinergiProgramModel,
        Slide $slideModel
    ) {
        $this->eventModel = $eventModel;
        $this->mediaSosialModel = $mediaSosialModel;
        $this->mediaTerkaitModel = $mediaTerkaitModel;
        $this->navigationModel = $navigationModel;
        $this->navMenuModel = $navMenuModel;
        $this->pengurusModel = $pengurusModel;
        $this->sinergiProgramModel = $sinergiProgramModel;
        $this->slideModel = $slideModel;
    }

    /**
     * Get all website data for frontend
     *
     * @return array
     */
    public function getAllWebsiteData()
    {
        return [
            'events' => $this->getEvents(),
            'medsos' => $this->getMediaSosial(),
            'media_terkait' => $this->getMediaTerkait(),
            'navigations' => $this->getNavigations(),
            'navmenus' => $this->getNavMenus(),
            'pengurus' => $this->getPengurus(),
            'sinergi' => $this->getSinergiProgram(),
            'slides' => $this->getSlides(),
            'counter' => $this->getCounterVisitor()
        ];
    }

    /**
     * Get open events
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEvents()
    {
        return Event::getOpenEvents();
    }

    /**
     * Get active media sosial
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMediaSosial()
    {
        return MediaSosial::where('status', 1)->get();
    }

    /**
     * Get active media terkait
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMediaTerkait()
    {
        return MediaTerkait::where('status', 1)->get();
    }

    /**
     * Get navigations with children
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNavigations()
    {
        return Navigation::with('childrens')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Get nav menus with children
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNavMenus()
    {
        return NavMenu::with('children.children')
            ->whereNull('parent_id')
            ->where('is_show', 1)
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Get active sinergi program
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSinergiProgram()
    {
        return SinergiProgram::where('status', 1)
            ->orderBy('urutan', 'asc')
            ->get();
    }

    /**
     * Get slides ordered by created_at
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSlides()
    {
        return Slide::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Get active pengurus
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPengurus()
    {
        return Pengurus::with('jabatan')->get();
    }

    public function getCounterVisitor(){
        return collect([
            'today' => Counter::visitors('today'),
            'yesterday' => Counter::visitors('yesterday'),
            'week' => Counter::visitors('week'),
            'month' => Counter::visitors('month'),
            'year' => Counter::visitors('year'),
            'all' => Counter::visitors('all')
        ]);
    }
}