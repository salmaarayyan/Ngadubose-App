<div class="space-y-6">
    
    <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-800">Manajemen Laporan / Tiket WBS</h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola dan pantau seluruh laporan pengaduan yang masuk dari masyarakat.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <select wire:model.live="statusFilter" class="bg-slate-50 border border-slate-200 text-slate-600 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-[#002B5B]">
                <option value="">Semua Status</option>
                @foreach(\App\Enums\StatusLaporan::cases() as $status)
                    <option value="{{ $status->value }}">{{ $status->value }}</option>
                @endforeach
            </select>

            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       class="w-full sm:w-64 pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-[#002B5B] focus:bg-white transition" 
                       placeholder="Cari kode, judul, pelapor...">
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Kode Tiket</th>
                        <th class="py-4 px-6">Pelapor</th>
                        <th class="py-4 px-6">Judul & Klasifikasi</th>
                        <th class="py-4 px-6">Jenis Pengaduan</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse ($tickets as $ticket)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-[#002B5B]">
                                {{ $ticket->ticket_code }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-700">{{ $ticket->nama_pelapor ?? 'Anonim' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800 line-clamp-1">{{ $ticket->judul_laporan }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $ticket->klasifikasi ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-[10px] font-semibold text-slate-600 uppercase">
                                    {{ str_replace('_', ' ', $ticket->jenis_pengaduan) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statusColors = [
                                        'Diterima' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Diverifikasi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Diproses' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 border rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$ticket->status] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="showDetail({{ $ticket->id }})" class="bg-blue-50 hover:bg-blue-100 text-[#002B5B] p-2 rounded-lg transition cursor-pointer" title="Lihat Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                @if($search || $statusFilter)
                                    Tidak ditemukan laporan yang sesuai dengan filter pencarian Anda.
                                @else
                                    Belum ada data laporan pengaduan masuk.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $tickets->links('components.pagination') }}
        </div>
    </div>

    @if($isDetailOpen && $selectedTicket)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden max-h-[90vh] flex flex-col">
                
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Detail Laporan: {{ $selectedTicket->ticket_code }}</h3>
                        <p class="text-[10px] text-slate-400">Masuk pada {{ $selectedTicket->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <button wire:click="closeDetail" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 overflow-y-auto text-xs text-slate-600 flex-1">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Pelapor</span>
                            <span class="font-bold text-slate-800">{{ $selectedTicket->nama_pelapor ?? 'Anonim' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Terlapor</span>
                            <span class="font-bold text-slate-800">{{ $selectedTicket->nama_terlapor }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-3 rounded-xl">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Jenis Pengaduan</span>
                            <span class="font-bold text-slate-800 uppercase">{{ str_replace('_', ' ', $selectedTicket->jenis_pengaduan) }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Tanggal Kejadian</span>
                            <span class="font-bold text-slate-800">{{ $selectedTicket->tanggal_kejadian }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Judul Laporan</span>
                        <div class="font-bold text-slate-800 bg-slate-50 p-3 rounded-xl">{{ $selectedTicket->judul_laporan }}</div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Uraian / Kronologi</span>
                        <div class="bg-slate-50 p-3 rounded-xl whitespace-pre-line leading-relaxed">{{ $selectedTicket->uraian }}</div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Ubah Status Laporan:</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(\App\Enums\StatusLaporan::cases() as $stEnum)
                                <button type="button" 
                                        wire:click="updateStatus({{ $selectedTicket->id }}, '{{ $stEnum->value }}')"
                                        class="px-3 py-1.5 rounded-xl font-bold uppercase text-[10px] transition cursor-pointer border {{ $selectedTicket->status === $stEnum->value ? 'bg-[#002B5B] text-white border-[#002B5B]' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                                    {{ $stEnum->value }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end shrink-0">
                    <button type="button" wire:click="closeDetail" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition cursor-pointer">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>