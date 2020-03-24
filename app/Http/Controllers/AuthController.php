<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Validator;

class AuthController extends Controller
{    
    public function register(Request $request)
    {
        //you can write Validator here
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password'=> 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validation->errors()->all(),
            ]);
        }
        $request->type = ($request->type == null || $request->type != 'vendor') ? 'user' : 'vendor';
        $user = User::create([
             'name'     => $request->name,
             'email'    => $request->email,
             'password' => $request->password,
             'type'     => $request->type,
         ]);
        $token = auth()->login($user);
        return response()->json([
            'success'    => true,
            'data'       => 'Successfull register',
            'token'      => $token,
        ]);
    }

    public function update(Request $request)
    {   
        $validation = Validator::make($request->all(),[
            'name' => 'required|max:9',
            'email' => 'required|email',
            'password'=> 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validation->errors()->all(),
            ]);
        }
        $user = auth()->user(); 
        if ($user) {
            $user = User::where('id', $user->id)->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'success'    => true,
                'data'       => 'Successfull update',
            ]);
        }
    }

    public function login(Request $request)
    {   
        $credentials = $request->only('email', 'password', 'type');
        $validation = Validator::make($credentials,[
            'email' => 'required|email',
            'password'=> 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validation->errors()->all(),
            ]);
        }       
        if (! $user = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'data'  => 'Wrong Crendentials'
            ]);
        }
        return response()->json([
            'success'    => true,
            'data'       => 'Successfull login',
            'token'      => $user,
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getAuthUser(Request $request)
    {        
        $user = auth()->user(); 
 
        return response()->json(['user' => $user]);
    }
}
