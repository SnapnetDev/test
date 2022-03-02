<?php


namespace App\Traits;


use Carbon\Carbon;

trait General
{
    public $constants = ['%first_name%','%middle_name%','%last_name%','%diff_dob%','%diff_hiredate%', '%dob%', '%hiredate%', '%day%', '%month%', '%year%'];


    public function resolveDynamicValues($user, $message): string
    {

        foreach ($this->constants as $constant) {
            $resolved_value = $this->resolveValue($user, $constant);
            $message = str_replace($constant, $resolved_value, $message);
        }
        return $message;
    }

    public function resolveValue($user, $constant): string
    {

        $user = collect($user)->toArray();
        $constantKey = str_replace('%', '', $constant);
        if (in_array($constantKey, ['diff_dob', 'diff_hiredate'])) {
            $nf = new \NumberFormatter("en-US", \NumberFormatter::ORDINAL);
          
            return $nf->format(Carbon::parse($user[str_replace('diff_','',$constantKey)])->diffInYears(Carbon::now()));
        }
        if (in_array($constantKey, ['day', 'month', 'year'])) {
            $precise = $constantKey[0] == 'y' ? 'Y' : $constantKey[0];
            return date($precise);
        }
        return isset($user[$constantKey]) ? $user[$constantKey] : $constantKey;

    }

    public function getMonth($date, $months_to_add = 0, $d = '')
    {
//        check if its actual date if actual , just pass the date direct
        if (strlen($months_to_add) > 1) {
            return Carbon::parse($date)->format('Y-F-d');
        }
        return Carbon::parse($date)->addMonth($months_to_add)->format('Y-F' . $d);
    }


    public function getHumanReadableProbationStatus($id)
    {
        $date = '';
        if ($this->probation_period == 0):
            $date = $realdate = $this->getMonth($this->hiredate, $this->company->probationpolicy->probation_period);

        else:
            $date = $realdate = $this->getMonth($this->hiredate, $this->company->probationpolicy->probation_period);
        endif;
        if (Carbon::parse($date)->isPast()) {
            return "Over-Due For Probation: $realdate";
        }
        if (Carbon::parse($date)->isToday()) {
            return "Due For Probation: $realdate";
        }
        return "On-Probation: $realdate";
    }

    public function getProbationListHtmlTable($users, $probationPolicies)
    {
        return view('settings.notificationsettings.emailprobationlist', compact('users', 'probationPolicies'));
    }

    public function autoChangeStatus($user, $probation_policies)


{        if ($probation_policies->automatic_status_change == 'yes') {
$probation_period = $user->probation_period == 0 ? $probation_policies->probation_period : $user->probation_period;
            if ($this->checkIfMonthReached($this->getMonth($user->hiredate, $probation_period, '-d'))) {

                \App\User::where('id', $user->id)->update(['status' => 1]);
            }
        }
    }

    public function checkIfMonthReached($month)
    {
        return Carbon::parse($month)->isBirthday();
    }


    public function allRoles()
    {
        if (strlen(request()->q) >= 2) {
            return $roles = \App\Role::selectRaw('id, name as text')->where('name', 'LIKE', "%" . request()->q . "%")->get();
        }
        return [['id' => '', 'text' => '-Select Role-']];
    }

    public function allGroups()
    {
        if (strlen(request()->q) >= 2) {
            return $roles = \App\UserGroup::selectRaw('id, name as text')->where('name', 'LIKE', "%" . request()->q . "%")->get();
        }

        return [['id' => '', 'text' => '-Select Group-']];

    }

    private function exportToExcel($view,$data_view){

        return     \Excel::create("$view", function($excel) use ($data_view,$view) {

            $excel->sheet($view, function($sheet) use ($data_view,$view) {
                $sheet->loadView($view,$data_view)
                    ->setOrientation('landscape');
            });

        })->export('xlsx');
//            return [['id'=>'','text'=>'-Select Group-']];

    }
}



