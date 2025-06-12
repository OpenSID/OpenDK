<div>
    @if ($paginator->hasPages())
        <nav>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="disabled">
                        <span>@lang('pagination.previous')</span>
                    </li>
                @else
                    @if (method_exists($paginator, 'getCursorName'))
                        <li>
                            <a href="javascript:void(0);" dusk="previousPage" wire:click="setPage('{{ $paginator->previousCursor()->encode() }}', '{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" rel="prev">@lang('pagination.previous')</a>
                        </li>
                    @else
                        <li>
                            <a href="javascript:void(0);" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev">@lang('pagination.previous')</a>
                        </li>
                    @endif
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    @if (method_exists($paginator, 'getCursorName'))
                        <li>
                            <a href="javascript:void(0);" dusk="nextPage" wire:click="setPage('{{ $paginator->nextCursor()->encode() }}', '{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" rel="next">@lang('pagination.next')</a>
                        </li>
                    @else
                        <li>
                            <a href="javascript:void(0);" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next">@lang('pagination.next')</a>
                        </li>
                    @endif
                @else
                    <li class="disabled">
                        <span>@lang('pagination.next')</span>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>
