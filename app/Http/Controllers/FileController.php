<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
    }
}
