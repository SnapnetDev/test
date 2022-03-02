@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/examples/css/charts/chartjs.css')}}">

<style type="text/css">
  a.list-group-item:hover {
    text-decoration: none;
    background-color: #26a69a;
    color:#fff;

}

  a.list-group-item:hover h4 {
    color:#fff;
    background-color: #26a69a;

}
.panel-title{
  font-size:14px !important;
}
.list-group-item-heading {
  font-size:14px !important;
}


.bouton-image:before {
    content: "";
    width: 16px;
    height: 16px;
    display: inline-block;
    margin-right: 5px;
    vertical-align: text-top;
    background-color: transparent;
    background-position : center center;
    background-repeat:no-repeat;
}

.monBouton:before{
     background-image : url({{ asset('assets/images/microsoft-sharepoint.png')}});
}


</style>
@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <div>
          
          <h1 class="page-title">Dashboard</h1>
      </div>
      
      
      <div class="page-header-actions">
    <div class="row no-space w-250 hidden-sm-down">

      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

        </div>
      </div>
    </div>
  </div>
    </div>
    <div class="page-content container-fluid">
        
      <div class="row">

          <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{url('userprofile')}}" type="button" class="btn btn-floating btn-sm btn-dark">
                  <i class="icon md-account-box"></i>
                </a>
                <span class="m-l-15 font-weight-400">Profile</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
           <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{ url('performances/employee?id='.Auth::user()->id) }}"  class="btn btn-floating btn-sm btn-success">
                  <i class="fa fa-signal"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-success">Performance</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
           <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{ url('compensation/user_payroll_list') }}" type="button" class="btn btn-floating btn-sm btn-primary">
                  <i class="icon fa fa-list"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-primary">Payslips</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
           <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{ route('attendance.user',['user_id'=>Auth::user()->id]) }}"  class="btn btn-floating btn-sm btn-success">
                  <i class="icon md-calendar-alt"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-success">My Attendance</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
           <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{ url('leave/myrequests') }}"  class="btn btn-floating btn-sm btn-primary">
                  <i class="fa fa-calendar"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-primary">Leave Requests</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
           <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{ url('leave/approvals') }}"  class="btn btn-floating btn-sm btn-warning">
                  <i class="icon md-calendar-check"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-warning">Leave Approvals</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
          @if(Auth::user()->has('my_departments'))
           <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{ url('leave/department_approvals') }}"  class="btn btn-floating btn-sm btn-success">
                  <i class="icon md-calendar-check"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-success">Department Leave Approvals</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
          @endif
          @if(Auth::user()->role->permissions->contains('constant', 'run_payroll'))
          <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{ url('compensation') }}"  class="btn btn-floating btn-sm btn-dark">
                  <i class="fa fa-money"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-dark">Compensation and Benefits </span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
          @endif
           {{-- <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <button onclick="url('https://snapnet.hcmatrix.com/employee/performance')" type="button" class="btn btn-floating btn-sm btn-info">
                  <i class="icon md-accounts-list"></i>
                </button>
                <span class="m-l-15 font-weight-400 text-info">Job Openings</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div> --}}
          <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="https://primeatlantic.sharepoint.com" type="button" class="btn btn-floating btn-sm btn-info">
                  <i class="fa fa-share-alt"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-info">Sharepoint</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
          <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="sip:"  class="btn btn-floating btn-sm btn-info">
                  <i class="fa fa-skype"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-info">Skype For Business</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
          @if(Auth::user()->role->permissions->contains('constant', 'manage_user') && Auth::user()->role->manages=='all')
          <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <div class="card card-shadow card-shadow1">
              <div class="card-block bg-white p-30">
                <a href="{{url('users')}}"  class="btn btn-floating btn-sm btn-success">
                  <i class="fa fa-users"></i>
                </a>
                <span class="m-l-15 font-weight-400 text-success">Manage Employees</span>
                <div class="content-text text-xs-center m-b-0">

                </div>
              </div>
              </div>
          </div>
        @endif

        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
          <div class="card card-shadow card-shadow1">
            <div class="card-block bg-white p-30">
              <a href="{{url('polls')}}"  class="btn btn-floating btn-sm btn-primary">
                <i class="fa fa-bar-chart"></i>
              </a>
              <span class="m-l-15 font-weight-400 text-primary">Polls</span>
              <div class="content-text text-xs-center m-b-0">

              </div>
            </div>
          </div>
        </div>
      </div>
      @if(Auth::user()->role->permissions->contains('constant', 'request_panel'))
        <div class="row">
        <div class="col-xl-4 col-md-4" >
          <div class="panel panel-bordered " >
            <div class="panel-heading">
              
              <h3 class="panel-title">Birthdays for the Month</h3>
            </div>
            <div class="panel-body bg-pink-50">
              <ul class="list-group list-group-dividered h-250 scrollable is-enabled scrollable-vertical " data-plugin="scrollable" style="position: relative;">
                <div data-role="container" class="scrollable-container" style="height: 250px; width: 586px;">
                  <div data-role="content" class="scrollable-content" style="width: 569px;">
                  @foreach($b_users as $bemployee)
                    <li class="list-group-item bg-pink-50">
                      
                          <h5 class="list-group-item-text mt-0 mb-5">
                            
                            {{$bemployee->name}}
                          </h5>
                          <h5 class="list-group-item-text"><i class="fa fa-birthday-cake pink-a200" aria-hidden="true"></i>&nbsp;{{date('F j',strtotime($bemployee->dob))}}</h5>
                       
                    </li>
                    @endforeach
                  </div>
                </div>
              <div class="scrollable-bar scrollable-bar-vertical scrollable-bar-hide" draggable="false"><div class="scrollable-bar-handle" style="height: 228.302px; transform: translate3d(0px, 0px, 0px);"></div></div>
              </ul>
            </div>
          </div>
          
          
        </div>
        <div class="col-xl-4 col-md-4">
          <div class="panel panel-bordered ">
            <div class="panel-heading">
              <h3 class="panel-title">Work Anniversaries for the Month</h3>
            </div>
            @php
             //$nf = new \NumberFormatter("en-US", \NumberFormatter::ORDINAL);
          
             
            @endphp
            <div class="panel-body bg-brown-50">
               <ul class="list-group list-group-dividered h-250 scrollable is-enabled scrollable-vertical" data-plugin="scrollable" style="position: relative;">
                <div data-role="container" class="scrollable-container" style="height: 250px; width: 586px;">
                  <div data-role="content" class="scrollable-content" style="width: 569px;">
                  @foreach($a_users as $aemployee)
                    <li class="list-group-item bg-brown-50">
                      
                          <h5 class="list-group-item-text mt-0 mb-5">
                            @php
                            //$anni=$nf->format(\Carbon\Carbon::parse($aemployee->hiredate)->diffInYears(\Carbon\Carbon::now()));
                            @endphp
                            {{$aemployee->name}} ({{--$anni--}}&nbsp; Anniversary)
                          </h5>
                          <h5 class="list-group-item-text"><i class="fa fa-briefcase brown-400" aria-hidden="true"></i>&nbsp;{{date('F j',strtotime($aemployee->hiredate))}}</h5>
                       
                    </li>
                    @endforeach
                  </div>
                </div>
              <div class="scrollable-bar scrollable-bar-vertical scrollable-bar-hide" draggable="false"><div class="scrollable-bar-handle" style="height: 228.302px; transform: translate3d(0px, 0px, 0px);"></div></div>
              </ul>
            </div>
          </div>
        </div>
       
        <div class="col-xl-4 col-md-4">
          <div class="panel panel-bordered ">
            <div class="panel-heading">
              <h3 class="panel-title">Pending Requests</h3>
            </div>
            <div class="panel-body bg-teal-50">
                <div class="list-group list-group-dividered h-250 scrollable is-enabled scrollable-vertical" data-plugin="scrollable" style="position: relative;">
                    <div data-role="container" class="scrollable-container" style="height: 250px; width: 586px;">
                  <div data-role="content" class="scrollable-content" style="width: 569px;">
<a class="list-group-item bg-teal-50" target="_blank" href="{{url('leave/hrrequests')}}"><h4 class="list-group-item-heading ">&nbsp;<span class="tag tag-default bg-teal-400 teal-50">{{$pending_leave_requests}}</span> Leave Requests</h4></a>
<a class="list-group-item bg-teal-50" target="_blank" href="{{url('userprofile/change_approval')}}"><h4 class="list-group-item-heading ">&nbsp;<span class="tag tag-default bg-teal-400 teal-50">{{$pending_profile_requests}}</span> Profile Change Requests</h4></a>
<a class="list-group-item bg-teal-50" target="_blank" href="{{url('userprofile/nok_change_approval')}}"><h4 class="list-group-item-heading ">&nbsp;<span class="tag tag-default bg-teal-400 teal-50">{{$pending_nok_requests}}</span> Next of Kin Change Requests</h4></a>
<a class="list-group-item bg-teal-50" target="_blank" href="{{url('userprofile/educational_history_change_approval')}}"><h4 class="list-group-item-heading ">&nbsp;<span class="tag tag-default bg-teal-400 teal-50">{{$pending_academic_history_requests}}</span> Academic History Change Requests</h4></a>
<a class="list-group-item bg-teal-50" target="_blank" href="{{url('userprofile/employment_history_change_approval')}}"><h4 class="list-group-item-heading ">&nbsp;<span class="tag tag-default bg-teal-400 teal-50">{{$pending_employment_history_requests}}</span> Employment History Change Requests</h4></a>
<a class="list-group-item bg-teal-50" target="_blank" href="{{url('userprofile/dependant_change_approval')}}"><h4 class="list-group-item-heading ">&nbsp;<span class="tag tag-default bg-teal-400 teal-50">{{$pending_dependants_requests}}</span> Dependants Change Requests</h4></a>
</div>
</div>
          </div>
            </div>
          </div>
        </div>
        </div>
        @endif
      <div class="row">
        <div class="col-md-4">
          <div class="card card-shadow" style="padding:0 0 5px 0; ">
          @if(Auth::user()->role->id==0)
              @else
            <div class="ribbon ribbon-clip ribbon-success" >
              <span onclick="clockIn()" id="clocking" style="z-index:999999999; cursor:pointer;" class="ribbon-inner">{{__('Clock In')}}</span>
            </div>
              <div  class="ribbon ribbon-clip ribbon-reverse ribbon-danger" >
              <span onclick="clockOut()" class="ribbon-inner" id="clockout" style="z-index:999999999; cursor:pointer;">{{__('Clock Out')}}</span>
            </div>
                    @endif
            <div class="card-block text-xs-center bg-white p-40">
             
            <div id="clockin"  >
            
            
            </div><br><br> 
            
             </div>
          </div>
        </div>
        <div class="col-md-8 col-xs-12 ">
          <div class="card">
            <div class="card-header white bg-cyan-600 p-30 clearfix">
              <a class="avatar avatar-100 pull-xs-left m-r-20" href="javascript:void(0)">
                <img src="{{ file_exists(public_path('uploads/avatar'.Auth::user()->image))?asset('uploads/avatar'.Auth::user()->image):(Auth::user()->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="">
              </a>
              <div class="pull-xs-left">
                <div class="font-size-20 m-b-15">{{Auth::user()->name}}</div>
                <p class="m-b-5 text-nowrap"><i title="Address" class="icon fa fa-map-marker m-r-10" aria-hidden="true"></i>
                  <span class="text-break">{{Auth::user()->address}}</span>
                </p>
                <p class="m-b-5 text-nowrap"><i title="Email" class="icon fa fa-envelope m-r-10" aria-hidden="true"></i>
                  <span class="text-break">{{Auth::user()->email}}</span>
                </p>
                <p class="m-b-5 text-nowrap"><i title="Phone" class="icon fa fa-phone m-r-10" aria-hidden="true"></i>
                  <span class="text-break">{{Auth::user()->phone}}</span>
                </p>
                <p class="m-b-5 text-nowrap"><i title="Job Title" class="icon fa fa-briefcase m-r-10" aria-hidden="true"></i>
                  {{-- <span class="text-break">{{Auth::user()->job->title}}</span> --}}
                </p>
                <p class="m-b-5 text-nowrap"><i title="User Role" class="icon fa fa-sign-in m-r-10" aria-hidden="true"></i>
                  <span class="text-break">{{Auth::user()->role->name}}</span>
                </p>
                {{-- <p class="m-b-5 text-nowrap"><i title="Gender" class="icon fa fa-star-o m-r-10" aria-hidden="true"></i> --}}

                   {{-- @if(count(Auth::user()->promotionHistories()>0) --}}
            
            
                  {{-- <span class="text-break">Level {{(Auth::user()->promotionHistories()->latest()->first()->grade)?Auth::user()->promotionHistories()->latest()->first()->grade->level:''}}</span> --}}
                  {{-- @endif --}}
                </p>
              </div>
            </div>
            <div class="card-footer bg-white">
              <div class="row no-space p-y-20 p-x-30 text-xs-center">
                <div class="col-xs-4">
                  <div class="counter">
                    <span class="counter-number cyan-600">{{Auth::user()->employees()->count()}}</span>
                    <div class="counter-label">Direct Reports</div>
                  </div>
                </div>
                <div class="col-xs-4">
                  <div class="counter">
                    <span class="counter-number cyan-600"></span>
                    <div class="counter-label">Clients</div>
                  </div>
                </div>
                <div class="col-xs-4">
                  <div class="counter">
                    <span class="counter-number cyan-600">3</span>
                    <div class="counter-label">Projects</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     
       <div class="row" data-plugin="matchHeight" data-by-row="true">
        @if(Auth::user()->role->permissions->contains('constant', 'attendance_panel'))
          
        @endif
        
        @if(Auth::user()->role->permissions->contains('constant', 'attendance_kpi'))
        
        @endif
        @if(Auth::user()->role->permissions->contains('constant', 'employee_kpi'))
      
        @endif
        @if(Auth::user()->role->permissions->contains('constant', 'finance_kpi'))
       
        @endif

      
  </div>
      <div class="row">
        
        @if(Auth::user()->role->permissions->contains('constant', 'vacant_position_panel'))
        <div class="col-lg-12 col-xs-12 masonry-item">
          <!-- Panel Projects Status -->
          <div class="panel">
            <div class="panel-heading">
              <h3 class="panel-title">
                Vacant Positions
                <span class="tag tag-pill tag-info">6</span>
              </h3>
              <div class="panel-actions panel-actions-keep">
                <a href="#" class="btn btn-info"> View All Vacancies</a>
                
              </div>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
              <table class="table table-striped dataTable" id="vacancytable">
                <thead>
                  <tr>
                    <td>S/N</td>
                    <td>Position</td>
                    <td>Department</td>
                    <td>Priority</td>
                    <td>No. of Vacancies</td>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $sn=1;
                  @endphp
                  @foreach ($jobs as $job)
                  @if($job->users()->count() <$job->personnel)
                  <tr>
                    <td>{{$sn}}</td>
                    <td>{{$job->title}}</td>
                    <td>{{$job->department->name}}</td>
                    <td>
                      <span class="tag tag-danger">Urgent</span>
                    </td>

                    <td>{{$job->personnel-$job->users()->count()}}</td>
                  </tr>
                  @php
                    $sn++;
                  @endphp
                  @endif
                  @endforeach
                  
                  
                </tbody>
              </table>
                </div>  
            </div>
            <div class="panel-footer">
                
            </div>
            
          </div>
          <!-- End Panel Projects Stats -->
        </div>
        @endif  
      </div>
  </div>
  </div>
  <!-- Site Action -->
<div class="modal fade in modal-3d-flip-horizontal modal-info" id="requestsModal" aria-hidden="true" aria-labelledby="requestsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" >Pending Requests</h4>
          </div>
            <div class="modal-body">         
              <div class="col-xs-12 "> 
                <div class="list-group list-group-gap">
                  <a class="list-group-item list-group-item-warning" href="javascript:void(0)">
                    <h4 class="list-group-item-heading grey-50">Leave Requests</h4>
                    <p class="list-group-item-text grey-50">{{$pending_leave_requests}} requests</p>
                  </a>
                  <a class="list-group-item list-group-item-success" href="javascript:void(0)">
                    <h4 class="list-group-item-heading grey-50">Profile Change Requests</h4>
                    <p class="list-group-item-text grey-50">2 requests</p>
                  </a>
                  <a class="list-group-item list-group-item-info" href="javascript:void(0)">
                    <h4 class="list-group-item-heading grey-50">Expenses Requests</h4>
                    <p class="list-group-item-text grey-50">5 requests</p>
                  </a>
                </div>
               </div>        
            </div>
            <div class="modal-footer">
             </div>
         </div>
        
      </div>
    </div>
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="usersModal" aria-hidden="true" aria-labelledby="requestsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" >All Users</h4>
          </div>
            <div class="modal-body">         
              <div class="col-xs-12 "> 
                @php
                  $arrX = ['red-900','pink-900','deep-orange-900','teal-900','lime-900','cyan-900','blue-900','purple-900','deep-purple-900','light-blue-900','amber-900','yellow-900','orange-900','blue-grey-900','brown-900'];   
                @endphp
                <div class="list-group list-group-gap">
                  @foreach($companies as $company)
                  @php
                    $randIndex = array_rand($arrX);
                  @endphp
                  <a class="list-group-item bg-{{$arrX[$randIndex]}}" href="javascript:void(0)">
                    <h4 class="list-group-item-heading grey-50">{{$company->name}}</h4>
                    <p class="list-group-item-text grey-50">{{mt_rand(100,900)}} employees</p>
                  </a>
                  @endforeach
                  
                </div>
               </div>        
            </div>
            <div class="modal-footer">
             </div>
         </div>
        
      </div>
    </div>
  
  <!-- End Add User Form -->
@endsection
@section('scripts')
   <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
  <script src="{{ asset('global/vendor/chart-js/Chart.js') }}"></script>
  <script src="{{ asset('global/vendor/moment/moment.min.js') }}"></script>
  <script src="{{ asset('global/vendor/moment/moment-duration-format.js') }}"></script>
   <script>
     function getLocation(type) {
       if (navigator.geolocation) {
         if (type=='clockin'){
           navigator.geolocation.getCurrentPosition(clockInPost);
         }
         else {
           navigator.geolocation.getCurrentPosition(clockOutPost);
         }
       } else {
         alertify.error('Cannot clock in or out with current device');
       }
     }
     function showPosition(position) {
       message = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
       console.log(message)
     }
     function clockIn() {
       alertify.success('Processing...');
       getLocation('clockin')
     }
     function clockOut() {
       getLocation('clockout')
     }
     function clockInPost(position) {
       alertify.confirm('Are you sure you want to Clock In now?', function () {
         $.get('{{ url('/bio/softclockin') }}?long='+position.coords.longitude+'&lat='+position.coords.latitude, function (data) {
           console.log(data)
           toastr.success(data);
         });
       }, function () {
         alertify.error('Cancelled');
       });
     }
     function clockOutPost(position) {
       alertify.confirm('Are you sure you want to Clock Out now?', function () {
         $.get('{{ url('/bio/softclockout') }}?long='+position.coords.longitude+'&lat='+position.coords.latitude, function (data) {
           toastr.success(data);
         });
       }, function () {
         alertify.error('Cancelled');
       });
     }
   </script>
   <script type="text/javascript">
  $(document).ready(function() {
    $('#vacancytable').DataTable( );
} );
@if($last_month_early_users->count()>0)
 var ctx = document.getElementById('earlyChart').getContext('2d');
    var myLineChart = new Chart(ctx, {
  type: "bar",
  data:
        {
            datasets:
            [
                {
                    labels: 'Top Five early Comers',
                    backgroundColor: [ '#483C32', '#5B92E5', '#77c949', '#FFC1CC', '#ffbb44', '#f32f53', '#67a8e4'],
                    borderColor: '#ffffff',
                    data: [@foreach($last_month_early_users as $last_month_early_user) "{{time_to_seconds($last_month_early_user->average_first_clock_in)}}", @endforeach],
                }
            ],
            labels: [@foreach($last_month_early_users as $last_month_early_user)"{{$last_month_early_user->user?$last_month_early_user->user->name:''}}", @endforeach],
        },
  options: {
    elements: {
      line: {
        tension: 0
      }
    },
    scales: {
      yAxes: [
        {
          ticks: {
            stepSize: 50,
            callback: function(tickValue, index, ticks) {
              if (!(index % parseInt(ticks.length / 5))) {
                 return moment.duration(tickValue, 's').format('h:mm a', { trim: false });
              }
            }
          }
        }
      ]
    }
  }
});
  @endif
  @if($last_month_late_users->count()>0)
    var ctx = document.getElementById('lateChart').getContext('2d');

var myLineChart = new Chart(ctx, {
  type: "bar",

  data:
        {
            datasets:
            [
                {
                    labels: 'Top Five late Comers',
                    backgroundColor: [ '#483C32', '#5B92E5', '#77c949', '#FFC1CC', '#ffbb44', '#f32f53', '#67a8e4'],
                    borderColor: '#ffffff',
                    data: [@foreach($last_month_late_users as $last_month_late_user) "{{time_to_seconds($last_month_late_user->average_first_clock_in)}}", @endforeach],
                }
            ],
            labels: [@foreach($last_month_late_users as $last_month_late_user)"{{$last_month_late_user->user?$last_month_late_user->user->name:''}}", @endforeach],
        },

  options: {
    elements: {
      line: {
        tension: 0
      }
    },
    scales: {
      yAxes: [
        {
          ticks: {
            stepSize: 50,
            callback: function(tickValue, index, ticks) {
              if (!(index % parseInt(ticks.length / 5))) {
                 return moment.duration(tickValue, 's').format('h:mm a', { trim: false });
              }
            }
          }
        }
      ]
    }
  }
});
@endif
  function showRequests() {
    $('#requestsModal').modal();
  }
  function showUsers() {
    $('#usersModal').modal();
  }
</script>
@endsection