<?php

namespace App\Http\Controllers;
use App\Cart;
use App\Product;
use App\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        
       // $token = $request->token;//bunu yerine
        $user = auth()->user(); //bele de ishleyecey
        $quantity = $request->quantity;
        
        if ($quantity == 0) { 
            $quantity = 1;
        }
        if ($user) {
            //o biri bu usere aid deyiulki ona gore error qaytarirda
            // get[0]ci ile first -un fergi nedi ki? 
            //first = arrya() -> burda index istemir
           // get[0] = array[0] -> ama burda imenni 0-ci indexi isteyir hee tamam
           //
            $existing = Cart::where('user_id', $user->id)->where('product_id', $id)->first();
            $existing = $existing == null ? false : Cart::where('user_id', $user->id)->where('product_id', $id)->get()[0] ;
            if ($existing == false) {
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
            else {
            $cart = Cart::where('user_id', $user->id)->update([
                'quantity'    => $quantity + $existing->quantity,
            ]);
           return response()->json([
               'success'    => true,
               'message'    => 'Product is updated',
               'data'       => $cart,
           ]);
        }
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
            //return Cart::where('user_id', $user->id)->get();
            return DB::table('users')
            ->where('users.id', $user->id)
            ->join('carts', 'carts.user_id', 'users.id')
            ->get();
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
        $cart = Cart::where('user_id', $user->id)->where('product_id', $id)->get()[0];
        if ($user) {
            return $cart->quantity;
        }
    }

    public function increase(Request $request, $id)
    {
        $token = $request->token;
        $user = auth()->user($token); 
        $existing = Cart::where('user_id', $user->id)->where('product_id', $id)->get()[0];

        if ($user) {
            $cart = $existing->update([
                'quantity' => $existing->quantity + 1,
            ]);
            return $existing->quantity;
        }

    }

    public function decrease(Request $request, $id)
    {
        $token = $request->token;
        $user = auth()->user($token); 
        $existing = Cart::where('user_id', $user->id)->where('product_id', $id)->get()[0];

        if ($user) {
            if ($existing->quantity > 0) {
                $cart = $existing->update([
                    'quantity' => $existing->quantity - 1,
                ]);
                return $existing->quantity;
            }
            else CartController::deleteProduct($request, $id);
      }
    }
}
