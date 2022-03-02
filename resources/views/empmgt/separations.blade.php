@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
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
      <h1 class="page-title">Separations</h1>
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
                <h3 class="panel-title">Employees Separated</h3>
                  <div class="panel-actions">

                      <button class="btn btn-info" data-toggle="modal" data-target="#addSeparationModal">New Separation
                      </button>
                  </div>

              </div>

              <div class="panel-body">
            <table class="table table-stripped" id="">
              <thead>
                <tr>
                  <th>Name</th>
                   <th>Hiredate</th>
                   <th>Date Separated</th>
                   <th>Time in Employment</th>
                    <th>Separation Stage</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($separations as $separation)
                    <tr>
                      <td>{{ $separation->user->name }}</td>
                      <td>{{ date("F j, Y", strtotime($separation->hiredate)) }}</td>
                      <td>{{ date("F j, Y", strtotime($separation->date_of_separation)) }}</td>
                      <td>{{ $diff = Carbon\Carbon::parse($separation->hiredate)->diffForHumans(Carbon\Carbon::parse($separation->date_of_separation),true,false,5) }} </td>
                        <td>{{$separation->stage==1?'Exit Form Completion':($separation->stage==2?'Exit Checklist Approval':($separation->stage==3?'Exited':''))}}</td>
                      <td>
                          <span  data-toggle="tooltip" title="Delete"><a href="{{ url('separation/notify_staff_separation?separation_id='.$separation->id) }}" title="Notify Staff Via Email"  class="btn-sm btn btn-success   "><i class="fa fa-envelope" aria-hidden="true"></i></a></span>
                 <span  data-toggle="tooltip" title="View"><a href="{{ url('separation/view_separation?separation_id='.$separation->id) }}"  class="btn-sm btn btn-info   "><i class="fa fa-eye" aria-hidden="true"></i></a></span>
                <span  data-toggle="tooltip" title="Delete"><a href="{{ url('separation/delete_separation?separation_id='.$separation->id) }}"  class="btn-sm btn btn-danger   "><i class="fa fa-trash" aria-hidden="true"></i></a></span>
            </td>

                    </tr>
                  @endforeach

              </tbody>

            </table>
              {!! $separations->appends(Request::capture()->except('page'))->render() !!}
          </div>
        </div>


          </div>

          </div>

  </div>


</div>
@include('separations.modals.addseparation')
 @endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
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
  $(function() {
      $('.datepicker').datepicker();
      $('.select2').select2();
      $('#separation_type').select2({
          ajax: {
              delay: 250,
              processResults: function (data) {
                  return {
                      results: data
                  };
              },
              url: function () {
                  return '{{url('separation/separation_types')}}/';
              }
          },
          tags: true
      });
      $(document).on('submit','#addSeparationForm',function(event){
          event.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{route('separation.store')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  if(data=='success'){
                      toastr.success("User has been designated as separated successfully",'Success');
                      $('#addSeparationModal').modal('toggle');
                      location.reload();
                  }
                  if(data=='failed'){
                      toastr.error("You have no set the HireDate",'Wrong Hire date');
                  }


              },
              error:function(data, textStatus, jqXHR){
                  jQuery.each( data['responseJSON'], function( i, val ) {
                      jQuery.each( val, function( i, valchild ) {
                          toastr.error(valchild[0]);
                      });
                  });
              }
          });

      });

  });
  </script>
@endsection
