<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
  protected static string $resource = OrderResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\EditAction::make(),
      Actions\Action::make('download_invoice')
        ->label('Download Invoice')
        ->icon('heroicon-o-document-arrow-down')
        ->color('success')
        ->url(fn() => route('filament.orders.invoice', $this->record))
        ->openUrlInNewTab(),
      Actions\Action::make('mark_completed')
        ->label('Mark Completed')
        ->icon('heroicon-o-check')
        ->color('success')
        ->visible(fn() => $this->record->status === 'pending')
        ->requiresConfirmation()
        ->action(function () {
          $this->record->markAsCompleted();
          $this->refreshFormData(['status']);
        }),
      Actions\Action::make('mark_canceled')
        ->label('Mark Canceled')
        ->icon('heroicon-o-x-mark')
        ->color('danger')
        ->visible(fn() => $this->record->status === 'pending')
        ->requiresConfirmation()
        ->action(function () {
          $this->record->markAsCanceled();
          $this->refreshFormData(['status']);
        }),
    ];
  }
}



