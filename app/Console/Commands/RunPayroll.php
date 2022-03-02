<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\PayrollTrait;

class RunPayroll extends Command
{
    use PayrollTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $month=0;
    public $year=0;
    public $company_id=0;
    protected $signature = 'payroll:run {month} {company_id} {creator_id} {thread_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payroll run';

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
        
        $data=(object) $this->arguments();
    
        $this->commandrunPayroll($data);

    }


}
