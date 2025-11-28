<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'phone',
    'address_city',
    'address_street',
    'address_building',
    'num_purchases',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'num_purchases' => 'integer',
    ];
  }

  /**
   * Get all orders for this customer.
   */
  public function orders(): HasMany
  {
    return $this->hasMany(Order::class);
  }

  /**
   * Increment the purchase count for this customer.
   */
  public function incrementPurchases(): void
  {
    $this->increment('num_purchases');
  }

  /**
   * Get the full address as a formatted string.
   */
  public function getFullAddressAttribute(): string
  {
    $parts = array_filter([
      $this->address_building,
      $this->address_street,
      $this->address_city,
    ]);

    return implode(', ', $parts);
  }
}



