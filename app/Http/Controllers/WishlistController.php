<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Wishlist;
use App\Product;
use App\User;
use DB;

class WishlistController extends Controller
{   
    public function addWish(Request $request, $id)
    {
        $token = $request->token;
        $user = auth()->user($token); 
        if ($user) {
            $wishlist = Wishlist::where('user_id', $user->id)->where('product_id',$id)->create([
                'user_id' => $user->id,
                'product_id' => $id,
                ]);
            return response()->json([
                'success' => true,
                'data' => 'Product added to Wishlist',
            ]);
        }
    }

    public function deleteWish(Request $request, $id)
    {
        $token = $request->token;
        $user = auth()->user($token); 
        if ($user) {
            $wishlist = Wishlist::where('user_id', $user->id)->where('product_id',$id)->delete();
            return response()->json([
                'success' => true,
                'data' => 'Product deleted from Wishlist',
            ]);
        }
    }
}
