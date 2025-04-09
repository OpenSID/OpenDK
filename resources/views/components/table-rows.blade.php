@props(['sortable' => false])

<tbody id="{{ $attributes->get('id', 'sortable') }}" {{ $attributes }}>
    {{ $slot }}
</tbody>

@if ($sortable)
    @push('styles')
        <style>
            .drag-handle {
                cursor: grab;
            }

            .drag-handle:active {
                cursor: grabbing;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let el = document.getElementById('sortable');

                if (!el) return;

                new Sortable(el, {
                    handle: ".drag-handle",
                    animation: 150,
                    onEnd: function(evt) {
                        let items = [];
                        el.querySelectorAll("tr").forEach((row, index) => {
                            items.push(row.getAttribute("data-id"));
                        });

                        @this.call('updateOrder', items)
                    }
                });
            });
        </script>
    @endpush
@endif
