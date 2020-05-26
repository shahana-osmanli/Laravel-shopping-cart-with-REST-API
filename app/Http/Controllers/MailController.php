<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendMail;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        $details = [
            'from' => $request->email,
            'message' => $request->message
        ];

        \Mail::to('sahanasalmanova@gmail.com')->send(new SendMail($details));
    }
}