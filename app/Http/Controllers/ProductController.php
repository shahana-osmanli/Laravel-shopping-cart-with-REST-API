<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use DB;

class ProductController extends Controller
{
    public function getAll()
    {
        return Product::get()->all();
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
