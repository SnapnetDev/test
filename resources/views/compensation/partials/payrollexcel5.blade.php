<table>
    <thead>

    <tr>
        <th></th>
        <th>Employee Number</th>
        <th>Employee Name</th>

        <th>Basic pay</th>
        
        @foreach ($components as $key=>$component)
           @if($component['type']==1)  
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
            @if($component['type']==0)  
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
        <th>Gross pay</th>
        <th>Net Pay</th>
        <th>Payroll Type</th>

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
                
                    @if($component['type']==1)
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
                
                    @if($component['type']==0)
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
            <td>{{($detail->basic_pay+$allowances)}}</td>
            <td>{{($detail->basic_pay+$allowances-($deductions+$detail->paye+$detail->union_dues))}}</td>

            {{-- display for individual --}}

        

           
            <td>{{$detail->payroll_type}}</td>
        </tr>
        @php
            $sn++;
        @endphp

        @endforeach


        </tr>
    </tbody>
</table>
