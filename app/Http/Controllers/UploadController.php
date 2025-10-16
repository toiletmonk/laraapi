<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Services\UploadService;

class UploadController extends Controller
{
    protected UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function upload(UploadRequest $request)
    {
        $file = $request->file('file');

        $this->uploadService->upload($file);

        return response()->json(['message' => 'File successfully uploaded.'], 201);
    }
}
