@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
 <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
 <link rel="stylesheet" href="{{ asset('global/vendor/webui-popover/webui-popover.min.css') }}">
 <style type="text/css">
   .btn-floating.btn-sm {

     width: 4rem;
     height: 4rem;

}



 </style>
@endsection
@section('content')
<!-- Page -->

  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">{{__('Leave Request')}} for {{request()->year!=''? request()->year : date('Y')}}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active">{{__('Leave Request')}}</li>
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
        <div class="row" data-plugin="matchHeight" data-by-row="true">
  <!-- First Row -->
  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-info">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400">All Leave Requests</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">

            {{$leave_requests->count()>0? number_format($leave_requests->count()):0}} 

          </span>
          <p class="blue-grey-400 font-weight-100 m-0">All</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-warning">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400">Pending Leave Requests</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">

            {{$pending_requests}}

          </span>
          <p class="blue-grey-400 font-weight-100 m-0">Pending</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-success">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400"> Approved Leave Requests</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">

            {{$approved_requests}}

          </span>
          <p class="blue-grey-400 font-weight-100 m-0">Approved</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-danger">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400">Rejected Leave Requests</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">

            {{$rejected_requests}}

          </span>
          <p class="blue-grey-400 font-weight-100 m-0">Rejected</p>
        </div>
      </div>
    </div>
  </div>

  <!-- End First Row -->
            @if(request()->year==date('Y') or request()->year=='')
            <div class="col-ms-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Active Leave for this month</h3>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table striped datatable">
                                <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Employee Line Manager</th>
                                    <th>Applied on</th>
                                    <th>Leave Type</th>
                                    <th>Starts</th>
                                    <th>Ends</th>
                                    <th>Priority</th>
                                    <th>Reason</th>
                                    <th>Leave length</th>
                                    <th>Approval Status</th>
                                    <th>With Pay</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($month_leave_requests as $leave_request)
                                        <td>{{$leave_request->user->name}}</td>
                                        <td>{{$leave_request->user->plmanager?$leave_request->user->plmanager->name:''}}</td>
                                        <td>{{date("F j, Y", strtotime($leave_request->created_at))}}</td>
                                        <td>{{$leave_request->leave_name}}</td>
                                        <td>{{date("F j, Y", strtotime($leave_request->start_date))}}</td>
                                        <td>{{date("F j, Y", strtotime($leave_request->end_date))}}</td>
                                        <td><span class=" tag tag-outline  {{$leave_request->priority==0?'tag-success':($leave_request->priority==1?'tag-warning':'tag-danger')}}">{{$leave_request->priority==0?'normal':($leave_request->priority==1?'medium':'high')}}</span></td>
                                        <td>{{$leave_request->reason}}</td>
                                        <td>{{$leave_request->length}}</td>
                                        <td><span class=" tag   {{$leave_request->status==0?'tag-warning':($leave_request->status==1?'tag-success':'tag-danger')}}">{{$leave_request->status==0?'pending':($leave_request->status==1?'approved':'rejected')}}</span></td>
                                        <td>{{$leave_request->paystatus==0?'No':'Yes'}}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                    <a data-id="{{ $leave_request->id }}" style="cursor:pointer;"class="dropdown-item view-approval" id="{{$leave_request->id}}" onclick="viewRequestApproval(this.id)"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Approval</a>

                                                    @if($leave_request->absence_doc!='')
                                                        <a style="cursor:pointer;"class="dropdown-item" id="" href="{{url('leave/download?leave_request_id='.$leave_request->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Document</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                </tr>
                                @endforeach

                                </tbody>

                            </table>
                        </div>


                    </div>
                </div>

            </div>
            @endif
  {{-- second row --}}
  <div class="col-ms-12 col-xs-12 col-md-12">
    <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title"> Year Leave Requests</h3>
                  <div class="panel-actions">
                      @php $first_year=2020; @endphp
                      <select class="form-control" onchange="changeYear(this.value)">
                          @for($i=$first_year;$i<=date('Y');$i++)
                          @if(request()->year!='')
                          <option {{request()->year==$i?"selected":''}}>{{$i}}</option>
                          @else
                           <option {{date('Y')==$i?"selected":''}}>{{$i}}</option>
                          @endif
                          @endfor
                      </select>
                     
                    </div>
                  </div>
                <div class="panel-body">
                  <div class="table-responsive">
         <table class="table table striped datatable">
          <thead>
            <tr>
            <th>Employee</th>
            <th>Employee Line Manager</th>
            <th>Applied on</th>
            <th>Leave Type</th>
            <th>Starts</th>
            <th>Ends</th>
            <th>Priority</th>
            <th>Reason</th>
            <th>Leave length</th>
            <th>Approval Status</th>
            <th>With Pay</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
            <tr>
          @foreach($leave_requests as $leave_request)
          <td>{{$leave_request->user->name}}</td>
          <td>{{$leave_request->user->plmanager?$leave_request->user->plmanager->name:''}}</td>
          <td>{{date("F j, Y", strtotime($leave_request->created_at))}}</td>
          <td>{{$leave_request->leave_name}}</td>
            <td>{{date("F j, Y", strtotime($leave_request->start_date))}}</td>
            <td>{{date("F j, Y", strtotime($leave_request->end_date))}}</td>
            <td><span class=" tag tag-outline  {{$leave_request->priority==0?'tag-success':($leave_request->priority==1?'tag-warning':'tag-danger')}}">{{$leave_request->priority==0?'normal':($leave_request->priority==1?'medium':'high')}}</span></td>
            <td>{{$leave_request->reason}}</td>
            <td>{{$leave_request->length}}</td>
            <td><span class=" tag   {{$leave_request->status==0?'tag-warning':($leave_request->status==1?'tag-success':'tag-danger')}}">{{$leave_request->status==0?'pending':($leave_request->status==1?'approved':'rejected')}}</span></td>
            <td>{{$leave_request->paystatus==0?'No':'Yes'}}</td>
            <td>
              <div class="btn-group" role="group">
                  <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                  data-toggle="dropdown" aria-expanded="false">
                    Action
                  </button>
                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                  <a data-id="{{ $leave_request->id }}" style="cursor:pointer;"class="dropdown-item view-approval" id="{{$leave_request->id}}" onclick="viewRequestApproval(this.id)"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Approval</a>
                 
                   @if($leave_request->absence_doc!='')
                  <a style="cursor:pointer;"class="dropdown-item" id="" href="{{url('leave/download?leave_request_id='.$leave_request->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Document</a>
                  @endif
                </div>
              </div>
            </td>
        </tr>
          @endforeach

          </tbody>

         </table>
         </div>


      </div>
  </div>

    </div>

  </div>
</div>
</div>

  <!-- End Page -->

   {{-- Leave Request Details Modal --}}
   <div class="modal fade in modal-3d-flip-horizontal modal-info" id="leaveDetailsModal" aria-hidden="true" aria-labelledby="leaveDetailsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Leave Request Details</h4>
          </div>
            <div class="modal-body">
                <div class="row row-lg col-xs-12">
                  <div class="col-xs-12" id="detailLoader">

                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                </div>
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">


                  <!-- End Example Textarea -->
                </div>
             </div>
         </div>
      </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{ asset('global/vendor/webui-popover/jquery.webui-popover.min.js') }}"></script>

  <script type="text/javascript">


    (function($){

       $(function(){
            $('.datatable').DataTable();

    $('.selecttwo').select2();
$('#holiday').webuiPopover({title:'Holidays',url:'#holidayContent',placement:'left',trigger:'hover'});
$('#leave').webuiPopover({title:'Leave Types',url:'#leaveContent',placement:'left',trigger:'hover'});
$('#leave_plan').webuiPopover({title:'Leave Plans',url:'#leavePlanContent',placement:'left',trigger:'hover',width:500});
       ///module start///

      let check = 0;
      let vl = 0;

      function doValidate(v){
        vl = v || vl;
        check = vl - $('#leave_days_requested').val();
        if (check < 0){

          toastr.error('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');

           //alert('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');
         }
        return {
          check: check > 0,
          value: check
        };
      }


        function ajaxStart(){
          toastr.info('Processing ...');

        }

        function ajaxStop(){
          toastr.info('Done.');
            document.getElementById("loader").style.display = "none";
        }


        function postForm($form,$api){

          let promise = {
            success:function(cb,inValue){
              // cb(inValue);
              console.log(this);
              this._success = cb;
              return promise;
            },
            before:function(cb){
             this._before = cb;
             return promise;
            },
            _before:function(){
              return true;
            },
            _success:function(){
              //
            },///
            _error:function(){
             //
            },
            error:function(cb,inError){
              // cb(inError);
              this._error = cb;
              return promise;
            },
            processSuccess:function(response){
              this._success(response);
            },
            processError:function(responseError){
              this._error(responseError);
            },
            processBefore:function(content){
              return this._before(content);
            }
          };

          if (promise.processBefore({})){

             ajaxStart();

              var form = $form;
              var formdata = false;
              if (window.FormData){
                  formdata = new FormData(form[0]);
              }
              $.ajax({
                  url         : $api,
                  data        : formdata ? formdata : form.serialize(),
                  cache       : false,
                  contentType : false,
                  processData : false,
                  type        : 'POST',
                  success     : function(data, textStatus, jqXHR){

                    // promise.success();
                    promise.processSuccess({
                      data:data,
                      textStatus:textStatus,
                      jqXHR:jqXHR
                    });

                    ajaxStop();

                       // toastr.success("Changes saved successfully..",'Success');
                      // $('#addLeaveRequestModal').modal('toggle');

                              // location.reload();

                  },
                  error:function(data, textStatus, jqXHR){

                    promise.processSuccess({
                      data:data,
                      textStatus:textStatus,
                      jqXHR:jqXHR
                    });

                    ajaxStop();

                    //  jQuery.each( data['responseJSON'], function( i, val ) {
                    //   jQuery.each( val, function( i, valchild ) {
                    //   toastr.error(valchild[0]);
                    // });
                    // });

                  }
              });

          }

          return promise;
        }


        function initAddLeaveForm(){

           postForm($('#addLeaveRequestForm'),"{{route('leave.store')}}")
           .before(function(){
              return doValidate().check;
           })
           .success(function(response){
                toastr.success("Changes saved successfully..",'Success');
                $('#addLeaveRequestModal').modal('toggle');
                location.reload();
           })
           .error(function(responseError){
               toastr.error('Something went wrong!');
           });

        }

        function initEditLeaveForm(){

           postForm($('#editLeaveRequestForm'),"{{route('leave.store')}}")
           .before(function(){
              return doValidate().check;
           })
           .success(function(response){
                toastr.success("Changes saved successfully..",'Success');
                $('#editLeaveRequestModal').modal('toggle');
                location.reload();
           })
           .error(function(responseError){
               toastr.error('Something went wrong!');
           });

        }
        function initAddLeavePlanForm(){

           postForm($('#addLeavePlanForm'),"{{route('leave.store')}}")
           .before(function(){
              return doValidate().check;
           })
           .success(function(response){
                toastr.success("Changes saved successfully..",'Success');
                $('#addLeavePlanModal').modal('toggle');

                location.reload();
           })
           .error(function(responseError){
               toastr.error('Something went wrong!');
           });

        }


      // $('.input-daterange').datepicker({
      //   autoclose: true,
      //   format:'yyyy-mm-dd'
      // });
           $('.input-daterange').datepicker({
               format: 'yyyy-mm-dd',
               startDate: new Date,
               autoclose: true,
               closeOnDateSelect: true
           }).datepicker("setDate", 'now');



      $(document).on('submit','#addLeaveRequestForm',function(event){
        event.preventDefault();
        initAddLeaveForm();
      });
      $(document).on('submit','#addLeavePlanForm',function(event){
        event.preventDefault();
        initAddLeavePlanForm();
      });


      function viewRequestApproval(leave_request_id)
      {
         $(document).ready(function() {
            $("#detailLoader").load('{{ url('/leave/getdetails') }}?leave_request_id='+leave_request_id);
          $('#leaveDetailsModal').modal();
        });

      }

      window.viewRequestApproval = viewRequestApproval;  //Export to global windows object.

       $('#abtype').on('change', function() {
        leave_id= $(this).val();

          $.get('{{ url('/leave/get_entitled_leave_length') }}/',{ leave_id: leave_id },function(data){
          $('#leavelength').val(data.balance);
           $('#paystatus').val(data.paystatus);

           if (doValidate(data.balance).check){
             $('#leaveremaining').val(doValidate(data.balance).value);
           }

         });
       });

       $('#fromdate').on('change', function() {
        fromdate= $('#fromdate').val();
         todate= $('#todate').val();

          $.get('{{ url('/leave/get_leave_requested_days') }}/',{ fromdate: fromdate,todate:todate },function(data){
          $('#leave_days_requested').val(data);
         });
       });




       $('#todate').on('change', function() {
        fromdate= $('#fromdate').val();
         todate= $('#todate').val();

          $.get('{{ url('/leave/get_leave_requested_days') }}/',{ fromdate: fromdate,todate:todate },function(data){
          $('#leave_days_requested').val(data);
         });
       });



      $('#emps').select2({
        placeholder: "Employee Name",
         multiple: false,
        id: function(bond){ return bond._id; },
         ajax: {
         delay: 250,
         processResults: function (data) {
              return {
        results: data
          };
        },
        url: function (params) {
        return '{{url('users')}}/search';
        }
        }

      });


       ///module stop///

       });

    })(jQuery);

    ////End - JQuery ...

  </script>
  <script>


     function cancelLeave(id){
       //http://127.0.0.1:8000/leave/myrequests
       $(function(){
       $.ajax({
         url:'{{ url('leave/cancel_leave') }}?id=' + id,
         type: 'GET',
         success:function(response){
          toastr.info('Leave Cancellation',response.message);
          setTimeout(function(){
            location.reload();
          },5000);
         }
       });
       });
     }
 $(function(){
      $('[data-leave-cancel]').each(function(){
        var id = $(this).data('leave-cancel');
        $(this).on('click',function(){
           //cancel_leave
           if (confirm('Do you want to confirm this removal?')){
             cancelLeave(id);
           }
           return false;
        });
      });
      });




</script>
<script type="text/javascript">

  function datePicker(){
     $('.period_daterange').datepicker({
                            autoclose: true,
                            format:'yyyy-mm-dd'
                          });
  }

  $(document).ready(function() {

        datePicker();

     // var compcont = $('#plancont');

       $('#addComponent').on('click', function() {
                        $('.datepickerDiv').clone().appendTo('#plancont').removeClass('datepickerDiv');
                        datePicker()
         });


        $(document).on('click',".remComponent",function() {
            if($('#plancont').text()==''){
              return toastr.info('You cannot remove the only component');
            }
                   $(this).parents('li').remove();
        });

        // $('.period_start_date').change(function() {
        // $(this).nextAll('.period_end_date:first').val( $(this).val());
        // return false;
        // });
        $(document).on('click',".deleteLeavePlan",function() {
          leave_id=$(this).attr('id');
          toastr.info('Deleting.....');
         $.get('{{ url('/leave/delete_leave_plan') }}/',{ leave_id: leave_id },function(data){
          if (data=='success') {
            toastr.success('Leave Plan deleted successfully');
          }else{
            toastr.info('Leave Plan could not be deleted');
          }
          });

         });


      //           $('.leave_period-daterange').datepicker({
      //   autoclose: true,
      //   format:'yyyy-mm-dd'
      // });
                });
                function changeYear(year){
                    location.href = "{{request()->url()}}"+"?year="+year;
                }
</script>
@endsection
