<?php
namespace App\Services;

use App\OffPayrollComputation;
use App\OffPayrollComputationDetail;
use App\OffPayrollItem;
use App\OffPayrollItemComponent;
use App\PaceSalaryCategory;
use App\PaceSalaryComponent;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;


class OffPayrollItemService {

    public static function storeItem($request)
    {
        
        $item=OffPayrollItem::updateOrCreate(['id'=>$request->id],['off_payroll_type_id'=>$request->type,
        'name'=>$request->name,'source'=>$request->source,
        'salary_component_constant'=>$request->salary_component_constant,
        'payroll_constant'=>$request->payroll_constant,
        'amount'=>$request->amount,
        'is_prorated'=>$request->is_prorated,
        'proration_type'=>1,
        'percentage'=>$request->percentage]);

        $no_of_components = 0;
        if ($request->input('comp_source') !== null) {
            $no_of_components = count($request->input('comp_source'));

        
        if ($request->input('comp_salary_component') !== null) {
            $no_of_salary_components = count($request->input('comp_salary_component'));
        }
        if ($request->input('comp_payroll_constant') !== null) {
            $no_of_payroll_constants = count($request->input('comp_payroll_constant'));
        }
        if ($request->input('comp_amount') !== null) {
            $no_of_amounts = count($request->input('comp_amount'));
        }
        $no_of_salary_components_used = 0;
        $no_of_payroll_constants_used = 0;
        $no_of_amounts_used = 0;
        for ($i = 0; $i < $no_of_components; $i++) {
            $component = OffPayrollItemComponent::find($request->component_id[$i]);
            //'off_payroll_item_id','name','source','salary_component_constant','payroll_constant','amount','percentage'
            if ($component) {
                if ($request->comp_source[$i] == 'salary_component') {
                    $component->update(['salary_component_constant' => $request->comp_salary_component[$i], 'source' => $request->comp_source[$i],  'percentage' => $request->comp_percentage[$i]]);
                    $no_of_salary_components_used++;
                } elseif ($request->comp_source[$i] == 'payroll_component') {
                    $component->update(['payroll_constant' => $request->comp_payroll_constant[$i], 'source' => $request->comp_source[$i], 'percentage' => $request->comp_percentage[$i]]);
                    $no_of_payroll_constants_used++;

                } elseif ($request->comp_source[$i] == 'amount') {
                    $component->update(['amount' => $request->comp_amount[$i], 'source' => $request->comp_source[$i],'percentage' => $request->comp_percentage[$i]]);
                    $no_of_amounts_used++;

                }
            } else {
                if ($request->comp_source[$i] == 'salary_component') {
                    //'off_payroll_item_id','name','source','salary_component_constant','payroll_constant','amount','percentage'
                    $component = OffPayrollItemComponent::create(['name'=>$request->comp_salary_component[$i],'off_payroll_item_id'=>$item->id,'salary_component_constant' => $request->comp_salary_component[$i],  'source' => $request->comp_source[$i],'percentage' => $request->comp_percentage[$i]]);
                    $no_of_salary_components_used++;
                } elseif ($request->comp_source[$i] == 3) {
                    $component = OffPayrollItemComponent::create(['name'=>$request->comp_payroll_constant[$i],'payroll_constant' => $request->comp_payroll_constant[$i],'off_payroll_item_id'=>$item->id, 'source' => $request->comp_source[$i], 'percentage' => $request->comp_percentage[$i]]);
                    $no_of_payroll_constants_used++;

                } elseif ($request->comp_source[$i] == 4) {
                    $component = OffPayrollItemComponent::create(['name'=>$request->comp_source[$i],'off_payroll_item_id'=>$item->id,'amount' => $request->comp_amount[$i], 'source' => $request->comp_source[$i], 'percentage' => $request->comp_percentage[$i]]);
                    $no_of_amounts_used++;

                }
            }

        }
        return self::response(200, [], 'success');
       
    }
    }

    public static function employeesComputation($item_id,$year)
    {
        $users=User::where(['status'=>1])->get();
        $item=OffPayrollItem::findOrFail($item_id);
        $computation=OffPayrollComputation::updateOrCreate(['off_payroll_item_id'=>$item->id,
        'year'=>$year],['off_payroll_item_id'=>$item->id,'year'=>$year]);       
        foreach($users as $user){
            $project_payroll_category=PaceSalaryCategory::find($user->project_salary_category_id);
            if($project_payroll_category){
                $amount= self::offPayrollCalculation($user,$item,$year);
                OffPayrollComputationDetail::updateOrCreate(['off_payroll_computation_id'=>$computation->id,
                'user_id'=>$user->id],
                    ['off_payroll_computation_id'=>$computation->id,
                    'user_id'=>$user->id,'amount'=>$amount]);
            }
           
          

        }
        return self::response(200, [], 'success');
    }
    public static function employeeComputation($item_id,$year,$user_id)
    {
        $user=User::find($user_id);
        $item=OffPayrollItem::findOrFail($item_id);
        $computation=OffPayrollComputation::updateOrCreate(['off_payroll_item_id'=>$item->id,
        'year'=>$year],['off_payroll_item_id'=>$item->id,'year'=>$year]); 
        $project_payroll_category=PaceSalaryCategory::find($user->project_salary_category_id);
            if($project_payroll_category){      
        
           $amount= self::offPayrollCalculation($user,$item,$year);
           OffPayrollComputationDetail::updateOrCreate(['off_payroll_computation_id'=>$computation->id,
           'user_id'=>$user->id],
               ['off_payroll_computation_id'=>$computation->id,
               'user_id'=>$user->id,'amount'=>$amount]);
            }
               return self::response(200, [], 'success');
    }
    static function offPayrollCalculation($user,$item,$year)
    {
        //check if employee has a project payroll category
        $project_payroll_category=PaceSalaryCategory::find($user->project_salary_category_id);
        if(!$project_payroll_category){
            return 0;
        }
        $amount=0;
        //check item source
        if($item->source=='payroll_constant'){
            //check if employee has a project payroll category
            $project_payroll_category=PaceSalaryCategory::find($user->project_salary_category_id);
            $amount= $project_payroll_category->basic_salary*12;
        }
        if($item->source=='salary_component_constant'){
            //check if employee has a project payroll category
            $project_payroll_category=PaceSalaryCategory::find($user->project_salary_category_id);
            $payroll_salary_component=PaceSalaryComponent::where(['pace_salary_category_id'=>$project_payroll_category->id,
            'constant'=>$item->salary_component_constant])->first();
            $amount= $payroll_salary_component->amount*12;
        }
        if($item->source=='amount'){
           
            $amount= $item->amount;
        }

            
            if ($item->item_components) {
                foreach($item->item_components as $item_component){
                    $component_amount=0;
                    if($item_component->source=='payroll_constant'){
                        //check if employee has a project payroll category
                        $project_payroll_category=PaceSalaryCategory::find($user->project_salary_category_id);
                        $component_amount+= $project_payroll_category->basic_salary*12;
                    }
                    if($item_component->source=='salary_component'){
                        //check if employee has a project payroll category
                        $project_payroll_category=PaceSalaryCategory::find($user->project_salary_category_id);
                        $payroll_salary_component=PaceSalaryComponent::where(['pace_salary_category_id'=>$project_payroll_category->id,
                        'constant'=>$item_component->salary_component_constant])->first();
                        $component_amount+= $payroll_salary_component->amount*12;
                    }
                    if($item_component->source=='amount'){
                        //check if employee has a project payroll category
                        
                        $component_amount+= $item_component->amount;
                    }
                    
                    if ($item_component->percentage>0) {
                        $component_amount=($component_amount*$item_component->percentage)/100;
                     }
                     $amount+=$component_amount;
                }
            }

            //check item percentage
            if ($item->percentage>0) {
                $amount=($amount*$item->percentage)/100;
             }
            //check employee hiredate for proration
            if (date('Y', strtotime($user->hiredate)) == $year && $item->is_prorated==1) {
                $amount = $amount / 12 * (12 - intval(date('m', strtotime($user->hiredate))) + 1);
            }

            return $amount;
    }
    static function response($status_code = 200, $data = [], $message = null)
    {
        $status = $status_code < 400;

        $response = [
            'success' => $status,
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }

        if (!$status && !empty($data)) {
            $response['errors'] = $data;
        }

        if ($status && !empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status_code);
    }

}