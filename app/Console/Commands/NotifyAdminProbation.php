<?php

namespace App\Console\Commands;

use App\Repositories\AnniversaryRepository;
use Illuminate\Console\Command;

class NotifyAdminProbation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:probationreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Monthly Probation reminder';

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
     * @param AnniversaryRepository $anivasory
     * @return mixed
     */
    public function handle(AnniversaryRepository $anivasory)
    {
         $anivasory->notifyAdminProbation();
         $this->info('Done');

    }

}
