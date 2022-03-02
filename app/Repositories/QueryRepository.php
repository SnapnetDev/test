<?php


namespace App\Repositories;


use App\Filters\UserFilter;
use App\Notifications\UserDateNotification;
use App\Traits\General;
use Maatwebsite\Excel\Excel;

class QueryRepository
{
    use General;

    public function processGet($route)
    {
        switch ($route) {
            case 'settings':
                return $this->viewSettings();
            case 'allqueries':
                return $this->allQueries();
            case 'allEmployees':
                return $this->allEmployees();
            case 'queryThread':
                return $this->queryThread();
            case 'allRoles':
                return $this->allRoles();
            case 'allGroups':
                return $this->allGroups();

            default:
                return redirect('notfound');
        }
    }

    public function processPost()
    {
        try {
            switch (request()->type) {
                case 'saveQueryType':
                    return $this->saveQueryType();
                case 'replyOrIssueQuery':
                    return $this->replyOrIssueQuery();
                case 'saveQueryEscalationFlow':
                    return $this->saveQueryEscalationFlow();
                default:
                    return response()->json(['status' => 'error', 'message' => 'Route not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function permissionDenied()
    {
        if (!request()->user()->role->permissions->contains('constant', 'issue_query') && !request()->ajax()) {
            return redirect('/');
        }
        if (!request()->user()->role->permissions->contains('constant', 'issue_query') && request()->ajax()) {

            return response()->json(['status' => 'error', 'message' => 'Permission Denied']);
        }
        return false;

    }

    private function viewSettings()
    {
        $query_types = \App\Query::where('company_id', companyId())->get();
        $escalationRoutes = \App\QueryEscalationFlow::where('company_id', companyId())->with('role','group')->get();

        return view('settings.querysettings.index', compact('query_types','escalationRoutes'));
    }

    private function saveQueryType()
    {
        if ($this->permissionDenied()) {
            return $this->permissionDenied();
        }
        $data = array_merge(request()->except('type', '_token'), ['created_by' => request()->user()->id, 'company_id' => companyId()]);
        $saveQuery = \App\Query::updateOrCreate(['id' => request()->id], $data);
        return response()->json(['status' => 'success', 'message' => 'Operation Successfull']);

    }

    private function allQueries()
    {
        $query_statistics = $this->getQueryData('statistics');
        $all_queries = $this->getQueryData();
        if(request()->filled('excel')){
            return $this->exportToExcel('query.allquery', compact('query_statistics', 'all_queries'));
        }
        return view('query.allquery', compact('query_statistics', 'all_queries'));
    }

    public function getQueryData($type = 'data',$pagin=50)
    {

        $query_data = \App\QueryThread::where('parent_id', 0);
        if (request()->user()->role->manages == 'dr') {
            $query_data = $query_data->where('created_by', request()->user()->id);
        }

        if (request()->user()->role->manages == 'none' || !request()->user()->role->permissions->contains('constant', 'issue_query')) {
            $query_data = $query_data->where('queried_user_id', request()->user()->id);
        }
        if (request()->filled('status') && $type == 'data') {
            $query_data = $query_data->where('status', request()->status);
        }
        if (request()->filled('query_id') && $type == 'data') {
            $query_data = $query_data->where('id', request()->query_id);
        }
        if(request()->filled('q') && $type == 'data'){

            $query_data = $query_data->where(function($query){
                $query->where('action_taken', 'LIKE',"%".request()->q."%")
                ->orwhereHas('querytype',function($query){
                    $query->where('title', 'LIKE',"%".request()->q."%");
                });

            });
        }
        if (request()->filled('queried_user_id') && $type == 'data' || request()->filled('queried_user_id')) {

            $query_data = $query_data->where('queried_user_id', request()->queried_user_id);
        }
        if(request()->filled('excel')){
            return $query_data->get();
        }
        if ($type == 'statistics') {
            return $query_data->selectRaw('count(status) as status_count,status')->groupBy('status')->get();
        }

        return $query_data->orderBy('created_at', 'desc')->paginate($pagin);
    }

    private function allEmployees()
    {
        return UserFilter::apply(request());
    }

    private function queryThread()
    {
        $query_threads = \App\QueryThread::where('parent_id', request()->parent_id)->get();
        return view('query.ajax.querythread', compact('query_threads'));
    }

    private function replyOrIssueQuery()
    {


        if (request()->has('type')) {
            $user=\App\User::where('id',request()->queried_user_id)->first();
            request()->request->add(['content'=>$this->resolveDynamicValues($user,request()->content)]);
        }
        $data = request()->except('_token', 'type');
        $query_thread = \App\QueryThread::create($data);
        if (request()->has('type')) {
            request()->request->add(['parent_id' => $query_thread['id']]);
        }
//        issued has no parent_id
        $type = request()->has('type') ? 'Issued' : 'Replied';
        $this->notifyUser($type);
        return response()->json(['status' => 'success', 'message' => "Query $type Successfully"]);

    }

    public function notifyUser($type)
    {

        $get_query_parent_details = \App\QueryThread::where('id', request()->parent_id)->select('id', 'created_by', 'queried_user_id','action_taken','effective_date')->first();

        $this->notifyCreatedBy($get_query_parent_details, $type);
        $this->notifyQueriedUser($get_query_parent_details, $type);

    }

    private function notifyCreatedBy($get_query_parent_details, $type)
    {
        $message = $this->resolveDynamicValues($this->userDetail($get_query_parent_details), request()->content);
        if ($get_query_parent_details->created_by != request()->user()->id) {
            $get_query_parent_details->createdby->notify(new UserDateNotification('', request()->filled('content') ? $message : request()->user()->name . " Just closed {$get_query_parent_details->querytype->title} that was previously issued to {$get_query_parent_details->querieduser->name} .<br> Action Taken : {$get_query_parent_details->action_taken} <br> Effective Date : {$get_query_parent_details->effective_date}", 'query/allqueries?query_id=' . request()->parent_id, 'New ' . $get_query_parent_details->querytype->title . "Query $type from " . $get_query_parent_details->querieduser->name));
        }
    }

    private function notifyQueriedUser($get_query_parent_details, $type)
    {

        $message = $this->resolveDynamicValues($this->userDetail($get_query_parent_details), request()->content);
        if ($get_query_parent_details->queried_user_id != request()->user()->id) {
            $get_query_parent_details->querieduser->notify(new UserDateNotification('', request()->has('content') ? $message : request()->user()->name . " Just closed {$get_query_parent_details->querytype->title} that was previously issued to {$get_query_parent_details->querieduser->name}. <br> Action Taken : {$get_query_parent_details->action_taken} <br> Effective Date : {$get_query_parent_details->effective_date}", 'query/allqueries?query_id=' . request()->parent_id, 'New ' . $get_query_parent_details->querytype->title . " Query $type from " . $get_query_parent_details->createdby->name));
        }
    }

    private function userDetail($get_query_parent_details)
    {
        if (($get_query_parent_details->queried_user_id == request()->user()->id)) {
            return $get_query_parent_details->createdby;
        }
        return  $get_query_parent_details->querieduser;
    }

    private function saveQueryEscalationFlow()
    {
        if (!request()->user()->role->permissions->contains('constant', 'query_escalation_settings') && request()->ajax()) {

            return response()->json(['status' => 'error', 'message' => 'Permission Denied']);
        }
        foreach (request()->data as $data) {
            \App\QueryEscalationFlow::updateOrCreate(['company_id' => companyId(),'num_of_reminder'=>$data['num_of_reminder']], array_merge($data, ['company_id' => companyId(), 'created_by' => request()->user()->id]));
        }
        return response()->json(['status' => 'success', 'message' => 'Operation Successfull']);
    }

    public function closeQueryConditions(){
        $data=request()->all();
        $data=['status'=>'closed'];
        if(request()->disciplinary_action=='yes'){
            if(in_array(request()->select_action_taken,['others','warning'])){
                if(request()->select_action_taken=='warning'){
                    $data=array_merge($data,['action_taken'=>request()->select_action_taken]);
                }
                if(request()->select_action_taken=='other'){
                    $data=array_merge($data,['action_taken'=>request()->other_action]);
                }
            }
            else{
                $data=array_merge($data,['action_taken'=>request()->select_action_taken,'effective_date'=>request()->effective_date]);
            }
        }
        return $data;


    }


}
