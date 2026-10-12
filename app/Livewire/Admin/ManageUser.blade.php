<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url; 
use Livewire\Component;
use Livewire\WithPagination;

class ManageUser extends Component
{
    use WithPagination;

    public $userId, $nama, $email, $nomor_hp, $password;
    public $isOpen = false;
    public $isEditMode = false;

    #[Url(except: '')]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function rules()
    {
        return [
            'nama'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'nomor_hp' => ['required', 'string', 'max:15', Rule::unique('users', 'nomor_hp')->ignore($this->userId)],
            'password' => $this->isEditMode ? 'nullable|string|min:8' : 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.unique'     => 'Email sudah terdaftar.',
            'nomor_hp.unique'  => 'Nomor HP sudah terdaftar.',
            'password.required'=> 'Password wajib diisi.',
            'password.min'     => 'Password minimal harus 8 karakter.',
        ];
    }

    private function normalizeInput()
    {
        $this->email = strtolower(trim((string) $this->email));
        $this->nomor_hp = preg_replace('/\D/', '', (string) $this->nomor_hp);
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-user', [
            'users' => $users
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->nomor_hp = $user->nomor_hp;
        $this->password = '';

        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->userId = null;
        $this->nama = '';
        $this->email = '';
        $this->nomor_hp = '';
        $this->password = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->normalizeInput();
        $this->validate();

        if ($this->isEditMode) {
            $user = User::findOrFail($this->userId);
            $user->nama = $this->nama;
            $user->email = $this->email;
            $user->nomor_hp = $this->nomor_hp;

            if (!empty($this->password)) {
                $user->password = $this->password;
            }

            $user->save();
            session()->flash('success', 'Data Admin berhasil diperbarui!');
        } else {
            User::create([
                'nama'     => $this->nama,
                'email'    => $this->email,
                'nomor_hp' => $this->nomor_hp,
                'password' => $this->password,
            ]);

            session()->flash('success', 'Data Admin berhasil disimpan!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            session()->flash('error', 'Anda tidak bisa menghapus akun yang sedang login.');
            return;
        }

        $user->delete();
        session()->flash('success', 'Data Admin berhasil dihapus!');
    }
}