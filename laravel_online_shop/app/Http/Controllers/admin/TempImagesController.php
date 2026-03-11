<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;    
use Intervention\Image\Facades\Image;
use App\Models\TempImage;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $imageFile = $request->file('image');
        if (!empty($imageFile)) {
            $ext = $imageFile->getClientOriginalExtension();
            $newName = time() . "." . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName; 
            $tempImage->save();

            $imageFile->move(public_path('temp'), $newName);

            // Generate Thumbnail
            $sourcePath = public_path('temp/' . $newName);
            $thumbDir = public_path('temp/thumb');
            if (!is_dir($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }
            $destPath = $thumbDir . '/' . $newName;

            $image = Image::make($sourcePath);
            $image->fit(300, 275, function ($constraint) {
                $constraint->upsize();
            })->save($destPath); 

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'imagePath' => asset('temp/thumb/'. $newName),
                'message' => 'Image uploaded successfully',
            ]);
        }
    }
}
