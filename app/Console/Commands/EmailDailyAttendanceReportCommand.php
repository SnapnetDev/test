<?php

namespace App\Console\Commands;

use App\AttendanceReport;
use App\Company;
use App\Mail\SendAttachMail;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailDailyAttendanceReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:report';

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
        $daily_attendance_report=Setting::where('name','daily_attendance_report')->first();
        if ($daily_attendance_report && $daily_attendance_report->value=='1'){
            $date = Carbon::today();
            $users = User::where('company_id', 8)->get();
            $attendances = AttendanceReport::whereIn('user_id', $users->pluck('id')->toArray())->whereDate('date', $date->format('Y-m-d'))->with('user')->get();
            $earlys = $attendances->where('status', 'early')->count();
            $lates = $attendances->where('status', 'late')->count();
            $absentees = $attendances->where('status', 'absent')->count();
            $presents = $attendances->whereIn('status', ['early', 'late'])->count();
            $view = 'attendance.excel.exceldailyAttendanceReport';

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView($view, compact('attendances', 'lates', 'earlys', 'date', 'absentees'))->setPaper('a4', 'landscape');

            $from='info@snapnet.com.ng';
            $subject='Daily Attendance Report for '.$date->format('d M, Y');
            $data=['mail'=>'Kindly download the report'];
            $mail_body_view='emails.plain_body';
            $name=$date->format('d M, Y').' report';

            Mail::to('timothy@snapnet.com.ng')->send(new SendAttachMail($from,$subject,$data,$mail_body_view,$pdf,$name));
            $this->info('Sent');
        }
    }

}
