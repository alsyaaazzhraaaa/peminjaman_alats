<?php

namespace App\Livewire\Auth;

use Illuminate\Validation\ValidationException;
use Livewire\Component;

class UnifiedLogin extends Component
{
    public ?array $data = [];

    public function mount()
    {
        if (\Filament\Facades\Filament::auth()->check()) {
            return $this->redirectBasedOnRole(\Filament\Facades\Filament::auth()->user());
        }
    }

    public function authenticate()
    {
        $credentials = [
            'username' => $this->data['username'] ?? '',
            'password' => $this->data['password'] ?? '',
        ];

        if (! \Filament\Facades\Filament::auth()->attempt($credentials, $this->data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.username' => 'Username atau password salah.',
            ]);
        }

        session()->regenerate();

        $user = \Filament\Facades\Filament::auth()->user();

        return $this->redirectBasedOnRole($user);
    }

    protected function redirectBasedOnRole($user)
    {
        $role = strtolower($user->role ?? '');

        if (! in_array($role, ['admin', 'petugas', 'peminjam'], true)) {
            \Filament\Facades\Filament::auth()->logout();

            throw ValidationException::withMessages([
                'data.username' => 'Role pengguna tidak valid, silakan hubungi admin.',
            ]);
        }

        return redirect("/{$role}/dashboard");
    }

    public function render()
    {
        return view('filament.pages.auth.login')->layout('components.layouts.guest');
    }
}
