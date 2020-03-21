<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use App\Wishlist;
use DB;

class ProductController extends Controller
{
    public function addProduct(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required|min:9',
            'price'=> 'required|integer',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validation->errors()->all(),
            ]);
        }
        $user = auth()->user();
        if ($user) {
            $product = Product::create([
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
            ]);
           return response()->json([
               'success'    => true,
               'data'       => 'Product added',
           ]);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required|min:9',
            'price'=> 'required|integer',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validation->errors()->all(),
            ]);
        }
        $user = auth()->user();
        if ($user) {
            $product = Product::where('id', $id)->update([
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
            ]);
            return response()->json([
                'success'    => true,
                'data'       => 'Successfull update',
            ]);
        }
    }

    public function getAll(Request $request)
    {
        $token = $request->token;
        $user = auth()->user($token);
        $product = Product::get();
         $array = [];
        if ($user) {
            for($i = 0; $i < count($product); $i++){
                if (Wishlist::checkWishlist($product[$i]->id, $user->id)) {
                    $return = response()->json([
                        'data' => $product[$i],
                    ]);
                    array_push($array, $return);
                    $product[$i]->wishlist = 1;
                }
                else {
                    $return = response()->json([
                        'data' => $product[$i],
                    ]);
                    array_push($array, $return);
                    $product[$i]->wishlist = 0;
                }
            }
        }
        else {
            for($i = 0; $i < count($product); $i++){
                $return = response()->json([
                    'data' => $product[$i],
                ]);
                array_push($array, $return);
                $product[$i]->wishlist = 0;
            }
        }
        return response()->json(['data' =>$product]); 
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
