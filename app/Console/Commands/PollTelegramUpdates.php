<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class PollTelegramUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtener actualizaciones desde Telegram';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Procesar actualizaciones y comandos
            $updates = Telegram::commandsHandler(true);

            // Log para verificar actualizaciones
            if (!empty($updates)) {
                $this->info('Actualizaciones recibidas:');
                $this->info(json_encode($updates, JSON_PRETTY_PRINT));
            } else {
                $this->info('Sin actualizaciones.');
            }
        } catch (\Exception $e) {
            $this->error('Error durante polling: ' . $e->getMessage());
        }
    }
}
