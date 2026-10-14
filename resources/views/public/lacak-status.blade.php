<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGADUBOSE - Lacak Status Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#F0F7FF] text-gray-900 min-h-screen flex flex-col justify-between">

    @include('components.navbar')

    <main class="max-w-6xl w-full mx-auto px-4 py-8 flex-grow">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
            <h2 class="text-lg font-bold text-[#002B5B] mb-2 text-center">Lacak Status Laporan Anda</h2>
            <p class="text-xs text-gray-500 text-center mb-4">Masukkan kode tiket resmi (WBS-XXXX) yang Anda dapatkan setelah mengirim laporan.</p>
            
            <form action="{{ url('/lacak-status') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                <input 
                    type="text" 
                    name="kode_tiket" 
                    value="{{ $ticketCode ?? '' }}" 
                    placeholder="Contoh: WBS-2026-X7F2K" 
                    required
                    class="flex-grow px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono"
                >
                <button type="submit" class="bg-[#002B5B] text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-blue-900 transition flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Cari Laporan
                </button>
            </form>

            @if(isset($error) && $error)
                <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex gap-3 items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5 shadow-sm"></i>
                    <div>
                        <p class="text-sm text-red-700 font-medium">{{ $error }}</p>
                        <a href="{{ url('/form-lapor') }}" class="text-xs text-red-600 underline font-semibold mt-1 inline-block hover:text-red-800">
                            Kembali ke Formulir Pengaduan &rarr;
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if(isset($ticket) && $ticket)
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-[#002B5B]">Detail Pelacakan Laporan</h1>
                <p class="text-sm text-gray-500">Kode Tiket: <span class="font-mono font-bold text-gray-700 bg-gray-200 px-2 py-0.5 rounded">{{ $ticket->ticket_code }}</span></p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#002B5B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Rincian Laporan Anda
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                            <div>
                                <span class="text-gray-400 block">Klasifikasi</span>
                                <span class="font-semibold text-gray-700">{{ $ticket->klasifikasi ?? 'Pengaduan' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Kategori Pelanggaran</span>
                                <span class="font-semibold text-gray-700 uppercase">{{ str_replace('_', ' ', $ticket->jenis_pengaduan) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Nama Pelapor</span>
                                <span class="font-semibold text-gray-700">{{ $ticket->nama_pelapor ?? 'Anonim' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Nama Terlapor</span>
                                <span class="font-semibold text-gray-700">{{ $ticket->nama_terlapor }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Tanggal Kejadian</span>
                                <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($ticket->tanggal_kejadian)->translatedFormat('d F Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Tanggal Masuk Sistem</span>
                                <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y - H:i') }} WIB</span>
                            </div>
                        </div>

                        <div class="text-sm border-t border-gray-50 pt-4">
                            <span class="text-gray-400 block mb-1">Uraian Kronologi Kejadian</span>
                            <div class="bg-gray-50 p-4 rounded-lg text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ $ticket->uraian }}
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN RIWAYAT TANGGAPAN / CATATAN ADMIN BPS -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Riwayat Tanggapan & Catatan Admin BPS
                        </h2>
                        
                        @php
                            $progressList = $ticket->ticketProgress()->with('user')->latest()->get();
                        @endphp

                        @if($progressList->count() > 0)
                            <div class="space-y-4">
                                @foreach($progressList as $progress)
                                    <div class="flex items-start gap-4 bg-amber-50/60 p-4 rounded-lg border border-amber-100 text-sm">
                                        <div class="bg-[#002B5B] text-white p-2 rounded-full shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        <div class="w-full">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-bold text-[#002B5B]">
                                                    {{ $progress->user->nama ?? 'Admin BPS' }} 
                                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 bg-white rounded border border-amber-200 ml-1 text-slate-600">Status: {{ $progress->status }}</span>
                                                </span>
                                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($progress->created_at)->translatedFormat('d M Y - H:i') }} WIB</span>
                                            </div>
                                            <p class="text-gray-700 leading-relaxed mt-2">
                                                {{ $progress->catatan_admin }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic text-center py-4">Belum ada catatan atau tanggapan resmi dari tim pemeriksa BPS untuk saat ini.</p>
                        @endif
                    </div>

                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Status Penanganan
                        </h2>

                        <!-- Ambil status saat ini secara akurat dari kolom status di tabel tickets -->
                        @php 
                            $currentStatus = strtolower($ticket->status ?? 'diterima'); 
                        @endphp

                        <div class="relative pl-6 space-y-8 before:absolute before:bottom-2 before:top-2 before:left-[9px] before:w-0.5 before:bg-gray-200">
                            
                            <!-- Step 1: Diterima -->
                            <div class="relative">
                                <span class="absolute -left-[23px] top-0.5 bg-green-500 text-white rounded-full p-1 z-10">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <h4 class="text-sm font-bold text-gray-800">Laporan Diterima</h4>
                                <p class="text-xs text-gray-500">Laporan sukses masuk ke sistem antrean WBS.</p>
                            </div>

                            <!-- Step 2: Diverifikasi -->
                            <div class="relative {{ in_array($currentStatus, ['diverifikasi', 'diproses', 'selesai']) ? '' : 'opacity-40' }}">
                                @if(in_array($currentStatus, ['diverifikasi', 'diproses', 'selesai']))
                                    <span class="absolute -left-[23px] top-0.5 bg-green-500 text-white rounded-full p-1 z-10">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                @else
                                    <span class="absolute -left-[19px] top-1.5 bg-gray-300 rounded-full h-2 w-2 z-10"></span>
                                @endif
                                <h4 class="text-sm font-bold text-gray-800">Verifikasi Internal</h4>
                                <p class="text-xs text-gray-500">Pemeriksaan dokumen dan bukti aduan oleh tim verifikator.</p>
                            </div>

                            <!-- Step 3: Diproses -->
                            <div class="relative {{ in_array($currentStatus, ['diproses', 'selesai']) ? '' : 'opacity-40' }}">
                                @if($currentStatus == 'diproses')
                                    <span class="absolute -left-[22px] top-1 flex h-4 w-4 z-10">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-[#002B5B]"></span>
                                    </span>
                                @elseif($currentStatus == 'selesai')
                                    <span class="absolute -left-[23px] top-0.5 bg-green-500 text-white rounded-full p-1 z-10">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                @else
                                    <span class="absolute -left-[19px] top-1.5 bg-gray-300 rounded-full h-2 w-2 z-10"></span>
                                @endif
                                <h4 class="text-sm font-bold text-gray-800">Proses Tindak Lanjut</h4>
                                @if($currentStatus == 'diproses')
                                    <span class="text-[10px] text-blue-600 font-bold bg-blue-50 px-1.5 py-0.5 rounded inline-block mt-0.5">Sedang Berjalan</span>
                                @endif
                                <p class="text-xs text-gray-500">Tim inspektorat/atasan sedang melakukan investigasi lapangan.</p>
                            </div>

                            <!-- Step 4: Selesai -->
                            <div class="relative {{ $currentStatus == 'selesai' ? '' : 'opacity-40' }}">
                                @if($currentStatus == 'selesai')
                                    <span class="absolute -left-[22px] top-1 flex h-4 w-4 z-10">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-600"></span>
                                    </span>
                                @else
                                    <span class="absolute -left-[19px] top-1.5 bg-gray-300 rounded-full h-2 w-2 z-10"></span>
                                @endif
                                <h4 class="text-sm font-bold text-gray-800">Umpan Balik Selesai</h4>
                                @if($currentStatus == 'selesai')
                                    <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded inline-block mt-0.5">Selesai</span>
                                @endif
                                <p class="text-xs text-gray-500">Pemberian solusi akhir dan penutupan berkas laporan.</p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        @elseif(!isset($error) || !$error)
            <div class="text-center py-16 opacity-60 max-w-sm mx-auto">
                <div class="text-6xl text-slate-300 mb-4">
                    <i class="fas fa-search-location"></i>
                </div>
                <h3 class="text-base font-bold text-slate-700">Belum Ada Riwayat Pencarian</h3>
                <p class="text-xs text-slate-500 mt-1">Silakan masukkan kode tiket pengaduan Anda pada form di atas untuk melihat rincian progres penanganan dari BPS.</p>
            </div>
        @endif

    </main>

</body>
</html>