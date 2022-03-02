{{-- Pension --}}
{{-- tax --}}
{{-- loan --}}
<table>
    <thead>
    <tr>
        <th>Code</th>
        <th>date</th>
        <th>GL Code</th>
        <th>Description</th>
        <th>Project Code</th>
        <th>DR</th>
        <th>CR</th>
    </tr>
    </thead>
    <tbody>

    @php
        $section_total=[];
        $credit_total=0;
        $debit_total=0;
        foreach($sections as $section){
            $section_total[$section->id]=0;
        }

            $npp=0;


    @endphp

    @foreach($chart_of_accounts as $account)
        @php  $filteredusers=$allusers; @endphp
        {{-- Check if it is a non payroll provision --}}
        @if($account->uses_group==1)
            @php

                $filteredusers=$allusers->filter(function($filtereduser) use ($account) {

                   if($filtereduser->user_groups->contains('id',$account->group_id)){
                    return $filtereduser;
                   }

                });

            @endphp
        @endif
        @if($account->non_payroll_provision==0)
            {{-- Check display type --}}

            @php
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




            @endphp
            @if($account->display==1)
                {{-- display for cummulative --}}

                {{--display for payroll components--}}
                @if($account->source==1)
                    @php
                        $account_sum=0;
                    @endphp
                    {{--loop through all office payroll details  --}}
                    @foreach($details as $pdetail)
                        @php
                            //unserialize t`he salary component details
                            $scdetails=unserialize($pdetail->sc_details);
                        @endphp
                        {{-- check if the was an entry --}}
                        @if(isset($scdetails['sc_allowances'][$account->salary_component_constant]))
                            @php
                                // add the amount to the account sum
                                $account_sum+=$scdetails['sc_allowances'][$account->salary_component_constant];
                            @endphp

                        @elseif(isset($scdetails['sc_deductions'][$account->salary_component_constant]))
                            @php
                                // add the amount to the account sum
                                $account_sum+=$scdetails['sc_deductions'][$account->salary_component_constant];
                            @endphp
                        @endif
                        @forelse($account->account_extra_components as $extra_component)
                            @php
                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                                $psscdetails=unserialize($pdetail->ssc_details);
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
                                    $psscdetails=unserialize($pdetail->ssc_details);

                                @endphp
                                @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                    $psscdetails=unserialize($pdetail->ssc_details);

                                @endphp
                                @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                        $account_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $account_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $account_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                    //$account_sum+=$pdetail->$const;
                                @endphp
                            @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                @php
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
                                @endphp
                            @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                @php
                                    // add the amount to the account sum


                                        $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                    //$account_sum+=$pdetail->$const;
                                @endphp
                            @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                @php
                                    // add the amount to the account sum
                                    $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                @endphp
                            @endif
                        @empty
                        @endforelse
                    @endforeach
                    @if($account_sum>0)
                        <tr>
                            <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                            <td>{{date('mdy',strtotime($date))}}</td>
                            <td>{{$account->code}}</td>
                            <td>{{$account->description}}-{{date('ym',strtotime($date))}}</td>
                            <td></td>
                            @if($account->type==1)
                                @php
                                    $debit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                @endphp
                                <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                </td>
                                <td></td>
                            @elseif($account->type==0)
                                @php
                                    $credit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                @endphp
                                <td></td>
                                <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                </td>
                            @endif
                        </tr>
                    @endif
                    {{--display for salary components--}}
                @elseif($account->source==2)
                    @php
                        $comp_sum=0;
                    @endphp
                    @foreach($details as $pdetail)
                        @php
                            $psscdetails=unserialize($pdetail->ssc_details);
                            // $comp_sum=0;
                        @endphp
                        @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                            @foreach($specific_salary_component_types as $ct)
                                @if($ct->id==$account->specific_salary_component_type_id)
                                    @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                        @php
                                            // dd($psscdetails);
                                        @endphp
                                        @if($det==$ct->id)
                                            @php
                                                $comp_sum+=$psscdetails['ssc_amount'][$key];

                                            @endphp


                                        @endif

                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        @forelse($account->account_extra_components as $extra_component)
                            @php
                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                                $psscdetails=unserialize($pdetail->ssc_details);
                            @endphp
                            @if($extra_component->source==1 && $extra_component->source=='addition')

                                @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                    @php
                                        // add the amount to the account sum
                                        $comp_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                    @endphp

                                @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                    @php
                                        // add the amount to the account sum
                                       $comp_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                    @endphp
                                @endif
                            @elseif($extra_component->source==1 && $extra_component->operator=='subtraction')
                                @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                    @php
                                        // add the amount to the account sum
                                        $comp_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                    @endphp

                                @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                    @php
                                        // add the amount to the account sum
                                       $comp_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                    @endphp
                                @endif
                            @elseif($extra_component->source==2 && $extra_component->operator=='addition')
                                @php
                                    $psscdetails=unserialize($pdetail->ssc_details);

                                @endphp
                                @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                    @foreach($specific_salary_component_types as $ct)
                                        @if($ct->id==$extra_component->specific_salary_component_type_id)
                                            @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                @php
                                                    // dd($psscdetails);
                                                @endphp
                                                @if($det==$ct->id)
                                                    @php
                                                        $comp_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                    @endphp
                                                @endif

                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @elseif($extra_component->source==2 && $extra_component->operator=='subtraction')
                                @php
                                    $psscdetails=unserialize($pdetail->ssc_details);

                                @endphp
                                @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                    @foreach($specific_salary_component_types as $ct)
                                        @if($ct->id==$extra_component->specific_salary_component_type_id)
                                            @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                @php
                                                    // dd($psscdetails);
                                                @endphp
                                                @if($det==$ct->id)
                                                    @php
                                                        $comp_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
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
                                        $comp_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $comp_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $comp_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                    //$comp_sum+=$pdetail->$const;
                                @endphp
                            @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                @php
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
                                @endphp
                            @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                @php
                                    // add the amount to the account sum


                                        $comp_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                    //$comp_sum+=$pdetail->$const;
                                @endphp
                            @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                @php
                                    // add the amount to the account sum
                                    $comp_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                @endphp
                            @endif
                        @empty
                        @endforelse
                    @endforeach
                    @if($comp_sum>0)
                        <tr>
                            <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                            <td>{{date('mdy',strtotime($date))}}</td>
                            <td>{{$account->code}}</td>
                            <td>{{$account->description}}-{{date('ym',strtotime($date))}}</td>
                            <td></td>
                            @if($account->type==1)
                                @php
                                    $debit_total+= $account->formula>0?($comp_sum*$account->formula)/100:$comp_sum;
                                @endphp
                                <td>{{$account->formula>0?($comp_sum*$account->formula)/100:$comp_sum}}
                                </td>
                                <td></td>
                            @elseif($account->type==0)
                                @php
                                    $credit_total+= $account->formula>0?($comp_sum*$account->formula)/100:$comp_sum;
                                @endphp
                                <td></td>
                                <td>{{$account->formula>0?($comp_sum*$account->formula)/100:$comp_sum}}
                                </td>
                            @endif
                        </tr>
                    @endif
                    {{--display for specific salry component type--}}
                @elseif($account->source==3)
                    @php
                        $account_sum=0;
                    @endphp
                    @foreach($details as $pdetail)
                        {{-- check if the was an entry --}}
                        @php
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
                        @endphp

                        @forelse($account->account_extra_components as $extra_component)
                            @php
                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                                $psscdetails=unserialize($pdetail->ssc_details);
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
                                    $psscdetails=unserialize($pdetail->ssc_details);

                                @endphp
                                @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                    $psscdetails=unserialize($pdetail->ssc_details);

                                @endphp
                                @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                        $account_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                    }elseif($const=='netpay'){
                                        $account_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                    }else{
                                        $account_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                    }
                                    //$account_sum+=$pdetail->$const;
                                @endphp
                            @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                @php
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
                                @endphp
                            @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                @php
                                    // add the amount to the account sum


                                        $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                    //$account_sum+=$pdetail->$const;
                                @endphp
                            @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                @php
                                    // add the amount to the account sum
                                    $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                @endphp
                            @endif
                        @empty
                        @endforelse
                    @endforeach
                    @if($account_sum>0)
                        <tr>
                            <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                            <td>{{date('mdy',strtotime($date))}}</td>
                            <td>{{$account->code}}</td>
                            <td>{{$account->description}}-{{date('ym',strtotime($date))}}</td>
                            <td></td>
                            @if($account->type==1)
                                @php
                                    $debit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                @endphp
                                <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                </td>
                                <td></td>
                            @elseif($account->type==0)
                                @php
                                    $credit_total+= $account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                @endphp
                                <td></td>
                                <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                </td>
                            @endif
                        </tr>
                    @endif
                @endif
            @elseif($account->display==2)
                {{-- display for spread --}}

                @foreach($sections as $section)
                    @php  $section_users=$section->users; @endphp
                    {{-- Check if it is a non payroll provision --}}
                    @if($account->uses_group==1)
                        @php

                            $section_users=$section->users->filter(function($filtereduser) use ($account) {

                               if($filtereduser->user_groups->contains('id',$account->group_id)){
                                return $filtereduser;
                               }

                            });

                        @endphp
                    @endif
                    @php
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
                    @endphp

                    @if($account->source==1)

                        {{--loop through all office payroll details  --}}
                        @foreach($details as $pdetail)
                            @php
                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
                            @endphp
                            {{-- check if the was an entry --}}
                            @if(isset($scdetails['sc_allowances'][$account->salary_component_constant]))
                                @php
                                    // add the amount to the account sum
                                    $section_sum+=$scdetails['sc_allowances'][$account->salary_component_constant];
                                @endphp

                            @elseif(isset($scdetails['sc_deductions'][$account->salary_component_constant]))
                                @php
                                    // add the amount to the account sum
                                    $section_sum+=$scdetails['sc_deductions'][$account->salary_component_constant];
                                @endphp
                            @endif
                            @forelse($account->account_extra_components as $extra_component)
                                @php
                                    //unserialize the salary component details
                                    $scdetails=unserialize($pdetail->sc_details);
                                    $psscdetails=unserialize($pdetail->ssc_details);
                                @endphp
                                @if($extra_component->source==1 && $extra_component->source=='addition')

                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                            $section_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $section_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==1 && $extra_component->operator=='subtraction')
                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                            $section_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $section_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='addition')
                                    @php
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $section_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                        @endphp
                                                    @endif

                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='subtraction')
                                    @php
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $section_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
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
                                            $section_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $section_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                        }else{
                                            $section_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                        }
                                        //$section_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                    @php
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
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum


                                            $section_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                        //$section_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                    @php
                                        // add the amount to the account sum
                                        $section_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                    @endphp
                                @endif
                            @empty
                            @endforelse
                        @endforeach
                        @if($section_sum>0)
                            <tr>
                                <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                                <td>{{date('mdy',strtotime($date))}}</td>
                                <td>{{$account->code}}</td>
                                <td>{{$account->description}}-{{$section->name}}</td>
                                @if ($account->salary_charge=='salary')
                                    <td>{{$section->salary_project_code}}</td>
                                @elseif($account->salary_charge=='charge')
                                    <td>{{$section->charge_project_code}}</td>
                                @else
                                    <td></td>
                                @endif
                                @if($account->type==1)
                                    @php
                                        $debit_total+= $account->formula>0?($section_sum*$account->formula)/100:$section_sum;
                                    @endphp
                                    <td>{{$account->formula>0?($section_sum*$account->formula)/100:$section_sum}}
                                    </td>
                                    <td></td>
                                @elseif($account->type==0)
                                    @php
                                        $credit_total+= $account->formula>0?($section_sum*$account->formula)/100:$section_sum;
                                    @endphp
                                    <td></td>
                                    <td>{{$account->formula>0?($section_sum*$account->formula)/100:$section_sum}}
                                    </td>
                                @endif
                            </tr>
                        @endif
                        {{--display for salary components--}}
                    @elseif($account->source==2)
                        @php
                            // $psscdetails=unserialize($pdetail->ssc_details);

                            $comp_sum=0;
                        @endphp
                        @foreach($details as $pdetail)
                            @php
                                $psscdetails=unserialize($pdetail->ssc_details);
                            @endphp
                            @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                @foreach($specific_salary_component_types as $ct)
                                    @if($ct->id==$account->specific_salary_component_type_id)
                                        @foreach($psscdetails['ssc_component_category'] as $key => $det)

                                            @php
                                                // dd($psscdetails);
                                            @endphp
                                            @if($det==$ct->id)
                                                @php
                                                    $comp_sum+=$psscdetails['ssc_amount'][$key];

                                                @endphp


                                            @endif

                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                            @forelse($account->account_extra_components as $extra_component)
                                @php
                                    //unserialize the salary component details
                                    $scdetails=unserialize($pdetail->sc_details);
                                    $psscdetails=unserialize($pdetail->ssc_details);
                                @endphp
                                @if($extra_component->source==1 && $extra_component->source=='addition')

                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                            $comp_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $comp_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==1 && $extra_component->operator=='subtraction')
                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                            $comp_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $comp_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='addition')
                                    @php
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $comp_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                        @endphp
                                                    @endif

                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='subtraction')
                                    @php
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $comp_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
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
                                            $comp_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $comp_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                        }else{
                                            $comp_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                        }
                                        //$comp_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                    @php
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
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum


                                            $comp_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                        //$comp_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                    @php
                                        // add the amount to the account sum
                                        $comp_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                    @endphp
                                @endif
                            @empty
                            @endforelse
                        @endforeach
                        @if($comp_sum>0)
                            <tr>
                                <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                                <td>{{date('mdy',strtotime($date))}}</td>
                                <td>{{$account->code}}</td>
                                <td>{{$account->description}}-{{$section->name}}</td>
                                @if ($account->salary_charge=='salary')
                                    <td>{{$section->salary_project_code}}</td>
                                @elseif($account->salary_charge=='charge')
                                    <td>{{$section->charge_project_code}}</td>
                                @else
                                    <td></td>
                                @endif
                                @if($account->type==1)
                                    @php
                                        $debit_total+= $account->formula>0?($comp_sum*$account->formula)/100:$comp_sum;
                                    @endphp
                                    <td>{{$account->formula>0?($comp_sum*$account->formula)/100:$comp_sum}}
                                    </td>
                                    <td></td>
                                @elseif($account->type==0)
                                    @php
                                        $credit_total+= $account->formula>0?($comp_sum*$account->formula)/100:$comp_sum;
                                    @endphp
                                    <td></td>
                                    <td>{{$account->formula>0?($comp_sum*$account->formula)/100:$comp_sum}}
                                    </td>
                                @endif
                            </tr>
                        @endif
                        {{--display for specific salry component type--}}
                    @elseif($account->source==3)
                        @php
                            $section_sum=0;
                        @endphp
                        @foreach($details as $pdetail)
                            {{-- check if the was an entry --}}
                            @php
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
                            @endphp
                            @forelse($account->account_extra_components as $extra_component)
                                @php
                                    //unserialize the salary component details
                                    $scdetails=unserialize($pdetail->sc_details);
                                    $psscdetails=unserialize($pdetail->ssc_details);
                                @endphp
                                @if($extra_component->source==1 && $extra_component->source=='addition')

                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $section_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                          $section_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==1 && $extra_component->operator=='subtraction')
                                    @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                           $section_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        @endphp

                                    @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                        @php
                                            // add the amount to the account sum
                                          $section_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                        @endphp
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='addition')
                                    @php
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $section_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                        @endphp
                                                    @endif

                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($extra_component->source==2 && $extra_component->operator=='subtraction')
                                    @php
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                        @foreach($specific_salary_component_types as $ct)
                                            @if($ct->id==$extra_component->specific_salary_component_type_id)
                                                @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                                    @php
                                                        // dd($psscdetails);
                                                    @endphp
                                                    @if($det==$ct->id)
                                                        @php
                                                            $section_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
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
                                           $section_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                           $section_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                        }else{
                                           $section_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                        }
                                        //$account_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                    @php
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
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum


                                           $section_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                        //$account_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                    @php
                                        // add the amount to the account sum
                                       $section_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                    @endphp
                                @endif
                            @empty
                            @endforelse
                        @endforeach
                        @if($section_sum>0)
                            <tr>
                                <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                                <td>{{date('mdy',strtotime($date))}}</td>
                                <td>{{$account->code}}</td>
                                <td>{{$account->description}}-{{$section->name}}</td>
                                @if ($account->salary_charge=='salary')
                                    <td>{{$section->salary_project_code}}</td>
                                @elseif($account->salary_charge=='charge')
                                    <td>{{$section->charge_project_code}}</td>
                                @else
                                    <td></td>
                                @endif
                                @if($account->type==1)
                                    @php
                                        $debit_total+= $account->formula>0?($section_sum*$account->formula)/100:$section_sum;
                                    @endphp
                                    <td>{{$account->formula>0?($section_sum*$account->formula)/100:$section_sum}}
                                    </td>
                                    <td></td>
                                @elseif($account->type==0)
                                    @php
                                        $credit_total+= $account->formula>0?($section_sum*$account->formula)/100:$section_sum;
                                    @endphp
                                    <td></td>
                                    <td>{{$account->formula>0?($section_sum*$account->formula)/100:$section_sum}}
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @endif
                @endforeach
                {{--display for all--}}
                {{--display for expatriates--}}
                {{--display for nationals--}}
            @elseif($account->display==3)
                {{-- display for individual --}}
                @php  $filteredusers=$allusers; @endphp
                {{-- Check if it is a non payroll provision --}}
                @if($account->uses_group==1)
                    @php

                        $filteredusers=$allusers->filter(function($filtereduser) use ($account) {

                           if($filtereduser->user_groups->contains('id',$account->group_id)){
                            return $filtereduser;
                           }

                        });

                    @endphp
                @endif

                {{-- Check display type --}}

                @php
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




                @endphp

                @foreach ($users as $user)
                    @php
                        $pdetail=$details->firstWhere('user_id', $user);
                        $account_sum=0;
                    @endphp
                    @if($pdetail)
                        @if($account->source==1)
                            @php
                                //unserialize the salary component details
                                $scdetails=unserialize($pdetail->sc_details);
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
                                    $scdetails=unserialize($pdetail->sc_details);
                                    $psscdetails=unserialize($pdetail->ssc_details);
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
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                            $account_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $account_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                        }else{
                                            $account_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                        }
                                        //$account_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                    @php
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
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum


                                            $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                        //$account_sum+=$pdetail->$const;
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
                                <tr>
                                    <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                                    <td>{{date('mdy',strtotime($date))}}</td>
                                    <td>{{$account->code}}</td>
                                    <td>{{$account->description}}-{{date('ym',strtotime($date))}}-{{isset($pdetail->user->name) ? $pdetail->user->name : '' }}</td>
                                    <td></td>
                                    @if($account->type==1)
                                        @php
                                            $debit_total+=$account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                        @endphp
                                        <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                        </td>
                                        <td></td>
                                    @elseif($account->type==0)
                                        @php
                                            $credit_total+=$account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                        @endphp
                                        <td></td>
                                        <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        @elseif($account->source==2)
                            @php
                                $psscdetails=unserialize($pdetail->ssc_details);

                                $individual_sum=0;
                            @endphp
                            @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                        $scdetails=unserialize($pdetail->sc_details);
                                        $psscdetails=unserialize($pdetail->ssc_details);
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
                                            $psscdetails=unserialize($pdetail->ssc_details);

                                        @endphp
                                        @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                            $psscdetails=unserialize($pdetail->ssc_details);

                                        @endphp
                                        @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                                $individual_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                            }elseif($const=='netpay'){
                                                $individual_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                            }else{
                                                $individual_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                            }
                                            //$individual_sum+=$pdetail->$const;
                                        @endphp
                                    @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                        @php
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
                                        @endphp
                                    @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                        @php
                                            // add the amount to the account sum


                                                $individual_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                            //$individual_sum+=$pdetail->$const;
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
                                    <tr>
                                        <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                                        <td>{{date('mdy',strtotime($date))}}</td>
                                        <td>{{$account->code}}</td>
                                        <td>{{$account->description}}-{{date('ym',strtotime($date))}}-{{$pdetail->user->name}}</td>
                                        <td></td>
                                        @if($account->type==1)
                                            @php
                                                $debit_total+=$account->formula>0?($individual_sum*$account->formula)/100:$individual_sum;
                                            @endphp
                                            <td>{{$account->formula>0?($individual_sum*$account->formula)/100:$individual_sum}}
                                            </td>
                                            <td></td>
                                        @elseif($account->type==0)
                                            @php
                                                $credit_total+=$account->formula>0?($individual_sum*$account->formula)/100:$individual_sum;
                                            @endphp
                                            <td></td>
                                            <td>{{$account->formula>0?($individual_sum*$account->formula)/100:$individual_sum}}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @else

                            @endif
                        @elseif($account->source==3)
                            @php
                                $account_sum=0;
                            @endphp
                            @php
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
                            @endphp
                            @forelse($account->account_extra_components as $extra_component)
                                @php
                                    //unserialize the salary component details
                                    $scdetails=unserialize($pdetail->sc_details);
                                    $psscdetails=unserialize($pdetail->ssc_details);
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
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                        $psscdetails=unserialize($pdetail->ssc_details);

                                    @endphp
                                    @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
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
                                            $account_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                        }elseif($const=='netpay'){
                                            $account_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                        }else{
                                            $account_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                        }
                                        //$account_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                                    @php
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
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                                    @php
                                        // add the amount to the account sum


                                            $account_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                        //$account_sum+=$pdetail->$const;
                                    @endphp
                                @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                                    @php
                                        // add the amount to the account sum
                                        $account_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                                    @endphp
                                @endif
                            @empty
                            @endforelse
                            <tr>
                                <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                                <td>{{date('mdy',strtotime($date))}}</td>
                                <td>{{$account->code}}</td>
                                <td>{{$account->description}}-{{date('ym',strtotime($date))}}-{{$pdetail->user->name}}</td>
                                <td></td>
                                @if($account->type==1)
                                    @php
                                        $debit_total+=$account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                    @endphp
                                    <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                    </td>
                                    <td></td>
                                @elseif($account->type==0)
                                    @php
                                        $credit_total+=$account->formula>0?($account_sum*$account->formula)/100:$account_sum;
                                    @endphp
                                    <td></td>
                                    <td>{{$account->formula>0?($account_sum*$account->formula)/100:$account_sum}}
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @endif

                @endforeach
                {{--display for all--}}
                {{--display for expatriates--}}
                {{--display for nationals--}}
            @endif
        @elseif($account->non_payroll_provision==1)


            @foreach($sections as $section)
                @php  $section_users=$section->users; @endphp
                {{-- Check if it is a non payroll provision --}}
                @if($account->uses_group==1)
                    @php

                        $section_users=$section->users->filter(function($filtereduser) use ($account) {

                           if($filtereduser->user_groups->contains('id',$account->group_id)){
                            return $filtereduser;
                           }

                        });

                    @endphp
                @endif

                @php
                    $users=$section_users->where('expat',0)->pluck('id');
                   $details= $payroll_details->whereIn('user_id', $users);
                   $section_sum=0;
                @endphp
                @foreach($details as $pdetail)
                    {{-- check if the was an entry --}}
                    @php
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
                    @endphp
                    @forelse($account->account_extra_components as $extra_component)
                        @php
                            //unserialize the salary component details
                            $scdetails=unserialize($pdetail->sc_details);
                            $psscdetails=unserialize($pdetail->ssc_details);
                        @endphp
                        @if($extra_component->source==1 && $extra_component->source=='addition')

                            @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                @php
                                    // add the amount to the account sum
                                   $section_sum+=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                @endphp

                            @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                @php
                                    // add the amount to the account sum
                                  $section_sum+=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                @endphp
                            @endif
                        @elseif($extra_component->source==1 && $extra_component->operator=='subtraction')
                            @if(isset($scdetails['sc_allowances'][$extra_component->salary_component_constant]))
                                @php
                                    // add the amount to the account sum
                                   $section_sum-=$extra_component->percentage>0?($scdetails['sc_allowances'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                @endphp

                            @elseif(isset($scdetails['sc_deductions'][$extra_component->salary_component_constant]))
                                @php
                                    // add the amount to the account sum
                                  $section_sum-=$extra_component->percentage>0?($scdetails['sc_deductions'][$extra_component->salary_component_constant]*$extra_component->percentage)/100:$scdetails['sc_deductions'][$extra_component->salary_component_constant];
                                @endphp
                            @endif
                        @elseif($extra_component->source==2 && $extra_component->operator=='addition')
                            @php
                                $psscdetails=unserialize($pdetail->ssc_details);

                            @endphp
                            @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                @foreach($specific_salary_component_types as $ct)
                                    @if($ct->id==$extra_component->specific_salary_component_type_id)
                                        @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                            @php
                                                // dd($psscdetails);
                                            @endphp
                                            @if($det==$ct->id)
                                                @php
                                                    $section_sum+=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
                                                @endphp
                                            @endif

                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @elseif($extra_component->source==2 && $extra_component->operator=='subtraction')
                            @php
                                $psscdetails=unserialize($pdetail->ssc_details);

                            @endphp
                            @if($pdetail->ssc_allowances>0 ||$pdetail->ssc_deductions>0)
                                @foreach($specific_salary_component_types as $ct)
                                    @if($ct->id==$extra_component->specific_salary_component_type_id)
                                        @foreach($psscdetails['ssc_component_category'] as $key => $det)
                                            @php
                                                // dd($psscdetails);
                                            @endphp
                                            @if($det==$ct->id)
                                                @php
                                                    $section_sum-=$extra_component->percentage>0?($psscdetails['ssc_amount'][$key]*$extra_component->percentage)/100:$psscdetails['ssc_amount'][$key];
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
                                   $section_sum+=$extra_component->percentage>0?(($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances;
                                }elseif($const=='netpay'){
                                   $section_sum+=$extra_component->percentage>0?((($pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances)-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues))*$extra_component->percentage)/100:$pdetail->basic_pay+$pdetail->sc_allowances+$pdetail->ssc_allowances-($pdetail->sc_deductions+$pdetail->ssc_deductions+$pdetail->paye+$pdetail->union_dues);
                                }else{
                                   $section_sum+=$extra_component->percentage>0?($pdetail->$const*$extra_component->percentage)/100:$pdetail->$const;
                                }
                                //$account_sum+=$pdetail->$const;
                            @endphp
                        @elseif($extra_component->source==3 && $extra_component->operator=='subtraction')
                            @php
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
                            @endphp
                        @elseif($extra_component->source==4 && $extra_component->operator=='addition')
                            @php
                                // add the amount to the account sum


                                   $section_sum+=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;

                                //$account_sum+=$pdetail->$const;
                            @endphp
                        @elseif($extra_component->source==4 && $extra_component->operator=='subtraction')
                            @php
                                // add the amount to the account sum
                               $section_sum-=$extra_component->percentage>0?($extra_component->amount*$extra_component->percentage)/100:$extra_component->amount;
                            @endphp
                        @endif
                    @empty
                    @endforelse

                @endforeach
                @php
                    if($section_sum>0){
                        if($account->formula>0){
                            $section_total[$section->id]+= ($section_sum*$account->formula)/100;
                            $npp+=($section_sum*$account->formula)/100;
                        }else{
                            $section_total[$section->id]+=$section_sum;
                            $npp+=$section_sum;
                        }

                    }


                @endphp
            @endforeach
            {{-- end of non payroll provisions --}}
        @endif
        {{-- end of chart of accounts loop --}}
    @endforeach
    {{-- Long Service Award Contribution display --}}

    @if ($pp->display_lsa_on_nav_export==1)



        @foreach($sections as $section)
            {{-- non payroll provisions --}}

            @php  $section_users=$section->users; @endphp
            {{-- Check if it is a non payroll provision --}}
            @if($account->uses_group==1)
                @php

                    $section_users=$section->users->filter(function($filtereduser) use ($account) {

                       if($filtereduser->user_groups->contains('id',$account->group_id)){
                        return $filtereduser;
                       }

                    });

                @endphp
            @endif

            @php
                $users=$section_users->where('expat',0)->pluck('id');
               $details= $payroll_details->whereIn('user_id', $users);
               $section_sum=0;
            @endphp
            @foreach($details as $pdetail)
                {{-- check if the was an entry --}}

                @foreach($lsas as $award)

                    @if ($pdetail->user->months_of_service <= ($award->max_year*12))

                        @php
                            $section_sum+=$award->amount/$award->difference/12;
                            break;
                        @endphp
                    @endif



                @endforeach
            @endforeach
            @php
                $npp +=  $section_sum;
                    $section_sum+= $section_total[$section->id];

            @endphp
            <tr>
                <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
                <td>{{date('mdy',strtotime($date))}}</td>
                <td>6414111</td>
                <td>Non Payroll Provision-{{$section->name}}</td>

                <td>{{$section->salary_project_code}}</td>



                <td>{{$section_sum}}
                </td>
                <td></td>

            </tr>
        @endforeach
    @else{
    @foreach($sections as $section)
        <tr>
            <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
            <td>{{date('mdy',strtotime($date))}}</td>
            <td>6414111</td>
            <td>Non Payroll Provision-{{$section->name}}</td>

            <td>{{$section->salary_project_code}}</td>



            <td>{{$section_total[$section->id]}}
            </td>
            <td></td>

        </tr>
    @endforeach
    }
    @endif

    <tr>
        <td>PAYROLL-{{date('ym',strtotime($date))}}</td>
        <td>{{date('mdy',strtotime($date))}}</td>
        <td>4285060</td>
        <td>Non Payroll Provision-National Staff</td>

        <td></td>


        <td></td>
        <td>{{$npp}}
        </td>

    </tr>
    </tbody>
</table>
