<?php

namespace App\Filament\Resources\Vouchers\Tables;

use App\Enums\VoucherType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === VoucherType::Percentage || $state?->value === 'percentage' ? 'Persen' : 'Nominal')
                    ->color(fn ($state) => $state === VoucherType::Percentage || $state?->value === 'percentage' ? 'info' : 'success'),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(function ($record) {
                        return $record->type === VoucherType::Percentage
                            ? "{$record->value}%"
                            : 'Rp '.number_format($record->value, 0, ',', '.');
                    }),
                TextColumn::make('min_purchase')
                    ->label('Min. Belanja')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('used_count')
                    ->label('Terpakai')
                    ->formatStateUsing(fn ($record) => $record->quota ? "{$record->used_count} / {$record->quota}" : "{$record->used_count} / ∞"),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('ends_at')
                    ->label('Berakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Tanpa batas'),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Nonaktif',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
