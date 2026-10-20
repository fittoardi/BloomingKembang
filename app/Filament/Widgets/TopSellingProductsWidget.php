<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopSellingProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Produk Terlaris';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->with('category')
                    ->orderBy('sold_count', 'desc')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('sold_count')
                    ->label('Terjual')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => "{$state} pcs")
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
