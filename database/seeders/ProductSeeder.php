<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product::create([
        //     'name' => 'Test Product',
        //     'description' => 'Test Description',
        //     'price' => 100,
        //     'stock' => 10,
        //     'image' => 'products/G0CJMjUn7WwyfxYND53OAlforknGrM875q51dDTW.jpg'
        // ]);
        Product::factory()->count(10)->create();
    }
}
