@if ($paginator->hasPages())
    <nav class="navigation pagination" aria-label="Posts pagination">
        <h2 class="screen-reader-text" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0;">Posts pagination</h2>
        <div class="nav-links" style="display: flex; align-items: center; justify-content: center; gap: 4px; flex-wrap: wrap;">
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="page-numbers dots" style="padding: 8px 12px; color: #999;">…</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="page-numbers current" style="padding: 8px 12px; background: #f8c2eb; color: #000; border-radius: 4px; font-weight: 700; display: inline-block;">{{ $page }}</span>
                        @else
                            <a class="page-numbers" href="{{ $url }}" style="padding: 8px 12px; color: #333; text-decoration: none; border-radius: 4px; transition: all 0.3s; display: inline-block;" onmouseover="this.style.background='#f8c2eb'; this.style.color='#000'" onmouseout="this.style.background='transparent'; this.style.color='#333'">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="next page-numbers" href="{{ $paginator->nextPageUrl() }}" style="padding: 8px 12px; color: #333; text-decoration: none; border-radius: 4px; transition: all 0.3s; display: inline-block;" onmouseover="this.style.background='#f8c2eb'; this.style.color='#000'" onmouseout="this.style.background='transparent'; this.style.color='#333'">
                    Next
                </a>
            @else
                <span class="page-numbers disabled" style="padding: 8px 12px; color: #999; pointer-events: none; display: inline-block;">Next</span>
            @endif
        </div>
    </nav>
@endif

