<?php


namespace App\Repositories;


class NotificationRepository
{


    public function processPost()
    {
        switch (request()->type){
            case 'toggleNotification':
                return $this->toggleNotification();
            case 'saveMessage':
                return $this->saveMessage();
            default:
                return response()->json(['status'=>'error','message'=>'Route Not Found'],404);
        }
    }

    public function processGet($route)
    {
        switch ($route){
            case 'index':
                return $this->home();
        }
    }

    private function saveNotification()
    {

    }

    private function home()
    {

        $notification_types=\App\NotificationType::select('name','id')->with(['notificationstatus','notificationmessage'])->get();
        $holidays=\App\Holiday::where('company_id',companyId())->orWhereNull('company_id')->get();
        $reminders=["30"=>"One Month Before" ,"7"=>"One Week Before" ,"1"=>"One Day Before" ,"3"=>"Three Day Before" ,"0"=>"On Day"];
        return view('settings.notificationsettings.index',compact('notification_types','holidays','reminders'));
    }

    private function toggleNotification()
    {
         $i=0;
        foreach(request()->status as $status){
           $data=['notification_type_id'=>request()->notification_type_id[$i],'company_id'=>companyId(),'status'=>$status,'a_id'=>request()->user()->id,'reminder_before'=>request()->reminder_before[$i]];
            \App\NotificationActiveStatus::updateOrCreate(['company_id'=>companyId(),'notification_type_id'=>request()->notification_type_id[$i]],$data);
            $i++;
        }

           return response()->json(['status'=>'success','message'=>'Operation Successfully Completed']);
    }

    private function saveMessage()
    {
        $model_name="\App\\".request()->model_name;
        $column_name=$this->resolveColumnName();
        $data=array_merge(['company_id'=>companyId(),$column_name=>request()->model_id,'a_id'=>request()->user()->id],request()->except('type','_token','model_id'));
        $saveMessage=$model_name::updateOrCreate(['company_id'=>companyId(),$column_name=>request()->model_id],$data);
        return response()->json(['status'=>'success','message'=>'Operation Successfully Completed']);
    }

    private function resolveColumnName()
    {
        if(request()->model_name=='HolidayMessage'){
            return 'holiday_id';
        }
         return 'notification_type_id';
    }
}
