<?php

namespace App\Console\Commands;

use App\Http\Controllers\Client\ProductController;
use Illuminate\Console\Command;
use App\Models\{CsvProductImport};
use Illuminate\Http\Request;

class ImportProductViaCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        error_log("done1");
    }
}
