<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateSearchCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beatsaver:update-search-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update search cache to speed up searches';

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
     * @return mixed
     */
    public function handle()
    {
        $this->top100();
    }

    protected function top100()
    {

    }
}
