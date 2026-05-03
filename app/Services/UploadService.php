<?php

namespace App\Services;

class UploadService
{
    public function uploadImage($file)
    {
        return $file->store('designs', 'public');
    }
}
