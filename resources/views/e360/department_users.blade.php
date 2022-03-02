@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <style media="screen">
    .form-cont{
      border: 1px solid #cccccc;
      padding: 10px;
      border-radius: 5px;
    }
    #stgcont {
      list-style: none;
    }
    #stgcont li{
      margin-bottom: 10px;
    }
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Balance Scorecard Department Selection</h1>
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

          <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
                 @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('error') }}
                </div>
            @endif
            <div class="panel panel-info ">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">{{$det->department->name}} Department Employees List</h3>
              </div>
              
                
              <div class="panel-body">

              <table class="table table-striped" id="userstable">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Job Role</th>
                    <th>Has Reviewed</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $sn=1;
                  @endphp
                  @foreach ($det->department->users as $user)
                    <tr>
                      <td>{{$sn}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->grade?$user->grade->level:''}}</td>
                    <td>{{$user->job?$user->job->title:''}}</td>
                    <td><span class=" tag   
                      {{Auth::user()->myreviews?(Auth::user()->myreviews->where('e360_det_id',$det->id)->contains('user_id', $user->id)?'tag-success':'tag-warning'):'tag-warning'}}">
                      {{Auth::user()->myreviews?(Auth::user()->myreviews->where('e360_det_id',$det->id)->contains('user_id', $user->id)?'Yes':'No'):'No'}}</span></td>
                    <td><a href="{{url('e360/get_evaluation')."/?det=".$det->id."&employee=".$user->id}}"   class="btn btn-info   btn-sm "   ><i class="fa fa-pencil" aria-hidden="true"></i>Review</a></td>
                    </tr>
                  @php
                    $sn++;
                  @endphp
                  @endforeach
                  
                </tbody>
              </table>
              </div>
         
          
        </div>


          </div>
          </div>

  </div>


</div>
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
 <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('#userstable').DataTable();
    $('.input-daterange').datepicker({
    autoclose: true
});
$('.select2').select2();
    var selected = [];
     
     $('.active-toggle').change(function() {
       var id = $(this).attr('id');
        var isChecked = $(this).is(":checked");
        console.log(isChecked);
        $.get(
          '{{ route('workflows.alter-status') }}',
          { id: id, status: isChecked },
          function(data) {
            if(data=="enabled"){
              toastr.success('Enabled!', 'Workflow Status');
            }
            if(data=="disabled"){
              toastr.error('Disabled!', 'Workflow Status')
            }else{
              toastr.error(data, 'Workflow Status');

            }


          }
        );

    });
 $('#user').select2({
        ajax: {
         delay: 250,
         processResults: function (data) {
              return {        
        results: data
          };
        },
        url: function (params) {
        return '{{url('bsc/usersearch')}}';
        } 
        }
    });
} );
  </script>
@endsection