<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Image as InterventionImage;
use Illuminate\Support\Str;
use File;

class ImageController extends Controller
{
    public $fileConfig;
    public function __construct()
    {
        $this->fileConfig = config("general_config.upload.image");

    }
    public function uploadImage(Request $request)
    {

        try{

              $filename  = Str::random(32) . '.' . $request->file->getClientOriginalExtension();
                $file_make = InterventionImage::make($request->file->getRealPath());
                


                /*Original Image*/

                $file_make->save($this->fileConfig["original"]["path"] . '/' . $filename);

                /*Medium Image*/

                $file_make->resize($this->fileConfig["medium"]["size"][0], $this->fileConfig["medium"]["size"][1], function ($constraint) {
                    $constraint->aspectRatio();
                })->save($this->fileConfig["medium"]["path"] . '/' . $filename);

                /** Thumb Image */

                $file_make->resize($this->fileConfig["thumb"]["size"][0], $this->fileConfig["thumb"]["size"][1], function ($constraint) {
                    $constraint->aspectRatio();
                })->save($this->fileConfig["thumb"]["path"] . '/' . $filename);



                $result = Image::create(['name' => $filename]);
                if($result){
                    return response()->json(["status" => 'success', 'message' => 'file '.$filename." is Uploaded Successfully", "fileID" => $result->id]);
                }
                 return response()->json(["status" => 'error', 'message' => 'file '.$filename." failed to save to database"], 400);

                

        }catch(\Exception $e){
          return response()->json(["status" => 'error', 'message' => $e->getMessage()]);

        }

      



    }


      public function all(Request $request)
    {

         try{
             $images = Image::where("status", true)->orderBy("sort", "asc")->get();
             return response()->json(["images" => $images]);
         }catch(\Exception $e){
           return response(['status' => 'error', 'message' => $e->getMessage()]);
         }
  

    }


    public function sort(Request $request)
    {
         try{
          Image::setNewOrder($request->recordsArray);
           return response(['status' => 'success', 'message' => 'Images sorted successfully']);
         }catch(\Exception $e){
           return response(['status' => 'error', 'message' => $e->getMessage()]);
         }
     

    }


     public function delete(Request $request)
    {
     $image = Image::findOrFail($request->fileID);
     $filename = $image->name;
     try{
      File::delete([$this->fileConfig["original"]["path"] . '/' . $filename, $this->fileConfig["medium"]["path"] . '/' . $filename, $this->fileConfig["thumb"]["path"] . '/' . $filename]);

      
        $image->delete();

        return response()->json(["status" => "success", "message" => "Image Deleted Successfully!"]);
      

     }catch(\Exception $e){

        return response()->json(["status" => "error", "message" => $e->getMessage()]);

     }

    }
}
