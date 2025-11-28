<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ComingProduct extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'coming_products';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
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
      'is_active' => 'boolean',
    ];
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
   * Scope a query to only include active coming products.
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }
}



