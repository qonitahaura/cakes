<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function uploadImage(UploadedFile $file): string
    {
        return $file->store('designs', 'public');
    }

    /**
     * Store product image and return public URL path (e.g. /storage/products/..).
     */
    public function uploadProductImage(UploadedFile $file): string
    {
        $path = $file->store('products', 'public');

        return Storage::disk('public')->url($path);
    }
}
