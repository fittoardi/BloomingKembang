<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Riwayat Pesanan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof OrderStatus ? $state->label() : (OrderStatus::tryFrom($state)?->label() ?? $state))
                    ->color(fn ($state) => match ($state instanceof OrderStatus ? $state->value : $state) {
                        'pending_payment' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'completed' => 'success',
                        'cancelled', 'expired' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PaymentStatus ? $state->label() : (PaymentStatus::tryFrom($state)?->label() ?? $state))
                    ->color(fn ($state) => match ($state instanceof PaymentStatus ? $state->value : $state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed', 'expired' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('tracking_number')
                    ->label('Resi')
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('view')
                    ->label('Detail Pesanan')
                    ->icon(Heroicon::OutlinedEye)
                    ->url(fn ($record) => OrderResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
