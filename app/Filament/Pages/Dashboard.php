<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dashboard extends BaseDashboard
{
  protected static ?string $navigationIcon = 'heroicon-o-home';

  protected static string $view = 'filament.pages.dashboard';

  public function getStats(): array
  {
    return [
      Stat::make('Total Products', Product::count())
        ->description('Active: ' . Product::where('is_active', true)->count())
        ->color('success'),
      Stat::make('Total Orders', Order::count())
        ->description('Pending: ' . Order::where('status', 'pending')->count())
        ->color('warning'),
      Stat::make('Total Customers', Customer::count())
        ->description('Total purchases: ' . Customer::sum('num_purchases'))
        ->color('info'),
      Stat::make('Total Revenue', number_format(Order::where('status', 'completed')->sum('total'), 2))
        ->description('From completed orders')
        ->color('success'),
    ];
  }
}



