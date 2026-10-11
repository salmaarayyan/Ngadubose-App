<nav id="main-navbar"
     class="border-b border-[#0A2F55] py-4 sticky top-0 z-50 shadow-md px-6"
     style="background-color: #0A2F55; transition: transform 0.3s ease;">
    <div class="max-w-7xl mx-auto flex justify-between items-center">

        <!-- Logo (kiri) -->
        <a href="{{ url('/') }}" class="text-2xl font-black tracking-wider text-white">
            ngadubose
        </a>

        <!-- Menu Desktop (kanan, semua menu) -->
        <div class="hidden md:flex items-center gap-2">
            <a href="{{ url('/') }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition
               {{ Request::is('/') ? 'bg-[#1E4E8C] text-white shadow-md' : 'text-white hover:bg-[#1E4E8C]/70' }}">
               Beranda
            </a>

            <a href="{{ url('/lacak-status') }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition
               {{ Request::is('lacak-status*') ? 'bg-[#1E4E8C] text-white shadow-md' : 'text-white hover:bg-[#1E4E8C]/70' }}">
               Lacak Laporan
            </a>

            <a href="{{ url('/form-lapor') }}"
               class="inline-flex items-center gap-2 bg-[#1E4E8C] hover:bg-[#2A5FA3] text-white text-sm font-semibold px-5 py-2.5 rounded-xl border border-white/20 transition shadow-md ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Buat Laporan
            </a>
        </div>

        <!-- Tombol Garis Tiga (hanya mobile/tablet) - HARUS di luar #mobile-menu -->
        <button id="menu-toggle" type="button" aria-label="Buka menu"
                class="md:hidden text-white p-2 rounded-lg hover:bg-[#1E4E8C]/70 focus:outline-none transition">
            <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Menu Mobile (tersembunyi, dibuka lewat garis tiga) -->
    <div id="mobile-menu" class="hidden md:hidden mt-4 pt-4 border-t border-white/20 flex-col space-y-2">
        <a href="{{ url('/') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition
           {{ Request::is('/') ? 'bg-[#1E4E8C] text-white shadow-md' : 'text-white hover:bg-[#1E4E8C]/70' }}">
           Beranda
        </a>

        <a href="{{ url('/lacak-status') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition
           {{ Request::is('lacak-status*') ? 'bg-[#1E4E8C] text-white shadow-md' : 'text-white hover:bg-[#1E4E8C]/70' }}">
           Lacak Laporan
        </a>

        <a href="{{ url('/form-lapor') }}"
           class="inline-flex items-center justify-center gap-2 bg-[#1E4E8C] hover:bg-[#2A5FA3] text-white text-sm font-semibold px-5 py-2.5 rounded-xl border border-white/20 transition shadow-md mt-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
            Buat Laporan
        </a>
    </div>
</nav>

<script>
    (function () {
        const navbar = document.getElementById('main-navbar');
        const toggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');
        let lastY = window.scrollY;

        // Buka/tutup menu mobile
        toggle.addEventListener('click', function () {
            const isHidden = menu.classList.toggle('hidden');
            menu.classList.toggle('flex', !isHidden);
            iconOpen.classList.toggle('hidden', !isHidden);
            iconClose.classList.toggle('hidden', isHidden);
        });

        // Navbar hilang saat scroll ke bawah, muncul saat scroll ke atas
        window.addEventListener('scroll', function () {
            const y = window.scrollY;
            const menuOpen = !menu.classList.contains('hidden');

            if (y <= 10 || menuOpen) {
                navbar.style.transform = 'translateY(0)';
            } else if (Math.abs(y - lastY) > 5) {
                navbar.style.transform = y > lastY ? 'translateY(-100%)' : 'translateY(0)';
            }

            lastY = y;
        }, { passive: true });
    })();
</script>
