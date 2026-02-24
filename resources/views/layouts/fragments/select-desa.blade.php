<?php
// pass attributes when including: @include('layouts.fragments.select-desa', ['selectAttributes' => ['data-url'=>'/x','class'=>'custom']])
$selectAttributes = $selectAttributes ?? [];
$selectedOption = $selectedOption ?? null;
// merge passed class with default classes
$mergedClass = trim('form-control select2 ' . ($selectAttributes['class'] ?? ''));
unset($selectAttributes['class']);

// build attribute string (escaped)
$attrParts = [];
foreach ($selectAttributes as $k => $v) {
    $attrParts[] = $k . '="' . e($v) . '"';
}
$attrString = 'class="' . e($mergedClass) . '"' . ($attrParts ? ' ' . implode(' ', $attrParts) : '');
?>
<select {!! $attrString !!} id="list_desa" >
    <option value="Semua">Semua {{ config('setting.sebutan_desa') }}</option>
    @foreach ((new App\Services\DesaService())->listDesa()->pluck('nama', 'desa_id') as $key => $value)
    <option value="{{ $key }}" @selected($selectedOption == $key)>{{ $value }}</option>
    @endforeach
</select>