<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Upload implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $file)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $path = $this->file->store('files', 'public');

        $fileModel = new File([
            'filename'=>$this->file->getClientOriginalName(),
            'filetype'=>$this->file->getClientOriginalExtension(),
            'filepath'=>$path,
        ]);

        $fileModel->save();
    }
}
