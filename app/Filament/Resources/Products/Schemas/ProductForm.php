<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductStatus;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Kategori'),
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(160)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(180),
                TextInput::make('material')
                    ->label('Bahan Hijab (mis. Voal Ultrafine, Ceruty Babydoll, Paris Premium)')
                    ->maxLength(80),
                Select::make('status')
                    ->label('Status Produk')
                    ->options(ProductStatus::class)
                    ->default(ProductStatus::Draft)
                    ->required(),
                Toggle::make('is_featured')
                    ->label('Produk Unggulan (Featured di Beranda)')
                    ->default(false),
                SpatieMediaLibraryFileUpload::make('images')
                    ->label('Foto Produk (Multi-Gambar)')
                    ->collection('images')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->conversion('thumb')
                    ->columnSpanFull(),
                Textarea::make('short_description')
                    ->label('Deskripsi Singkat')
                    ->rows(2)
                    ->maxLength(300)
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->label('Deskripsi Lengkap')
                    ->columnSpanFull(),
                Textarea::make('size_guide')
                    ->label('Panduan Ukuran')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('care_guide')
                    ->label('Panduan Perawatan')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('seo_title')
                    ->label('SEO Meta Title')
                    ->maxLength(70),
                TextInput::make('seo_description')
                    ->label('SEO Meta Description')
                    ->maxLength(160),
            ]);
    }
}
