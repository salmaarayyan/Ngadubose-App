<div class="space-y-6">
    
    <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-800">Manajemen User / Admin</h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola akun administrator dan hak akses sistem Ngadubose.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       class="w-full sm:w-64 pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-[#002B5B] focus:bg-white transition" 
                       placeholder="Cari nama atau email...">
            </div>

            <button wire:click="create" class="bg-[#002B5B] hover:bg-blue-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2 cursor-pointer shadow-sm shrink-0">
                <i class="fas fa-user-plus text-xs"></i>
                <span>Tambah Admin Baru</span>
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 text-xs px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-red-600 text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">No</th>
                        <th class="py-4 px-6">Nama Lengkap</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Nomor HP</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse ($users as $index => $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-mono text-slate-400">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full bg-blue-100 text-[#002B5B] flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($user->nama, 0, 1) }}
                                </div>
                                {{ $user->nama }}
                            </td>
                            <td class="py-4 px-6">{{ $user->email }}</td>
                            <td class="py-4 px-6">{{ $user->nomor_hp }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="edit({{ $user->id }})" class="bg-amber-50 hover:bg-amber-100 text-amber-600 p-2 rounded-lg transition cursor-pointer" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button wire:click="delete({{ $user->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus user ini?"
                                            class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition cursor-pointer" title="Hapus">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                @if($search)
                                    Tidak ditemukan user dengan kata kunci "<span class="font-semibold text-slate-600">{{ $search }}</span>".
                                @else
                                    Belum ada data admin/user terdaftar.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $users->links('components.pagination') }}
        </div>
    </div>

    @if($isOpen)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-200">
                
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-sm font-black text-slate-800">
                        {{ $isEditMode ? 'Edit Data Admin' : 'Tambah Admin Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="nama" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-[#002B5B]" placeholder="Contoh: Budi Santoso">
                        @error('nama') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Email Aktif</label>
                        <input type="email" wire:model="email" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-[#002B5B]" placeholder="admin@bps.go.id">
                        @error('email') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" wire:model="nomor_hp" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-[#002B5B]" placeholder="08123456789">
                        @error('nomor_hp') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">
                            Password {{ $isEditMode ? '(Kosongkan jika tidak ingin diubah)' : '' }}
                        </label>
                        <input type="password" wire:model="password" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-[#002B5B]" placeholder="Minimal 8 karakter">
                        @error('password') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#002B5B] hover:bg-blue-900 text-white text-xs font-bold rounded-xl transition cursor-pointer flex items-center gap-2">
                            <span wire:loading.remove>Simpan Data</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

</div>