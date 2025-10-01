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

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\Traits\HandlesFileUpload;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Services\ActivityLogger;

class UserController extends Controller
{
    use HandlesFileUpload;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Pengguna';
        $page_description = 'Daftar Data';

        return view('user.index', compact('page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Pengguna';
        $page_description = 'Tambah Data';
        $item = Role::where('name', '!=', 'super-admin')->pluck('name', 'name')->toArray();
        $pengurus = Pengurus::status()->doesntHave('user')->get();

        return view('user.create', compact('page_title', 'page_description', 'item', 'pengurus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        try {
            $status = ! empty($request->status) ? 1 : 1;
            $request->merge(['status' => $status]);
            $input = $request->validated();
            $this->handleFileUpload($request, $input, 'image', 'user', false);
            $user = User::create($input);
            $roles = $request->input('role') ? $request->input('role') : [];
            $user->assignRole($roles);

            ActivityLogger::log(
                category: 'pengguna',
                event: 'created',
                message: "Membuat pengguna baru: {$user->name} ({$user->email})",
                subject: $user,
                causer: auth()->user(),
                additionalProperties: [
                    'user_id' => $user->id,
                    'roles' => $roles,
                ]
            );

            return redirect()->route('setting.user.index')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            report($e);

            ActivityLogger::log(
                category: 'pengguna',
                event: 'failed',
                message: 'Gagal membuat pengguna baru',
                causer: auth()->user(),
                additionalProperties: [
                    'error' => $e->getMessage(),
                    'input' => $request->except(['password', 'password_confirmation', '_token']),
                ]
            );

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $page_title = 'Pengguna';
        $page_description = 'Ubah Data';
        $user = User::findOrFail($id);
        $item = Role::where('name', '!=', 'super-admin')->pluck('name', 'name')->toArray();
        $pengurus = Pengurus::status()->doesntHave('user')->orWhere('id', $user->pengurus_id)->get();

        return view('user.edit', compact('page_title', 'page_description', 'user', 'item', 'pengurus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $input = $request->validated();
            $user = User::findOrFail($id);

            $this->handleFileUpload($request, $input, 'image', 'user', false);
            $user->update($input);
            $roles = $request->input('role') ? $request->input('role') : [];
            if (! empty($roles)) {
                $user->syncRoles($roles);
            }

            ActivityLogger::log(
                category: 'pengguna',
                event: 'updated',
                message: "Mengubah data pengguna: {$user->name} ({$user->email})",
                subject: $user,
                causer: auth()->user(),
                additionalProperties: [
                    'user_id' => $user->id,
                    'roles' => $roles,
                    'changes' => $user->getChanges(),
                ]
            );

            return redirect()->route('setting.user.index')->with('success', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            report($e);

            ActivityLogger::log(
                category: 'pengguna',
                event: 'failed',
                message: 'Gagal mengubah data pengguna',
                causer: auth()->user(),
                additionalProperties: [
                    'error' => $e->getMessage(),
                    'user_id' => $id,
                ]
            );

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function updatePassword(UserUpdateRequest $request, $id)
    {
        try {
            $user_find = User::findOrFail($id);

            $user = $user_find->update($request->all());
            $user_find->update([
                'password' => bcrypt($request->password),
            ]);

            ActivityLogger::log(
                category: 'pengguna',
                event: 'password_updated',
                message: "Mengubah password pengguna: {$user_find->name} ({$user_find->email})",
                subject: $user_find,
                causer: auth()->user(),
                additionalProperties: [
                    'user_id' => $user_find->id,
                ]
            );

            flash()->success('Password pengguna berhasil diperbarui');

            return redirect()->route('setting.user.index');
        } catch (\Exception $e) {
            report($e);
            
            ActivityLogger::log(
                category: 'pengguna',
                event: 'failed',
                message: 'Gagal mengubah password pengguna',
                causer: auth()->user(),
                additionalProperties: [
                    'error' => $e->getMessage(),
                    'user_id' => $id,
                ]
            );
            
            flash()->error('Gagal mengubah password pengguna');

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = 0;
            $user->save();

            ActivityLogger::log(
                category: 'pengguna',
                event: 'suspended',
                message: "Menonaktifkan pengguna: {$user->name} ({$user->email})",
                subject: $user,
                causer: auth()->user(),
                additionalProperties: [
                    'user_id' => $user->id,
                    'previous_status' => 1,
                    'new_status' => 0,
                ]
            );

            flash()->success('Pengguna berhasil dinonaktifkan');

            return redirect()->route('setting.user.index');
        } catch (\Exception $e) {
            report($e);
            
            ActivityLogger::log(
                category: 'pengguna',
                event: 'failed',
                message: 'Gagal menonaktifkan pengguna',
                causer: auth()->user(),
                additionalProperties: [
                    'error' => $e->getMessage(),
                    'user_id' => $id,
                ]
            );
            
            flash()->error('Gagal menonaktifkan pengguna');

            return redirect()->route('setting.user.index');
        }
    }

    /**
     * Active User
     *
     * @param  int  $id
     * @return Response
     */
    public function active($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = 1;
            $user->save();

            ActivityLogger::log(
                category: 'pengguna',
                event: 'activated',
                message: "Mengaktifkan pengguna: {$user->name} ({$user->email})",
                subject: $user,
                causer: auth()->user(),
                additionalProperties: [
                    'user_id' => $user->id,
                    'previous_status' => 0,
                    'new_status' => 1,
                ]
            );

            flash()->success('Pengguna berhasil diaktifkan');

            return redirect()->route('setting.user.index');
        } catch (\Exception $e) {
            report($e);
            
            ActivityLogger::log(
                category: 'pengguna',
                event: 'failed',
                message: 'Gagal mengaktifkan pengguna',
                causer: auth()->user(),
                additionalProperties: [
                    'error' => $e->getMessage(),
                    'user_id' => $id,
                ]
            );
            
            flash()->error('Gagal mengaktifkan pengguna');

            return redirect()->route('setting.user.index');
        }
    }

    /**
     * Gets the data.
     */
    public function getDataUser()
    {
        return DataTables::of(User::datatables())
        ->editColumn('status', function ($user) {
            return $user->status == 1 ? 'Active' : 'Not Active';
        })
        ->addColumn('aksi', function ($user) {
            if ($user->id != 1) {
                if ($user->status == 1) {
                    $data['suspend_url'] = route('setting.user.destroy', $user->id);
                } else {
                    $data['active_url'] = route('setting.user.active', $user->id);
                }
            }

            $data['edit_url'] = route('setting.user.edit', $user->id);

            return view('forms.aksi', $data);
        })
        ->make(true);
    }
}
