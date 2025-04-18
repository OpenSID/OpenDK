<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    public const WIDGET_SISTEM  = 1;
    public const WIDGET_STATIS  = 2;
    public const WIDGET_DINAMIS = 3;

    protected $fillable = [
        'isi',
        'enabled',
        'judul',
        'jenis_widget',
        'urut',
        'form_admin',
        'setting',
        'foto'
    ];

    public function scopeSearch($query, $search)
    {
        return empty($search) 
            ? $query 
            : $query->where('judul', 'LIKE', "%{$search}%");
    }

    public function scopeStatusAdmin($query, $status)
    {
        return $query->when(!empty($status), function($query) use($status){
            $query->where('enabled', $status);
        });
    }

    public function scopeGetWidget($query, $id)
    {
        $data = $query->where('id', $id)->get()->map(static function ($item) {
            $item->judul = e($item->judul);
            $item->isi   = htmlentities($item->isi);

            return $item;
        })->toArray();

        return $data[0];
    }

    public function scopeListWidgetBaru(): array
    {
        $allTheme    = theme_new()->orderBy('system', 'desc')->get();

        $list_widget = [];
        
        foreach ($allTheme as $tema) {
            $list        = $this->widget($tema->view_path . '/widgets/*.blade.php');

            $list_widget = array_merge($list_widget, $list);
        }
        return $list_widget;
    }

    public function scopeGetSetting($query, string $widget, $opsi = '')
    {
        // Data di kolom setting dalam format json
        $data    = $query->where('isi', $widget . '.php')->first('setting');
        $setting = json_decode((string) $data['setting'], true);
        if (empty($setting)) {
            return [];
        }

        return empty($opsi) ? $setting : $setting[$opsi];
    }

    /**
     * @return string[]
     */
    public function widget(mixed $lokasi): array
    {
        $this->listWidgetStatis();
        $list_widget = glob($lokasi);

        $l_widget = [];

        foreach ($list_widget as $widget) {
            $l_widget[] = $widget;
        }

        return $l_widget;
    }

    public function listWidgetStatis()
    {
        return static::where('jenis_widget', 2)
            ->pluck('isi')
            ->toArray();
    }

    public function scopeJenis($query, $value)
    {
        if (empty($value)) {
            return $query->whereNotNull('jenis_widget');
        }

        if (is_array($value)) {
            return $query->whereIn('jenis_widget', $value);
        }

        return $query->where('jenis_widget', $value);
    }

    public function scopeStatus($query, $value = 1)
    {
        return $query->where('enabled', $value);
    }

    public function scopeNomorUrut($query, $id, $direction)
    {
        $data = $this->findOrFail($id);

        $currentNo = $data->urut;
        $targetNo  = ($direction == 2) ? $currentNo - 1 : $currentNo + 1;

        $query->where('urut', $targetNo)->update(['urut' => $currentNo]);

        $data->update(['urut' => $targetNo]);

        return $query;
    }

    public function scopeUrutMax($query): int|float
    {
        return $query->orderByDesc('urut')->first()->urut + 1;
    }

    public static function updateUrutan(): void
    {
        $all  = Widget::orderBy('urut')->get();
        $urut = 1;

        foreach ($all as $w) {
            $w->update(['urut' => $urut++]);
        }
    }

    public function getIsiAttribute($value): string
    {
        if ($this->jenis_widget == 2 && strpos($value, '/widgets/') !== false) {
            $value = str_replace('/widgets/', '/resources/views/widgets/', $value);
        }

        if (strpos($value, '.php') !== false && strpos($value, 'blade') === false) {
            $value = preg_replace('/(?<!blade)\.php$/', '.blade.php', $value);
        }

        return str_replace('/resources/views/resources/views/', '/resources/views/', $value);
    }

}
