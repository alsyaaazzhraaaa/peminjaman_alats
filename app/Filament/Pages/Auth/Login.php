<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';


    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->autocomplete()
            ->autofocus();
    }


    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
    {
        $data = $this->data;

        $credentials = [
            'username' => $data['username'] ?? '',
            'password' => $data['password'] ?? '',
        ];

        \Illuminate\Support\Facades\Log::info("Login submitted: " . json_encode($credentials));

        if (! \Filament\Facades\Filament::auth()->attempt($credentials, $data['remember'] ?? false)) {
            \Illuminate\Support\Facades\Log::info("Login failed");
            $this->throwFailureValidationException();
        }

        $user = \Filament\Facades\Filament::auth()->user();
        \Illuminate\Support\Facades\Log::info("Login attempt SUCCESS for user: " . $user->username);

        session()->regenerate();
        \Illuminate\Support\Facades\Log::info("Session regenerated, returning LoginResponse...");

        return app(\Filament\Auth\Http\Responses\Contracts\LoginResponse::class);
    }
}
