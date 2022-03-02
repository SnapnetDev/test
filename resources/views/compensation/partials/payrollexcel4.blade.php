<table>
    <thead>

    <tr>
        <th></th>
        <th>Employee Number</th>
        <th>Employee Name</th>

        <th>Basic pay</th>
        
        @foreach ($components as $key=>$component)
            @if($component['type']==1 && ($component['constant']!='leave_allowance')&&($component['constant']!='13th_month'))
            <th>
                {{$component['name']}}
            </th>
            @endif
        @endforeach
       
        @foreach($specific_salary_component_types as $ct)
            @if($ct->type==1)
            <th>{{$ct->name}}</th>
            @endif
        @endforeach
        @foreach ($components as $key=>$component)
            @if($component['type']==0 && ($component['constant']!='leave_allowance_deduction')&&($component['constant']!='13th_month_deduction'))
            <th>
                {{$component['name']}}
            </th>
            @endif
        @endforeach

        @foreach($specific_salary_component_types as $ct)
            @if($ct->type==0)
            <th>{{$ct->name}}</th>
            @endif
        @endforeach
        <th>PAYE</th>
        <th>Union Dues</th>
        <th>Gross pay With Reimbursables</th>
        <th>Net Pay</th>

        @foreach($chart_of_accounts as $account)
            <th>{{$account->name}}</th>
        @endforeach
        @if ($pp->display_lsa_on_nav_export==1)
            <th>Long Service Award</th>
        @endif
        <th>Section</th>
        <th>Designation</th>
        <th>Payroll Type</th>
        <th>Grade</th>

    </tr>
    </thead>
    <tbody>
    @php
        $sn=1;

    @endphp
    @foreach ($payroll->payroll_details as $detail)
        @php
            $allowances=0;
            $deductions=0;
            $gp=0;

        @endphp
        <tr @if($detail->user->expat==1)style="background: #5677a3;color:#ffffff;"@endif>
            <td>{{$sn}}</td>
            <td>{{$detail->user->emp_num}}</td>
            <td>{{$detail->user->name}}</td>

            <td>{{$detail->basic_pay}}</td>


            @php
                $details=unserialize($detail->sc_details);
                // $num=count($days);
            @endphp
            @foreach ($components as $key=>$component)
                
                    @if($component['type']==1&&($component['constant']!='leave_allowance')&&($component['constant']!='13th_month'))
                    <td>
                    @if( isset($details['sc_allowances'][$component['constant']]))
                        @php
                            $allowances+=$details['sc_allowances'][$component['constant']];
                        @endphp
                        {{isset($details['sc_allowances'][$component['constant']])?$details['sc_allowances'][$component['constant']]:""}}
                    @else
                    0.00
                    @endif
                     </td>
                    @endif
               
            @endforeach

            @php
                $psscdetails=unserialize($detail->ssc_details);
                // dd($psscdetails);
                // $num=count($days);
            @endphp
            @if($detail->ssc_allowances>0 )
                @foreach($specific_salary_component_types as $ct)
                @if($ct->type==1)
                    @php
                        $ct_sum=0;


                    @endphp
                    @foreach($psscdetails['ssc_component_category'] as $key => $det)

                        @php
                            // dd($psscdetails);
                        @endphp
                        @if($det==$ct->id)
                            @php
                                $ct_sum+=$psscdetails['ssc_amount'][$key];
                                if ($psscdetails['ssc_component_type'][$key]==1):
                                    $allowances+=$psscdetails['ssc_amount'][$key];
                                
                                endif;
                            @endphp


                        @endif

                    @endforeach
                    <td>{{$ct_sum}}</td>
                @endif
                @endforeach
            @else
                @foreach($specific_salary_component_types as $ct)
                 @if($ct->type==1)
                    <td>0.00</td>
                 @endif
                @endforeach
            @endif
            @foreach ($components as $key=>$component)
                
                    @if($component['type']==0 &&($component['constant']!='leave_allowance_deduction')&&($component['constant']!='13th_month_deduction'))
                    <td>
                    @if(isset($details['sc_deductions'][$component['constant']]))
                        @php
                            $deductions+=$details['sc_deductions'][$component['constant']];
                        @endphp
                        {{isset($details['sc_deductions'][$component['constant']])?$details['sc_deductions'][$component['constant']]:""}}
                    @else
                    0.00
                    @endif
                    </td>
                    @endif
                
            @endforeach
            @if($detail->ssc_deductions>0)
                @foreach($specific_salary_component_types as $ct)
                @if($ct->type==0)
                    @php
                        $ct_sum=0;


                    @endphp
                    @foreach($psscdetails['ssc_component_category'] as $key => $det)

                        @php
                            // dd($psscdetails);
                        @endphp
                        @if($det==$ct->id)
                            @php
                                $ct_sum+=$psscdetails['ssc_amount'][$key];
                                if($psscdetails['ssc_component_type'][$key]==0):
                                    $deductions+=$psscdetails['ssc_amount'][$key];
                                endif;
                            @endphp


                        @endif

                    @endforeach
                    <td>{{$ct_sum}}</td>
                @endif
                @endforeach
            @else
                @foreach($specific_salary_component_types as $ct)
                 @if($ct->type==0)
                    <td>0.00</td>
                 @endif
                @endforeach
            @endif
            <td>{{$detail->paye}}</td>
            <td>{{$detail->union_dues}}</td>
            <td>{{($detail->basic_pay+$allowances)}}</td>
            <td>{{($detail->basic_pay+$allowances-($deductions+$detail->paye+$detail->union_dues))}}</td>

            {{-- display for individual --}}

            @foreach($chart_of_accounts as $account)
           
                @php

                    $account_sum=0;
                @endphp
                
                    @if(($account->uses_group==1 && $detail->user->user_groups->contains('id',$account->group_id))||(($account->uses_group==0)&&($account->nationality_display==2 &&$detail->user->expat==1)) ||(($account->uses_group==0)&&($account->nationality_display==3 &&$detail->user->expat==0)) ||($account->uses_group==0&&$account->nationality_display==1)||($account->non_payroll_provision==1 && $detail->user->expat==0))
                        @if($account->source==1)
                            @php
                                //unserialize the salary component details
                                $scdetails=unserialize($detail->sc_details);
                            @endphp
                            {{-- check if the was an entry --}}
                            @if(isset($scdetails['sc_allowances'][$account->salary_component_constant]))
                                @php
                                    // add the amount to the account sum
                                    $account_sum=$scdetails['sc_allowances'][$account->salary_component_constant];
                                @endphp

                            @elseif(isset($scdetails['sc_deductions'][$account->salary_component_constant]))
                                @php
                                    // add the amount to the account sum
                                    $account_sum=$scdetails['sc_deductions'][$account->salary_component_constant];
                                @endphp
                            @endif

                            @forelse($account->account_extra_components as $extra_component)
                                @php
                                    //unserialize the salary component details
                                    $scdetails=unserialize($detail->sc_details);
                                    $psscdetails=unserialize($detail->ssc_details);
                                @endphp
                                @if($extra_component->source==1 && $extra_component->source=='addition')

                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                            $account_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $account_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==1 && $extra_component->operator=='subtraction')
                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                            $account_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $account_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='addition')
                                    @php
                                        $psscdetails=unserialize($detail->ssc_details);

                                    @endphp
                                    @if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $account_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                        @endphp
                                                    @endif

                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='subtraction')
                                    @php
                                        $psscdetails=unserialize($detail->ssc_details);

                                    @endphp
                                    @if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $account_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                        @endphp
                                                    @endif

                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($extra_component->source==3 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum
                                        $const=$extra_component->payroll_constant;
                                        if($const=='gross_pay'){
                                            $account_sum+=$extra_component->percentage>0?(($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $account_sum+=$extra_component->percentage>0?((($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues))*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues);
                                        }else{
                                            $account_sum+=$extra_component->percentage>0?($detail->$const*$extra_component->percentage)/100:$detail->$const;
                                        }
                                        //$account_sum+=$detail->$const;
                                    @endphp
                                @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                    @php
                                        // add the amount to the account sum
                                        $const=$extra_component->payroll_constant;
                                        if($const=='gross_pay'){
                                            $account_sum-=$extra_component->percentage>0?(($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $account_sum-=$extra_component->percentage>0?((($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues))*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues);
                                        }else{
                                            $account_sum-=$extra_component->percentage>0?($detail->$const*$extra_component->percentage)/100:$detail->$const;
                                        }
                                        //$account_sum+=$detail->$const;
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum
                                       
                                        
                                            $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                        
                                        //$account_sum+=$detail->$const;
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                    @php
                                        // add the amount to the account sum
                                        $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                    @endphp
                                @endif
                            @empty
                            @endforelse
                            @if($account_sum>0)

                                <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                </td>
                            @else
                                <td>0.00</td>
                            @endif
                        @elseif($account->source==2)
                            @php
                                $psscdetails=unserialize($detail->ssc_details);

                                $individual_sum=0;
                            @endphp
                            @if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
                                @foreach($specific_salary_component_types as $ct)
                                    @if($ct->id==$account->specific_salary_component_type_id)
                                        @foreach($psscdetails['ssc_component_category'] as $key => $det)

                                            @php
                                                // dd($psscdetails);
                                            @endphp
                                            @if($det==$ct->id)
                                                @php
                                                    $individual_sum+=$psscdetails['ssc_amount'][$key];

                                                @endphp


                                            @endif

                                        @endforeach
                                    @endif
                                @endforeach
                                @forelse($account->account_extra_components as $extra_component)
                                    @php
                                        //unserialize the salary component details
                                        $scdetails=unserialize($detail->sc_details);
                                        $psscdetails=unserialize($detail->ssc_details);
                                    @endphp
                                    @if($extra_component->source==1 && $extra_component->source=='addition')

                                        @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                            @php
                                                // add the amount to the account sum
                                                $individual_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                            @endphp

                                        @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                            @php
                                                // add the amount to the account sum
                                               $individual_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                            @endphp
                                        @endif
                                    @elseif($extra_component->source==1 && $extra_component->operator=='subtraction')
                                        @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                            @php
                                                // add the amount to the account sum
                                                $individual_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                            @endphp

                                        @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                            @php
                                                // add the amount to the account sum
                                               $individual_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                            @endphp
                                        @endif
                                    @elseif($extra_component->source==2 && $extra_component->operator=='addition')
                                        @php
                                            $psscdetails=unserialize($detail->ssc_details);

                                        @endphp
                                        @if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
                                            @foreach($specific_salary_component_types as $ct)
                                                @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                    @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                        @php
                                                            // dd($psscdetails);
                                                        @endphp
                                                        @if($det==$ct->id)
                                                            @php
                                                                $individual_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                            @endphp
                                                        @endif

                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($extra_component->source==2 && $extra_component->operator=='subtraction')
                                        @php
                                            $psscdetails=unserialize($detail->ssc_details);

                                        @endphp
                                        @if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
                                            @foreach($specific_salary_component_types as $ct)
                                                @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                    @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                        @php
                                                            // dd($psscdetails);
                                                        @endphp
                                                        @if($det==$ct->id)
                                                            @php
                                                                $individual_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                            @endphp
                                                        @endif

                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($extra_component->source==3 && $extra_component->operator=='addition')
                                        @php
                                            // add the amount to the account sum
                                            $const=$extra_component->payroll_constant;
                                            if($const=='gross_pay'){
                                                $individual_sum+=$extra_component->percentage>0?(($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances;
                                            }elseif($const=='netpay'){
                                                $individual_sum+=$extra_component->percentage>0?((($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues))*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues);
                                            }else{
                                                $individual_sum+=$extra_component->percentage>0?($detail->$const*$extra_component->percentage)/100:$detail->$const;
                                            }
                                            //$individual_sum+=$detail->$const;
                                        @endphp
                                    @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                        @php
                                            // add the amount to the account sum
                                            $const=$extra_component->payroll_constant;
                                            if($const=='gross_pay'){
                                                $individual_sum-=$extra_component->percentage>0?(($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances;
                                            }elseif($const=='netpay'){
                                                $individual_sum-=$extra_component->percentage>0?((($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues))*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues);
                                            }else{
                                                $individual_sum-=$extra_component->percentage>0?($detail->$const*$extra_component->percentage)/100:$detail->$const;
                                            }
                                            //$individual_sum+=$detail->$const;
                                        @endphp
                                    @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                        @php
                                            // add the amount to the account sum


                                                $individual_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                            //$individual_sum+=$detail->$const;
                                        @endphp
                                    @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                        @php
                                            // add the amount to the account sum
                                            $individual_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                        @endphp
                                    @endif
                                @empty
                                @endforelse
                                @if($individual_sum>0)
                                    <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                    </td>
                                @else
                                    <td>0.00</td>
                                @endif
                            @else

                            @endif
                        @elseif($account->source==3)
                            @php
                                
                                // add the amount to the account sum
                                $const=$account->other_constant;
                                if($const=='gross_pay'){
                                    $account_sum+=$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances;

                                }elseif($const=='netpay'){
                                    $account_sum+=$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues);
                                }else{
                                    $account_sum+=$detail->$const;
                                }
                                //$account_sum=$detail->$const;
                            @endphp
                            @forelse($account->account_extra_components as $extra_component)
                                @php
                                    //unserialize the salary component details
                                    $scdetails=unserialize($detail->sc_details);
                                    $psscdetails=unserialize($detail->ssc_details);
                                @endphp
                                @if($extra_component->source==1 && $extra_component->source=='addition')

                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                            $account_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $account_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==1 && $extra_component->operator=='subtraction')
                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                            $account_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $account_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='addition')
                                    @php
                                        $psscdetails=unserialize($detail->ssc_details);

                                    @endphp
                                    @if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $account_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                        @endphp
                                                    @endif

                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='subtraction')
                                    @php
                                        $psscdetails=unserialize($detail->ssc_details);

                                    @endphp
                                    @if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $account_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                        @endphp
                                                    @endif

                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($extra_component->source==3 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum
                                        $const=$extra_component->payroll_constant;
                                        if($const=='gross_pay'){
                                            $account_sum+=$extra_component->percentage>0?(($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $account_sum+=$extra_component->percentage>0?((($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues))*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues);
                                        }else{
                                            $account_sum+=$extra_component->percentage>0?($detail->$const*$extra_component->percentage)/100:$detail->$const;
                                        }
                                        //$account_sum+=$detail->$const;
                                    @endphp
                                @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                    @php
                                        // add the amount to the account sum
                                        $const=$extra_component->payroll_constant;
                                        if($const=='gross_pay'){
                                            $account_sum-=$extra_component->percentage>0?(($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $account_sum-=$extra_component->percentage>0?((($detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances)-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues))*$extra_component->percentage)/100:$detail->basic_pay+$detail->sc_allowances+$detail->ssc_allowances-($detail->sc_deductions+$detail->ssc_deductions+$detail->paye+$detail->union_dues);
                                        }else{
                                            $account_sum-=$extra_component->percentage>0?($detail->$const*$extra_component->percentage)/100:$detail->$const;
                                        }
                                        //$account_sum+=$detail->$const;
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum


                                            $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                        //$account_sum+=$detail->$const;
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                    @php
                                        // add the amount to the account sum
                                        $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                    @endphp
                                @endif
                            @empty
                            @endforelse
                            @if($account_sum>0)
                                <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                </td>
                            @else
                                <td>0.00</td>
                            @endif


                        @endif
                    @else
                        <td>0.00</td>
                    @endif
                
            @endforeach

             @if ($pp->display_lsa_on_nav_export==1)
                @if( $detail->user->expat==0)
                
                    {{-- check if the was an entry --}}
                    @php
                        $aw_amount=0;
                    @endphp
                     @foreach($lsas as $award)

                     @if ($detail->user->months_of_service <= ($award->max_year*12))
                        @php 
                        $aw_amount=$award->amount/$award->difference/12;
                        
                         break;
                     @endphp
                     @endif
                     @endforeach
                    <td>{{$aw_amount}}</td>
                @else
                <td>0.00</td>
            @endif
            @endif
            <td>{{$detail->user->section?$detail->user->section->name:''}}</td>
            <td>{{$detail->user->job?$detail->user->job->title:''}}</td>
            <td>{{$detail->payroll_type}}</td>
            <td>{{$detail->user->expat==1?'Expatriate':'National'}}</td>
            <td>{{$detail->user->grade?$detail->user->grade->level:''}}</td>
        </tr>
        @php
            $sn++;
        @endphp

        @endforeach


        </tr>
    </tbody>
</table>
