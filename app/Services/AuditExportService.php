<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditExportService
{
    public static function checkExcelRequest(Request $request, Carbon $dt_from, Carbon $dt_to, string $activityType, string $typeValue)
    {
        if ($request->excelall == true) {
            $activities = Activity::where($activityType, $typeValue)->orderBy('id', 'desc')->get();
            self::exportActivities($activities);
        }
        if ($request->excel == true & $request->filled('start_date')) {
            $activities = Activity::whereBetween('activity_log.created_at', [$dt_from, $dt_to])
                ->where($activityType, $typeValue)->orderBy('id', 'desc')->get();
            self::exportActivities($activities);
        }
        if ($request->excel == true) {
            $activities = Activity::where($activityType, $typeValue)->orderBy('id', 'desc')->paginate(10);
            self::exportActivities($activities);
        }

        if ($request->filled('start_date')) {

            $activities = Activity::whereBetween('activity_log.created_at', [$dt_from, $dt_to])
                ->where($activityType, $typeValue)->orderBy('id', 'desc')->paginate(10);
        }
        else {
            $activities = Activity::where($activityType, $typeValue)->orderBy('id', 'desc')->paginate(10);
        }

        return $activities;
    }

    // Get the export activity
    public static function exportActivities($activities)
    {
        $view = 'audit.list-excel';

        return \Excel::create("export", function ($excel) use ($activities, $view) {

            $excel->sheet("export", function ($sheet) use ($activities, $view) {
                $sheet->loadView("{$view}", compact('activities'))
                    ->setOrientation('landscape');
            });

        })->export('xlsx');


    }
}