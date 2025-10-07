@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        {{-- Mobile --}}
        <div class="flex justify-between flex-1 sm:hidden">
            <button type="button"
                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                @disabled($paginator->onFirstPage())
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md">
                « Anterior
            </button>

            <button type="button"
                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                @disabled(! $paginator->hasMorePages())
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md">
                Siguiente »
            </button>
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div class="text-sm text-gray-700">
                Mostrando
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                a
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                de
                <span class="font-medium">{{ $paginator->total() }}</span>
                resultados
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {{-- Prev --}}
                    <button type="button"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        @disabled($paginator->onFirstPage())
                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium border rounded-l-md">
                        ‹
                    </button>

                    {{-- Numbers --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium border">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <button type="button"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    @class([
                                        'relative inline-flex items-center px-4 py-2 text-sm font-medium border',
                                        'bg-gray-100 font-semibold' => $page === $paginator->currentPage(),
                                    ])>
                                    {{ $page }}
                                </button>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next --}}
                    <button type="button"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        @disabled(! $paginator->hasMorePages())
                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium border rounded-r-md">
                        ›
                    </button>
                </span>
            </div>
        </div>
    </nav>
@endif
