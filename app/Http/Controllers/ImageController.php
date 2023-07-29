<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageCheckRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Models\Image;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Jobs\ProcessImageWatermark;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function fetch() {
        $data = Image::all();
        $data = collect($data)->map(function ($val) {
            $val['url'] = Storage::url($val['path']);
            return $val;
        });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function check(ImageCheckRequest $request) {
        $datarequest = $request->validated();
        $data = Image::find($datarequest['image_id']);
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function upload(ImageUploadRequest $request) {
        // dd($request->all());
        $request->validated();

        $path = Storage::disk("public")->exists("images/");
        if (!$path) mkdir(storage_path("app/public/images"), 7777, true);
        
        $file = $request->file("image");
        $filename = time() . "_" . $file->getClientOriginalName();
        $full_filename = "images/". $filename;
        $output = Storage::disk("public")->put($full_filename, File::get($file));
        $imagedata = Image::create([
            'path' => $full_filename,
            'status' => 'processing',
            'dest' => null,
            'type' => $request->post("type")
        ]);
        
        // dispatch(new ProcessImageWatermark);

        return response()->json([
            'status' => true,
            'data' => $imagedata
        ]);
    }
}
