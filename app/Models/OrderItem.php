<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'order_id',
    'product_id',
    'name',
    'price',
    'quantity',
    'total',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'price' => 'decimal:2',
      'total' => 'decimal:2',
      'quantity' => 'integer',
    ];
  }

  /**
   * Get the order that this item belongs to.
   */
  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * Get the product that this item references.
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Calculate the total for this item.
   */
  public static function calculateTotal(string $price, int $quantity): string
  {
    return number_format((float) $price * $quantity, 2, '.', '');
  }
}



