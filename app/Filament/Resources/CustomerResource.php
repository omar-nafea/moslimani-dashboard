<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
  protected static ?string $model = Customer::class;

  protected static ?string $navigationIcon = 'heroicon-o-user-group';

  protected static ?string $navigationGroup = 'Customers';

  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Customer Information')
          ->schema([
            Forms\Components\TextInput::make('name')
              ->required()
              ->maxLength(255),

            Forms\Components\TextInput::make('phone')
              ->required()
              ->unique(ignoreRecord: true)
              ->maxLength(20),

            Forms\Components\TextInput::make('num_purchases')
              ->numeric()
              ->default(0)
              ->disabled(),
          ])
          ->columns(3),

        Forms\Components\Section::make('Address')
          ->schema([
            Forms\Components\TextInput::make('address_city')
              ->label('City')
              ->maxLength(255),

            Forms\Components\TextInput::make('address_street')
              ->label('Street')
              ->maxLength(255),

            Forms\Components\TextInput::make('address_building')
              ->label('Building')
              ->maxLength(255),
          ])
          ->columns(3),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('phone')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('address_city')
          ->label('City')
          ->toggleable(),

        Tables\Columns\TextColumn::make('num_purchases')
          ->label('Purchases')
          ->sortable(),

        Tables\Columns\TextColumn::make('orders_count')
          ->counts('orders')
          ->label('Orders'),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Section::make('Customer Information')
          ->schema([
            Infolists\Components\TextEntry::make('name'),
            Infolists\Components\TextEntry::make('phone'),
            Infolists\Components\TextEntry::make('num_purchases')
              ->label('Total Purchases'),
          ])
          ->columns(3),

        Infolists\Components\Section::make('Address')
          ->schema([
            Infolists\Components\TextEntry::make('address_city')
              ->label('City'),
            Infolists\Components\TextEntry::make('address_street')
              ->label('Street'),
            Infolists\Components\TextEntry::make('address_building')
              ->label('Building'),
          ])
          ->columns(3),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      RelationManagers\OrdersRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListCustomers::route('/'),
      'create' => Pages\CreateCustomer::route('/create'),
      'view' => Pages\ViewCustomer::route('/{record}'),
      'edit' => Pages\EditCustomer::route('/{record}/edit'),
    ];
  }
}



