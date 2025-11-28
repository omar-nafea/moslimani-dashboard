<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
  protected static ?string $model = Product::class;

  protected static ?string $navigationIcon = 'heroicon-o-cube';

  protected static ?string $navigationGroup = 'Shop';

  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Product Information')
          ->schema([
            Forms\Components\TextInput::make('name')
              ->required()
              ->maxLength(255),

            Forms\Components\Textarea::make('description')
              ->rows(4)
              ->columnSpanFull(),

            Forms\Components\TextInput::make('price')
              ->required()
              ->numeric()
              ->prefix('$')
              ->minValue(0),

            Forms\Components\Toggle::make('is_active')
              ->label('Active')
              ->default(true),
          ])
          ->columns(2),

        Forms\Components\Section::make('Product Image')
          ->schema([
            Forms\Components\FileUpload::make('image')
              ->image()
              ->disk('public')
              ->directory('products')
              ->visibility('public')
              ->imageResizeMode('cover')
              ->imageCropAspectRatio('1:1')
              ->imageResizeTargetWidth('500')
              ->imageResizeTargetHeight('500'),
          ]),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\ImageColumn::make('image')
          ->disk('public')
          ->circular(),

        Tables\Columns\TextColumn::make('name')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('price')
          ->money('USD')
          ->sortable(),

        Tables\Columns\IconColumn::make('is_active')
          ->boolean()
          ->label('Active'),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Tables\Filters\TernaryFilter::make('is_active')
          ->label('Active Status'),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListProducts::route('/'),
      'create' => Pages\CreateProduct::route('/create'),
      'edit' => Pages\EditProduct::route('/{record}/edit'),
    ];
  }
}



