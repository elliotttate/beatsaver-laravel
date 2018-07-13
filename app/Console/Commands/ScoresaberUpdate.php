<?php

namespace App\Console\Commands;

use App\Models\SongDetail;
use Illuminate\Console\Command;

class ScoresaberUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beatsaver:scoresaber-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update song played count info from scoresaber.com website';

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
        if (config('beatsaver.scoreSaber.enabled') && config('beatsaver.scoreSaber.authKey')) {
            SongDetail::where('download_count', '>=', config('beatsaver.scoreSaber.syncMinDownloadCount'))->chunk(1000, function ($details) {
                foreach ($details as $detail) {
                    $url = "https://scoresaber.com/api.php?function=getPlays&param1=" . strtoupper($detail->hash_md5) . "&key=" . config('beatsaver.scoreSaber.authKey');
                    $playCount = file_get_contents($url) ?: 0;
                    $detail->play_count = is_numeric($playCount) ? $playCount : 0;
                    $detail->save();
                }
            });
        }
    }
}
