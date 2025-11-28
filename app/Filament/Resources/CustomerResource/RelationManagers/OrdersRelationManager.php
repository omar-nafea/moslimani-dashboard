<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
  protected static string $relationship = 'orders';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('invoice_number')
          ->disabled(),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('invoice_number')
      ->columns([
        Tables\Columns\TextColumn::make('invoice_number')
          ->searchable(),

        Tables\Columns\TextColumn::make('status')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'pending' => 'warning',
            'completed' => 'success',
            'canceled' => 'danger',
            default => 'gray',
          }),

        Tables\Columns\TextColumn::make('total')
          ->money('USD'),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable(),
      ])
      ->filters([
        //
      ])
      ->headerActions([
        //
      ])
      ->actions([
        Tables\Actions\ViewAction::make()
          ->url(fn($record) => route('filament.admin.resources.orders.view', $record)),
      ])
      ->bulkActions([
        //
      ]);
  }
}



