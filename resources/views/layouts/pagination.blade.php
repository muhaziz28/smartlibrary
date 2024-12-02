@if ($paginator->hasPages())
<ul class="pagination m-0 ms-auto">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <li class="page-item disabled">
        <span class="page-link">
            <!-- Your custom SVG icon or text for previous page -->
            prev
        </span>
    </li>
    @else
    <li class="page-item">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
            <!-- Your custom SVG icon or text for previous page -->
            prev
        </a>
    </li>
    @endif

    {{-- Pagination Elements --}}
    {!! $paginator->render() !!}

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <li class="page-item">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
            <!-- Your custom SVG icon or text for next page -->
            next
        </a>
    </li>
    @else
    <li class="page-item disabled">
        <span class="page-link">
            <!-- Your custom SVG icon or text for next page -->
            next
        </span>
    </li>
    @endif
</ul>
@endif