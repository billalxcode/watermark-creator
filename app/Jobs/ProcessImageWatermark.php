<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProcessImageWatermark implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
           
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $images = \App\Models\Image::where("status", "processing")->get();
        
        foreach ($images as $imagedata) {
            $fileexists = Storage::disk("public")->exists($imagedata->path);
            if ($fileexists) {
                $filepath = storage_path("app/public/" . $imagedata->path);
                $filepathname = File::name($filepath);
                $extension = File::extension($filepath);
                $result_storage_path = storage_path('app/public/images/'.$filepathname.'_thumb' . '.' . $extension);
                
                $image = \Intervention\Image\Facades\Image::make($filepath);
                $watermark = \Intervention\Image\Facades\Image::make(storage_path("logo.png"));
                $original_width = $image->width();
                $original_height = $image->height();
                // resize watermark
                $watermark_width = round($original_width / 25);
                $watermark_height = $watermark->width() / 8;

                $watermark->resize($watermark_width, $watermark_height, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image->resize($original_width/2, $original_height/2)
                    ->insert($watermark, "top-left")
                    ->save($result_storage_path);
                $imagedata['dest'] = Storage::url("images/" . $filepathname.'_thumb' . '.' . $extension);
                $imagedata['status'] = 'success';
                $imagedata->save();
            }
        }
    }
}
