<?php

namespace App\Console\Commands;

use App\PaceSalaryCategory;
use App\PaceSalaryComponent;
use Illuminate\Console\Command;

class AddPayrollDeductions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $company_id=0;
    protected $signature = 'payroll:add_deduction {company_id}';

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
     * @return mixed
     */
    public function handle()
    {
        
    }
}
