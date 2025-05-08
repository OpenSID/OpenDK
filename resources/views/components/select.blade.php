<select {!! $attributes !!} {{ $attributes->merge(['class' => 'form-control input-sm']) }}>
    {{ $slot }}
</select>
