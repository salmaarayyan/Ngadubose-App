<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Home extends Component
{
    public bool $tampilLacak = false;

    #[Validate('required|regex:/^WBS-\d{4}-\d{4}$/', message: [
        'kode.required' => 'Kode tiket wajib diisi.',
        'kode.regex' => 'Format kode tidak sesuai. Contoh: WBS-2026-0001.',
    ])]
    public string $kode = '';

    public function lacak()
    {
        $this->validate();

        return $this->redirect(url('/lacak/' . $this->kode), navigate: true);
    }

    public function render()
    {
        return view('livewire.home');
    }
}