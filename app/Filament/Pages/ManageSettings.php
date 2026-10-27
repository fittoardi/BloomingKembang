<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSettings extends Page
{
    use InteractsWithFormActions;

    protected string $view = 'filament.pages.manage-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan Toko';

    protected static ?string $title = 'Pengaturan Toko & Operasional';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 10;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public function mount(): void
    {
        $warehouse = Setting::get('store.warehouse_address', []);

        $this->form->fill([
            'store_name' => Setting::get('store.name', 'BloomingKembang'),
            'store_tagline' => Setting::get('store.tagline', 'Sentuhan Lembut & Elegan Hijab Pilihan'),
            'store_whatsapp' => Setting::get('store.whatsapp', '6281234567890'),
            'store_email' => Setting::get('store.email', 'cs@bloomingkembang.com'),

            'warehouse_province' => $warehouse['province'] ?? 'Jawa Barat',
            'warehouse_city' => $warehouse['city'] ?? 'Bandung',
            'warehouse_district' => $warehouse['district'] ?? 'Coblong',
            'warehouse_postal_code' => $warehouse['postal_code'] ?? '40132',
            'warehouse_detail' => $warehouse['detail'] ?? 'Jl. Dago Asri No. 12',

            'guest_checkout_enabled' => (bool) Setting::get('checkout.guest_enabled', true),
            'payment_expiry_hours' => (int) Setting::get('order.payment_expiry_hours', 24),
            'active_couriers' => Setting::get('shipping.couriers', ['jne', 'jnt', 'sicepat']),

            'ga4_id' => Setting::get('analytics.ga4_id', ''),
            'meta_pixel_id' => Setting::get('analytics.meta_pixel_id', ''),
            'whatsapp_token' => Setting::get('whatsapp.token', ''),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Informasi & Kontak Toko')
                    ->description('Identitas toko, nama brand, dan saluran komunikasi customer service.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('store_name')
                                ->label('Nama Toko')
                                ->required(),
                            TextInput::make('store_tagline')
                                ->label('Tagline / Slogan'),
                            TextInput::make('store_whatsapp')
                                ->label('Nomor WhatsApp Toko')
                                ->helperText('Format internasional tanpa tanda +, contoh: 6281234567890')
                                ->required(),
                            TextInput::make('store_email')
                                ->label('Email Customer Service')
                                ->email()
                                ->required(),
                        ]),
                    ]),

                Section::make('Alamat Gudang Asal Pengiriman')
                    ->description('Lokasi gudang utama untuk penentuan perhitungan titik asal ongkos kirim.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('warehouse_province')->label('Provinsi Asal')->required(),
                            TextInput::make('warehouse_city')->label('Kota/Kabupaten Asal')->required(),
                            TextInput::make('warehouse_district')->label('Kecamatan Asal')->required(),
                            TextInput::make('warehouse_postal_code')->label('Kode Pos')->required(),
                        ]),
                        TextInput::make('warehouse_detail')->label('Detail Alamat Gudang')->required(),
                    ]),

                Section::make('Pengaturan Checkout & Pesanan')
                    ->description('Konfigurasi batas waktu pembayaran dan mode guest checkout.')
                    ->schema([
                        Toggle::make('guest_checkout_enabled')
                            ->label('Izinkan Guest Checkout')
                            ->helperText('Jika dinonaktifkan, pembeli wajib masuk atau mendaftar akun sebelum checkout.')
                            ->default(true),
                        TextInput::make('payment_expiry_hours')
                            ->label('Batas Waktu Pembayaran (Jam)')
                            ->numeric()
                            ->default(24)
                            ->minValue(1)
                            ->maxValue(72)
                            ->helperText('Pesanan yang belum dibayar melewati batas jam ini akan dibatalkan otomatis oleh sistem dan reservasi stok dilepas.')
                            ->required(),
                        CheckboxList::make('active_couriers')
                            ->label('Kurir Ekspedisi Aktif')
                            ->options([
                                'jne' => 'JNE Express',
                                'jnt' => 'J&T Express',
                                'sicepat' => 'SiCepat Express',
                            ])
                            ->columns(3),
                    ]),

                Section::make('Notifikasi WhatsApp Gateway (Fonnte)')
                    ->description('Integrasi pengiriman pesan WhatsApp otomatis untuk konfirmasi order, pembayaran, dan update resi.')
                    ->schema([
                        TextInput::make('whatsapp_token')
                            ->label('Fonnte API Token')
                            ->password()
                            ->revealable()
                            ->placeholder('Isi token dari dashboard Fonnte')
                            ->helperText('Kosongkan untuk menggunakan logging lokal selama pengembangan.'),
                    ]),

                Section::make('Integrasi Analitik Pemasaran (Opsional)')
                    ->description('Koneksi pelacakan konversi e-commerce untuk Google Analytics 4 dan Meta Pixel.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('ga4_id')
                                ->label('Google Analytics Measurement ID')
                                ->placeholder('G-XXXXXXXXXX'),
                            TextInput::make('meta_pixel_id')
                                ->label('Meta (Facebook) Pixel ID')
                                ->placeholder('123456789012345'),
                        ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make($this->getFormActions()),
                    ]),
            ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('store.name', $data['store_name']);
        Setting::set('store.tagline', $data['store_tagline']);
        Setting::set('store.phone', $data['store_whatsapp']);
        Setting::set('store.whatsapp', $data['store_whatsapp']);
        Setting::set('store.email', $data['store_email']);

        Setting::set('store.warehouse_address', [
            'province' => $data['warehouse_province'],
            'city' => $data['warehouse_city'],
            'district' => $data['warehouse_district'],
            'postal_code' => $data['warehouse_postal_code'],
            'detail' => $data['warehouse_detail'],
        ]);

        Setting::set('checkout.guest_enabled', (bool) $data['guest_checkout_enabled']);
        Setting::set('order.payment_expiry_hours', (int) $data['payment_expiry_hours']);
        Setting::set('shipping.couriers', $data['active_couriers'] ?? ['jne', 'jnt', 'sicepat']);

        Setting::set('analytics.ga4_id', $data['ga4_id'] ?: null);
        Setting::set('analytics.meta_pixel_id', $data['meta_pixel_id'] ?: null);
        Setting::set('whatsapp.token', $data['whatsapp_token'] ?: null);

        Notification::make()
            ->title('Pengaturan toko berhasil disimpan')
            ->success()
            ->send();
    }
}
