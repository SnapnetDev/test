@php
    $pdetails=unserialize($detail->details);
    // $num=count($days);
@endphp
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$detail->user->name}}-{{$detail->payroll->month}}{{$detail->payroll->year}}</title>


    <style type="text/css">
        @page { size: 596pt 842pt; }
        body {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            margin-top:-10px;
            /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
            width: 18cm;
            height: 29.7cm;
            padding-left: 20px;
            padding-top:70px;
            font-family: arial, sans-serif;
            border-top:10px #00a4de solid;
            border-left:10px #00a4de solid;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        .small-font{
            font-size: 10px;
        }

        td,  th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 2px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
        h1,h4 {
            font-family: arial,sans-serif;
        }
        #header td, #header th {
            border: 0px solid #dddddd;
            text-align: left;
            padding: 2px;
        }
        header {
            position: fixed;
            top: 20px;
            float:left;
            height: 50px;


        }

        #watermark {
            position: fixed;

            /**
                Set a position in the page for your image
                This should center it vertically
            **/
            top:65%;
            width :auto;
            height: 3.5rem;
            opacity:0.1;
            /*-ms-transform: rotate(310deg);  IE 9 */
            /*-webkit-transform: rotate(310deg);  Safari 3-8 */
            /*transform: rotate(310deg);*/
            padding:100px;

            /** Your watermark should be behind every content**/
            z-index:  -1000;
        }

    </style>
</head>
<body>
<div id="watermark">
    <img src="{{ asset('logo_0.png') }}" style="height:300%;width:auto;;background-color:#000" />
</div>
<header style="float: left;padding:5px"><img src="{{ asset('logo_0.png') }}" style="height: 3.5rem;background-color:#000; width: auto;"></header>
<div class="cont" >


<!--<strong style="text-transform: capitalize; color:red; font-size:10px">{{$company->name}}</strong>-->

    <table style="width:100%" id="header">
        <tr>
            <td style="width:33%">
            <span style="font-size:10px;">
          {{$company->address}}
         
       <br>
       
      {{ $company->email}}
       </span>


            </td>
            <td style="width:34%">
                <center style="background: #337ab7;color: #fff;"><h4>PAYSLIP</h4></center>
            </td>
            <td style="width:33%">
                {{-- <img style="width: 150px;height:auto;" src="{{ asset('storage/'.$logo) }}" class="img-responsive"> --}}
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="text-align:left" class="small-font">Employee's Name</td>
            <td style="text-align:right" class="small-font">{{$detail->user->name}}</td>
        </tr>
        <tr>
            <td style="text-align:left" class="small-font">Employee's Designation</td>
            <td style="text-align:right" class="small-font">{{$detail->user->job?$detail->user->job->level:""}}</td>
        </tr>
        {{--    <tr>
               <td style="text-align:left" class="small-font">Date  of Employment</td>
               <td style="text-align:right" class="small-font">{{date('F, j Y',strtotime($detail->user->hiredate))}}</td>
           </tr>
           --}}
    </table>
    <hr>
    <table>
        <tr>
            <td style="text-align:left" class="small-font">Payslip for the period of</td>
            <td style="text-align:right" class="small-font">{{date("F", mktime(0, 0, 0, $detail->payroll->month, 10))}} {{$detail->payroll->year}}</td>
        </tr>

    </table>


    <table class="payslip small-font" style="width:100%;">
        <thead class="head">
        <tr class="bg-primary">
            <th style="width: 33%;"></th>
            <th style="width: 34%; text-align: center;"  class="bg-primary"><span>Employee Details</span></th>
            <th style="width: 33%;"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            @php

                $all_allowances=0;
            @endphp
            {{-- <td>


              Grade: @if($level!='') {{$gc[0] }}@endif
            </td>
            <td>
              Level @if($level!='') {{$gc[1] }}@endif
            </td> --}}
            <td>
                Grade:@if($detail->user->grade){{$detail->user->only_grade}} @endif
            </td>
            <td>

            </td>
        </tr>
        </tbody>
    </table>
    <div class="table-responsive">


        <table class="table table-striped small-font">

            <tbody>
            <tr>
                <th colspan="3" style="text-align:center;">Allowances</th>
            </tr>
            <tr>
                <th style="width: 50%;">Item</th>
                <th >Days</th>
                <th >Amount</th>
            </tr>
            <tr>

                <th style="width: 50%;">Basic Pay</th>
                <td></td>
                <td style="text-align: right">{{number_format($detail->basic_pay,2)}}</td>
                @php
                    $all_allowances+=$detail->basic_pay;
                @endphp
            </tr>
            @foreach($pdetails['allowances'] as $key=>$allowance)
                <tr>

                    <th style="width: 50%;">{{$pdetails['component_names'][$key]}}</th>


                    @php
                        if($detail->user->payroll_type=='project' and $detail->user->project_salary_category){
                        $component=$detail->user->project_salary_category->paceSalaryComponents()->where('constant',$key)->first();
                        if($component){
                        $timesheet=$component->project_salary_timesheets()->where(['for'=>$detail->payroll->for,'user_id'=>$detail->user->id])->first();
                        if($timesheet){
                          echo '<td>'.$timesheet->days.'</td>';
                        }else{
                        echo '<td>-</td>';
                        }
                        }else{
                         echo '<td>-</td>';
                        }
                        }else{
                         echo '<td>-</td>';
                        }
                    @endphp

                    <td style="text-align: right">{{number_format($allowance,2)}}</td>
                    @php
                        $all_allowances+=$allowance;
                    @endphp

                </tr>
            @endforeach
            <tr>

                <th style="width: 50%;"><strong>Gross Pay</strong></th>
                <th></th>
                <td style="text-align: right"><strong>{{number_format($all_allowances,2)}}</strong></td>

            </tr>
            </tbody>

        </table>


        <table class="table table-striped small-font">

            <tbody>
            <tr>
                <th colspan="2" style="text-align:center;">Deductions</th>
            </tr>
            @foreach($pdetails['deductions'] as $key=>$deduction)
                <tr>

                    <th style="width: 50%;">{{$pdetails['component_names'][$key]}}</th>
                    <td style="text-align: right">-{{number_format($deduction,2)}}</td>

                </tr>
            @endforeach
            <tr>
                <th style="width: 50%;">Income Tax</th>
                <td style="text-align: right">-{{number_format($detail->paye,2)}}</td>
            </tr>
            @if($detail->user->union)
                <tr>
                    <th style="width: 50%;">Union Dues</th>
                    <td style="text-align: right">-{{number_format($detail->union_dues,2)}}</td>
                </tr>
            @endif
            </tbody>

        </table>
        <hr>

        <table class="table table-striped small-font">

            <tbody>

            <tr>

                <th style="width: 50%;">Net Salary</th>
                <th style="text-align: right">N{{number_format(($detail->basic_pay+$detail->allowances)-($detail->deductions+$detail->paye+$detail->union_dues),2)}}</th>

            </tr>

            </tbody>

        </table>

    </div>
</div>
</body>
</html>
