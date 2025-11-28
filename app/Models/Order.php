<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
  use HasFactory;

  /**
   * Order status constants.
   */
  public const STATUS_PENDING = 'pending';
  public const STATUS_COMPLETED = 'completed';
  public const STATUS_CANCELED = 'canceled';

  /**
   * Available order statuses.
   */
  public const STATUSES = [
    self::STATUS_PENDING,
    self::STATUS_COMPLETED,
    self::STATUS_CANCELED,
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'customer_id',
    'status',
    'subtotal',
    'shipping_cost',
    'total',
    'phone',
    'address_street',
    'address_building',
    'address_city',
    'notes',
    'invoice_number',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'subtotal' => 'decimal:2',
      'shipping_cost' => 'decimal:2',
      'total' => 'decimal:2',
    ];
  }

  /**
   * Get the customer that owns this order.
   */
  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  /**
   * Get all items for this order.
   */
  public function items(): HasMany
  {
    return $this->hasMany(OrderItem::class);
  }

  /**
   * Generate a unique invoice number for this order.
   */
  public static function generateInvoiceNumber(int $orderId): string
  {
    return 'INV-' . now()->format('Ymd') . '-' . str_pad($orderId, 5, '0', STR_PAD_LEFT);
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

  /**
   * Check if the order is pending.
   */
  public function isPending(): bool
  {
    return $this->status === self::STATUS_PENDING;
  }

  /**
   * Check if the order is completed.
   */
  public function isCompleted(): bool
  {
    return $this->status === self::STATUS_COMPLETED;
  }

  /**
   * Check if the order is canceled.
   */
  public function isCanceled(): bool
  {
    return $this->status === self::STATUS_CANCELED;
  }

  /**
   * Mark the order as completed.
   */
  public function markAsCompleted(): void
  {
    $previousStatus = $this->status;
    $this->status = self::STATUS_COMPLETED;
    $this->save();

    // Increment customer purchases only when transitioning to completed
    if ($previousStatus !== self::STATUS_COMPLETED) {
      $this->customer->incrementPurchases();
    }
  }

  /**
   * Mark the order as canceled.
   */
  public function markAsCanceled(): void
  {
    $this->status = self::STATUS_CANCELED;
    $this->save();
  }

  /**
   * Scope a query to filter orders by status.
   */
  public function scopeStatus($query, ?string $status)
  {
    if ($status && in_array($status, self::STATUSES)) {
      return $query->where('status', $status);
    }

    return $query;
  }
}



