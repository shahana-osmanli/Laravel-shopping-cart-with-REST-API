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
        else {
        $user = User::create([
             'name'     => $request->name,
             'email'    => $request->email,
             'password' => $request->password,
         ]);
        $token = auth()->login($user);
        return response()->json([
            'success'    => true,
            'data'       => 'Successfull register',
            'token'      => $token,
        ]);
        }
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
        else {
        //return response()->json(['data' => $request->all()]);
        $token = $request->token;
        $user = auth()->user($token); 
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
}

    public function login(Request $request)
    {   

        $credentials = $request->only('email', 'password');
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
        else {        
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'data'  => 'Wrong Crendentials'
            ]);
        }
        return response()->json([
            'success'    => true,
            'data'       => 'Successfull login',
            'token'      => $token,
        ]);
    }
}

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getAuthUser(Request $request)
    {        
        $token = $request->token;
        $user = auth()->user($token); 
 
        return response()->json(['user' => $user]);
    }
}
