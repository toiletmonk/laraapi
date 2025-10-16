<?php

namespace App\Services;
use App\Jobs\Upload;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function upload($file)
    {
        Upload::dispatch($file);
    }

    public function delete($file): bool
    {
        if (Storage::disk('public')->exists($file->filepath)) {
            Storage::disk('public')->delete($file->filepath);
        }

        return $file->delete();
    }
}
