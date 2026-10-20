<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printInvoice')
                ->label('Cetak Invoice')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('gray')
                ->action(function () {
                    $order = $this->getRecord()->loadMissing(['items.variant', 'user']);
                    $pdf = Pdf::loadView('pdf.invoice', ['order' => $order]);

                    return response()->streamDownload(
                        fn () => print ($pdf->output()),
                        "invoice-{$order->number}.pdf"
                    );
                }),

            Action::make('printLabel')
                ->label('Cetak Label Pengiriman')
                ->icon(Heroicon::OutlinedPrinter)
                ->color('gray')
                ->action(function () {
                    $order = $this->getRecord()->loadMissing('items');
                    $pdf = Pdf::loadView('pdf.shipping-label', ['order' => $order])
                        ->setPaper([0, 0, 283.46, 425.20], 'portrait');

                    return response()->streamDownload(
                        fn () => print ($pdf->output()),
                        "shipping-label-{$order->number}.pdf"
                    );
                }),

            EditAction::make(),
        ];
    }
}
