<?php

namespace App\Filament\Resources\ComingProductResource\Pages;

use App\Filament\Resources\ComingProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComingProducts extends ListRecords
{
  protected static string $resource = ComingProductResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}



