<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::guest')] class extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    protected function messages(): array
    {
        return [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    public function login()
    {
        $this->validate();

        $throttleKey = Str::lower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('login_error', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($throttleKey);
            $this->reset('password');
            $this->addError('login_error', 'Email atau password salah.');
            return;
        }

        // OPSIONAL: aktifkan dan sesuaikan dengan logika AdminMiddleware Anda
        // (nama kolom dan nilai role), supaya non-admin tidak mentok di 403.
        //
        // if (Auth::user()->role !== 'admin') {
        //     Auth::logout();
        //     $this->addError('login_error', 'Akun ini tidak memiliki akses admin.');
        //     return;
        // }

        RateLimiter::clear($throttleKey);
        session()->regenerate();

        return $this->redirectIntended(route('admin.dashboard'), navigate: true);
    }
};
?>

<div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
    <div class="bg-[#002B5B] p-6 text-white text-center">
        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-user-shield text-xl text-blue-300"></i>
        </div>
        <h1 class="text-xl font-black tracking-wider uppercase">Panel Admin</h1>
        <p class="text-xs text-blue-200 mt-1">Whistleblowing System (WBS) Internal</p>
    </div>

    <form wire:submit="login" class="p-6 space-y-4">

        @error('login_error')
            <div class="p-3 bg-red-50 border-l-4 border-red-500 rounded-lg text-red-700 text-xs font-semibold flex items-start gap-2">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email Administrator</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fas fa-envelope text-sm"></i>
                </span>
                <input type="email" wire:model="email" autocomplete="username"
                    placeholder="Masukkan email resmi admin"
                    class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#002B5B]/20 focus:border-[#002B5B] transition {{ $errors->has('email') ? 'border-red-400' : 'border-slate-200' }}">
            </div>
            @error('email')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input type="password" wire:model="password" autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#002B5B]/20 focus:border-[#002B5B] transition {{ $errors->has('password') ? 'border-red-400' : 'border-slate-200' }}">
            </div>
            @error('password')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="login"
            class="w-full bg-[#002B5B] hover:bg-blue-900 disabled:opacity-60 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 mt-2">
            <span wire:loading.remove wire:target="login">Masuk Ke Sistem</span>
            <span wire:loading wire:target="login">Memproses...</span>
            <i class="fas fa-arrow-right text-xs" wire:loading.remove wire:target="login"></i>
            <i class="fas fa-spinner fa-spin text-xs" wire:loading wire:target="login"></i>
        </button>
    </form>

    <div class="bg-slate-50 px-6 py-4 text-center border-t border-slate-100">
        <a href="{{ url('/') }}" wire:navigate class="text-xs text-slate-500 hover:text-[#002B5B] transition font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda Utama
        </a>
    </div>
</div>