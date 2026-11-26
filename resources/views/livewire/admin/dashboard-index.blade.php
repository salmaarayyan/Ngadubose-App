<div class="space-y-8" wire:poll.10s>
    
    <div class="bg-white border-b border-slate-100 px-8 py-4 flex justify-between items-center shadow-sm rounded-2xl">
        <h2 class="text-base font-bold text-slate-700">Statistik Sistem</h2>
        <span class="text-xs text-slate-400 font-mono bg-slate-50 px-2 py-1 rounded">Hari ini: {{ date('d M Y') }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Semua Laporan</span>
                <span class="text-3xl font-black text-[#002B5B] mt-1 block">{{ $totalTickets }}</span>
            </div>
            <div class="bg-blue-50 text-[#002B5B] w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-folder-open"></i>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Laporan Diproses</span>
                <span class="text-3xl font-black text-blue-600 mt-1 block">{{ $statusDiproses }}</span>
            </div>
            <div class="bg-blue-50 text-blue-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-spinner animate-spin"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Laporan Selesai</span>
                <span class="text-3xl font-black text-emerald-600 mt-1 block">{{ $statusSelesai }}</span>
            </div>
            <div class="bg-emerald-50 text-emerald-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm lg:col-span-2 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-700 uppercase tracking-wide mb-1">Proporsi Status Penanganan</h3>
                <p class="text-xs text-slate-400 mb-4">Persentase laporan berdasarkan status riil di sistem.</p>
            </div>
            <div class="w-full max-w-[240px] mx-auto py-2" wire:ignore>
                <canvas id="chartStatus"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm lg:col-span-3 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-700 uppercase tracking-wide mb-1">Jumlah Kategori Pelanggaran</h3>
                <p class="text-xs text-slate-400 mb-4">Grafik frekuensi aduan berdasarkan jenis topik pelanggaran.</p>
            </div>
            <div class="w-full h-full min-h-[240px]" wire:ignore>
                <canvas id="chartKategori"></canvas>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stats = [
                {{ $statusDiterima }}, 
                {{ $statusDiverifikasi }}, 
                {{ $statusDiproses }}, 
                {{ $statusSelesai }}
            ];
            
            const kategori = [
                {{ $gratifikasi }}, 
                {{ $benturanKepentingan }}, 
                {{ $korupsi }}, 
                {{ $pelanggaranAturan }}, 
                {{ $lainnya }}
            ];

            if (typeof window.initAdminCharts === 'function') {
                window.initAdminCharts(stats, kategori);
            }
        });
    </script>
    @endpush

</div>