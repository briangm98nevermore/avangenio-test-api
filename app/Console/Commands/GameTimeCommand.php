<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GameTimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:time-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para iniciar el tiempo del game';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = DB::table('toro_vaca_games')->insertGetId(
            ['nombre'=>'NameTestGame',
             'edad'=>26,
             'numeroPropuesto'=>fake()->randomNumber(4,true),
             ]
        );
    }
}
