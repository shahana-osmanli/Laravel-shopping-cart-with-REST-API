<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {

        // $token = $request->token;
        $user = auth()->user();
        $quantity = $request->quantity;

        if ($quantity == 0) {
            $quantity = 1;
        }
        if ($user) {
            $existing = Cart::where('user_id', $user->id)->where('product_id', $id)->first();
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
            } else {
                $cart = Cart::where('user_id', $user->id)->update([
                    'quantity'    => $quantity + $existing->quantity,
                ]);
                return response()->json([
                    'success'    => true,
                    'message'    => 'Product is updated',
                    'data'       => $cart,
                ]);
            }
        } else {
            return response()->json([
                'success'    => false,
                'message'    => 'required auth',
            ]);
        }
    }
    public function getCartProducts(Request $request)
    {
        $user = auth()->user();
        //return Cart::where('user_id', $user->id)->get();
        return DB::table('users')
            ->where('users.id', $user->id)
            ->join('carts', 'carts.user_id', 'users.id')
            ->get();
    }
    public function deleteProduct(Request $request, $id)
    {
        $user = auth()->user();
        Cart::where('user_id', $user->id)->where('product_id', $id)->delete();
        return response()->json([
            'success'    => true,
            'data'       => 'Product removed',
        ]);
    }

    public function getQuantity(Request $request, $id)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->where('product_id', $id)->first();
        return $cart->quantity;
    }

    public function increase(Request $request, $id)
    {
        $user = auth()->user();
        $existing = Cart::where('user_id', $user->id)->where('product_id', $id)->first();
        $existing->update([
            'quantity' => $existing->quantity + 1,
        ]);
        return $existing->quantity;
    }

    public function decrease(Request $request, $id)
    {
        $user = auth()->user();
        $existing = Cart::where('user_id', $user->id)->where('product_id', $id)->get()[0];

        if ($existing->quantity > 0) {
            $existing->update([
                'quantity' => $existing->quantity - 1,
            ]);
            return $existing->quantity;
        } else $this->deleteProduct($request, $id);
    }
}