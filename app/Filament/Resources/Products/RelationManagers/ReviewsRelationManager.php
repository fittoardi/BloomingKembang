<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\Review;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Ulasan & Rating Pelanggan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => str_repeat('★', (int) $state)." ({$state}/5)"),
                TextColumn::make('comment')
                    ->label('Isi Ulasan')
                    ->limit(80)
                    ->wrap(),
                IconColumn::make('is_approved')
                    ->label('Disetujui')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('toggleApproved')
                    ->label(fn (Review $record) => $record->is_approved ? 'Sembunyikan' : 'Setujui')
                    ->icon(fn (Review $record) => $record->is_approved ? Heroicon::OutlinedNoSymbol : Heroicon::OutlinedCheckCircle)
                    ->color(fn (Review $record) => $record->is_approved ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->action(function (Review $record): void {
                        $record->update(['is_approved' => ! $record->is_approved]);

                        Notification::make()
                            ->title('Status ulasan diperbarui')
                            ->body($record->is_approved ? 'Ulasan disetujui dan tampil di storefront.' : 'Ulasan disembunyikan dari storefront.')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ]);
    }
}
