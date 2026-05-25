<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('CPF ou e-mail')
            ->required()
            ->autocomplete('username')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        $input = trim((string) $data['email']);
        $cpfDigits = preg_replace('/\D/', '', $input);

        if (strlen($cpfDigits) === 11) {
            $user = User::query()
                ->where('cpf', $cpfDigits)
                ->orWhere('cpf', self::formatCpf($cpfDigits))
                ->first();

            if ($user !== null) {
                return [
                    'email' => $user->email,
                    'password' => $data['password'],
                ];
            }
        }

        return [
            'email' => $input,
            'password' => $data['password'],
        ];
    }

    protected static function formatCpf(string $digits): string
    {
        return substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2);
    }
}
