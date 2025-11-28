<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
  protected static ?string $model = Order::class;

  protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

  protected static ?string $navigationGroup = 'Orders';

  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Order Information')
          ->schema([
            Forms\Components\Select::make('customer_id')
              ->relationship('customer', 'name')
              ->searchable()
              ->preload()
              ->required(),

            Forms\Components\Select::make('status')
              ->options([
                'pending' => 'Pending',
                'completed' => 'Completed',
                'canceled' => 'Canceled',
              ])
              ->required(),

            Forms\Components\TextInput::make('invoice_number')
              ->disabled(),
          ])
          ->columns(3),

        Forms\Components\Section::make('Address & Contact')
          ->schema([
            Forms\Components\TextInput::make('phone')
              ->required(),

            Forms\Components\TextInput::make('address_city')
              ->label('City'),

            Forms\Components\TextInput::make('address_street')
              ->label('Street'),

            Forms\Components\TextInput::make('address_building')
              ->label('Building'),
          ])
          ->columns(4),

        Forms\Components\Section::make('Totals')
          ->schema([
            Forms\Components\TextInput::make('subtotal')
              ->numeric()
              ->prefix('EGP')
              ->required()
              ->live(onBlur: true)
              ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                $subtotal = floatval($get('subtotal') ?? 0);
                $shipping = floatval($get('shipping_cost') ?? 0);
                $set('total', number_format($subtotal + $shipping, 2, '.', ''));
              }),

            Forms\Components\TextInput::make('shipping_cost')
              ->numeric()
              ->prefix('EGP')
              ->required()
              ->default(35)
              ->live(onBlur: true)
              ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                $subtotal = floatval($get('subtotal') ?? 0);
                $shipping = floatval($get('shipping_cost') ?? 0);
                $set('total', number_format($subtotal + $shipping, 2, '.', ''));
              }),

            Forms\Components\TextInput::make('total')
              ->numeric()
              ->prefix('EGP')
              ->required()
              ->readOnly()
              ->dehydrated(),
          ])
          ->columns(3),

        Forms\Components\Section::make('Notes')
          ->schema([
            Forms\Components\Textarea::make('notes')
              ->rows(3)
              ->columnSpanFull(),
          ]),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('id')
          ->label('ID')
          ->sortable(),

        Tables\Columns\TextColumn::make('invoice_number')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('customer.name')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('phone')
          ->searchable(),

        Tables\Columns\TextColumn::make('total')
          ->money('EGP')
          ->sortable(),

        Tables\Columns\TextColumn::make('status')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'pending' => 'warning',
            'completed' => 'success',
            'canceled' => 'danger',
            default => 'gray',
          }),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable(),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            'pending' => 'Pending',
            'completed' => 'Completed',
            'canceled' => 'Canceled',
          ]),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('download_invoice')
          ->label('Invoice')
          ->icon('heroicon-o-document-arrow-down')
          ->color('success')
          ->url(fn(Order $record) => route('filament.orders.invoice', $record))
          ->openUrlInNewTab(),
        Tables\Actions\Action::make('mark_completed')
          ->label('Complete')
          ->icon('heroicon-o-check')
          ->color('success')
          ->visible(fn(Order $record) => $record->status === 'pending')
          ->requiresConfirmation()
          ->action(fn(Order $record) => $record->markAsCompleted()),
        Tables\Actions\Action::make('mark_canceled')
          ->label('Cancel')
          ->icon('heroicon-o-x-mark')
          ->color('danger')
          ->visible(fn(Order $record) => $record->status === 'pending')
          ->requiresConfirmation()
          ->action(fn(Order $record) => $record->markAsCanceled()),
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
        Infolists\Components\Section::make('Order Information')
          ->schema([
            Infolists\Components\TextEntry::make('invoice_number'),
            Infolists\Components\TextEntry::make('status')
              ->badge()
              ->color(fn(string $state): string => match ($state) {
                'pending' => 'warning',
                'completed' => 'success',
                'canceled' => 'danger',
                default => 'gray',
              }),
            Infolists\Components\TextEntry::make('created_at')
              ->dateTime(),
          ])
          ->columns(3),

        Infolists\Components\Section::make('Customer')
          ->schema([
            Infolists\Components\TextEntry::make('customer.name'),
            Infolists\Components\TextEntry::make('phone'),
            Infolists\Components\TextEntry::make('full_address')
              ->label('Address'),
          ])
          ->columns(3),

        Infolists\Components\Section::make('Totals')
          ->schema([
            Infolists\Components\TextEntry::make('subtotal')
              ->money('EGP'),
            Infolists\Components\TextEntry::make('shipping_cost')
              ->money('EGP'),
            Infolists\Components\TextEntry::make('total')
              ->money('EGP')
              ->weight('bold'),
          ])
          ->columns(3),

        Infolists\Components\Section::make('Notes')
          ->schema([
            Infolists\Components\TextEntry::make('notes')
              ->default('-'),
          ])
          ->collapsible(),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      RelationManagers\ItemsRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListOrders::route('/'),
      'create' => Pages\CreateOrder::route('/create'),
      'view' => Pages\ViewOrder::route('/{record}'),
      'edit' => Pages\EditOrder::route('/{record}/edit'),
    ];
  }
}



