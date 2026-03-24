<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product details')
                    ->columnSpanFull()
                        ->tabs([
                            Tab::make('Basic Information')
                                ->icon(Heroicon:: InformationCircle)
                                    ->schema([
                                        Section::make('Product Details')
                                            ->schema([
                                                TextInput::make('name')
                                                    ->required(),
                                                TextInput::make('slug')
                                                    ->unique(ignoreRecord: true)
                                                    ->visibleOn('edit')
                                                    ->required(),
                                                Select::make('category_id')
                                                    ->relationship('category', 'name')
                                                    ->preload()
                                                    ->searchable()
                                                    ->required()
                                                    ->createOptionForm([
                                                        TextInput::make('name')
                                                            ->required(),
                                                        TextInput::make('slug')
                                                            ->unique(ignoreRecord: true)
                                                            ->readOnly()
                                                            ->visibleOn('edit'),
                                                    ]),
                                                Select::make('brand_id')
                                                    ->relationship('brand', 'name')
                                                    ->preload()
                                                    ->searchable()
                                                    ->default(null)
                                                    ->createOptionForm([
                                                        TextInput::make('name')
                                                            ->required(),
                                                        TextInput::make('slug')
                                                            ->unique(ignoreRecord: true)
                                                            ->readOnly()
                                                            ->visibleOn('edit'),
                                                    ]),
                                            ])->columns(2),
                                        Section::make('Product Description')
                                            ->schema([
                                                Textarea::make('short_description')
                                                    ->default(null)
                                                    ->columnSpanFull(),
                                                RichEditor::make('description')
                                                    ->default(null)
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),
                            Tab::make('Pricing & Inventory')
                                ->icon(Heroicon::CurrencyDollar)
                                    ->schema([
                                        Section::make('Pricing')
                                            ->schema([
                                                TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->unique(ignoreRecord: true)
                                                    ->default(fn() => 'SKU-' . strtoupper(Str::random(8)))
                                                    ->helperText('Stock keeping Unit - unique identifier')
                                                    ->required(),
                                                TextInput::make('price')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->step(0.01)
                                                    ->helperText('Selling Price')
                                                    ->prefix('$'),
                                                TextInput::make('compare_price')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->step(0.01)
                                                    ->helperText('Original price to show discount')
                                                    ->prefix('$'),
                                                TextInput::make('cost_price')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->step(0.01)
                                                    ->helperText('Cost price to calculate profit')
                                                    ->prefix('$'),
                                            ])->columns(2),
                                        Section::make('Inventory')
                                            ->schema([
                                                Toggle::make('manage_stock')
                                                    ->default(true)
                                                    ->helperText('Enable stock management for this product')
                                                    ->live(),
                                                TextInput::make('stock_quantity')
                                                    ->label('Stock Quantity')
                                                    ->required(fn(callable $get): mixed => $get('manage_stock'))
                                                    ->disabled(fn(callable $get): mixed => !$get('manage_stock'))
                                                    ->numeric()
                                                    ->default(0),
                                                TextInput::make('low_stock_threshold')
                                                    ->label('Low Stock Alert Threshold ')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0)
                                                    ->helperText('Get notified when stock falls below this number'),
                                                Select::make('stock_status')     
                                                    ->options([
                                                        'in_stock' => 'In stock',
                                                        'out_of_stock' => 'Out of stock',
                                                        'on_backorder' => 'On backorder',
                                                    ])
                                                    ->native(false)
                                                    ->default('in_stock')
                                                    ->required(),
                                                TextInput::make('weight')
                                                    ->label('Weight (kg)')
                                                    ->numeric()
                                                    ->minValue('0')
                                                    ->helperText('Used for shipping calculations')
                                                    ->default(null),
                                            ])->columns(2),
                                    ]),
                            Tab::make('Images')
                                ->icon(Heroicon::Photo)
                                    ->schema([
                                        Section::make('Product Images')
                                            ->description('Upload multiple images. The first image will be the primary image.')
                                            ->schema([
                                                FileUpload::make('images')
                                                    ->label('Product Images')
                                                    ->multiple()
                                                    ->image()
                                                    ->directory('products')
                                                    ->imageEditor()
                                                    ->maxSize(2048)
                                                    ->reorderable()
                                                    ->columnSpanFull()
                                                    ->helperText('You can drag and drop to reorder')
                                                    ->saveRelationshipsUsing(function($component, $state, $record): void{
                                                        // Delete existing images 
                                                        $record->images()->delete();
                                                        if(is_array($state)){
                                                            foreach($state as $index => $imagePath){
                                                                $record->images()->create([
                                                                    'image_path' => $imagePath,
                                                                    'is_primary' => $index === 0,
                                                                    'sort_order' => $index,
                                                                ]);
                                                            }
                                                        }  
                                                    })
                                                    ->dehydrated(false),
                                            ]),
                                    ]),
                            Tab::make('Product Variants')
                                ->icon(Heroicon::Squares2x2)
                                    ->schema([
                                        Toggle::make('has_variants')
                                            ->live()
                                            ->required(),
                                            Section::make('Product Variants')
                                                ->description('Add variants like different sizes or colors')
                                                ->schema([
                                                    Repeater::make('variants')
                                                        ->relationship('variants')
                                                        ->schema([
                                                            TextInput::make('name')
                                                                ->required()
                                                                ->label('Variant Name')
                                                                ->placeholder('e.g., Red - Large')
                                                                ->columnSpanFull(),
                                                            KeyValue::make('options'),
                                                            TextInput::make('sku')
                                                                ->label('SKU')
                                                                ->unique(ignoreRecord: true)
                                                                ->helperText('Stock keeping Unit - unique identifier')
                                                                ->default(fn() => 'VAR-' . strtoupper(Str::random(8)))
                                                                ->required()
                                                                ->columnSpan(2),
                                                            TextInput::make('price')
                                                                ->required()
                                                                ->numeric()
                                                                ->prefix('$')
                                                                ->minValue(0)
                                                                ->step(0.01),
                                                            TextInput::make('compare_price')
                                                                ->label('Compare Price')
                                                                ->numeric()
                                                                ->prefix('$')
                                                                ->minValue(0)
                                                                ->step(0.01),
                                                            TextInput::make('stock_quantity')
                                                                ->label('Stock')
                                                                ->numeric()
                                                                ->default(0)
                                                                ->minValue(0)
                                                                ->required(),
                                                            Select::make('stock_status')
                                                                ->options([
                                                                    'in_stock' => 'In stock',
                                                                    'out_of_stock' => 'Out of stock',
                                                                    'on_backorder' => 'On backorder',
                                                                ])
                                                                ->default('in_stock')
                                                                ->required()
                                                                ->native(false),
                                                            Toggle::make('is_active')
                                                                ->label('Active')
                                                                ->default(true)
                                                        ])
                                                        ->columns(2)
                                                        ->defaultItems(0)
                                                        ->collapsible()
                                                        ->itemLabel(fn($state) => $state['name'] ?? null)
                                                        ->addActionLabel('Add Variant')
                                
                                                ])
                                                ->visible(fn($get) => $get('has_variants'))
                                                ->columnSpanFull()
                                    ]),
                            Tab::make('Settings')
                                ->icon(Heroicon::Cog6Tooth)
                                    ->schema([
                                        Section::make('Product status')
                                            ->schema([
                                                Toggle::make('is_active')
                                                    ->required(),
                                                Toggle::make('is_featured')
                                                    ->required(),
                                            ])->columns(2),
                                        Section::make('statistics')
                                            ->schema([
                                                TextEntry::make('views_count')
                                                    ->state(fn ($record) => $record?->views_count ?? 0),
                                                TextEntry::make('created_at')
                                                    ->label('Created')
                                                    ->state(fn ($record) => $record?->created_at?->diffForHumans() ?? '-'),
                                            ]),
                                    ]),
                            Tab::make('SEO')
                                ->icon(Heroicon::MagnifyingGlass)
                                    ->schema([
                                        Section::make('Search Engine Optimization')
                                            ->schema([
                                                TextInput::make('meta_title')
                                                    ->default(null),
                                                Textarea::make('meta_description')
                                                    ->default(null)
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),
                        ]),
            ]);
    }
}
