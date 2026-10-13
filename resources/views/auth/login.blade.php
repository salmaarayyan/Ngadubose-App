<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ngadubose BPS Kabupaten Bantul</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-6">
        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm p-8 space-y-6">
            <div class="text-center space-y-2">
                <div class="w-12 h-12 bg-blue-50 text-[#002B5B] rounded-2xl flex items-center justify-center mx-auto font-black text-lg">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="text-lg font-black text-slate-800">Admin Ngadubose</h1>
                <p class="text-xs text-slate-400">Silakan masukkan akun administrator Anda untuk melanjutkan.</p>
            </div>

            @if($errors->has('login_error'))
                <div class="bg-red-50 border border-red-200 text-red-700 text-xs px-4 py-3 rounded-2xl flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-500 text-sm"></i>
                    <span>{{ $errors->first('login_error') }}</span>
                </div>
            @endif

            <form action="{{ route('auth.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Email Aktif</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full pl-9 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:ring-1 focus:ring-[#002B5B] focus:bg-white transition" 
                               placeholder="admin@bps.go.id">
                    </div>
                    @error('email') 
                        <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input type="password" name="password" required 
                               class="w-full pl-9 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:ring-1 focus:ring-[#002B5B] focus:bg-white transition" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#002B5B] focus:ring-[#002B5B]">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-[#002B5B] hover:bg-blue-900 text-white text-xs font-bold rounded-2xl transition shadow-sm cursor-pointer flex items-center justify-center gap-2">
                    <span>Masuk ke Panel</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </button>
            </form>

        </div>
        
        <div class="text-center mt-6 text-[11px] text-slate-400">
            &copy; {{ date('Y') }} Whistleblowing System - BPS Kabupaten Bantul
        </div>
    </div>

</body>
</html>