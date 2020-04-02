<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Image;
use App\File;
use App\Product;

class FileController extends Controller
{
    public function fileUpload(Request $request, $id)
    {
        $user = auth()->user();
        $product = Product::where('user_id', $user->id)->findOrFail($id);
        //$product = Product::find($id)->user()->get();
            if ($request->hasFile('file')) {
                $files = $request->file('file');
                foreach ($files as $file) {
                    $fileName = $file->getClientOriginalName();
                    $file->move(public_path('/uploads'), $fileName);
                    $fileUrl = 'public/uploads/'.$fileName;
                    $upload = File::create([
                        'product_id' => $product->id,
                        'url' => $fileUrl,
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Uploaded successfully',
            ]);
        //$img = Image::make('public/foo.jpg')->resize(320, 240)->insert('public/watermark.png');
    }

    public function Watermark(Request $request)
    {
        $fileName = $request->file('file')->getClientOriginalName(); 
        return $request->file('file');
        $photo = Image::make($request->file('file'))->resize(300, 400)->insert('/uploads/watermark.png')->save();
        $photo->move(public_path('/uploads'), $fileName);
        return 'ok';
    }
}
