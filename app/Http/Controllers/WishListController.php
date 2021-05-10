<?php

namespace App\Http\Controllers;

use App\Mail\NewProductAdded;
use App\Mail\ProductRemoved;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class WishListController extends Controller
{
    public function products()
    {
        $products = DB::select('SELECT * FROM products');
        for ($x = 0; $x < count($products); $x++) {
            $json [$x] = [
                "id" => $products[$x]->product_id,
                "name" => $products[$x]->name
            ];
        }

        return response()->json($json);
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

        //verifica se o produto j''a está na wishlist do usuário
        if (!empty($products)) {
            return response()->json(['message' => 'produto já adicionado à sua lista de desejos'], 500);
        }

        try {
            $product = new Wishlist();
            $product->costumer_id = Auth::id();
            $product->product_id = $request->get('product_id');
            $product->save();

            //recuperar o nome do produto e enviar um email
            $product_name = DB::table('products')
                ->where('product_id', '=', [$request->get('product_id')])
                ->first();
            Mail::send(new NewProductAdded($product_name->name));

            return response()->json(['status' => 'success'], 201);
        } catch (Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(string $products)
    {
        $product_name = DB::table('products')
            ->where('product_id', '=', [$products])
            ->first();
        try {
            $products = Wishlist::where('product_id', $products)
                ->where('costumer_id', Auth::id())->delete();
            if ($products) {
                Mail::send(new ProductRemoved($product_name->name));
                return response()->json(['message' => 'produto removido da lista de desejos'], 200);
            }
        } catch (Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'produto não encontrado na sua lista de desejos'], 500);

    }
}
