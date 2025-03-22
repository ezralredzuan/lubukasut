@if ($paginator->hasPages())
  <div class="card-body d-flex justify-content-center">
    <nav aria-label="Page navigation">
    <ul class="pagination" style="font-size: 18px;">

      {{-- First Page --}}
      @if (!$paginator->onFirstPage())
      <li class="page-item first">
      <a class="page-link" href="{{ $paginator->appends(request()->query())->url(1) }}">
      <i class="tf-icon ri-skip-back-mini-line" style="font-size: 24px;"></i>
      </a>
      </li>
    @endif

      {{-- Previous Page --}}
      @if ($paginator->onFirstPage())
      <li class="page-item prev disabled">
      <a class="page-link" href="javascript:void(0);">
      <i class="tf-icon ri-arrow-left-s-line" style="font-size: 24px;"></i>
      </a>
      </li>
    @else
      <li class="page-item prev">
      <a class="page-link" href="{{ $paginator->appends(request()->query())->previousPageUrl() }}">
      <i class="tf-icon ri-arrow-left-s-line" style="font-size: 24px;"></i>
      </a>
      </li>
    @endif

      {{-- Page Numbers --}}
      @foreach ($elements as $element)
      @if (is_array($element))
      @foreach ($element as $page => $url)
      <li class="page-item {{ ($page == $paginator->currentPage()) ? 'active' : '' }}">
      <a class="page-link" href="{{ $paginator->appends(request()->query())->url($page) }}">{{ $page }}</a>
      </li>
    @endforeach
    @endif
    @endforeach

      {{-- Next Page --}}
      @if ($paginator->hasMorePages())
      <li class="page-item next">
      <a class="page-link" href="{{ $paginator->appends(request()->query())->nextPageUrl() }}">
      <i class="tf-icon ri-arrow-right-s-line" style="font-size: 24px;"></i>
      </a>
      </li>
    @else
      <li class="page-item next disabled">
      <a class="page-link" href="javascript:void(0);">
      <i class="tf-icon ri-arrow-right-s-line" style="font-size: 24px;"></i>
      </a>
      </li>
    @endif

      {{-- Last Page --}}
      @if ($paginator->hasMorePages())
      <li class="page-item last">
      <a class="page-link" href="{{ $paginator->appends(request()->query())->url($paginator->lastPage()) }}">
      <i class="tf-icon ri-skip-forward-mini-line" style="font-size: 24px;"></i>
      </a>
      </li>
    @endif

    </ul>
    </nav>
  </div>
@endif
