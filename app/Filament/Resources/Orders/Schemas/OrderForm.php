<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pesanan & Status')
                    ->columns(3)
                    ->schema([
                        TextInput::make('number')
                            ->label('Nomor Pesanan')
                            ->disabled(),
                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options(collect(OrderStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                            ->required(),
                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                            ->required(),
                        DateTimePicker::make('created_at')
                            ->label('Waktu Pemesanan')
                            ->disabled(),
                        DateTimePicker::make('paid_at')
                            ->label('Waktu Lunas'),
                        DateTimePicker::make('shipped_at')
                            ->label('Waktu Dikirim'),
                    ]),

                Section::make('Informasi Customer & Pengiriman')
                    ->columns(3)
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Nama Customer')
                            ->required(),
                        TextInput::make('customer_email')
                            ->label('Email Customer')
                            ->email()
                            ->required(),
                        TextInput::make('customer_phone')
                            ->label('Nomor Telepon')
                            ->required(),
                        TextInput::make('courier')
                            ->label('Kurir'),
                        TextInput::make('courier_service')
                            ->label('Layanan Kurir'),
                        TextInput::make('tracking_number')
                            ->label('Nomor Resi (Tracking Number)'),
                    ]),

                Section::make('Rincian Biaya')
                    ->columns(4)
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal (IDR)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                        TextInput::make('discount_total')
                            ->label('Diskon Voucher (IDR)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                        TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim (IDR)')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('grand_total')
                            ->label('Total Akhir (IDR)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                    ]),

                Section::make('Catatan')
                    ->columns(2)
                    ->schema([
                        Textarea::make('customer_note')
                            ->label('Catatan dari Pelanggan')
                            ->disabled(),
                        Textarea::make('internal_note')
                            ->label('Catatan Internal Admin / Staf Gudang')
                            ->helperText('Catatan ini hanya terlihat oleh admin dan staf gudang.'),
                    ]),
            ]);
    }
}
