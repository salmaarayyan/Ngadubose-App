{{-- resources/views/livewire/home.blade.php --}}
<div class="bg-bps-mist text-slate-800">
    <style>
        .reveal { opacity: 0; transform: translateY(10px); transition: opacity .5s ease, transform .5s ease; }
        .reveal.visible { opacity: 1; transform: none; }
        .caret { animation: caret-blink 1s steps(1) infinite; }
        @keyframes caret-blink { 0%, 49% { opacity: 1; } 50%, 100% { opacity: 0; } }

        .nilai-card {
            --accent: #60A5FA;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: transparent;
            border-radius: 14px;
            padding: 1.5rem;
            cursor: pointer;
            text-align: left;
            transition: background-color .3s ease, border-color .3s ease,
                        transform .3s ease, box-shadow .3s ease;
        }
        .nilai-card:hover {
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }
        .nilai-card:focus-visible {
            outline: 2px solid #fff;
            outline-offset: 3px;
        }
        .nilai-card .nilai-icon {
            color: rgba(255, 255, 255, 0.7);
            transition: color .3s ease;
        }
        .nilai-card.is-active {
            background: color-mix(in srgb, var(--accent) 22%, transparent);
            border-color: var(--accent);
            -webkit-backdrop-filter: blur(14px);
            backdrop-filter: blur(14px);
            box-shadow: 0 8px 32px color-mix(in srgb, var(--accent) 25%, transparent);
        }
        .nilai-card.is-active .nilai-icon {
            color: var(--accent);
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
            .caret { animation: none; }
            .nilai-card { transition: none; }
        }
    </style>

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-linear-to-br from-bps-mist via-bps-blue/5 to-bps-green/15">
        <div class="mx-auto max-w-5xl px-6 pb-24 pt-16 text-center md:pb-28 md:pt-24">

            {{-- Judul: wire:ignore agar animasi tidak ter-reset saat Livewire re-render --}}
            <div wire:ignore>
                <h1 class="text-4xl font-extrabold leading-[1.1] tracking-tight text-bps-navy sm:text-5xl md:text-6xl">
                    <span class="sr-only">Laporkan Pelanggaran, Tanpa Rasa Takut, Dengan Aman, Secara Rahasia, Tanpa Login</span>

                    <span aria-hidden="true" class="block">Laporkan Pelanggaran</span>

                    <span aria-hidden="true"
                          class="mt-3 inline-flex min-h-[1.25em] min-w-[7ch] items-center justify-center rounded-xl border border-bps-blue/20 bg-bps-blue/10 px-4 py-1 text-bps-blue md:px-6"
                          x-data="{
                              words: ['Tanpa Rasa Takut', 'Dengan Aman', 'Secara Rahasia', 'Tanpa Login'],
                              i: 0,
                              text: '',
                              deleting: false,
                              init() {
                                  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                                      this.text = this.words[0];
                                      return;
                                  }
                                  this.tick();
                              },
                              tick() {
                                  const word = this.words[this.i];
                                  this.text = this.deleting
                                      ? word.slice(0, this.text.length - 1)
                                      : word.slice(0, this.text.length + 1);

                                  let delay = this.deleting ? 40 : 90;

                                  if (!this.deleting && this.text === word) {
                                      delay = 1600;          // jeda setelah kata selesai diketik
                                      this.deleting = true;
                                  } else if (this.deleting && this.text === '') {
                                      this.deleting = false;
                                      this.i = (this.i + 1) % this.words.length;
                                      delay = 350;           // jeda sebelum kata berikutnya
                                  }
                                  setTimeout(() => this.tick(), delay);
                              }
                          }">
                        <span x-text="text"></span><span class="caret ml-1 inline-block h-[0.85em] w-[3px] bg-bps-orange"></span>
                    </span>
                </h1>
            </div>

            <p class="mx-auto mt-8 max-w-2xl text-base leading-relaxed text-slate-600 md:text-lg">
                Ngadubose adalah <strong class="text-bps-navy">Whistle Blowing System (WBS)</strong> BPS Kabupaten Bantul
                untuk melaporkan dugaan pelanggaran, gratifikasi, atau pelayanan buruk dari pegawai BPS Bantul.
            </p>

            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ url('/form-lapor') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-bps-navy px-8 py-4 font-semibold text-white shadow-md transition hover:bg-bps-blue focus:outline-none focus-visible:ring-4 focus-visible:ring-bps-blue/40 sm:w-auto">
                    <i class="fas fa-file-signature" aria-hidden="true"></i>
                    Buat Laporan Baru
                </a>
                <a href="{{ url('/lacak-status') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-lg border-2 border-bps-navy/30 px-8 py-4 font-semibold text-bps-navy transition hover:border-bps-navy hover:bg-white/60 focus:outline-none focus-visible:ring-4 focus-visible:ring-bps-blue/30 sm:w-auto">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    Lacak Laporan
                </a>
            </div>
        </div>
    </section>

    {{-- ALUR PENANGANAN --}}
    <section id="alur" class="mx-auto max-w-7xl scroll-mt-20 px-6 py-20">
        <div class="reveal mb-14 text-center">
            <h2 class="text-3xl font-extrabold text-bps-navy">Alur Penanganan Laporan</h2>
            <p class="mt-2 text-slate-600">Berikut adalah langkah-langkah penanganan laporan Anda setiap tahapnya.</p>
        </div>

        <div class="reveal relative grid grid-cols-1 gap-10 md:grid-cols-4 md:gap-8">
            <div class="absolute left-[12.5%] right-[12.5%] top-6 hidden h-0.5 bg-slate-300 md:block" aria-hidden="true"></div>

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
                                {{ $loop->last ? 'bg-bps-green text-white' : 'border-2 border-bps-blue bg-white text-bps-blue' }}">
                        <i class="fas {{ $ikon }}" aria-hidden="true"></i>
                    </div>
                    <h3 class="mt-4 font-bold text-bps-navy">{{ $judul }}</h3>
                    <p class="mt-2 max-w-[14rem] text-sm leading-relaxed text-slate-600">{{ $isi }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- NILAI-NILAI (latar biru, kartu garis, efek kaca saat diklik) --}}
    <section id="nilai" class="relative scroll-mt-20 overflow-hidden py-20"
             style="background: linear-gradient(135deg, #0A2F55 0%, #1E4E8C 100%);">

        {{-- Hiasan blur supaya efek kaca terlihat --}}
        <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full opacity-30"
             style="background: #60A5FA; filter: blur(90px);" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-24 -right-24 h-72 w-72 rounded-full opacity-25"
             style="background: #38BDF8; filter: blur(90px);" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="reveal mb-12 text-center">
                <h2 class="text-3xl font-extrabold text-white">Nilai-Nilai Kami</h2>
                <p class="mt-2 text-slate-300">Prinsip utama dalam pengelolaan pengaduan di BPS Kabupaten Bantul.</p>
            </div>

            {{-- wire:ignore agar status kartu aktif tidak ter-reset saat Livewire re-render --}}
            <div wire:ignore id="nilai-grid" class="reveal grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-4">
                @php
                    $nilai = [
                        ['fa-bullseye', 'Transparansi', 'Kami menjamin keterbukaan informasi dalam setiap alur penanganan laporan masyarakat.', '#60A5FA'],
                        ['fa-shield-alt', 'Akuntabilitas', 'Setiap laporan diproses secara profesional dan dapat dilacak progresnya.', '#4ADE80'],
                        ['fa-users', 'Partisipasi', 'Mendorong peran aktif masyarakat dalam menjaga integritas BPS Kabupaten Bantul.', '#FB923C'],
                        ['fa-heart', 'Kepedulian', 'Mengutamakan respon cepat dan empati atas setiap masukan yang diberikan masyarakat.', '#F472B6'],
                    ];
                @endphp
                @foreach ($nilai as [$ikon, $judul, $isi, $aksen])
                    <button type="button" class="nilai-card" style="--accent: {{ $aksen }};" aria-pressed="false">
                        <i class="fas {{ $ikon }} nilai-icon text-xl" aria-hidden="true"></i>
                        <h3 class="mt-4 text-base font-bold text-white">{{ $judul }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $isi }}</p>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- TENTANG --}}
    <section id="tentang" class="scroll-mt-20 border-t border-slate-200 bg-white">
        <div class="reveal mx-auto grid max-w-7xl items-start gap-12 px-6 py-16 md:grid-cols-2">
            <div class="border-l-4 border-bps-blue pl-6">
                <h2 class="mb-4 text-2xl font-bold text-bps-navy">Tentang Ngadubose</h2>
                <p class="leading-relaxed text-slate-600">
                    Sistem ini dibangun untuk mendukung pembangunan Zona Integritas menuju Wilayah Bebas dari Korupsi (WBK)
                    dan Wilayah Birokrasi Bersih dan Melayani (WBBM) dengan menjaga
                    <strong class="text-bps-navy">kerahasiaan pelapor</strong>.
                </p>
            </div>
            <ul class="space-y-3 text-slate-700">
                <li class="flex items-start gap-3"><i class="fas fa-check mt-1 text-bps-green" aria-hidden="true"></i> Melapor tanpa login</li>
                <li class="flex items-start gap-3"><i class="fas fa-check mt-1 text-bps-green" aria-hidden="true"></i> Nama pelapor tidak wajib diisi</li>
                <li class="flex items-start gap-3"><i class="fas fa-check mt-1 text-bps-green" aria-hidden="true"></i> Pantau perkembangan laporan dengan kode tiket</li>
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

        const nilaiCards = document.querySelectorAll('.nilai-card');
        nilaiCards.forEach(card => {
            card.addEventListener('click', () => {
                const willActivate = !card.classList.contains('is-active');

                nilaiCards.forEach(c => {
                    c.classList.remove('is-active');
                    c.setAttribute('aria-pressed', 'false');
                });

                if (willActivate) {
                    card.classList.add('is-active');
                    card.setAttribute('aria-pressed', 'true');
                }
            });
        });
    </script>
    @endscript
</div>