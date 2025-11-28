<?php

namespace App\Filament\Resources\ComingProductResource\Pages;

use App\Filament\Resources\ComingProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComingProduct extends EditRecord
{
  protected static string $resource = ComingProductResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}



