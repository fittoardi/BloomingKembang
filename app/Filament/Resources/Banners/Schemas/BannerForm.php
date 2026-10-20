<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Banner')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Banner')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('subtitle')
                            ->label('Sub-judul / Tagline')
                            ->maxLength(255),
                        TextInput::make('link_url')
                            ->label('Tautan Target (URL)')
                            ->placeholder('https://... atau /koleksi/pashmina')
                            ->maxLength(255),
                        Select::make('position')
                            ->label('Posisi Penempatan')
                            ->options([
                                'hero' => 'Hero Banner Beranda',
                                'top_bar' => 'Bar Pengumuman Atas (Announcement Bar)',
                                'promo' => 'Promo Section Beranda',
                            ])
                            ->default('hero')
                            ->required(),
                        FileUpload::make('image')
                            ->label('Gambar Banner')
                            ->image()
                            ->directory('banners')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan & Jadwal Tayang')
                    ->columns(3)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('starts_at')
                            ->label('Mulai Tayang'),
                        DateTimePicker::make('ends_at')
                            ->label('Selesai Tayang'),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
