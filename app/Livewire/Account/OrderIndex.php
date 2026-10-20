<?php

namespace App\Livewire\Account;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{
    use WithPagination;

    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    public function setFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Order::query()
            ->where('user_id', Auth::id())
            ->with(['items.variant.product'])
            ->latest();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $orders = $query->paginate(8);

        return view('livewire.account.order-index', [
            'orders' => $orders,
        ])->layout('layouts.storefront', [
            'title' => 'Riwayat Pesanan — BloomingKembang',
        ]);
    }
}
