<?php


namespace App\Repositories;


use App\Notifications\UserDateNotification;
use App\Traits\General;


class AnniversaryRepository
{

    use General;
    var $reminder="";
    public function getNotificationDetails(): object
    {

        return (\App\NotificationType::pluck('name', 'id'));
    }

    public function sendReminders($users): string
    {
        $this->notifyUserOpenedQuery();
//        return '';

        $notificationDetails = $this->getNotificationDetails();

        foreach ($users as $user) {
          
            if (!is_null($user->dob) && $this->checkAnniversoryDate($user->dob,1, $user->company_id) && $this->checkActive(1, $user->company_id)) {
                $this->greetUser($user, 1, $notificationDetails);
                 echo $user->id;
                //$this->info('sending dob');
            }
            if (!is_null($user->hiredate) && $this->checkAnniversoryDate($user->hiredate,2, $user->company_id) && $this->checkActive(2, $user->company_id)) {
                $this->greetUser($user, 2, $notificationDetails);
                //$this->info('sending hire');
            }
            if ($this->activePublicHoliday($user->company_id)) {
                foreach ($this->getPublicHolidays($user->company_id) as $public_holiday_day) {
                    if ($public_holiday_day->company_id == $user->company_id || $public_holiday_day->company_id == 'NULL') {
                        $notificationDetails = [$public_holiday_day->id => $public_holiday_day->title];
                        $company_ids = $public_holiday_day->holidaymessagesconsole->where('company_id', $user->company_id)->pluck('company_id')->toArray();
                        $holiday_message = in_array($user->company_id, $company_ids) ? $public_holiday_day->holidaymessagesconsole->where('company_id', $user->company_id)->pluck('message')[0] : '';
                        $this->greetUser($user, $public_holiday_day->id, $notificationDetails, $holiday_message);
                    }
                }
            }
        }
        return 'All notification Completed';
    }


    public function getPublicHolidays($company_id)
    {

        $publicHoliday = \App\Holiday::select('title', 'date', 'company_id', 'id')->with('holidaymessagesconsole:holiday_id,company_id,message')->where('status', 1)->get()->reject(function ($value)  use ($company_id) {
            return !$this->checkAnniversoryDate($value->date,$company_id,3);
        });

        return $publicHoliday;

    }

    public function checkAnniversoryDate(string $date,$company_id,$id): bool
    {
        $val=false;
        if ($today= \Carbon\Carbon::parse($date)->isBirthday()){
            $val= true;
        }
        return $val;
        
        
        $backDate=$this->getReminderPolicies($company_id,$id);
        $backDate=explode(',',$backDate->reminder_before);

        foreach($backDate as $backDate){
            $backDate=isset($backDate) ? 0 : $backDate;
            $today= \Carbon\Carbon::parse($date)->subDay($backDate)->isBirthday();
            $this->reminder=$today ? '' : 'This is a Reminder For';
        }
        return false;
    }


    public function greetUser($user, $notification_id, $notificationDetails, $notification_message = null)
    {

        $notification_message = $this->configuredMessage($user, $notification_id, $notification_message);
        $user->notify(new UserDateNotification($notification_id, $notification_message, 'userprofile/notifications', $this->reminder." ".$notificationDetails[$notification_id]));
        echo 'notification sent';
    }

    private function configuredMessage($user, $notification_id, $notification_message = null): string
    {
        if (is_null($notification_message)) {
            $notification_message = \App\NotificationMessage::where('company_id', $user->company_id)->where('notification_type_id', $notification_id)->value('message');
        }
        return $this->resolveDynamicValues($user, $notification_message);

    }


    private function activePublicHoliday($company_id)
    {
        $status = \App\NotificationActiveStatus::where('notification_type_id', 3)->where('company_id', $company_id)->value('status');
        if ($status == 'active') {
            return true;
        }
        return false;
    }

    private function checkActive(int $id, $company_id)
    {
        $checkDisable = \App\NotificationActiveStatus::where(['company_id' => $company_id, 'notification_type_id' => $id])->exists();

        if ($checkDisable) {
            return true;
        }
        return false;
    }

    private function getReminderPolicies($company_id,$notification_type_id){
       return \App\NotificationActiveStatus::select('reminder_before')->first();
    }


    public function notifyUserOpenedQuery()
    {
        $openedQueries = \App\QueryThread::where('parent_id', 0)->where('status', 'open')->get();
        $parent_ids = [];
        foreach ($openedQueries as $query) {
            if ($this->checkActive(4, $query->querieduser->company_id)) {
                $query->querieduser->notify(new UserDateNotification('', "Dear {$query->querieduser->name} <br> You have opened queries awaiting your <b>prompt</b> response", 'query/allqueries?query_id=' . $query->id, 'Opened  ' . $query->querytype->title . " Query issued from " . $query->createdby->name));
                $this->sendEscalationEmail($query);
                $parent_ids[] = $query->id;
            }
        }
//        dd($parent_ids);
        $this->updateNumRetries($parent_ids);
    }

    private function updateNumRetries(array $ids)
    {

        \App\QueryThread::whereIn('id', $ids)->update(['num_of_reminders' => \DB::raw("num_of_reminders+1")]);
    }

    public function notifyAdminProbation()
    {
        $companies = $this->getCompanies();
        foreach ($companies as $id => $company) {
            $probationPolicies = $this->getProbationPolicy($id);

            $users = \App\User::where('status', 0)->where('company_id', $id)->select('id', 'company_id', 'hiredate', 'name', 'role_id','probation_period')->get();

            if (count($users) > 0) {

                if (@$probationPolicies->probation_reminder == 'yes') {

                    $employeeOnProbation = $this->getProbationListHtmlTable($users, $probationPolicies);;

                    $this->notifySelectedRoleOfProbation($probationPolicies, $employeeOnProbation, $id, $users);
                }
            }
        }
    }

    public function getProbationPolicy($company_id)
    {

        return \App\ProbationPolicy::where('company_id', $company_id)->where('probation_reminder', 'yes')->first();
    }

    private function getCompanies()
    {
        return \App\Company::selectRaw('id, id as id2')->pluck('id', 'id2');
    }

    private function notifySelectedRoleOfProbation($probationPolicies, string $employeeOnProbation, $company_id, $users)
    {

        $roles = explode(',', @$probationPolicies->notify_roles);

        foreach ($roles as $role_id) {
            $users_with_role = $users->where('role_id', $role_id);
            foreach ($users_with_role as $user) {
                $user->notify(new UserDateNotification('', $employeeOnProbation, 'user?status=0', 'Employee Due for Probation Reminder'));
            }
        }

    }

    private function sendEscalationEmail($query)
    {
        $query_escalation_policies = $this->getQueryEscalationPolicies($query->querieduser->company_id, $query->num_of_reminders);
        if (!is_null($query_escalation_policies)) {
            if ($query_escalation_policies->group_id != 0) {
                $this->escalateToAllUserInGroup($query, $query_escalation_policies->group_id);
            }
            if ($query_escalation_policies->role_id != 0) {
                $this->escalateToAllUserInRole($query, $query_escalation_policies->role_id);
            }
        }
    }

    private function getQueryEscalationPolicies($company_id, $num_of_reminders)
    {
        $escalation_policies = \App\QueryEscalationFlow::where('company_id', $company_id);
        if ($num_of_reminders > 3) {
            $escalation_policies = $escalation_policies->where('num_of_reminder', 4);
        } else {
            $escalation_policies = $escalation_policies->where('num_of_reminder', $num_of_reminders);
        }
        return $escalation_policies->first();
    }

    private function escalateToAllUserInGroup($query, $group_id)
    {
        $users_in_group = \App\UserGroup::where('id', $group_id)->select('id')->with('users:company_id')->first();
        $users=$users_in_group->users->reject(function ($value) use ($query){
            return $value->company_id!=$query->querieduser->company_id;
        });

        $this->notifyAllEscalationUsers($query, $users);
    }

    private function notifyAllEscalationUsers($query, $users)
    {

        $message = "<b>{$query->querieduser->name}</b> is yet to response to <br> <b>Query Type :</b> {$query->querytype->title} <br> <b>Query Issuer :</b> {$query->createdby->name} <br> <b>Query Content :</b> {$query->content}";
       foreach($users as $user){
           $user->notify(new UserDateNotification('', $message, 'query/allqueries?query_id=' . $query->id, 'Escalation of Un-answered Query ' . $query->querytype->title,$user->name));
       }
           }

    private function escalateToAllUserInRole($query, $role_id)
    {
        $users_in_role = \App\User::where('role_id', $role_id)->where('company_id',$query->querieduser->company_id)->select('id','company_id')->get();
        $this->notifyAllEscalationUsers($query, $users_in_role);
    }


}
