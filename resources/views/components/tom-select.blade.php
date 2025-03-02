<select x-data="{ value: @entangle($attributes->wire('model')).defer }" x-init="new TomSelect($el, {
    sortField: {
        field: 'name',
        direction: 'asc'
    },
    valueField: 'id',
    labelField: 'name',
    searchField: 'name'
});" {{ $attributes }}>
    {{ $slot }}
</select>
