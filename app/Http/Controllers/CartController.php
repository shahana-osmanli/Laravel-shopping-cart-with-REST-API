<?php

namespace App\Http\Controllers;
use App\Cart;
use App\Product;
use App\User;
use DB;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        /*return response()->json([
            'data' => $request->all(),
            'id' => $id,
            ]);*/
        $token = $request->token;
        $user = auth()->user($token); 
        
        if ($user) {
            $cart = Cart::create([
                'user_id'     => $user->id,
                'product_id'  => $id,
                'quantity'    => $request->quantity,
            ]);
           return response()->json([
               'success'    => true,
               'message'    => 'Product is added',
               'data'       => $cart,
           ]);
        }
        else {
            return response()->json([
                'success'    => false,
                'message'    => 'required auth',
            ]);
        }
    }
}
