<?php

namespace App\Livewire\Account;

use App\Models\Address;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddressBook extends Component
{
    public bool $isFormOpen = false;

    public ?int $editingAddressId = null;

    #[Validate('required|string|max:50')]
    public string $label = 'Rumah';

    #[Validate('required|string|max:120')]
    public string $recipient_name = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('required|string|max:100')]
    public string $province = '';

    #[Validate('required|string|max:100')]
    public string $city = '';

    #[Validate('required|string|max:100')]
    public string $district = '';

    #[Validate('required|string|max:10')]
    public string $postal_code = '';

    #[Validate('required|string|max:500')]
    public string $detail = '';

    public bool $is_default = false;

    public function openCreateModal(): void
    {
        $this->reset(['editingAddressId', 'label', 'recipient_name', 'phone', 'province', 'city', 'district', 'postal_code', 'detail', 'is_default']);
        $this->recipient_name = Auth::user()->name;
        $this->phone = Auth::user()->phone ?? '';
        $this->label = 'Rumah';
        $this->is_default = Auth::user()->addresses()->count() === 0;
        $this->isFormOpen = true;
    }

    public function openEditModal(int $addressId): void
    {
        $address = Address::findOrFail($addressId);
        $this->authorize('update', $address);

        $this->editingAddressId = $address->id;
        $this->label = $address->label;
        $this->recipient_name = $address->recipient_name;
        $this->phone = $address->phone;
        $this->province = $address->province;
        $this->city = $address->city;
        $this->district = $address->district;
        $this->postal_code = $address->postal_code;
        $this->detail = $address->detail;
        $this->is_default = (bool) $address->is_default;
        $this->isFormOpen = true;
    }

    public function closeForm(): void
    {
        $this->isFormOpen = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate();
        $user = Auth::user();

        if ($this->is_default) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        if ($this->editingAddressId) {
            $address = Address::findOrFail($this->editingAddressId);
            $this->authorize('update', $address);

            $address->update($validated + ['is_default' => $this->is_default]);
            session()->flash('status', 'Alamat berhasil diperbarui.');
        } else {
            $user->addresses()->create($validated + ['is_default' => $this->is_default]);
            session()->flash('status', 'Alamat baru berhasil ditambahkan.');
        }

        $this->closeForm();
    }

    public function setDefault(int $addressId): void
    {
        $address = Address::findOrFail($addressId);
        $this->authorize('update', $address);

        $user = Auth::user();
        Address::where('user_id', $user->id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        session()->flash('status', 'Alamat utama berhasil diatur.');
    }

    public function deleteAddress(int $addressId): void
    {
        $address = Address::findOrFail($addressId);
        $this->authorize('delete', $address);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            Address::where('user_id', Auth::id())->latest()->first()?->update(['is_default' => true]);
        }

        session()->flash('status', 'Alamat berhasil dihapus.');
    }

    public function render(): View
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->latest()->get();

        return view('pages.account.address.index', [
            'addresses' => $addresses,
        ])->layout('layouts.storefront', [
            'title' => 'Buku Alamat — BloomingKembang',
        ]);
    }
}
