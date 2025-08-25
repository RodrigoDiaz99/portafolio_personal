<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request){
        $path = Storage::disk('public')->put('posts/images', $request->file('upload'));
        
        
        return [
            'url' => Storage::disk('public')->url($path)
        ];
    }

    
}
