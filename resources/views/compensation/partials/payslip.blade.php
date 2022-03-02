@php
  $pdetails=unserialize($detail->details);
  // $num=count($days);
@endphp
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payslip</title>
   
    
      <style type="text/css">
      body {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            margin-top:10px;
            /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
            width: 18cm;
            height: 29.7cm; 
            padding: 0px;
            font-family: arial, sans-serif;
          }
         
            table {
              font-family: arial, sans-serif;
              border-collapse: collapse;
              width: 100%;
          }
          .small-font{
            font-size: 12px;
          }

           td,  th {
              border: 1px solid #dddddd;
              text-align: left;
              padding: 8px;
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
              padding: 8px;
          }
          header {
                position: fixed;
                top: -40px;
                float:right;
                height: 50px;

              
            }
           
            #watermark {
                position: fixed;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
               top:45%;
               width :100%;
               height :100%;
               opacity:0.1;
               /*-ms-transform: rotate(310deg);  IE 9 */
              /*-webkit-transform: rotate(310deg);  Safari 3-8 */
              /*transform: rotate(310deg);*/
              padding:300px;

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
          
      </style>
  </head>
  <body>
      <div id="watermark">
            {{-- <img src="{{url('uploads/logo').$company->logo}}" /> --}}
        </div>
     {{-- <header style="float: right;"><img src="{{url('uploads/logo').$company->logo}}" style="height: 2rem;background-color:#fff; width: auto;"></header> --}}
      {{ asset('logo_0.png') }}
      <div class="cont">
    <center>
        
        <h2 style="text-transform: uppercase;">{{$company->name}}</h2></center>
   <hr style="height:5px;background-color:#f00;">
    <table style="width:100%" id="header">
      <tr>
        <td style="width:33%">
          {{$company->address}}
       <br>
       
      {{ $company->email}}
       <br>
       
        </td>
        <td style="width:34%">
          <center style="background: #337ab7;color: #fff;"><h2>PAYSLIP</h2></center>
        </td>
        <td style="width:33%">
          {{-- <img style="width: 150px;height:auto;" src="{{ asset('storage/'.$logo) }}" class="img-responsive"> --}}
        </td>
      </tr>
    </table>

   <center><h4 class="bg-primary text-center" style="background: #337ab7;color: #fff; padding: 5px 0px; ">Payslip of <strong>{{$detail->user->name}}</strong> in {{date("F", mktime(0, 0, 0, $detail->payroll->month, 10))}} {{$detail->payroll->year}}</h4></center>
  <table class="payslip" style="width:100%;">
    <thead class="head">
      <tr class="bg-primary">
        <th style="width: 33%;"></th>
        <th style="width: 34%; text-align: center;"  class="bg-primary"><span>Employee Details</span></th>
        <th style="width: 33%;"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
      @if($detail->user->grade)
        @php
        $level='';
          if ($detail->user->promotionHistories) {
           $level=$detail->user->promotionHistories()->latest()->first()->grade->level;
            $gc = explode("-", $level);
          }
            @endphp
        {{-- <td>

          
          Grade: @if($level!='') {{$gc[0] }}@endif
        </td>
        <td>
          Level @if($level!='') {{$gc[1] }}@endif
        </td> --}}
        <td>
        Grade:{{$detail->user->only_grade}}{{$detail->user->only_level?'-'.$detail->user->only_level:''}}
        </td>
        @endif
        <td>
          DEPT:{{$detail->user->job?$detail->user->job->department->name:''}}
        </td>
      </tr>
    </tbody>
  </table>
  <div class="table-responsive">
<table class="table table-striped small-font">
  
  <tbody>
    <tr>
      
      <th style="width: 70%;">Gross Pay</th>
      <td style="text-align: right">N{{number_format($detail->gross_pay,2)}}</td>
      
    </tr>
  </tbody>
  
</table>

<table class="table table-striped small-font">
  
  <tbody>
      <tr>
          <th colspan="2" style="text-align:center;">Allowances</th>
      </tr>
    <tr>
      
      <th style="width: 70%;">Basic Pay</th>
      <td style="text-align: right">N{{number_format($detail->basic_pay,2)}}</td>
      
    </tr>
    @foreach($pdetails['allowances'] as $key=>$allowance)
    <tr>
      
      <th style="width: 70%;">{{$pdetails['component_names'][$key]}}</th>
      <td style="text-align: right">N{{number_format($allowance,2)}}</td>
      
    </tr>
    @endforeach
  </tbody>
  
</table>


<table class="table table-striped small-font">
  
  <tbody>
      <tr>
          <th colspan="2" style="text-align:center;">Deductions</th>
      </tr>
    @foreach($pdetails['deductions'] as $key=>$deduction)
    <tr>
      
      <th style="width: 70%;">{{$pdetails['component_names'][$key]}}</th>
      <td style="text-align: right">-N{{number_format($deduction,2)}}</td>
      
    </tr>
    @endforeach
    <tr>
      <th style="width: 70%;">Income Tax</th>
      <td style="text-align: right">-N{{number_format($detail->paye,2)}}</td>
    </tr>
  </tbody>
  
</table>
<hr>

<table class="table table-striped small-font">
  
  <tbody>
   
    <tr>
      
      <th style="width: 70%;">Net Salary</th>
      <th style="text-align: right">N{{number_format(($detail->basic_pay+$detail->allowances)-($detail->deductions+$detail->paye),2)}}</th>
      
    </tr>
   
  </tbody>
  
</table>
</div>
</div>
  </body>
</html>
