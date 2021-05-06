<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products_data = Http::withBasicAuth('269a1ec67dfdd434dfc8622a0ed77768',
            '4e788173c35d04421ab4793044be622f')
            ->get('https://send4-avaliacao.myshopify.com/admin/api/2020-01/products.json');

        $products = json_decode($products_data);
        for ($x = 0; $x < count($products->products); $x++) {
            Products::create([
                'name' => $products->products[$x]->title,
                'product_id' => $products->products[$x]->id
            ]);
        }
    }
}
