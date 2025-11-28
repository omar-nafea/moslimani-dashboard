<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'price',
    'is_active',
    'image',
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
      'is_active' => 'boolean',
    ];
  }

  /**
   * Get all order items for this product.
   */
  public function orderItems(): HasMany
  {
    return $this->hasMany(OrderItem::class);
  }

  /**
   * Get the full URL for the product image.
   */
  public function getImageUrlAttribute(): ?string
  {
    if (!$this->image) {
      return null;
    }

    return Storage::disk('public')->url($this->image);
  }

  /**
   * Scope a query to only include active products.
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * Scope a query to search products by name.
   */
  public function scopeSearch($query, ?string $search)
  {
    if ($search) {
      return $query->where('name', 'ilike', "%{$search}%");
    }

    return $query;
  }
}



