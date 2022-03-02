<?php

$data='[';
$sn=1;
$section_total=[];
$credit_total=0;
$debit_total=0;
foreach($sections as $section){
    $section_total[$section->id]=0;
}

$npp=0;




foreach($chart_of_accounts as $account):
    $filteredusers=$allusers;

    if($account->uses_group==1):


        $filteredusers=$allusers->filter(function($filtereduser) use ($account) {

            if($filtereduser->user_groups->contains('id',$account->group_id)){
                return $filtereduser;
            }

        });

    endif;
    if($account->non_payroll_provision==0):



        if ($account->nationality_display==1) {
            $users=$filteredusers->pluck('id');
            $details= $payroll_details->whereIn('user_id', $users);
        }elseif($account->nationality_display==2) {

            $users=$filteredusers->where('expat',1)->pluck('id');
            $details= $payroll_details->whereIn('user_id', $users);
        }elseif($account->nationality_display==3){
            $users=$filteredusers->where('expat',0)->pluck('id');
            $details= $payroll_details->whereIn('user_id', $users);
        }





        if($account->display==1):
//     display for cummulative

//    display for payroll components
            if($account->source==1):

                $account_sum=0;

                //loop through all office payroll details
                foreach($details as $pdetail):

                    //unserialize the salary component details
                    $scdetails=unserialize($pdetail->sc_details);

                    //check if the was an entry
                    if(isset($scdetails['sc_allowances'][$account->salary_component_constant])):

                        // add the amount to the account sum
                        $account_sum+=$scdetails['sc_allowances'][$account->salary_component_constant];


                    elseif(isset($scdetails['sc_deductions'][$account->salary_component_constant])):

                        // add the amount to the account sum
                        $account_sum+=$scdetails['sc_deductions'][$account->salary_component_constant];

                    endif;
                    if($account->account_extra_components):
                        foreach($account->account_extra_components as $extra_component):

                            //unserialize the salary component details
                            $scdetails=unserialize($pdetail->sc_details);
                            $psscdetails=unserialize($pdetail->ssc_details);

                            if($extra_component->source==1 && $extra_component->operator=='addition'):

                                if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $account_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $account_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                endif;
                            elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $account_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):
                                    // add the amount to the account sum
                                    $account_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                endif;
                            elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                $psscdetails=unserialize($pdetail->ssc_details);


                                if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                    foreach($specific_salary_component_types as $ct):
                                        if($ct->id==$extra_component->specific_salary_component_type_id):
                                            foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                // dd($psscdetails);

                                                if($det==$ct->id):

                                                    $account_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                endif;

                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                            elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                $psscdetails=unserialize($pdetail->ssc_details);


                                if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                    foreach($specific_salary_component_types as $ct):
                                        if($ct->id==$extra_component->specific_salary_component_type_id):
                                            foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                // dd($psscdetails);

                                                if($det==$ct->id):

                                                    $account_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                endif;

                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                            elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                // add the amount to the account sum
                                $const=$extra_component->payroll_constant;
                                if($const=='gross_pay'){
                                    $account_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                }elseif($const=='netpay'){
                                    $account_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                }else{
                                    $account_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                }
                            //$account_sum+=$pdetail->$const;

                            elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                // add the amount to the account sum
                                $const=$extra_component->payroll_constant;
                                if($const=='gross_pay'){
                                    $account_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                }elseif($const=='netpay'){
                                    $account_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                }else{
                                    $account_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                }
                            //$account_sum+=$pdetail->$const;

                            elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                // add the amount to the account sum


                                $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                            //$account_sum+=$pdetail->$const;

                            elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                // add the amount to the account sum
                                $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                            endif;

                        endforeach;
                    endif;
                endforeach;
                if($account_sum>0):
                    $data.='"'.$sn++."^";
                    $data.=date('m/d/Y',strtotime($date))."^";
                    $data.='PAYROLL-'.date('ym',strtotime($date))."^";
                    $data.=$account->code."^";
                    $data.=$account->description.'-'.date('ym',strtotime($date))."^";
                    $data.="^";
                    if($account->type==1):
                        $debit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                        if($account->formula>0):
                            $data.=(($account_sum*$account->formula)/100)."^";
                        else:
                            $data.=$account_sum."^";
                        endif;
                        $data.='^",';
                    elseif($account->type==0):

                        $credit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;

                        $data.="^";
                        if($account->formula>0):
                            $data.=(($account_sum*$account->formula)/100).'^",';
                        else:
                            $data.=$account_sum.'^",';
                        endif;
                    endif;

                endif;
            //display for salary components
            elseif($account->source==2):

                $comp_sum=0;

                foreach($details as $pdetail):

                    $psscdetails=unserialize($pdetail->ssc_details);
                    // $comp_sum=0;

                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                        foreach($specific_salary_component_types as $ct):
                            if($ct->id==$account->specific_salary_component_type_id):
                                foreach($psscdetails['ssc_component_category'] as $key => $det):

                                    if($det==$ct->id):

                                        $comp_sum+=$psscdetails['ssc_amount'][$key];

                                    endif;

                                endforeach;
                            endif;
                        endforeach;
                    endif;
                    if($account->account_extra_components):
                        foreach($account->account_extra_components as $extra_component):

                            //unserialize the salary component details
                            $scdetails=unserialize($pdetail->sc_details);
                            $psscdetails=unserialize($pdetail->ssc_details);

                            if($extra_component->source==1 && $extra_component->operator=='addition'):

                                if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $comp_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $comp_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                endif;
                            elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $comp_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $comp_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                endif;
                            elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                $psscdetails=unserialize($pdetail->ssc_details);

                                if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                    foreach($specific_salary_component_types as $ct):
                                        if($ct->id==$extra_component->specific_salary_component_type_id):
                                            foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                // dd($psscdetails);

                                                if($det==$ct->id):

                                                    $comp_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                endif;

                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                            elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                $psscdetails=unserialize($pdetail->ssc_details);


                                if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                    foreach($specific_salary_component_types as $ct):
                                        if($ct->id==$extra_component->specific_salary_component_type_id):
                                            foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                if($det==$ct->id):

                                                    $comp_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                endif;

                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                            elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                // add the amount to the account sum
                                $const=$extra_component->payroll_constant;
                                if($const=='gross_pay'){
                                    $comp_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                }elseif($const=='netpay'){
                                    $comp_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                }else{
                                    $comp_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                }
                            //$comp_sum+=$pdetail->$const;

                            elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                // add the amount to the account sum
                                $const=$extra_component->payroll_constant;
                                if($const=='gross_pay'){
                                    $comp_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                }elseif($const=='netpay'){
                                    $comp_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                }else{
                                    $comp_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                }
                            //$comp_sum+=$pdetail->$const;

                            elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                // add the amount to the account sum
                                $comp_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                            //$comp_sum+=$pdetail->$const;

                            elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                // add the amount to the account sum
                                $comp_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                            endif;

                        endforeach;
                    endif;
                endforeach;
                if($comp_sum>0):
                    $data.='"'.$sn++."^";
                    $data.=date('m/d/Y',strtotime($date))."^";
                    $data.='PAYROLL-'.date('ym',strtotime($date))."^";
                    $data.=$account->code."^";
                    $data.=$account->description.'-'.date('ym',strtotime($date))."^";
                    $data.="^";
                    if($account->type==1):

                        $debit_total+= $account->formula>0?($comp_sum*$account->formula)/100:$comp_sum;

                        if($account->formula>0):
                            $data.=(($comp_sum*$account->formula)/100)."^";
                        else:
                            $data.=$comp_sum."^";
                        endif;
                        $data.='^",';
                    elseif($account->type==0):

                        $credit_total+= $account->formula>0?($comp_sum*$account->formula)/100:$comp_sum;

                        $data.="^";
                        if($account->formula>0):
                            $data.=(($comp_sum*$account->formula)/100).'^",';
                        else:
                            $data.=$comp_sum.'^",';
                        endif;
                    endif;

                endif;
            //display for specific salry component type
            elseif($account->source==3):

                $account_sum=0;

                foreach($details as $pdetail):
                    //check if the was an entry

                    // add the amount to the account sum
                    $const=$account->other_constant;
                    if($const=='gross_pay'){
                        $account_sum+=$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                    }elseif($const=='netpay'){
                        $account_sum+=($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                    }else{
                        $account_sum+=$pdetail->$const;
                    }
                    //$account_sum+=$pdetail->$const;

                    if($account->account_extra_components):
                        foreach($account->account_extra_components as $extra_component):

                            //unserialize the salary component details
                            $scdetails=unserialize($pdetail->sc_details);
                            $psscdetails=unserialize($pdetail->ssc_details);

                            if($extra_component->source==1 && $extra_component->operator=='addition'):

                                if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $account_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $account_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                endif;
                            elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $account_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                    // add the amount to the account sum
                                    $account_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                endif;
                            elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                $psscdetails=unserialize($pdetail->ssc_details);

                                if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                    foreach($specific_salary_component_types as $ct):
                                        if($ct->id==$extra_component->specific_salary_component_type_id):
                                            foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                if($det==$ct->id):

                                                    $account_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                endif;

                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                            elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                $psscdetails=unserialize($pdetail->ssc_details);

                                if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                    foreach($specific_salary_component_types as $ct):
                                        if($ct->id==$extra_component->specific_salary_component_type_id):
                                            foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                if($det==$ct->id):

                                                    $account_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                endif;

                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                            elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                // add the amount to the account sum
                                $const=$extra_component->payroll_constant;
                                if($const=='gross_pay'){
                                    $account_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                }elseif($const=='netpay'){
                                    $account_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                }else{
                                    $account_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                }
                            //$account_sum+=$pdetail->$const;

                            elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                // add the amount to the account sum
                                $const=$extra_component->payroll_constant;
                                if($const=='gross_pay'){
                                    $account_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                }elseif($const=='netpay'){
                                    $account_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                }else{
                                    $account_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                }
                            //$account_sum+=$pdetail->$const;

                            elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                // add the amount to the account sum


                                $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                            //$account_sum+=$pdetail->$const;

                            elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                // add the amount to the account sum
                                $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                            endif;

                        endforeach;
                    endif;
                endforeach;
                if($account_sum>0):
                    $data.='"'.$sn++."^";
                    $data.=date('m/d/Y',strtotime($date))."^";
                    $data.='PAYROLL-'.date('ym',strtotime($date))."^";
                    $data.=$account->code."^";
                    $data.=$account->description.'-'.date('ym',strtotime($date))."^";
                    $data.="^";
                    if($account->type==1):
                        $debit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                        if($account->formula>0):
                            $data.=(($account_sum*$account->formula)/100)."^";
                        else:
                            $data.=$account_sum."^";
                        endif;
                        $data.='^",';
                    elseif($account->type==0):
                        $credit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                        $data.="^";
                        if($account->formula>0):
                            $data.=(($account_sum*$account->formula)/100).'^",';
                        else:
                            $data.=$account_sum.'^",';
                        endif;
                    endif;

                endif;
            endif;
        elseif($account->display==2):
            //display for spread

            foreach($sections as $section):
                $section_users=$section->users;
                //Check if it is a non payroll provision
                if($account->uses_group==1):


                    $section_users=$section->users->filter(function($filtereduser) use ($account) {

                        if($filtereduser->user_groups->contains('id',$account->group_id)){
                            return $filtereduser;
                        }

                    });


                endif;

                if ($account->nationality_display==1) {

                    $users=$section_users->pluck('id');
                    $details= $payroll_details->whereIn('user_id', $users);
                    $section_sum = 0;

                } elseif($account->nationality_display==2) {
                    $users=$section_users->where('expat',1)->pluck('id');
                    $details= $payroll_details->whereIn('user_id', $users);
                    $section_sum = 0;


                }elseif($account->nationality_display==3){
                    $users=$section_users->where('expat',0)->pluck('id');
                    $details= $payroll_details->whereIn('user_id', $users);
                    $section_sum = 0;

                }else{
                    $users=$section_users->pluck('id');
                    $details= $payroll_details->whereIn('user_id', $users);
                    $section_sum = 0;
                }


                if($account->source==1):

                    //loop through all office payroll details
                    foreach($details as $pdetail):

                        //unserialize the salary component details
                        $scdetails=unserialize($pdetail->sc_details);

                        //check if the was an entry
                        if(isset($scdetails['sc_allowances'][$account->salary_component_constant])):

                            // add the amount to the account sum
                            $section_sum+=$scdetails['sc_allowances'][$account->salary_component_constant];


                        elseif(isset($scdetails['sc_deductions'][$account->salary_component_constant])):

                            // add the amount to the account sum
                            $section_sum+=$scdetails['sc_deductions'][$account->salary_component_constant];

                        endif;
                        if($account->account_extra_components):
                            foreach($account->account_extra_components as $extra_component):

                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                                $psscdetails=unserialize($pdetail->ssc_details);

                                if($extra_component->source==1 && $extra_component->operator=='addition'):

                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                    if($det==$ct->id):

                                                        $section_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):


                                                    if($det==$ct->id):

                                                        $section_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $section_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $section_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $section_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$section_sum+=$pdetail->$const;

                                elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $section_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $section_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $section_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$section_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum


                                    $section_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                //$section_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $section_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                endif;

                            endforeach;
                        endif;
                    endforeach;
                    if($section_sum>0):
                        '"'.$sn++."^";
                        $data.=date('m/d/Y',strtotime($date))."^";
                        $data.='PAYROLL-'.$section->name."^";
                        $data.=$account->code."^";
                        $data.=$account->description.'-'.date('ym',strtotime($date))."^";
                        if ($account->salary_charge=='salary'):
                            $data.=$section->salary_project_code."^";
                        elseif($account->salary_charge=='charge'):
                            $data.=$section->charge_project_code."^";

                        else:
                            $data.="^";
                        endif;
                        if($account->type==1):
                            $debit_total+= $account->formula>0?($section_sum*$account->formula)/100:$section_sum;
                            if($account->formula>0):
                                $data.=(($section_sum*$account->formula)/100)."^";
                            else:
                                $data.=$section_sum."^";
                            endif;
                            $data.='^",';
                        elseif($account->type==0):
                            $credit_total+= $account->formula>0?($section_sum*$account->formula)/100:$section_sum;
                            $data.="^";
                            if($account->formula>0):
                                $data.=(($section_sum*$account->formula)/100).'^",';
                            else:
                                $data.=$section_sum.'^",';
                            endif;
                        endif;

                    endif;
                //display for salary components
                elseif($account->source==2):

                    // $psscdetails=unserialize($pdetail->ssc_details);

                    $comp_sum=0;

                    foreach($details as $pdetail):

                        $psscdetails=unserialize($pdetail->ssc_details);

                        if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                            foreach($specific_salary_component_types as $ct):
                                if($ct->id==$account->specific_salary_component_type_id):
                                    foreach($psscdetails['ssc_component_category'] as $key => $det):


                                        if($det==$ct->id):

                                            $comp_sum+=$psscdetails['ssc_amount'][$key];




                                        endif;

                                    endforeach;
                                endif;
                            endforeach;
                        endif;
                        if($account->account_extra_components):
                            foreach($account->account_extra_components as $extra_component):

                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                                $psscdetails=unserialize($pdetail->ssc_details);

                                if($extra_component->source==1 && $extra_component->operator=='addition'):

                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $comp_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $comp_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $comp_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $comp_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):


                                                    if($det==$ct->id):

                                                        $comp_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                    if($det==$ct->id):

                                                        $comp_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $comp_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $comp_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $comp_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$comp_sum+=$pdetail->$const;

                                elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $comp_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $comp_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $comp_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$comp_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum

                                    $comp_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                //$comp_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $comp_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                endif;

                            endforeach;
                        endif;
                    endforeach;
                    if($comp_sum>0):
                        $data.='"'.$sn++."^";
                        $data.=date('m/d/Y',strtotime($date))."^";
                        $data.='PAYROLL-'.date('ym',strtotime($date))."^";
                        $data.=$account->code."^";
                        $data.=$account->description.'-'.$section->name."^";
                        if ($account->salary_charge=='salary'):
                            $data.=$section->salary_project_code."^";
                        elseif($account->salary_charge=='charge'):
                            $data.=$section->charge_project_code."^";

                        else:
                            $data.="^";
                        endif;
                        if($account->type==1):
                            $debit_total+= $account->formula>0?($comp_sum*$account->formula)/100:$comp_sum;
                            if($account->formula>0):
                                $data.=(($comp_sum*$account->formula)/100)."^";
                            else:
                                $data.=$comp_sum."^";
                            endif;
                            $data.='^",';
                        elseif($account->type==0):
                            $credit_total+= $account->formula>0?($comp_sum*$account->formula)/100:$comp_sum;
                            $data.="^";
                            if($account->formula>0):
                                $data.=(($comp_sum*$account->formula)/100).'^",';
                            else:
                                $data.=$comp_sum.'^",';
                            endif;
                        endif;

                    endif;
                //display for specific salry component type
                elseif($account->source==3):

                    $section_sum=0;

                    foreach($details as $pdetail):
                        //check if the was an entry

                        // add the amount to the account sum
                        $const=$account->other_constant;
                        if($const=='gross_pay'){
                            $section_sum+=$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                        }elseif($const=='netpay'){
                            $section_sum+=$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                        }else{
                            $section_sum+=$pdetail->$const;
                        }
                        //$section_sum+=$pdetail->$const;
                        if($account->account_extra_components):
                            foreach($account->account_extra_components as $extra_component):

                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                                $psscdetails=unserialize($pdetail->ssc_details);

                                if($extra_component->source==1 && $extra_component->operator=='addition'):

                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):


                                                    if($det==$ct->id):

                                                        $section_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):



                                                    if($det==$ct->id):

                                                        $section_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $section_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $section_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $section_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $section_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $section_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $section_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum


                                    $section_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $section_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                endif;

                            endforeach;
                        endif;
                    endforeach;
                    if($section_sum>0):
                        $data.='"'.$sn++."^";
                        $data.=date('m/d/Y',strtotime($date))."^";
                        $data.='PAYROLL-'.date('ym',strtotime($date))."^";
                        $data.=$account->code."^";
                        $data.=$account->description.'-'.$section->name."^";
                        if ($account->salary_charge=='salary'):
                            $data.=$section->salary_project_code."^";
                        elseif($account->salary_charge=='charge'):
                            $data.=$section->charge_project_code."^";

                        else:
                            $data.="^";
                        endif;
                        if($account->type==1):
                            $debit_total+= $account->formula>0?($section_sum*$account->formula)/100:$section_sum;
                            if($account->formula>0):
                                $data.=(($section_sum*$account->formula)/100)."^";
                            else:
                                $data.=$section_sum."^";
                            endif;
                            $data.='^",';
                        elseif($account->type==0):
                            $credit_total+= $account->formula>0?($section_sum*$account->formula)/100:$section_sum;
                            $data.="^";
                            if($account->formula>0):
                                $data.=(($section_sum*$account->formula)/100).'^",';
                            else:
                                $data.=$section_sum.'^",';
                            endif;
                        endif;

                    endif;
                endif;
            endforeach;

        elseif($account->display==3):
            //display for individual
            $filteredusers=$allusers;
            //Check if it is a non payroll provision
            if($account->uses_group==1):


                $filteredusers=$allusers->filter(function($filtereduser) use ($account) {

                    if($filtereduser->user_groups->contains('id',$account->group_id)){
                        return $filtereduser;
                    }

                });


            endif;

//     Check display type


            if ($account->nationality_display==1) {
                $users=$filteredusers->pluck('id');
                $details= $payroll_details->whereIn('user_id', $users);
            }elseif($account->nationality_display==2) {

                $users=$filteredusers->where('expat',1)->pluck('id');
                $details= $payroll_details->whereIn('user_id', $users);
            }elseif($account->nationality_display==3){
                $users=$filteredusers->where('expat',0)->pluck('id');
                $details= $payroll_details->whereIn('user_id', $users);
            }






            foreach ($users as $user):

                $pdetail=$details->firstWhere('user_id', $user);
                $account_sum=0;

                if($pdetail):
                    if($account->source==1):

                        //unserialize the salary component details
                        $scdetails=unserialize($pdetail->sc_details);

                        //check if the was an entry
                        if(isset($scdetails['sc_allowances'][$account->salary_component_constant])):

                            // add the amount to the account sum
                            $account_sum=$scdetails['sc_allowances'][$account->salary_component_constant];


                        elseif(isset($scdetails['sc_deductions'][$account->salary_component_constant])):

                            // add the amount to the account sum
                            $account_sum=$scdetails['sc_deductions'][$account->salary_component_constant];
                        endif;

                        if($account->account_extra_components):
                            foreach($account->account_extra_components as $extra_component):

                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                                $psscdetails=unserialize($pdetail->ssc_details);

                                if($extra_component->source==1 && $extra_component->operator=='addition'):

                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $account_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $account_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $account_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $account_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):


                                                    if($det==$ct->id):

                                                        $account_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):


                                                    if($det==$ct->id):

                                                        $account_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $account_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $account_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $account_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $account_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $account_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $account_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum


                                    $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                endif;

                            endforeach;
                        endif;
                        if($account_sum>0):
                            $data.='"'.$sn++."^";
                            $data.=date('m/d/Y',strtotime($date))."^";
                            $data.='PAYROLL-'.date('ym',strtotime($date))."^";
                            $data.=$account->code."^";
                            $data.=$account->description.'-'.date('ym',strtotime($date)).'-'.(isset($pdetail->user->name) ? $pdetail->user->name : '')."^";
                            if ($account->salary_charge=='salary'):
                                $data.=$section->salary_project_code."^";
                            elseif($account->salary_charge=='charge'):
                                $data.=$section->charge_project_code."^";

                            else:
                                $data.="^";
                            endif;
                            if($account->type==1):
                                $debit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                if($account->formula>0):
                                    $data.=(($account_sum*$account->formula)/100)."^";
                                else:
                                    $data.=$account_sum."^";
                                endif;
                                $data.='^",';
                            elseif($account->type==0):
                                $credit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                $data.="^";
                                if($account->formula>0):
                                    $data.=(($account_sum*$account->formula)/100).'^",';
                                else:
                                    $data.=$account_sum.'^",';
                                endif;
                            endif;

                        endif;
                    elseif($account->source==2):

                        $psscdetails=unserialize($pdetail->ssc_details);

                        $individual_sum=0;

                        if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                            foreach($specific_salary_component_types as $ct):
                                if($ct->id==$account->specific_salary_component_type_id):
                                    foreach($psscdetails['ssc_component_category'] as $key => $det):


                                        if($det==$ct->id):

                                            $individual_sum+=$psscdetails['ssc_amount'][$key];



                                        endif;

                                    endforeach;
                                endif;
                            endforeach;
                            if($account->account_extra_components):
                                foreach($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails=unserialize($pdetail->sc_details);
                                    $psscdetails=unserialize($pdetail->ssc_details);

                                    if($extra_component->source==1 && $extra_component->operator=='addition'):

                                        if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $individual_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $individual_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                        if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $individual_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $individual_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                        $psscdetails=unserialize($pdetail->ssc_details);

                                        if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                            foreach($specific_salary_component_types as $ct):
                                                if($ct->id==$extra_component->specific_salary_component_type_id):
                                                    foreach($psscdetails['ssc_component_category'] as $key => $det):


                                                        if($det==$ct->id):

                                                            $individual_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                        $psscdetails=unserialize($pdetail->ssc_details);

                                        if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                            foreach($specific_salary_component_types as $ct):
                                                if($ct->id==$extra_component->specific_salary_component_type_id):
                                                    foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                        if($det==$ct->id):

                                                            $individual_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                        // add the amount to the account sum
                                        $const=$extra_component->payroll_constant;
                                        if($const=='gross_pay'){
                                            $individual_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $individual_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                        }else{
                                            $individual_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                        }
                                    //$individual_sum+=$pdetail->$const;

                                    elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                        // add the amount to the account sum
                                        $const=$extra_component->payroll_constant;
                                        if($const=='gross_pay'){
                                            $individual_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $individual_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                        }else{
                                            $individual_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                        }
                                    //$individual_sum+=$pdetail->$const;

                                    elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                        // add the amount to the account sum


                                        $individual_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                    //$individual_sum+=$pdetail->$const;

                                    elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                        // add the amount to the account sum
                                        $individual_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                    endif;

                                endforeach;
                            endif;
                            if($individual_sum>0):
                                $data.='"'.$sn++."^";
                                $data.=date('m/d/Y',strtotime($date))."^";
                                $data.='PAYROLL-'.date('ym',strtotime($date))."^";
                                $data.=$account->code."^";
                                $data.=$account->description.'-'.date('ym',strtotime($date)).'-'.(isset($pdetail->user->name) ? $pdetail->user->name : '')."^";
                                if ($account->salary_charge=='salary'):
                                    $data.=$section->salary_project_code."^";
                                elseif($account->salary_charge=='charge'):
                                    $data.=$section->charge_project_code."^";

                                else:
                                    $data.="^";
                                endif;
                                if($account->type==1):
                                    $debit_total+= $account->formula>0?($individual_sum*$account->formula)/100:$individual_sum;
                                    if($account->formula>0):
                                        $data.=(($individual_sum*$account->formula)/100)."^";
                                    else:
                                        $data.=$individual_sum."^";
                                    endif;
                                    $data.='^",';
                                elseif($account->type==0):
                                    $credit_total+= $account->formula>0?($individual_sum*$account->formula)/100:$individual_sum;
                                    $data.="^";
                                    if($account->formula>0):
                                        $data.=(($individual_sum*$account->formula)/100).'^",';
                                    else:
                                        $data.=$individual_sum.'^",';
                                    endif;
                                endif;

                            endif;
                        else:

                        endif;
                    elseif($account->source==3):

                        $account_sum=0;

                        // add the amount to the account sum
                        $const=$account->other_constant;
                        if($const=='gross_pay'){
                            $account_sum+=$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                        }elseif($const=='netpay'){
                            $account_sum+=$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                        }else{
                            $account_sum+=$pdetail->$const;
                        }
                        //$account_sum=$pdetail->$const;
                        if($account->account_extra_components):
                            foreach($account->account_extra_components as $extra_component):

                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                                $psscdetails=unserialize($pdetail->ssc_details);

                                if($extra_component->source==1 && $extra_component->operator=='addition'):

                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $account_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $account_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                                    if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $account_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $account_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                                    $psscdetails=unserialize($pdetail->ssc_details);


                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                    if($det==$ct->id):

                                                        $account_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                                    $psscdetails=unserialize($pdetail->ssc_details);

                                    if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                        foreach($specific_salary_component_types as $ct):
                                            if($ct->id==$extra_component->specific_salary_component_type_id):
                                                foreach($psscdetails['ssc_component_category'] as $key => $det):

                                                    if($det==$ct->id):

                                                        $account_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $account_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $account_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $account_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $const=$extra_component->payroll_constant;
                                    if($const=='gross_pay'){
                                        $account_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $account_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $account_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                                    // add the amount to the account sum


                                    $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                //$account_sum+=$pdetail->$const;

                                elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                                    // add the amount to the account sum
                                    $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                endif;

                            endforeach;
                        endif;
                        $data.='"'.$sn++."^";
                        $data.=date('m/d/Y',strtotime($date))."^";
                        $data.='PAYROLL-'.date('ym',strtotime($date))."^";
                        $data.=$account->code."^";
                        $data.=$account->description.'-'.date('ym',strtotime($date)).'-'.(isset($pdetail->user->name) ? $pdetail->user->name : '')."^";

                        $data.="^";
                        if($account->type==1):
                            $debit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                            if($account->formula>0):
                                $data.=(($account_sum*$account->formula)/100)."^";
                            else:
                                $data.=$account_sum."^";
                            endif;
                            $data.='^",';
                        elseif($account->type==0):
                            $credit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                            $data.="^";
                            if($account->formula>0):
                                $data.=(($account_sum*$account->formula)/100).'^",';
                            else:
                                $data.=$account_sum.'^",';
                            endif;
                        endif;

                    endif;
                endif;

            endforeach;

        endif;
    elseif($account->non_payroll_provision==1):


        foreach($sections as $section):
            $section_users=$section->users;

            if($account->uses_group==1):


                $section_users=$section->users->filter(function($filtereduser) use ($account) {

                    if($filtereduser->user_groups->contains('id',$account->group_id)){
                        return $filtereduser;
                    }

                });


            endif;


            $users=$section_users->where('expat',0)->pluck('id');
            $details= $payroll_details->whereIn('user_id', $users);
            $section_sum=0;

            foreach($details as $pdetail):


                // add the amount to the account sum

                // add the amount to the account sum
                $const=$account->other_constant;
                if($const=='gross_pay'){
                    $section_sum+=$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                }elseif($const=='netpay'){
                    $section_sum+=$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                }else{
                    $section_sum+=$pdetail->$const;
                }
                //$section_sum+=$pdetail->$const;
                if($account->account_extra_components):
                    foreach($account->account_extra_components as $extra_component):

                        //unserialize the salary component details
                        $scdetails=unserialize($pdetail->sc_details);
                        $psscdetails=unserialize($pdetail->ssc_details);

                        if($extra_component->source==1 && $extra_component->operator=='addition'):

                            if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                // add the amount to the account sum
                                $section_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                            elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                // add the amount to the account sum
                                $section_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                            endif;
                        elseif($extra_component->source==1 && $extra_component->operator=='subtraction'):
                            if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                // add the amount to the account sum
                                $section_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];


                            elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                // add the amount to the account sum
                                $section_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];

                            endif;
                        elseif($extra_component->source==2 && $extra_component->operator=='addition'):

                            $psscdetails=unserialize($pdetail->ssc_details);

                            if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                foreach($specific_salary_component_types as $ct):
                                    if($ct->id==$extra_component->specific_salary_component_type_id):
                                        foreach($psscdetails['ssc_component_category'] as $key => $det):


                                            if($det==$ct->id):

                                                $section_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                            endif;

                                        endforeach;
                                    endif;
                                endforeach;
                            endif;
                        elseif($extra_component->source==2 && $extra_component->operator=='subtraction'):

                            $psscdetails=unserialize($pdetail->ssc_details);


                            if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0):
                                foreach($specific_salary_component_types as $ct):
                                    if($ct->id==$extra_component->specific_salary_component_type_id):
                                        foreach($psscdetails['ssc_component_category'] as $key => $det):


                                            if($det==$ct->id):

                                                $section_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];

                                            endif;

                                        endforeach;
                                    endif;
                                endforeach;
                            endif;
                        elseif($extra_component->source==3 && $extra_component->operator=='addition'):

                            // add the amount to the account sum
                            $const=$extra_component->payroll_constant;
                            if($const=='gross_pay'){
                                $section_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                            }elseif($const=='netpay'){
                                $section_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                            }else{
                                $section_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                            }
                        //$account_sum+=$pdetail->$const;

                        elseif($extra_component->source==3 && $extra_component->operator=='subtraction'):

                            // add the amount to the account sum
                            $const=$extra_component->payroll_constant;
                            if($const=='gross_pay'){
                                $section_sum-=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                            }elseif($const=='netpay'){
                                $section_sum-=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                            }else{
                                $section_sum-=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                            }
                        //$account_sum+=$pdetail->$const;

                        elseif($extra_component->source==4 && $extra_component->operator=='addition'):

                            // add the amount to the account sum


                            $section_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                        //$account_sum+=$pdetail->$const;

                        elseif($extra_component->source==4 && $extra_component->operator=='subtraction'):

                            // add the amount to the account sum
                            $section_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                        endif;

                    endforeach;
                endif;

            endforeach;

            if($section_sum>0){
                if($account->formula>0){
                    $section_total[$section->id]+= ($section_sum*$account->formula)/100;
                    $npp+=($section_sum*$account->formula)/100;
                }else{
                    $section_total[$section->id]+=$section_sum;
                    $npp+=$section_sum;
                }

            }



        endforeach;
        //end of non payroll provisions
    endif;
    //end of chart of accounts loop
endforeach;
//Long Service Award Contribution display

if ($pp->display_lsa_on_nav_export==1):



    foreach($sections as $section):
        //non payroll provisions

        $section_users=$section->users;
        //Check if it is a non payroll provision
        if($account->uses_group==1):


            $section_users=$section->users->filter(function($filtereduser) use ($account) {

                if($filtereduser->user_groups->contains('id',$account->group_id)){
                    return $filtereduser;
                }

            });


        endif;


        $users=$section_users->where('expat',0)->pluck('id');
        $details= $payroll_details->whereIn('user_id', $users);
        $section_sum=0;

        foreach($details as $pdetail):
            //check if the was an entry

            foreach($lsas as $award):

                if ($pdetail->user->months_of_service <= ($award->max_year*12)):


                    $section_sum+=$award->amount/$award->difference/12;
                    break;

                endif;



            endforeach;
        endforeach;

        $npp +=  $section_sum;
        $section_sum+= $section_total[$section->id];


        $data.='"'.$sn++."^";
        $data.=date('m/d/Y',strtotime($date))."^";
        $data.='PAYROLL-'.date('ym',strtotime($date))."^";
        $data.="6414111^";
        $data.='Non Payroll Provision-'.$section->name."^";

        $data.=$section->salary_project_code."^";



        $data.=$section_sum."^";

        $data.='^",';
    endforeach;
else:
    foreach($sections as $section):
        $data.='"'.$sn++."^";
        $data.=date('m/d/Y',strtotime($date))."^";
        $data.='PAYROLL-'.date('ym',strtotime($date))."^";
        $data.="6414111^";
        $data.='Non Payroll Provision-'.$section->name."^";

        $data.=$section->salary_project_code."^";



        $data.=$section_total[$section->id]."^";

        $data.='^",';
    endforeach;

endif;

$data.='"'.$sn++."^";
$data.=date('m/d/Y',strtotime($date))."^";
$data.='PAYROLL-'.date('ym',strtotime($date))."^";
$data.="6414111^";
$data.='Non Payroll Provision-National Staff^';

$data.="^";



$data.="^";

$data.=$npp.'^",';
$data=trim($data, ",");
$data.="]";
json_encode($data);
print_r($data);
