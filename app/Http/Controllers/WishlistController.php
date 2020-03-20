<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Wishlist;
use App\Product;
use App\User;
use DB;

class WishlistController extends Controller
{   
    public function checkWishlist($product_id)
    {
        return Wishlist::where('product_id', $product_id);
    }
    public function addWish(Request $request, $id)
    {
        $user = auth()->user(); 
        $existing = Wishlist::where('user_id', $user->id)->where('product_id', $id)->first();
        if ($user && $existing == false) {
            $wishlist = Wishlist::where('user_id', $user->id)->where('product_id',$id)->create([
                'user_id' => $user->id,
                'product_id' => $id,
                ]);
            return response()->json([
                'success' => true,
                'data' => 'Product added to Wishlist',
            ]);
        }
        else return "Product is already in wishlist";
    }
//aaa ele json-un icinde versem? he jsonun icheinde vermelisen ama ki
//sende wishlist baasi var ordaki datalar ile user_id ni muqayise edeceysen hansilar beraberdi wishlist:1 ekshalda 0
 //hee vse bildim/
 //yaza bilersen? hee mence day dedin hamisini, yo demediyim 1-2 sheyde var. onlari demirem sabaha kimi catdir
 //eger laravel funksiyalarina aid bir sey varsa adlarini yaza bilersen meselen) ()yox laravellik doul)) hee vse onda
 // user login olubsa olmayibsa her shey bunun ustundedi) tamam. Okay,nese lazm olsa yazarsan
    public function deleteWish(Request $request, $id)
    {
        $user = auth()->user(); 
        if ($user) {
            $wishlist = Wishlist::where('user_id', $user->id)->where('product_id',$id)->delete();
            return response()->json([
                'success' => true,
                'data' => 'Product deleted from Wishlist',
            ]);
        }
    }
}
