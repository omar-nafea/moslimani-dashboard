<?php

namespace Database\Seeders;

use App\Models\ComingProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Sample products
    $products = [
      [
        'name' => 'منتج تجريبي 1',
        'description' => 'وصف المنتج التجريبي الأول. منتج ذو جودة عالية.',
        'price' => 150.00,
        'is_active' => true,
      ],
      [
        'name' => 'منتج تجريبي 2',
        'description' => 'وصف المنتج التجريبي الثاني. منتج مميز بسعر منافس.',
        'price' => 200.00,
        'is_active' => true,
      ],
      [
        'name' => 'منتج تجريبي 3',
        'description' => 'وصف المنتج التجريبي الثالث. أفضل اختيار للعملاء.',
        'price' => 350.00,
        'is_active' => true,
      ],
      [
        'name' => 'منتج غير متاح',
        'description' => 'هذا المنتج غير متاح حالياً.',
        'price' => 100.00,
        'is_active' => false,
      ],
    ];

    foreach ($products as $product) {
      Product::firstOrCreate(
        ['name' => $product['name']],
        $product
      );
    }

    // Sample coming products
    $comingProducts = [
      [
        'name' => 'منتج قادم قريباً 1',
        'description' => 'منتج جديد سيتوفر قريباً. ترقبوا!',
        'is_active' => true,
      ],
      [
        'name' => 'منتج قادم قريباً 2',
        'description' => 'إصدار جديد ومميز قادم في الطريق.',
        'is_active' => true,
      ],
    ];

    foreach ($comingProducts as $product) {
      ComingProduct::firstOrCreate(
        ['name' => $product['name']],
        $product
      );
    }

    $this->command->info('Sample products and coming products created.');
  }
}



