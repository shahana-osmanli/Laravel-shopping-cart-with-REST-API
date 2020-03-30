<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cart;
use App\Product;
use App\Checkout;

class CheckoutController extends Controller
{
    public function Send(Request $request)
    {   
        $total = 0;
        $user = auth()->user();
        $product = $request->products;
        if ($user) {
            for($i = 0; $i < count($product); $i++){
                $total += self::calculateTotalPrice($user->id, $product[$i]);
            }
        $cart = Cart::where('user_id', $user->id)->whereIn('product_id', $product)->get();
            if($cart){
                $cartProduct = DB::table('carts')
                        ->select(['product_id', 'quantity'])
                        ->where('user_id', $user->id)
                        ->get();
                $checkout = Checkout::create([
                    'user_id' => $user->id,
                    'products' => json_encode($cartProduct),
                    'note' => $request->note,
                    'total_price' => $total,
                ]);
                Cart::where('user_id', $user->id)->delete();                    

            return "Product sended to checkout";
            }
            else {
                return "Refresh your cart and try again";
            }
        }
    }

    public function calculateTotalPrice($user_id, $product_id)
    {
       $quantity = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
       $price = Product::where('id', $product_id)->first();
       return $price->price*$quantity->quantity;
    }
    
}