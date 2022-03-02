<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Curl;
use Auth;
use Excel;
class UploadAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:attendance';

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
        $user=\App\User::find(1);
        $user->notify( new \App\Notifications\NewUserCreatedNotify($user));
        // Excel::load(public_path('log11.xlsx'), function ($reader) {

        //         foreach ($reader->toArray() as $key => $row) {
        //             $hour= intval(date('H',strtotime($row['datetime'])));

        //             if ($hour>12) {
        //                 $status=1;
        //             }else{
        //                 $status=0;
        //             }
        //             $this->info($status);
        //             if (isset($status)) {
        //               $response = Curl::to('https://snapnet.hcmatrix.com/deviceclockin')
        //             ->withData( array( 'type' => $status,'empnum'=>$row['uid'],'time'=>date('Y-m-d H:i:s',strtotime($row['datetime'] ))) )
        //             ->get();
        //             }

        //             $this->info(date('Y-m-d H:i:s',strtotime($row['datetime'] )));
        //             $this->info($response);


        //                  }
        //     });
         $this->info('sent test mail successful');
    }
}
