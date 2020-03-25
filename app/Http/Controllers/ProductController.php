<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use App\Wishlist;
use DB;
use Validator;
use App;

class ProductController extends Controller
{
    public function addProduct(Request $request)
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
                'user_id'     => $user->id,
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

    public function deleteProduct(Request $request, $id)
    {   
        $user = auth()->user(); 
        if ($user) {
            User::find($user->id)->products()->where('id', $id)->delete();
            return response()->json([
             'success'    => true,
             'data'       => 'Product removed',
         ]);
        }
    }

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $product = Product::get();
        App::setLocale($request->header("X-Lang-Key"));
        if ($user) {
            for($i = 0; $i < count($product); $i++){
                $product[$i] = Product::select('products.*', 'translation.name', 'translation.description')
                ->where('products.id', $product[$i]->id)
                ->leftJoin('language', function($join){
                    $join->where('language.code','=', App::getLocale());
                })
                ->leftJoin('translation', function($join){
                    $join->on('translation.language_id','=','language.id');
                    $join->on('translation.product_id','=','products.id');
                })
                ->first();
                if (Wishlist::checkWishlist($product[$i]->id, $user->id)) {
                    $product[$i]->wishlist = 1;
                }
                else {
                    $product[$i]->wishlist = 0;
                }
            }
        }
        else {
            for($i = 0; $i < count($product); $i++){
                $product[$i] = Product::select('products.*', 'translation.name', 'translation.description')
                ->where('products.id', $product[$i]->id)
                ->leftJoin('language', function($join){
                    $join->where('language.code','=', App::getLocale());
                })
                ->leftJoin('translation', function($join){
                    $join->on('translation.language_id','=','language.id');
                    $join->on('translation.product_id','=','products.id');
                })
                ->first();
                $product[$i]->wishlist = 0;
            }
        }
        return $product;
    }

    public function getOne(Request $request, $id)
    {
        App::setLocale($request->header("X-Lang-Key"));
        $product = Product::select('products.*', 'translation.description')
        ->where('products.id', $id)
        ->leftJoin('language', function($join){
            $join->where('language.code','=', App::getLocale());
        })
        ->leftJoin('translation', function($join){
            $join->on('translation.language_id','=','language.id');
            $join->on('translation.product_id','=','products.id');
        })
        ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'data' => 'Sorry, product with id ' . $id . ' cannot be found'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
