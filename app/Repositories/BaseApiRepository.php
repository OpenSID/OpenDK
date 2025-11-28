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

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseApiRepository implements BaseRepositoryInterface
{
    protected Model|Builder $model;
    protected array $allowedFilters = [];
    protected array $allowedSorts = [];
    protected array $allowedIncludes = [];
    protected string $defaultSort = '-created_at';

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * Find record by ID
     *
     * @param int $id
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find record by slug
     *
     * @param string $slug
     * @param array $columns
     * @return Model|null
     */
    public function findBySlug(string $slug, array $columns = ['*'])
    {
        return $this->model->where('slug', $slug)->first($columns);
    }

    /**
     * Create new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data)
    {
        return $this->find($id)->update($data);
    }

    /**
     * Delete record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Apply filters to query
     *
     * @param array $filters
     * @return self
     */
    public function applyFilters(array $filters)
    {
        foreach ($filters as $key => $value) {
            if ($value !== null) {
                $this->model = $this->model->where($key, $value);
            }
        }

        return $this;
    }

    /**
     * Apply search to query
     *
     * @param string $search
     * @param array $fields
     * @return self
     */
    public function applySearch(string $search, array $fields = [])
    {
        if (empty($search) || empty($fields)) {
            return $this;
        }

        $this->model = $this->model->where(function (Builder $query) use ($search, $fields) {
            foreach ($fields as $field) {
                $query->orWhere($field, 'LIKE', "%{$search}%");
            }
        });

        return $this;
    }

    /**
     * Apply sorting to query
     *
     * @param string $field
     * @param string $direction
     * @return self
     */
    public function applySorting(string $field, string $direction = 'desc')
    {
        $this->model = $this->model->orderBy($field, $direction);

        return $this;
    }

    /**
     * Apply relationships to query
     *
     * @param array $relations
     * @return self
     */
    public function with(array $relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * Get the underlying query builder
     *
     * @return Builder
     */
    protected function getQuery(): Builder
    {
        return $this->model instanceof Builder ? $this->model : $this->model->newQuery();
    }

    /**
     * Get query builder with Spatie Query Builder (if available)
     * Laravel 11 compatibility: Ensure we pass a Builder instance
     */
    protected function getQueryBuilder()
    {
        $query = $this->model instanceof Builder ? $this->model : $this->model->newQuery();
        return QueryBuilder::for($query);
    }

    /**
     * Apply custom filters
     */
    protected function addCustomFilter(string $name, callable $callback): self
    {
        $this->allowedFilters[] = AllowedFilter::callback($name, $callback);
        return $this;
    }

    /**
     * Apply custom sorts
     */
    protected function addCustomSort(string $field): self
    {
        $this->allowedSorts[] = AllowedSort::callback('sort', $field);
        return $this;
    }

    /**
     * Add allowed includes
     */
    protected function addAllowedInclude(string $relation): self
    {
        $this->allowedIncludes[] = $relation;
        return $this;
    }

    protected function getFilteredApi()
    {
        return $this->getQueryBuilder()->allowedFilters($this->allowedFilters)->allowedSorts($this->allowedSorts)->allowedIncludes($this->allowedIncludes);
    }
}