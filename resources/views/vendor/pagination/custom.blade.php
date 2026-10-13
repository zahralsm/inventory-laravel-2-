@if ($paginator->hasPages() || $paginator->total() > 0)
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding: 14px 4px; border-top: 1px solid var(--border); margin-top: 15px;">
        <div style="font-size: 13px; color: var(--muted);">
            Menampilkan <strong>{{ $paginator->firstItem() ?? 0 }}</strong> sampai <strong>{{ $paginator->lastItem() ?? 0 }}</strong> dari <strong>{{ $paginator->total() }}</strong> data
        </div>

        @if ($paginator->hasPages())
            <div style="display: flex; gap: 6px; align-items: center;">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span style="display: inline-flex; align-items: center; padding: 6px 12px; font-size: 13px; font-weight: 500; border-radius: 6px; background: #f5f5f5; color: #aaa; border: 1px solid #e0e0e0; cursor: not-allowed;">
                        &laquo; Sebelumnya
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: inline-flex; align-items: center; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 6px; background: #ffffff; color: var(--text); border: 1px solid var(--border); text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f0f4f1'" onmouseout="this.style.background='#ffffff'">
                        &laquo; Sebelumnya
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; font-size: 13px; color: var(--muted);">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 8px; font-size: 13px; font-weight: 700; border-radius: 6px; background: var(--sage); color: #ffffff; border: 1px solid var(--sage-dark);">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 8px; font-size: 13px; font-weight: 600; border-radius: 6px; background: #ffffff; color: var(--text); border: 1px solid var(--border); text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f0f4f1'" onmouseout="this.style.background='#ffffff'">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: inline-flex; align-items: center; padding: 6px 12px; font-size: 13px; font-weight: 600; border-radius: 6px; background: #ffffff; color: var(--text); border: 1px solid var(--border); text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f0f4f1'" onmouseout="this.style.background='#ffffff'">
                        Berikutnya &raquo;
                    </a>
                @else
                    <span style="display: inline-flex; align-items: center; padding: 6px 12px; font-size: 13px; font-weight: 500; border-radius: 6px; background: #f5f5f5; color: #aaa; border: 1px solid #e0e0e0; cursor: not-allowed;">
                        Berikutnya &raquo;
                    </span>
                @endif
            </div>
        @endif
    </div>
@endif
