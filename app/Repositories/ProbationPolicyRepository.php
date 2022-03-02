<?php


namespace App\Repositories;


class ProbationPolicyRepository
{

    public function processPost()
    {
        try {
            switch (request()->type) {
                case 'saveProbationPolicy':
                    return $this->saveProbationPolicy();
                default :
                    return response()->json(['status' => 'error', 'message' => 'Route not found']);
            }
        }
        catch (\Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    private function saveProbationPolicy()
    {

        request()->request->add(['company_id'=>companyId(),'created_by'=>request()->user()->id,'notify_roles'=>collect(request()->notify_roles)->implode(',')]);

        if(!request()->user()->role->permissions->contains('constant', 'manage_probation_policy')){
            throw new \Exception('You do not have permission to manage probation');
        }
        \App\ProbationPolicy::updateOrCreate(['company_id'=>companyId()],request()->except('_token','type'));
        return response()->json(['status'=>'success','message'=>'Policy successfully saved']);
    }
}
