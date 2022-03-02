<?php

namespace App\Jobs;

use App\Attendance;
use App\Traits\Attendance as AttendanceTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessSingleAttendanceJob /*implements ShouldQueue*/
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AttendanceTrait;
    public $attendance;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attendance)
    {
        $this->attendance=$attendance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $attendance=Attendance::find($this->attendance);
        $this->attendanceReportForAttendance($attendance);
    }
}
