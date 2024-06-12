<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Counter;
use App\Models\Customer;
use App\Models\Invoice;

use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         Counter::factory(1)->create();
         Product::factory(50)->create();
         Customer::factory(50)->create();
         Invoice::factory(100)->create();
         InvoiceItem::factory(100)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
