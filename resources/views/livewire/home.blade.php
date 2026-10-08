<div class="bg-[#F2F7FC] text-slate-800 min-h-screen" style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <style>
        .reveal { opacity: 0; transform: translateY(10px); transition: opacity .5s ease, transform .5s ease; }
        .reveal.visible { opacity: 1; transform: none; }
        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
        }
    </style>

    {{-- HERO --}}
    <section class="bg-[#0B5FA5] text-white">
        <div class="max-w-5xl mx-auto px-6 pt-24 pb-20 text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight leading-[1.05]">
                Whistle Blowing System
            </h1>
            <p class="max-w-3xl mx-auto mt-5 text-base md:text-lg leading-relaxed text-blue-100">
                Ngadubose adalah platform <strong class="text-white">Whistle Blowing System (WBS)</strong> milik BPS Kabupaten Bantul
                untuk melaporkan dugaan pelanggaran, gratifikasi, atau pelayanan buruk dari pegawai BPS Bantul.
            </p>

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-10">
                <a href="{{ url('/form-lapor') }}"
                   class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-[#F28C1B] px-8 py-4 font-bold text-[#0A2F55] transition hover:bg-[#FFA03A] focus:outline-none focus-visible:ring-4 focus-visible:ring-white/50">
                    <i class="fas fa-file-signature" aria-hidden="true"></i>
                    Buat Laporan Baru
                </a>
                <button type="button" wire:click="$toggle('tampilLacak')" aria-expanded="{{ $tampilLacak ? 'true' : 'false' }}"
                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg border-2 border-white/70 px-8 py-4 font-semibold text-white transition hover:bg-white/10 focus:outline-none focus-visible:ring-4 focus-visible:ring-white/50">
                    <i class="fas {{ $tampilLacak ? 'fa-times' : 'fa-search' }}" aria-hidden="true"></i>
                    {{ $tampilLacak ? 'Tutup' : 'Lacak Laporan' }}
                </button>
            </div>

            {{-- Panel lacak (Livewire): muncul saat tombol Lacak Laporan diklik --}}
            @if ($tampilLacak)
                <div wire:transition class="mx-auto mt-8 max-w-xl rounded-xl bg-white p-5 text-left text-slate-800 shadow-lg">
                    <form wire:submit="lacak" novalidate>
                        <label for="kode" class="block text-sm font-semibold text-[#0A2F55]">Masukkan kode tiket Anda</label>
                        <div class="mt-2 flex flex-col sm:flex-row gap-2">
                            <input id="kode" type="text" wire:model.live.debounce.300ms="kode" autocomplete="off" autofocus
                                   placeholder="WBS-2026-0001"
                                   class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-3 font-mono tracking-wide placeholder:text-slate-400 focus:border-[#0B5FA5] focus:outline-none focus:ring-4 focus:ring-[#0B5FA5]/20 @error('kode') border-red-500 @enderror">
                            <button type="submit" wire:loading.attr="disabled"
                                    class="rounded-lg bg-[#0B5FA5] px-6 py-3 font-semibold text-white transition hover:bg-[#0A2F55] disabled:opacity-60 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#0B5FA5]/30">
                                Lacak
                            </button>
                        </div>
                        @error('kode')
                            <p class="mt-2 text-sm text-red-600" role="alert">{{ $message }}</p>
                        @else
                            <p class="mt-2 text-sm text-slate-500">Kode diberikan setelah laporan terkirim. Format: WBS-TAHUN-XXXX.</p>
                        @enderror
                    </form>
                </div>
            @endif
        </div>

        {{-- Garis tiga warna BPS --}}
        <div class="flex h-1.5" aria-hidden="true">
            <span class="flex-1 bg-[#0A2F55]"></span>
            <span class="flex-1 bg-[#3FA535]"></span>
            <span class="flex-1 bg-[#F28C1B]"></span>
        </div>
    </section>

    {{-- ALUR PENANGANAN --}}
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="reveal text-center mb-14">
            <h2 class="text-3xl font-extrabold text-[#0A2F55]">Alur Penanganan Laporan</h2>
            <p class="text-slate-600 mt-2">Berikut adalah langkah-langkah penanganan laporan Anda setiap tahapnya.</p>
        </div>

        <div class="reveal relative grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-8">
            <div class="hidden md:block absolute top-6 left-[12.5%] right-[12.5%] h-0.5 bg-slate-300" aria-hidden="true"></div>

            @php
                $langkah = [
                    ['fa-file-signature', 'Kirim Laporan', 'Isi form dengan lengkap dan benar.'],
                    ['fa-search', 'Verifikasi Internal', 'Tim akan meninjau laporan Anda.'],
                    ['fa-cog', 'Proses Tindak Lanjut', 'Laporan sedang diproses dan ditindaklanjuti.'],
                    ['fa-check', 'Umpan Balik Selesai', 'Status laporan diberikan dan selesai.'],
                ];
            @endphp
            @foreach ($langkah as [$ikon, $judul, $isi])
                <div class="relative flex flex-col items-center text-center">
                    <div class="relative z-10 flex h-12 w-12 items-center justify-center rounded-lg text-lg
                                {{ $loop->last ? 'bg-[#3FA535] text-white' : 'border-2 border-[#0B5FA5] bg-white text-[#0B5FA5]' }}">
                        <i class="fas {{ $ikon }}" aria-hidden="true"></i>
                    </div>
                    <h3 class="mt-4 font-bold text-[#0A2F55]">{{ $judul }}</h3>
                    <p class="mt-2 max-w-[14rem] text-sm leading-relaxed text-slate-600">{{ $isi }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- NILAI-NILAI --}}
    <section class="max-w-7xl mx-auto px-6 pb-20">
        <div class="reveal text-center mb-12">
            <h2 class="text-3xl font-extrabold text-[#0A2F55]">Nilai-Nilai Kami</h2>
            <p class="text-slate-600 mt-2">Prinsip utama dalam pengelolaan pengaduan di BPS Kabupaten Bantul.</p>
        </div>

        <div class="reveal grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $nilai = [
                    ['fa-bullseye', 'Transparansi', 'Kami menjamin keterbukaan informasi dalam setiap alur penanganan laporan masyarakat.', 'border-t-[#0B5FA5]', 'text-[#0B5FA5]'],
                    ['fa-shield-alt', 'Akuntabilitas', 'Setiap laporan diproses secara profesional dan dapat dilacak progresnya.', 'border-t-[#3FA535]', 'text-[#3FA535]'],
                    ['fa-users', 'Partisipasi', 'Mendorong peran aktif masyarakat dalam menjaga integritas BPS Kabupaten Bantul.', 'border-t-[#F28C1B]', 'text-[#F28C1B]'],
                    ['fa-heart', 'Kepedulian', 'Mengutamakan respon cepat dan empati atas setiap masukan yang diberikan masyarakat.', 'border-t-[#0A2F55]', 'text-[#0A2F55]'],
                ];
            @endphp
            @foreach ($nilai as [$ikon, $judul, $isi, $garis, $warna])
                <div class="border border-t-4 border-slate-200 {{ $garis }} bg-white p-6 rounded-md">
                    <i class="fas {{ $ikon }} {{ $warna }} text-2xl" aria-hidden="true"></i>
                    <h3 class="mt-4 font-bold text-[#0A2F55]">{{ $judul }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $isi }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- TENTANG --}}
    <section id="tentang" class="border-t border-slate-200 bg-white">
        <div class="reveal max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-12 items-start">
            <div class="border-l-4 border-[#0B5FA5] pl-6">
                <h2 class="text-2xl font-bold text-[#0A2F55] mb-4">Tentang Ngadubose</h2>
                <p class="text-slate-600 leading-relaxed">
                    Sistem ini dibangun untuk mendukung pembangunan Zona Integritas menuju Wilayah Bebas dari Korupsi (WBK)
                    dan Wilayah Birokrasi Bersih dan Melayani (WBBM) dengan menjaga
                    <strong class="text-[#0A2F55]">kerahasiaan pelapor</strong>.
                </p>
            </div>
            <ul class="space-y-3 text-slate-700">
                <li class="flex items-start gap-3"><i class="fas fa-check mt-1 text-[#3FA535]" aria-hidden="true"></i> Melapor tanpa login</li>
                <li class="flex items-start gap-3"><i class="fas fa-check mt-1 text-[#3FA535]" aria-hidden="true"></i> Nama pelapor tidak wajib diisi</li>
                <li class="flex items-start gap-3"><i class="fas fa-check mt-1 text-[#3FA535]" aria-hidden="true"></i> Pantau perkembangan laporan dengan kode tiket</li>
            </ul>
        </div>
    </section>

    @script
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
    @endscript
</div>