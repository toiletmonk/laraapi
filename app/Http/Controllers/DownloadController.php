<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download(File $file)
    {
        return Storage::disk('public')->download($file->filepath);
    }
}
