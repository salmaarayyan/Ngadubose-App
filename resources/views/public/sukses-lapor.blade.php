<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGADUBOSE - Laporan Berhasil Dikirim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#F0F7FF] text-slate-800 font-sans min-h-screen flex flex-col justify-between">

    <main class="max-w-xl w-full mx-auto px-6 py-16 flex-grow flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-8 text-center space-y-6 w-full">
            
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto text-4xl shadow-sm">
                <i class="fas fa-check-circle"></i>
            </div>

            <div class="space-y-2">
                <h1 class="text-2xl font-black text-[#002B5B]">Laporan Anda Berhasil Dikirim!</h1>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Terima kasih telah berpartisipasi menjaga integritas pelayanan. Laporan Anda telah aman tersimpan di dalam sistem inkubator WBS BPS Kabupaten Bantul.
                </p>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-2">
                <span class="text-xs font-bold text-slate-400 tracking-wider uppercase block">Kode Tiket Pelacakan Anda</span>
                <span class="font-mono text-2xl font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-4 py-1.5 rounded-lg inline-block select-all tracking-wide">
                    {{ session('kode_tiket') }}
                </span>
                <p class="text-[11px] text-amber-600 font-medium pt-1">
                    <i class="fas fa-exclamation-triangle"></i> Silakan salin dan simpan kode ini dengan baik untuk memantau status perkembangan laporan Anda secara berkala.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ url('/lacak-status?kode_tiket=' . session('kode_tiket')) }}" class="flex-1 bg-[#002B5B] hover:bg-blue-900 text-white text-sm font-bold py-3 px-4 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                    <i class="fas fa-search-location"></i> Lacak Status Sekarang
                </a>
                <a href="{{ url('/') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>

        </div>
    </main>

    @include('components.footer')

</body>
</html>