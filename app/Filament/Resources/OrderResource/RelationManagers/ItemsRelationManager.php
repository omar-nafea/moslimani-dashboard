<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
  protected static string $relationship = 'items';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('name')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('price')
          ->numeric()
          ->prefix('$')
          ->required(),
        Forms\Components\TextInput::make('quantity')
          ->numeric()
          ->required(),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('name')
      ->columns([
        Tables\Columns\TextColumn::make('product.name')
          ->label('Product'),

        Tables\Columns\TextColumn::make('name')
          ->label('Name at Order'),

        Tables\Columns\TextColumn::make('price')
          ->money('USD'),

        Tables\Columns\TextColumn::make('quantity'),

        Tables\Columns\TextColumn::make('total')
          ->money('USD'),
      ])
      ->filters([
        //
      ])
      ->headerActions([
        //
      ])
      ->actions([
        //
      ])
      ->bulkActions([
        //
      ]);
  }
}



