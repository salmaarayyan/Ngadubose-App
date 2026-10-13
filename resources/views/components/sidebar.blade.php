<aside :class="isOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0 md:-ml-64'" 
       class="fixed inset-y-0 left-0 z-50 w-64 bg-[#002B5B] text-white min-h-screen flex flex-col justify-between shadow-xl shrink-0 transition-transform duration-300 ease-in-out">
    <div>
        <!-- Header Sidebar -->
        <div class="p-6 border-b border-blue-900/50 flex items-center gap-3">
            <div class="bg-white/10 p-2 rounded-lg text-blue-300">
                <i class="fas fa-user-shield text-xl"></i>
            </div>
            <div>
                <h1 class="text-base font-black tracking-wider">NGADUBOSE</h1>
                <span class="text-[10px] text-blue-300 block font-semibold uppercase tracking-wide">Panel Admin BPS</span>
            </div>
        </div>

        <!-- Navigasi Menu -->
        <nav class="p-4 space-y-1">
            <span class="px-3 text-[10px] font-bold text-blue-300 uppercase tracking-wider block mb-2">Menu Utama</span>

            <a href="{{ url('/admin/dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition group 
                      {{ Request::is('admin/dashboard') ? 'bg-blue-900 text-white shadow-lg shadow-blue-950/50' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-chart-pie text-base transition group-hover:scale-110 {{ Request::is('admin/dashboard') ? 'text-blue-300' : 'text-blue-400' }}"></i>
                <span>Dashboard Home</span>
            </a>

            <a href="{{ url('/admin/manage-laporan') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition group 
                      {{ Request::is('admin/manage-laporan') ? 'bg-blue-900 text-white shadow-lg shadow-blue-950/50' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-ticket-alt text-base transition group-hover:scale-110 {{ Request::is('admin/pengaduan') ? 'text-blue-300' : 'text-blue-400' }}"></i>
                <span>Laporan / Tiket User</span>
            </a>
            
            <a href="{{ url('/admin/manage-user') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition group 
                      {{ Request::is('admin/manage-user') ? 'bg-blue-900 text-white shadow-lg shadow-blue-950/50' : 'text-blue-100 hover:bg-white/5 hover:text-white' }}">
                <i class="fas fa-users-cog text-base transition group-hover:scale-110 {{ Request::is('admin/manage-user') ? 'text-blue-300' : 'text-blue-400' }}"></i>
                <span>Manajemen User</span>
            </a>
        </nav>
    </div>

    <!-- Footer Sidebar -->
    <div class="p-4 border-t border-blue-900/50 bg-blue-950/30">
        <div class="flex items-center gap-3 px-2 mb-3">
           <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-300 font-bold flex items-center justify-center text-xs border border-blue-400/20">
                {{ Auth::user() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'AD' }}
            </div>

            <div class="overflow-hidden">
                <span class="block text-xs font-bold truncate text-white">
                    {{ Auth::user()->name ?? 'Administrator' }}
                </span>
                <span class="block text-[10px] text-emerald-400 font-medium flex items-center gap-1">
                    <span class="w-1.5 h-1..5 rounded-full bg-emerald-400 inline-block animate-pulse"></span> Admin Aktif
                </span>
            </div>
        </div>

        <form action="{{ url('/admin/logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white text-xs font-bold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2 border border-red-500/20 hover:border-transparent cursor-pointer">
                <i class="fas fa-sign-out-alt text-xs"></i>
                <span>Keluar Akun</span>
            </button>
        </form>
    </div>
</aside>