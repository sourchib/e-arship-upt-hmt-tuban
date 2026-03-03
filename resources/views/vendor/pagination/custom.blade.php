@if ($paginator->hasPages())
    <div class="custom-pagination-wrapper">
        <div class="pagination-info">
            Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> results
        </div>
        
        <nav class="pagination-nav">
            <ul class="pagination-list">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">&laquo; Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" data-page="{{ $paginator->currentPage() - 1 }}">&laquo; Previous</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}" data-page="{{ $page }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" data-page="{{ $paginator->currentPage() + 1 }}">Next &raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Next &raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@else
    <div class="custom-pagination-wrapper">
        <div class="pagination-info">
            Showing <strong>{{ $paginator->firstItem() ?? 0 }}</strong> to <strong>{{ $paginator->lastItem() ?? 0 }}</strong> of <strong>{{ $paginator->total() }}</strong> results
        </div>
    </div>
@endif
