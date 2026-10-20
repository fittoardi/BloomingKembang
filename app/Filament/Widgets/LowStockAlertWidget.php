<?php

namespace App\Filament\Widgets;

use App\Models\ProductVariant;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockAlertWidget extends BaseWidget
{
    protected static ?string $heading = 'Peringatan Stok Menipis';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductVariant::query()
                    ->with('product')
                    ->whereRaw('(stock - reserved) <= ?', [5])
                    ->orderByRaw('(stock - reserved) asc')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->weight('medium'),
                TextColumn::make('color')
                    ->label('Varian')
                    ->formatStateUsing(fn (ProductVariant $record) => trim("{$record->color} {$record->size}")),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->fontFamily('mono')
                    ->copyable(),
                TextColumn::make('stock')
                    ->label('Fisik')
                    ->alignCenter(),
                TextColumn::make('reserved')
                    ->label('Direservasi')
                    ->alignCenter(),
                TextColumn::make('available_stock')
                    ->label('Sisa Tersedia')
                    ->state(fn (ProductVariant $record) => max(0, $record->stock - $record->reserved))
                    ->badge()
                    ->color(fn (int $state) => $state <= 0 ? 'danger' : ($state <= 2 ? 'warning' : 'info'))
                    ->formatStateUsing(fn (int $state) => $state <= 0 ? 'HABIS (0)' : "{$state} pcs")
                    ->alignCenter(),
            ])
            ->paginated(false);
    }
}
