<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WishListController extends Controller
{
    public function index()
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

    /**
     * Show the form for creating a new resource.
     *
     *
     */
    public function show(Request $request)
    {
        $products = Wishlist::where('costumer_id', $request->get('costumer_id'))->get();
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
        $products = Wishlist::where('product_id', $request->get('product_id'))
            ->where('costumer_id', $request->get('costumer_id'))
            ->first();

        if (!empty($products)) {
            return response()->json(['message' => 'produto já adicionado à sua lista de desejos'],  500);
        }

        $product = new Wishlist();
        $product->costumer_id = $request->get('costumer_id');
        $product->product_id = $request->get('product_id');
        $product->save();

        return response()->json(['status' => 'success'], 201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Models\Wishlist  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wishlist $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wishlist  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $products)
    {
        echo Auth::user();
//        $products = Wishlist::where('product_id', $products)
//                            ->where('id', auth()->user()->getAuthIdentifier());
    }
}
