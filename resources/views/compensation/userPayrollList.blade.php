 @extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
 <link rel="stylesheet" href="{{ asset('global/vendor/morris/morris.css')}}">
 <style type="text/css">
   thead {
    background-color: #62A8EA !important;
   
}
thead tr th{
   color:#ffffff !important;
  }
 </style>
}
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">{{__('Employee Payroll')}}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active">{{__('Employee Payroll')}}</li>
      </ol>
      <div class="page-header-actions">
        <div class="row no-space w-250 hidden-sm-down">

          <div class="col-sm-6 col-xs-12">
            <div class="counter">
              <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

            </div>
          </div>
          <div class="col-sm-6 col-xs-12">
            <div class="counter">
              <span class="counter-number font-weight-medium" id="time"></span>
            </div>
          </div>
        </div>
      </div>
  </div>
    
    <div class="page-content container-fluid bg-white">
 <div class="col-xs-12 col-md-12">
              <!-- Widget Timeline -->
              <div class="card card-shadow card-responsive" id="widgetTimeline">
                <div class="card-block p-0">
                  <!--<table data-toggle="table" class="table table-striped" data-mobile-responsive="true" data-pagination="true" data-search="true">-->
                  <div class="clearfix"><br/></div>
                  <table class="table table-hover tabletable-striped datatable" id="data_table">
                      <thead> 
                          <tr>
                              <th>S.No</th>
                              <th>Employee No</th> 
                              <th>Name</th> 
                              <th>Grade</th>
                              <th>Gross Pay</th>
                              <th>Month - Year</th>                             
                              <th>Action</th> 
                              
                          </tr> 
                      </thead> 
                      
                      <tbody> 
                      <?php $sn = 1;  ?>

                    
                      @foreach (Auth::user()->payroll_details()->whereHas('payroll')->get() as $detail)
                        <tr>
                          <td>{{$sn}}</td>
                        <td>{{$detail->user->emp_num}}</td>
                        <td>{{$detail->user->name}}</td>
                        <td>{{$detail->user->user_grade?$detail->user->user_grade->level:''}}</td>
                        <td>{{$detail->gross_pay}}</td>
                        
                        <td>{{date("F", mktime(0, 0, 0, $detail->payroll->month, 10))}}-{{$detail->payroll->year}}</td>

                        <td> @if($detail->payroll->payslip_issued==1)<a target="_blank"  class="  btn btn-sm btn-dark waves-effect text-center" href="{{ url('compensation/download_payslip?id='.$detail->id) }}"><i class=" icon fa fa-download" aria-hidden="true" title="download payslip"></i>Download Payslip</a>@endif</td>
                      </tr>
                      @php
                        $sn++;
                      @endphp
                      @endforeach
                             
                      </tbody> 
                      
                  </table>    
                        
                </div>
              </div>          
              <!-- End Widget Timeline -->
            </div>

            </div> 

        </div>
</div>
  <!-- End Page -->
  
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
  <script src="{{ asset('global/vendor/raphael/raphael-min.js')}}"></script>
  <script src="{{ asset('global/vendor/morris/morris.min.js')}}"></script>
  <script type="text/javascript">
      $(document).ready(function() {
    $('.datepicker').datepicker({
    autoclose: true,
    format:'mm-yyyy',
     viewMode: "months", 
    minViewMode: "months"
});
     $('.datatable').DataTable( );
    
    
    });
     

  </script>
@endsection