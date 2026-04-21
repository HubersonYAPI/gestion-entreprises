<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Declaration;
use Carbon\Carbon;

class CheckPaiementExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:check-paiement-expiration';
    protected $signature = 'paiement:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire les paiements après 72h';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Declaration::where('statut', 'approuve')
            ->whereNotNull('date_limite_paiement')
            ->where('date_limite_paiement', '<', Carbon::now())
            ->update(['statut' => 'rejete']);

        $this->info('Paiements expirés mis à jour');
    }
}
