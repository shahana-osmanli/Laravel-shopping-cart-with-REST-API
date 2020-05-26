<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Storage;
use App\File;
use App\Product;

class FileController extends Controller
{
    public function fileUpload(Request $request, $id)
    {
        $user = auth()->user();
        $product = Product::where('user_id', $user->id)->findOrFail($id);
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('/uploads'), $fileName);
                $fileUrl = 'public/uploads/' . $fileName;
                File::create([
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

    public function Watermark(Request $request)
    {
        /*
        $fileName = $request->file('file')->getClientOriginalName();
        $photo = Image::make($request->file('file')->getRealPath())
                        ->resize(300, 400)
                        ->insert(public_path('/uploads'), 'watermark.png')
                        ->save();
        $photo->move(public_path('/uploads/edited'), $fileName);
        return 'ok';
        */

        if ($request->hasFile('file')) {
            //get filename with extension
            $filenamewithextension = $request->file('file')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('file')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;

            //Upload File
            $request->file('file')->storeAs('public/uploads', $filenametostore);
            $request->file('profile_image')->storeAs('public/uploads/edited', $filenametostore);

            //Resize image here
            $editedpath = public_path('storage/public/uploads/edited/' . $filenametostore);
            $img = Image::make($editedpath)->resize(300, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($editedpath);

            return 'ok';
        }
    }
}