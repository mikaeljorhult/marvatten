<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marvatten:import {path : Path to CSV import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import workstations form CSV';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = Storage::putFile('imports', new File($this->argument('path')));
        \App\Jobs\ImportCsv::dispatchNow($path);
        
        return 0;
    }
}
