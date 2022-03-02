@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
        <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
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
      <h1 class="page-title">Jobs</h1>
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
            @endif
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Job List</h3>

                <div class="panel-actions"> 
                  <a href="{{ route('jobs.create',['department_id'=>$department->id]) }}" class="btn btn-info">Create Job</a>
                </div>
              </div>

              <div class="panel-body">
            <table class="table table-stripped" id="">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Parent</th>
                  <th>Department</th>
                  {{-- <th>No. of Persons</th> --}}
                  <th>No. of Persons in</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($jobs as $job)
                    <tr>
                      <td>{{ $job->title }}</td>
                      <td>{{$job->parent? $job->parent->title:'' }}</td>
                      <td>{{ $job->department->name }}</td>
                      {{-- <td>{{ $job->personnel }}</td> --}}
                      <td>{{ $job->users->count() }}</td>
                      
                      <td>
                              <div class="btn-group" role="group">
                              <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                              data-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                            <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                               <a class="dropdown-item"  href="{{ route('jobs.view',$job->id) }}"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Job Details</a>
                              <a class="dropdown-item"   href="{{ route('jobs.edit',$job->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Job Details</a>
                               <a class="dropdown-item" id="{{$job->id}}" onclick="deleteJob(this.id);"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Job</a>
                              
                            </div>
                          </div></td>
                      

                    </tr>
                  @endforeach

              </tbody>

            </table>
              {!! $jobs->appends(Request::capture()->except('page'))->render() !!}
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
  <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('.input-daterange').datepicker({
    autoclose: true
});
$('.select2').select2();
    var selected = [];
     
    
{{--
    $('#gtable tbody').on('click', 'tr', function () {
        var id = this.id;
        var index = $.inArray(id, selected);

        // if ( index === -1 ) {
        //     selected.push( id );
        // } else {
        //     selected.splice( index, 1 );
        // }

        $(this).toggleClass('selected');
    }); --}}
} );
  function deleteJob(job_id){
  
  alertify.confirm('Are you sure you want to delete this job ?', function(){ 
  $.get('{{ url('jobs/delete') }}/'+job_id, 
    function(data, status){
        if(data=="success"){
           toastr.success('Job Deleted Successfully');
           setTimeout(function(){
            window.location.reload();
           },2000);
           return; 
        }
        toastr.error(data);
    });
    });

}
  </script>
@endsection