<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validateUser = Validator::make($request->all(), 
            [
                'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);

        if($validateUser->fails()){
            return $this->sendError($validateUser->errors(), 400);
        }

        try {
            $imagePath = $request->file('image')->store('image', 'public');

            $image = Image::create([
                'image_path' => $imagePath
            ]);

            return $this->sendSuccess($image, 'Success to upload image');

    
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 500);

        }



    }
}
