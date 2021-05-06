<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WishListController extends Controller
{
    public function products()
    {
        $products_data = Http::withBasicAuth('269a1ec67dfdd434dfc8622a0ed77768',
            '4e788173c35d04421ab4793044be622f')
            ->get('https://send4-avaliacao.myshopify.com/admin/api/2020-01/products.json');
        return response($products_data);

        //decoding the json and returning name list
//        $products = json_decode($products_data);
//        for ($x = 0; $x < count($products->products); $x++) {
//            $products_name[] = ($products->products[$x]->title);
//        }
//        return response()->json(['products_name' => $products_name]);
    }


    public function index(Request $request)
    {
        $products = Wishlist::where('costumer_id', Auth::id())->get();
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)//: JsonResponse
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $products = Wishlist::where('product_id', $request->get('product_id'))
            ->where('costumer_id', Auth::id())
            ->first();

        if (!empty($products)) {
            return response()->json(['message' => 'produto já adicionado à sua lista de desejos'], 500);
        }

        $product = new Wishlist();
        $product->costumer_id = Auth::id();
        $product->product_id = $request->get('product_id');
        $product->save();

        return response()->json(['status' => 'success'], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(string $products)
    {
        $products = Wishlist::where('product_id', $products)
            ->where('costumer_id', Auth::id())->delete();
        if ($products) {
            return response()->json(['message' => 'produto removido da lista de desejos'], 200);
        }
        return response()->json(['message' => 'produto não encontrado na sua lista de desejos'], 500);

    }
}
