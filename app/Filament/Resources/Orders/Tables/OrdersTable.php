<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Notifications\OrderShippedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as FacadeNotification;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Order $record) => $record->customer_phone ?? $record->customer_email),
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status Pesanan')
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
                TextColumn::make('courier')
                    ->label('Kurir')
                    ->formatStateUsing(fn (Order $record) => strtoupper($record->courier ?? '-').($record->courier_service ? " ({$record->courier_service})" : '')),
                TextColumn::make('tracking_number')
                    ->label('Resi')
                    ->searchable()
                    ->copyable()
                    ->placeholder('Belum ada resi'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])),
                SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()])),
            ])
            ->recordActions([
                Action::make('ship')
                    ->label('Input Resi')
                    ->icon(Heroicon::OutlinedTruck)
                    ->color('primary')
                    ->visible(fn (Order $record) => in_array($record->status->value ?? $record->status, ['paid', 'processing']))
                    ->form([
                        TextInput::make('tracking_number')
                            ->label('Nomor Resi Pengiriman')
                            ->placeholder('Contoh: JNE1234567890')
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $oldStatus = $record->status;
                        $record->update([
                            'tracking_number' => trim($data['tracking_number']),
                            'status' => OrderStatus::Shipped,
                            'shipped_at' => now(),
                        ]);

                        OrderStatusHistory::create([
                            'order_id' => $record->id,
                            'from_status' => $oldStatus->value ?? (string) $oldStatus,
                            'to_status' => OrderStatus::Shipped->value,
                            'changed_by' => Auth::id(),
                            'note' => 'Paket telah dikirim dengan nomor resi: '.trim($data['tracking_number']),
                        ]);

                        // Kirim notifikasi pesanan dikirim
                        if ($record->user) {
                            $record->user->notify(new OrderShippedNotification($record));
                        } elseif ($record->customer_email) {
                            FacadeNotification::route('mail', $record->customer_email)
                                ->notify(new OrderShippedNotification($record));
                        }

                        Notification::make()
                            ->title('Pesanan berhasil dikirim')
                            ->body("Nomor resi {$data['tracking_number']} telah tersimpan dan notifikasi dikirim ke customer.")
                            ->success()
                            ->send();
                    }),

                Action::make('printInvoice')
                    ->label('Invoice')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->color('gray')
                    ->action(function (Order $record) {
                        $record->loadMissing(['items.variant', 'user']);
                        $pdf = Pdf::loadView('pdf.invoice', ['order' => $record]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "invoice-{$record->number}.pdf"
                        );
                    }),

                Action::make('printLabel')
                    ->label('Label')
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('gray')
                    ->action(function (Order $record) {
                        $record->loadMissing('items');
                        $pdf = Pdf::loadView('pdf.shipping-label', ['order' => $record])
                            ->setPaper([0, 0, 283.46, 425.20], 'portrait');

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "shipping-label-{$record->number}.pdf"
                        );
                    }),

                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
