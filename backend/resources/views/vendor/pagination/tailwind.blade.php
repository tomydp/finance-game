{{-- resources/views/vendor/pagination/tailwind.blade.php --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex justify-start">
        <ul class="inline-flex items-center overflow-hidden rounded-full bg-white text-slate-700 shadow-sm ring-1 ring-slate-600 text-base">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span
                        class="inline-flex h-10 items-center px-4 select-none text-slate-400"
                        aria-disabled="true"
                        aria-label="@lang('pagination.previous')"
                    >‹</span>
                </li>
            @else
                <li>
                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        rel="prev"
                        class="inline-flex h-10 items-center px-5 hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                        aria-label="@lang('pagination.previous')"
                    >‹</a>
                </li>
            @endif

            {{-- Elements --}}
            @foreach ($elements as $element)
                {{-- Separador "..." --}}
                @if (is_string($element))
                    <li class="border-l border-slate-300">
                        <span class="inline-flex h-10 items-center px-4 select-none text-slate-400">{{ $element }}</span>
                    </li>
                @endif

                {{-- Páginas --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="border-l border-slate-200">
                                <span
                                    class="inline-flex h-10 items-center px-4 bg-blue-600 text-white select-none"
                                    aria-current="page"
                                >{{ $page }}</span>
                            </li>
                        @else
                            <li class="border-l border-slate-200">
                                <a
                                    href="{{ $url }}"
                                    class="inline-flex h-10 items-center px-4 hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                                >{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="border-l border-slate-200">
                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        rel="next"
                        class="inline-flex h-10 items-center px-5 hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                        aria-label="@lang('pagination.next')"
                    >›</a>
                </li>
            @else
                <li class="border-l border-slate-200">
                    <span
                        class="inline-flex h-10 items-center px-4 select-none text-slate-400"
                        aria-disabled="true"
                        aria-label="@lang('pagination.next')"
                    >›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
