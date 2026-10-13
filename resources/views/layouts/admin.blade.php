<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Ngadubose BPS</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin-charts.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    <div x-data="{ isOpen: true }" class="flex min-h-screen overflow-x-hidden">
        
        <x-sidebar />

        <div @click="isOpen = false" 
             x-show="isOpen" 
             class="fixed inset-0 bg-black/50 z-40 md:hidden"
             x-transition.opacity></div>

        <main class="flex-1 flex flex-col min-w-0 transition-all duration-300 ease-in-out"
              :class="isOpen ? 'md:ml-64' : 'ml-0'">
            
              <header class="h-[92px] bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-6 z-30 sticky top-0 shadow-sm">
                
                <div class="flex items-center">
                    <button @click="isOpen = !isOpen" 
                            class="mr-3 p-2 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors focus:outline-none cursor-pointer">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    
                    <span class="text-sm font-bold text-slate-700 hidden sm:inline-block">Dashboard Panel</span>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen" 
                                class="flex items-center space-x-3 focus:outline-none hover:bg-slate-50 p-1.5 rounded-xl transition-colors border border-transparent hover:border-slate-200 cursor-pointer">
                            
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-slate-800 leading-tight">
                                    {{ Auth::user()->name ?? 'Admin' }}
                                </p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider mt-0.5">
                                    Admin BPS
                                </p>
                            </div>
                            
                            <div class="w-9 h-9 rounded-full bg-[#002B5B] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 1)) }}
                            </div>
                            
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="profileOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="profileOpen" 
                             @click.away="profileOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1.5 border border-slate-100 z-50"
                             style="display: none;">
                            
                            <a href="{{ url('/admin/profile') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <i class="fas fa-user w-5 text-slate-400 mr-2"></i> Profil Saya
                            </a>
                            
                            <a href="{{ url('/admin/settings') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <i class="fas fa-cog w-5 text-slate-400 mr-2"></i> Pengaturan
                            </a>
                            
                            <div class="my-1 border-t border-slate-100"></div>
                            
                            <form action="{{ url('/admin/logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i> Keluar Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-4 md:p-6 lg:p-8">
                {{ $slot ?? '' }}
                @yield('content')
            </div>

        </main>
    </div>
    @stack('scripts')
    @livewireScripts
</body>
</html>

