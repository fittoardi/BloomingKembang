<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(120),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->maxLength(190),
                TextInput::make('phone')
                    ->label('Nomor WhatsApp / Telepon')
                    ->maxLength(20),
                Toggle::make('is_active')
                    ->label('Status Akun Aktif')
                    ->helperText('Hanya Super Admin yang dapat menonaktifkan akun pelanggan.')
                    ->disabled(fn () => ! (auth()->user()?->hasRole('super_admin') ?? false)),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Terverifikasi Pada')
                    ->disabled(),
                DateTimePicker::make('created_at')
                    ->label('Terdaftar Pada')
                    ->disabled(),
            ]);
    }
}
