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
        
        $token = $request->token;
        $user = auth()->user($token); 
        $quantity = $request->quantity;
        $existing = Cart::where('product_id', $id)->where('user_id', $user->id)->get()->all();
        
        if ($quantity == 0) {
            $quantity = 1;
        }

        if ($user && $existing == false) {
            $cart = Cart::create([
                'user_id'     => $user->id,
                'product_id'  => $id,
                'quantity'    => $quantity,
            ]);
           return response()->json([
               'success'    => true,
               'message'    => 'Product is added',
               'data'       => $cart,
           ]);
        }
        else if($user && $existing) {
            $cart = Cart::where('user_id', $user->id)->update([
                'quantity'    => $quantity + $existing->quantity,
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
    public function getCartProducts(Request $request)
    {   
        $token = $request->token;
        $user = auth()->user($token); 
        if ($user) {
            return Cart::where('user_id', $user->id)->get();
        }
        else return 'Auth required';
        
    }
    public function deleteProduct(Request $request, $id)
    {   
        $token = $request->token;
        $user = auth()->user($token); 
        if ($user) {
            Cart::where('user_id', $user->id)->where('product_id', $id)->delete();
            return response()->json([
               'success'    => true,
               'data'       => 'Product removed',
           ]);
        }
    }
    
    public function getQuantity(Request $request, $id)
    {
        $token = $request->token;
        $user = auth()->user($token); 
        $cart = Cart::where('user_id', $user->id)->where('product_id', $id)->get();
        if ($user) {
            return $cart->quantity;
        }
    }

    public function increase(Request $request, $id)
    {
        $token = $request->token;
        $user = auth()->user($token); 
        $existing = Cart::where('user_id', $user->id)->where('product_id', $id)->get();

        if ($user) {
            $cart = $existing->update([
                'quantity' => $existing->quantity + 1,
            ]);
        }

    }

    public function decrease(Request $request, $id)
    {
        $token = $request->token;
        $user = auth()->user($token); 
        $existing = Cart::where('user_id', $user->id)->where('product_id', $id)->get();

        if ($user) {
            if ($existing->quantity > 0) {
                $cart = $existing->update([
                    'quantity' => $existing->quantity - 1,
                ]);
                return response()->json([
                    'succes' => true,
                    'message' => 'Decreased',
                ]);
            }
            else CartController::deleteProduct($request, $id);
      }
    }
}
