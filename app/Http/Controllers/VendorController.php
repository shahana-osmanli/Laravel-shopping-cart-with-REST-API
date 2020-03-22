<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Product;

class VendorController extends Controller
{
    public function Show()
    {
        $user = auth()->user();
        $products = User::find($user->id)->products()->get();
        return $products;
    }
}
