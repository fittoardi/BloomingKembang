<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use App\Enums\VoucherType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Voucher')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(40)
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state)),
                Select::make('type')
                    ->label('Tipe Diskon')
                    ->options([
                        VoucherType::Percent->value => 'Persentase (%)',
                        VoucherType::Fixed->value => 'Nominal Tetap (Rp)',
                    ])
                    ->required(),
                TextInput::make('value')
                    ->label('Nilai Diskon')
                    ->required()
                    ->numeric()
                    ->helperText('Persen (mis. 10 untuk 10%) atau Rupiah (mis. 20000)'),
                TextInput::make('min_purchase')
                    ->label('Minimal Belanja (IDR)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                TextInput::make('max_discount')
                    ->label('Maksimal Diskon (IDR, khusus tipe persentase)')
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('quota')
                    ->label('Total Kuota Penggunaan')
                    ->numeric()
                    ->placeholder('Kosongkan untuk tanpa batas'),
                TextInput::make('per_user_limit')
                    ->label('Batas Pemakaian per Akun Customer')
                    ->numeric()
                    ->default(1),
                DateTimePicker::make('starts_at')
                    ->label('Mulai Berlaku'),
                DateTimePicker::make('ends_at')
                    ->label('Berakhir Pada'),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
                Textarea::make('description')
                    ->label('Keterangan / Catatan Promo')
                    ->columnSpanFull(),
            ]);
    }
}
