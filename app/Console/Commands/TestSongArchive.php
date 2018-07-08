<?php

namespace App\Console\Commands;

use App\Exceptions\UploadParserException;
use App\UploadParser;
use Illuminate\Console\Command;

class TestSongArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beatsaver:test-song {file : path to a song zip file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse the song archive and present the output';

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
        if (!$tfile = realpath($this->argument('file'))) {
            $this->error('file does not exit!');
            return;
        }

        try {
            $parser = new UploadParser($tfile);
            print_r($parser->getSongData(true));
        } catch (UploadParserException $e) {
            $this->error($e->getMessage());
        }
    }
}
