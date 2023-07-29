<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class WatermarkCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:watermark-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Handle processing");
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
                
            }
        }
    }
}
