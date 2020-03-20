<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use App\Wishlist;
use DB;

class ProductController extends Controller
{
    public function getAll(Request $request)
    {
        $token = $request->token;
        $user = auth()->user($token);
        
        $product = Product::get()->all();
        $array = [];
        if ($user) {
            for($i = 0; $i < count($product); $i++){
                if (WishlistController::checkWishlist($product[$i]->id)) {
                    $return = response()->json([
                        'data' => $product[$i],
                        'wishlist' => 1,
                    ]);
                    array_push($array, $return);
                }
                else {
                    $return = response()->json([
                        'data' => $product[$i],
                        'wishlist' => 0,
                    ]);
                    array_push($array, $return);
                }
            }
            return $array;
        }
        else {
            for($i = 0; $i < count($product); $i++){
                $return = response()->json([
                    'data' => $product[$i],
                    'wishlist' => 0,
                ]);
                array_push($array, $return);
            }
            return $array;
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
