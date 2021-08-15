<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ApiController extends Controller
{
    

    public function getImages(Request $request, $token){
        

        //simply use a sample token from the env file
        if($token !== env('API_TOKEN', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9')){
        return response()->json(["status" => 'error', 'statusCode' => 403, 'message' => 'Invalid Token'], 403);
        }
      
         $fileConfig = config('general_config.upload.image');

         //remap all image data for proper output
         $images = Image::where('status', true)->orderBy("sort", "asc")->get()->map(function($model) use($fileConfig){

           return [
             'id' => $model->id,
             'name' => $model->name,
             'large' => url($fileConfig['original']['path'].'/'.$model->name),
             'medium' => url($fileConfig['medium']['path'].'/'.$model->name),
             'thumb' => url($fileConfig['thumb']['path'].'/'.$model->name),
           ];
         });

         return response()->json(['status' => 'success', 'statusCode' => 200, 'data' => $images]);

    }
}
