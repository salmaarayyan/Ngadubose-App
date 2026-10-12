@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white px-6 py-4 border-t border-slate-100">
        <div class="text-xs text-slate-500">
            Menampilkan 
            <span class="font-bold text-slate-700">{{ $paginator->firstItem() }}</span> 
            sampai 
            <span class="font-bold text-slate-700">{{ $paginator->lastItem() }}</span> 
            dari 
            <span class="font-bold text-slate-700">{{ $paginator->total() }}</span> 
            data
        </div>

        <div class="flex items-center gap-1.5">
            
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-xs font-medium text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed">
                    <i class="fas fa-chevron-left mr-1"></i> Prev
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" 
                        class="px-3 py-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition cursor-pointer">
                    <i class="fas fa-chevron-left mr-1"></i> Prev
                </button>
            @endif

            {{-- Nomor Halaman & Ellipsis Logic --}}
            @foreach ($elements as $element)
                {-- "Three Dots" Separator --}
                @if (is_string($element))
                    <span class="px-3 py-2 text-xs text-slate-400 font-medium">{{ $element }}</span>
                @endif

                {-- Array Of Links --}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" 
                                  class="px-3.5 py-2 text-xs font-black text-white bg-[#002B5B] border border-[#002B5B] rounded-xl shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" 
                                    class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition cursor-pointer">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" 
                        class="px-3 py-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition cursor-pointer">
                    Next <i class="fas fa-chevron-right ml-1"></i>
                </button>
            @else
                <span class="px-3 py-2 text-xs font-medium text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed">
                    Next <i class="fas fa-chevron-right ml-1"></i>
                </span>
            @endif

        </div>
    </nav>
@endif