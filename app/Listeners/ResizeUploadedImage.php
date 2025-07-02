<?php

namespace App\Listeners;

use UniSharp\LaravelFilemanager\Events\ImageWasUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Image;

class ResizeUploadedImage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ImageWasUploaded $event)
    {
        $path = $event->path();
        $image = Image::make($path);
        if($image->width() < 1100) {
            return;
        }
        // resize the image to a width of 1100 and constrain aspect ratio (auto height)
        $image->resize(1100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path);
    }
}
