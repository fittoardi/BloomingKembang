<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $title = 'Buku Alamat Pengiriman';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Label')
                    ->badge(),
                TextColumn::make('recipient_name')
                    ->label('Nama Penerima')
                    ->weight('bold'),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('city')
                    ->label('Kota / Kabupaten'),
                TextColumn::make('province')
                    ->label('Provinsi'),
                TextColumn::make('district')
                    ->label('Kecamatan'),
                TextColumn::make('postal_code')
                    ->label('Kode Pos'),
                TextColumn::make('detail')
                    ->label('Alamat Lengkap')
                    ->limit(40),
                IconColumn::make('is_default')
                    ->label('Alamat Utama')
                    ->boolean(),
            ]);
    }
}
