<?php

namespace App\Jobs;

use App\Models\Workstation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Imported CSV file.
     *
     * @var string
     */
    protected $path;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ( ! ini_get('auto_detect_line_endings')) {
            ini_set('auto_detect_line_endings', '1');
        }

        $reader = Reader::createFromString(Storage::get($this->path));
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords(['Computer', 'Serial Number']);

        $recordsArray = collect($records)
            ->map(function ($item, $key) {
                return [
                    'name'       => $item['Computer'],
                    'serial'     => $item['Serial Number'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->toArray();


        if (Workstation::insert($recordsArray)) {
            Storage::delete($this->path);
        }
    }
}
