<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;

class AuthController extends Controller
{
    
    
    public function register(Request $request)
    {
        //you can write Validator here
        $user = User::create([
             'name'     => $request->name,
             'email'    => $request->email,
             'password' => $request->password,
         ]);// hash true olması burda password fieldini ozu encyrpt edir, bazaya baxsan goreceysen ozu sifreleyib atir bazaya
        //if you want to sign in after register
        $token = auth()->login($user);//burda qeyd oldugdan sonra hemin melumatlarla token yaradir tokeni qaytaririq responsla 
        //sonrada yazacagimiz funksyalarda ist edeceksen, meselen user update hissesinde
        //hansi user oldugunu tutmaq meqsedi ile
        return response()->json([
            'success'    => true,
            'data'       => 'Successfull register',
            'token'      => $token,
        ]);
    }

    public function update(Request $request)
    {
        $token = $request->token;
        $user = auth()->user($token); 
        if ($user) {
            $user = User::update([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ]);
            return response()->json([
                'success'    => true,
                'data'       => 'Successfull update',
            ]);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (! $token = auth()->attempt($credentials)) {//hemcininde burda, sen decyrpt etmeden ozu edib check edir
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

    //buda token invalidate edir
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
