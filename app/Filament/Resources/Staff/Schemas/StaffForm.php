<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kredensial Staf')
                    ->description('Detail login dan identitas anggota staf internal.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(190),
                        TextInput::make('phone')
                            ->label('Nomor WhatsApp / Telepon')
                            ->maxLength(20),
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Biarkan kosong jika tidak ingin mengubah kata sandi pada mode edit.'),
                        Select::make('roles')
                            ->label('Hak Akses / Peran')
                            ->relationship('roles', 'name', modifyQueryUsing: fn ($query) => $query->whereIn('name', ['super_admin', 'staff']))
                            ->getOptionLabelFromRecordUsing(fn ($record) => match ($record->name) {
                                'super_admin' => 'Super Admin (Akses Penuh)',
                                'staff' => 'Staff Operasional (Katalog & Pesanan)',
                                default => $record->name,
                            })
                            ->preload()
                            ->multiple()
                            ->required()
                            ->native(false),
                        Toggle::make('is_active')
                            ->label('Status Akun Aktif')
                            ->helperText('Jika dinonaktifkan, staf tidak akan dapat masuk ke panel admin.')
                            ->default(true),
                    ]),
            ]);
    }
}
