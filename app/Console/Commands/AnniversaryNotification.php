<?php

namespace App\Console\Commands;

use App\Repositories\AnniversaryRepository;
use Illuminate\Console\Command;
use App\Interfaces\AnniversaryInterface;

class AnniversaryNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminder to all users based on configured settings';
    public $anivasory,$users;

    /**
     * Create a new command instance.
     *
     * @param AnniversaryInterface $anivasory
     */
    public function __construct(AnniversaryRepository $anivasory)
    {
        parent::__construct();
        $this->anivasory=$anivasory;
        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->info('Sending Reminders... ... ...');
        $this->users=\App\User::select('dob','id','first_name','middle_name','last_name','company_id','hiredate','email')->get();
        $this->info($this->anivasory->sendReminders($this->users));
    }
}
