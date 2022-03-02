@extends('layouts.master')
@section('stylesheets')
  <style type="text/css">
  	.margin{
  		margin-bottom:5px; 
  	}
  	.hide{
  		display: none;
  	}
  </style>
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-markdown/bootstrap-markdown.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/switchery/switchery.css')}}">

  <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-table/bootstrap-table.css')}}">

  <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.css')}}">

@endsection


@section('script-footer')

    @include('performance.training.scripts.course_script')

@endsection

@section('content')


<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{ucfirst($employee->name)}}'s  {{__('Performance')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{$employee->name}}'s {{__(' Perfomance')}}</li>
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
    
      <div class="page-content container-fluid">
      	 
		<div class="col-lg-3 col-xs-3 masonry-item">
          <div class="card card-shadow">
            <div class="card-header bg-blue-600 white p-15 clearfix">
              <a class="avatar avatar-lg pull-xs-left m-r-20" href="javascript:void(0)">
                <img src="{{ file_exists(public_path('uploads/avatar'.$employee->image))?asset('uploads/avatar'.$employee->image):($employee->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="">
              </a>
              <div class="font-size-18">{{$employee->name}}</div>
              <div class="grey-300 font-size-14">
              	{{-- <p>{{$employee->job->title}}</p> --}}
              
              </div>
            </div>
            <ul class="list-group list-group-bordered m-b-0">
              <li class="list-group-item"> 
              	<p class="margin"><b class="text-primary">Hire Date</b></p>
               <p class="margin">                
                	{{date('F j, Y',strtotime($employee->hiredate))}}</p>
                	<p class="margin">{{$employee->hiredate->diffForHumans()}}</p>
              </li>
 			<li class="list-group-item"> 
              	<p class="margin"><b class="text-primary">Manager(s)</b></p>
            
              	@foreach($employee->managers as $manager)
                         
                	<p class="margin"><a href="{{url('users')}}/{{$manager->id}}/edit">{{$manager->name}}</a></p>
                	 
          
                @endforeach
                 
              </li>
              
              <li class="list-group-item"> 
              	<p class="margin"><b class="text-primary">Employee Number</b></p>

              	<p class="margin"> {{$employee->emp_num}}</p>
              </li>
              <li class="list-group-item"> 
              	<p class="margin"><b class="text-primary">Grade</b></p>

              	<p class="margin"> {{$employee->grade?$employee->grade->level:''}}</p>
              </li> 
                 <li class="list-group-item"> 
              	<p class="margin"><b class="text-primary">Gender</b></p>

              	<p class="margin"> {{$employee->sex}}</p>
              </li> 
               <li class="list-group-item"> 
              	<p class="margin"><b class="text-primary">Organization</b></p>

              	<p class="margin">{{$employee->company->name}}</p>
              </li>
               <li class="list-group-item"> 
              	<p class="margin"><b class="text-primary">Status</b></p>

              	<p class="margin">{{$employee->probation_status}}</p>
              </li>
                	 
              <li class="list-group-item"> 
              	<p class="margin"><b class="text-primary">Direct Report(s)</b></p>
            	 
              	@foreach($employee->employees as $employeeLoop)
                         
                <p class="margin" data-toggle="tooltip" data-original-title="View {{$employeeLoop->name}} name"><a  style="text-decoration: none"  href="{{url('users')}}/{{$employeeLoop->id}}/edit">{{$employeeLoop->name}}</a></p>
                	 
                @endforeach
                 
              </li>
            </ul>
          </div>
        </div>
   
        <div class="col-sm-9">
        	<div class="panel nav-tabs-horizontal" data-plugin="tabs">
            <div class="panel-heading">
              <h3 class="panel-title">Performance Appraisal - 
                @if(isset($_GET['quarter']))  
                (Quarter {{$_GET['quarter']}})
                 @else
                  (Quarter {{Auth::user()->getquarter()}}) 
                  @endif 
 
              	@if(Auth::user()->role->permissions->contains('constant', 'edit_performance'))
              	(On/Off)&nbsp;&nbsp;
              	 <li class="list-inline-item m-r-25 m-b-25">
                    <input id="perf_switch" disabled type="checkbox" data-plugin="switchery" {{$employee->performanceseason()==1 ? 'checked' : ''}} />
                  </li>
                  @endif
              </h3>
              <div class="panel-actions panel-actions-keep">

              	<div class="btn-group" role="group" data-toggle="tooltip" data-original-title="Click to dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle waves-effect" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                      <i class="icon md-apps" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                      <a class="dropdown-item" onclick="showModal('addGoal')" href="javascript:void(0)" role="menuitem">Add Goal</a>
                      @if(Auth::user()->role->permissions->contains('constant', 'add_kpi') && Auth::user()->id!=$employee->id)
                      <a class="dropdown-item" data-target="#addkpi" onclick="unhide()" title="add Kpi" class="btn btn-outline btn-pure btn-success" data-toggle="modal" href="javascript:void(0)" role="menuitem">Add Kpi</a> 
                      @endif
                      <a class="dropdown-item" data-target="#changeQuarter"   title="Change Quarter" class="btn btn-outline btn-pure btn-success" data-toggle="modal" href="javascript:void(0)" role="menuitem">Change Quarter</a> 
                          
                    </div>
                  </div>  
              </div>
            </div>
            <ul class="nav nav-tabs nav-tabs-line" id="arcordion" role="tablist">
                <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#pilotapp" onclick="saveState('pilotapp')" aria-controls="" role="tab" aria-expanded="true">
                            <i class="icon wb-stats-bars" aria-hidden="true"></i> Organizational Goals
                        </a>
                    </li>
                    @if($employee->managers->count()>1)
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" onclick="saveState('lmapp')" href="#lmapp" aria-controls="" role="tab">
                            <i class="icon wb-arrow-shrink"></i>Dual Role Goals
                        </a>
                    </li>
                    @endif
                     <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" onclick="saveState('kpis')" href="#kpis" aria-controls="" role="tab">
                            <i class="icon wb-arrow-shrink"></i>KPI's
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="saveState('idpapp')" data-toggle="tab" href="#idpapp" aria-controls="" role="tab">
                            <i class="icon wb-user"></i>Individual Dev. Plans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" onclick="saveState('carapp')" href="#carapp" aria-controls="" role="tab">
                            <i class="icon wb-library"></i>Career Aspirations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="p-training-header" class="nav-link" data-toggle="tab" href="#p-training" aria-controls="" role="tab">
                            <i class="icon wb-library"></i>Trainings
                        </a>
                    </li>
                    
            </ul>
            <div class="panel-body">
              <div class="tab-content">



                  <div class="tab-pane" id="p-training" role="tabpanel" aria-expanded="false">
                      <!-- START HERE -->

                      {!! $training !!}


                  <!-- END HERE -->
                  </div>



                  <div class="tab-pane active" id="pilotapp" role="tabpanel" aria-expanded="false">
                    <!-- START HERE -->
               		@include('partials.pilot')

                 <!-- END HERE -->
                </div>
                <div class="tab-pane" id="lmapp" role="tabpanel" aria-expanded="false">
                  <!-- START HERE -->
						    @include('partials.lmapp')
                 <!-- END HERE -->
                </div>
                <div class="tab-pane" id="idpapp" role="tabpanel" aria-expanded="true">
                  <!-- START HERE -->
                  @include('partials.idpapp')
                 
                 <!-- END HERE -->
                </div>
                <div class="tab-pane" id="carapp" role="tabpanel">
                   <!-- START HERE -->
                   @include('partials.carapp')
                 
                 <!-- END HERE -->
                </div>
                <div class="tab-pane" id="kpis" role="tabpanel">
                	<!-- START HERE -->
                	@include('partials.kpis')

                	<!-- END HERE -->
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  	
	</div>
@include('performance.modals.Appraisalcomment')
@include('performance.modals.addGoal')
@include('performance.modals.addKpiModal')
@include('performance.modals.changeQuarter')
  <!-- End Page -->
@endsection

@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-markdown/bootstrap-markdown.js')}}"></script>
  <script src="{{asset('js/jQuery-gRating.min.js')}}"></script>
  <script src="{{asset('global/vendor/switchery/switchery.min.js')}}"></script>

  <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

 <script src="{{asset('js/sweetalert.min.js')}}"></script>
  <script type="text/javascript">

    $(function(){
    $('.rate_quarter').change(function(){ 
      window.location="{{url('performances')}}/employee?id={{$_GET['id']}}&quarter="+$('.rate_quarter').val();
       
    })

  });
  	function loadcbox( id , reportcomment,kpidel ){

  		$('#reportcomment').val($('#realComment'+id).val());
  		// alert($('#reportcomment').text());
  		sessionStorage.setItem('commentid',id);
  		sessionStorage.setItem('kpidel',kpidel); 

  	}

  	// ADD KPI'S
function unhide(){

 $('#modaltitle').text('Add Kpi');
        $('#addbtn').text('Add Kpi');
        sessionStorage.setItem('modify',0);
        sessionStorage.setItem('formid',0);
  $('#emphideid').removeClass('hide');
    $deliverables=$('#deliverables').val('');
       $targetweight=$('#targetweight').val('');
       $targetamount=$('#targetamount').val('');
       $comment=$('#comment').val('');
      // $('#emphideid').addClass('hide');
        $quarter=$('#kpi_quarter').val('');
     
}

 

  function fillmodal(deliverable,targetweight,targetamount,quarter,comment,formid){

    $deliverables=$('#deliverables').val(deliverable);
    $targetweight=$('#targetweight').val(targetweight);
    $targetamount=$('#targetamount').val(targetamount);
    $comment=$('#comment').val(comment);
    $('#emphideid').addClass('hide');
    $('#kpi_quarter').val(quarter);
    sessionStorage.setItem('modify',1);
    sessionStorage.setItem('formid',formid);
    $('#modaltitle').text('Modify Kpi');
    $('#addbtn').text('Modify Kpi');
    $('#addkpi').modal('show');

  }  
 $('#kpiform').submit(function(){
                                  event.preventDefault();
                                  $deliverables=$('#deliverables').val();
                                  $targetweight=$('#targetweight').val();
                                  $targetamount=$('#targetamount').val();
                                  $comment=$('#comment').val();

                                  $department_assigned=$('#department_assigned').val();

                                    $Assigned=$('#assigned_to').val();
 
                                  if($department_assigned=='this_employee'){
                  
                                    $department_assigned=0;
                                  }
                                  else{
                                    $Assigned=0;
                                   
                                  }
                                  $type=sessionStorage.getItem('modify');
                                  $formid=sessionStorage.getItem('formid');
                                  if($formid==0){
                                    $formid=0;
                                  }
                                  else{
                                    $formid=$formid;
                                  }
                                  $quarter=$('#kpi_quarter').val();

                              $.post('{{url('performance')}}',{

                                  deliverables:$deliverables,
                                  targetamount:$targetamount,
                                  targetweight:$targetweight,
                                  comment:$comment,
                                  type:$type,
                                  formid:$formid,
                                  assignedto:$Assigned,
                                  quarter:$quarter,
                                  department_id:$department_assigned, 
                                  type:'addKpi',
                                  _token:"{{csrf_token()}}"

                              },function(data,status,xhr){
                                  if(data.status=="success"){
                                    if($formid==0){
                                    toastr.success('Kpi Added Successfully');
                                  }
                                  else{
                                    toastr.success('Kpi Modified Successfully');
                                  }
                                    setTimeout(function(){

                                      window.location.reload();

                                    },2000);
                                    return;
                                  }
                                  toastr.error(data.message);

                              })

                            });

 // KPI'S
@if(Auth::user()->role->permissions->contains('constant', 'edit_performance')==false)

    $('#savecomment').submit(function(){

                  event.preventDefault();
                  $comment=$('#reportcomment').val();
                  $reportid=sessionStorage.getItem('commentid');
                  if($comment==""){ return toastr.warning('Comment Field Blank'); }

                  $.post('{{url('performance')}}',{

                      comment:$comment,
                      reportid:$reportid,
                      empid:{{$employee->id}},
                      kpiname:sessionStorage.getItem('kpidel'),
                      _token:'{{csrf_token()}}',
                      type:'saveReportComment'

                  },function(data,status,xhr){
                      if(data.status=="success"){
                      	$('#realComment'+$reportid).val($comment);
                      	$('#commentbox').modal('hide');
                        return toastr.success('Comment Successfully Saved and Employee has been Notified');
                      }
                      toastr.error(data.message);

                  });
      });

@endif

  	$(function(){
 		loadStar('hrrating');
 		loadStar('lmrating');

 $('#addKpiReport').submit(function(e){

        e.preventDefault();

        $progressreport=$('#progressreport').val();
        $reportfroms=$('input[name=start]').val();
        $reporttos=$('input[name=end]').val();
        $achievedamount=$('#achievedamount').val();
        // $achievedtodate=$('#achievedtodate').val();
        $commentrep=$('#commentrep').val();
   
        if($('input[name=status]').is(':checked')){
          $status=1;
        }
        else{
          $status=0;
        }

        $.post('{{url('performance')}}',{
          progressreport:$progressreport,
          reportfroms:$reportfroms,
          reporttos:$reporttos,
          achievedamount:$achievedamount,
          type:'addProgressReport',
               // achievedtodate:$achievedtodate,
               commentrep:$commentrep,
               status:$status,
               emp_id:{{Auth::user()->id}},
               kpiid:sessionStorage.getItem('progkpiid'),
               kpiname:sessionStorage.getItem('deliverable'),
               _token:'{{csrf_token()}}'

             },function(data,status,xhr){

              if(data.status=='success'){

                toastr.success(data.message);
                setTimeout(function(){
                  window.location.reload();

                },2000);
                return;
              }
              return toastr.error(data.message);


            });



      }); 

 		$('a[href="#' + sessionStorage.getItem('state') + '"]').trigger('click');

    $("#perf_switch").on("change", function(e) {
    		e.preventDefault();



    	  swal({
              title: "Warning!",
              text: "Are you Sure you want to continue ?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#f96868",
              confirmButtonText: "Yes!",
              cancelButtonText: "No, Go back.",
              closeOnConfirm: false,
              closeOnCancel: false
            }, function(isConfirm){
              if(isConfirm)
              {
              	$.get('{{url('performance')}}/toggleSeason',function(data){
              		if(data.status=='success'){

              	swal('Success',data.message,'success');
              	setTimeout(function(){
              		window.location.reload();
              	},2000);
              			return toastr.success(data.message);
              		}

              	swal('Error',data.message,'error');
              		return toastr.error(data.message);  
              	})
              }
              else{
               // reset first
   			 
    		 	$(this).prop('checked',false);
    	 

              	swal('Error','Operation Cancelled','error');
              	return
              }
            });
    		
    });

  	});
		
	 function showModal(id){

	 		$('#'+id).modal('show');
	 }

	 function saveState(state){
	 	sessionStorage.setItem('state',state);
	 }

	 function loadStar(id){
	 	 $("."+id).grating({
  		ratingCss:{
  			color: "#000",
  			fontSize: "20px"
  		},
  			callback: function(owner, value)
			  {

			  	$('#ratingStar').val(value);
			    $('#'+id).val(value);
			  }
		});

	 }

	function comment($pilot_id,$employee_id,text,$type=0){
	 	pilotid=sessionStorage.setItem('pilotid',$pilot_id);
	 	employeeid=sessionStorage.setItem('employeeid',$employee_id);
	 	if($type==0){

	 		$('#rate_quarterPack').removeClass('hide');
	 	}
	 	else{
	 		$('#rate_quarterPack').addClass('hide');
	 	}
   
	 $('#commentMsg').val(text);
	 	$('#commentModal').modal('show');
	 }
 
 
  </script>
@endsection