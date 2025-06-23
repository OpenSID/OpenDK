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

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\NavMenu;
use App\Models\JenisDokumen;
use Illuminate\Http\Request;

class NavMenuController extends Controller
{

    public function index()
    {
        $page_title = 'Pengaturan Menu';
        $page_description = 'Struktur Menu';
        $menus = NavMenu::selectRaw("id, parent_id , name as text, url as href, target, type, is_show,'fa fa-list' as icon")
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->selectRaw("id, parent_id , name as text, url as href, target, is_show,type,'fa fa-list' as icon");
            }])
            ->orderBy('order')
            ->get()
            ->toArray();

        $nav_menus = json_encode($menus);

        $sourceItem = [
            // 'Halaman' => Artikel::cursor()->pluck('judul', 'link')->toArray(),
            'Halaman' => array_merge(
                ['profil/struktur-organisasi' => 'Struktur Organisasi'], // Tambahkan opsi statis di sini
                ['survei' => 'Survei Indeks Kepuasan Masyarakat'], // Tambahkan opsi survei ikm
                Artikel::cursor()->pluck('judul', 'link')->toArray()
            ),
            'Kategori' => Kategori::cursor()->pluck('nama', 'link')->toArray(),
            'Dokumen' => JenisDokumen::cursor()->pluck('nama', 'link')->toArray(),
        ];

        return view('setting.nav_menu.index', compact('page_title', 'page_description', 'nav_menus', 'sourceItem'));
    }


    public function store(Request $request)
    {
        NavMenu::whereNotNull('id')->delete();
        try {
            // hapus data lama lalu buat lagi
            $json = json_decode($request->json_menu, 1);

            $this->loopTree($json);

            return redirect()->route('setting.navmenu.index')->with('success', 'Menu berhasil disimpan!');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function loopTree(array $elements, $parentId = null)
    {
        $sequence = 1;
        foreach ($elements as $element) {
            $input = [
                'name' => $element['text'],
                'url' => $element['href'],
                'target' => $element['target'],
                'type' => $element['type'],
                'is_show' => isset($element['is_show']) ? $element['is_show'] : 1,
                'order' => $sequence,
                'parent_id' => $parentId,
            ];

            $model = NavMenu::create($input);

            // Jika ada children, rekursi untuk menyimpan sub-menu
            if (isset($element['children'])) {
                $this->loopTree($element['children'], $model->id);
            }
            $sequence++;
        }
    }
}
