<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Halaman')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Halaman')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->prefix('/halaman/'),
                        RichEditor::make('body')
                            ->label('Isi Konten Halaman')
                            ->columnSpanFull(),
                        Toggle::make('is_published')
                            ->label('Publikasikan Halaman')
                            ->default(true),
                    ]),

                Section::make('Search Engine Optimization (SEO)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Judul SEO (Meta Title)')
                            ->placeholder('Judul yang muncul di hasil pencarian Google')
                            ->maxLength(160),
                        Textarea::make('seo_description')
                            ->label('Deskripsi SEO (Meta Description)')
                            ->placeholder('Ringkasan konten maksimal 160 karakter')
                            ->maxLength(255),
                    ]),
            ]);
    }
}
