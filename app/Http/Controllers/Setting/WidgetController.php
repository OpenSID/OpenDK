<?php
/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Widget;
use Yajra\DataTables\DataTables;

define('LOKASI_WIDGET', base_path() . '/resources/views/widgets/');

class WidgetController extends Controller
{
    public function index()
    {
        $page_title       = 'Widget';
        $page_description = 'Daftar Widget';

        return view('setting.widget.index', compact('page_title', 'page_description'));
    }

    // Get Data Widget
    public function getData()
    {
        return DataTables::of(Widget::orderBy('urut', 'ASC')->get())
            ->addColumn('urut', function ($row){
                $data = [];
                $data['naik'] = route('setting.widget.urut', ['id' => $row->id, 'arah' => 2]);
                $data['turun']   = route('setting.widget.urut', ['id' => $row->id, 'arah' => 1]);
                return view('forms.aksi', $data);
            })
            ->addColumn('aksi', function ($row) {
                if ($row->jenis_widget != 1) {
                    $data['edit_url']   = route('setting.widget.index', $row->id);
                    $data['delete_url'] = route('setting.widget.index', $row->id);
                }

                if ($row->enabled == 1) {
                    $data['suspend_url'] = route('setting.widget.disable', $row->id);
                } else {
                    $data['active_url'] = route('setting.widget.enable', $row->id);
                }
                return view('forms.aksi', $data);
            })
            ->make();
    }

    public function create()
    {
        $page_title       = 'Widget';
        $page_description = 'Tambah Widget';
        $widget = null;
        $widget_list = $this->getNewWidget();

        return view('setting.widget.create', compact('page_title', 'page_description', 'widget', 'widget_list'));
    }

    public function urut($id = 0, $arah = 0)
    {
        Widget::urut($id, $arah);
        return redirect()->route('setting.widget.index');
    }

    public function disableWidget($id)
    {
        $widget = Widget::find($id);
        $widget->enabled = 0;
        $widget->save();
        return redirect()->route('setting.widget.index');
    }

    public function enableWidget($id)
    {
        $widget = Widget::find($id);
        $widget->enabled = 1;
        $widget->save();
        return redirect()->route('setting.widget.index');
    }

    public function getWidgetActive()
    {
        return Widget::where('enabled', 1)->get();
    }

    public function getNewWidget()
    {
        $defaultThemeWidget = $this->widget(str_replace("/","\\", LOKASI_WIDGET) . '*.php');

        // Return hanya nama file saja
        // Cohtoh : event.blalde.php
        $widget_list = array_map(function ($file){
            $parts = explode('\\', $file);
            return end($parts);
        }, $defaultThemeWidget);

        return array_combine($widget_list, $widget_list);
    }

    public function widget($lokasi)
    {
        $list_widget = glob($lokasi);
        $l_widget = [];

        foreach ($list_widget as $widget) {
            $l_widget[] = $widget;
        }

        return $l_widget;
    }


    public function storeWidget(Request $request)
    {
        $data = $request->all();
        $data['urut'] = Widget::max('urut') + 1;
        $data['isi'] = $data['isi-dinamis'] ?? $data['isi-statis'];

        if ($data['jenis_widget'] == 3){
            $data['isi'] = $this->bersihkan_html($data['isi']);
        }

        $existing = Widget::where('isi', $data['isi'])->first();
        if ($existing != null){
            return back()->withInput()->with('error', 'Widget ini sudah tersedia.');
        }
        Widget::create($data);
        return redirect()->route('setting.widget.index')->with('success', 'Widget berhasil ditambahkan.');
    }

    private function bersihkan_html($isi)
    {
        // Konfigurasi tidy
        $config = [
            'indent'         => true,
            'output-xhtml'   => true,
            'show-body-only' => true,
            'clean'          => true,
            'coerce-endtags' => true,
        ];
        $tidy = new \tidy();
        $tidy->parseString($isi, $config, 'utf8');
        $tidy->cleanRepair();

        return tidy_get_output($tidy);
    }
}
