<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function userIndex()
    {
        $users = User::latest()->get();
        return view('admin.manage-user', compact('users'));
    }

    public function userStore(Request $request)
    {
        $this->normalizeInput($request);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'nomor_hp' => 'required|string|max:15|unique:users,nomor_hp',
            'password' => 'required|string|min:8',
        ], $this->messages());

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.manage-user')
            ->with('success', 'Data Admin berhasil disimpan!');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->normalizeInput($request);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nomor_hp' => ['required', 'string', 'max:15', Rule::unique('users', 'nomor_hp')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
        ], $this->messages());

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->nomor_hp = $request->nomor_hp;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.manage-user')
            ->with('success', 'Data Admin berhasil diperbarui!');
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.manage-user')
                ->with('error', 'Anda tidak bisa menghapus akun yang sedang login.');
        }

        $user->delete();

        return redirect()->route('admin.manage-user')
            ->with('success', 'Data Admin berhasil dihapus!');
    }

    private function normalizeInput(Request $request): void
    {
        $request->merge([
            'email'    => strtolower(trim((string) $request->email)),
            'nomor_hp' => preg_replace('/\D/', '', (string) $request->nomor_hp),
        ]);
    }

    private function messages(): array
    {
        return [
            'email.unique'    => 'Email sudah terdaftar.',
            'nomor_hp.unique' => 'Nomor HP sudah terdaftar.',
        ];
    }
}