<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use DB;

class ProductController extends Controller
{
    public function getAll(Request $request)
    {
        $wishlist = 0;
        if ($request->token == null) {
            for( $i = 0; $i < count(Product::get()); $i++ ) {
                echo $wishlist;
                printf (Product::get()[$i]);
                echo PHP_EOL;
            }
        }
        else {
            $wishlist = 1;
            for( $i = 0; $i < count(Product::get()); $i++ ) {
                echo $wishlist;
                printf (Product::get()[$i]);
                echo PHP_EOL;
            }
        }
    }

    public function getOne($id)
    {
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json([
                'success' => false,
                'data' => 'Sorry, product with id ' . $id . ' cannot be found'
            ], 400);
        }
        else {
            return response()->json([
                'success'=>true,
                'data'=>$product
            ]);
        }
    }
}
