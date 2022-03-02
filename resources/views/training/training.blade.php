@extends('layouts.master')
@section('stylesheets')
  {{-- <link rel="stylesheet" href="{{ asset('datatables/DataTables-1.10.16/css/dataTables.bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title"> Trainings</h1>
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
          {{-- Left Div --}}
          <div class="col-md-9">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @elseif(Session::has('error'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Training Details</h3>
                <div class="panel-actions">
                  <a href="{{ route('create_training') }}" class="btn btn-info">Create New Training</a>
                </div>
              </div>

              <div class="panel-body">
                <table class="table table-stripped" id="tr_table">
                  <thead>
                    <tr>
                      <th>Training</th>
                      <th>Training Mode</th>
                      <th>Duration</th>
                      <th>Department</th>
                      <th>Status</th>
                      <th>Remark</th>
                      <th style="text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($trainings as $training)
                        <tr>
                          <td>{{ $training->training_name }}</td>
                          <td>{{ $training->training_mode }}</td>
                          <td>{{ $training->duration }} Days</td>
                          <td>@if($training->department){{ $training->department->name }}@endif</td>
                          <td>
                            {{$training->status_id}}
                          </td>
                          <td>{{ $training->remark }}</td>
                          <td>
                              <a data-toggle="tooltip" title="Delete" id="{{$training->id}}" class="my-btn btn-sm text-danger pull-right" onclick="deletesubject(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>
                              </a>
                              <a data-toggle="tooltip" title="View" href="{{route('view_training', $training->id)}}" class="my-btn btn-sm text-success pull-right"><i class="fa fa-eye" aria-hidden="true"></i></a>
                              <a class="my-btn btn-sm text-info pull-right" id="{{$training->id}}" data-toggle="tooltip" title="Edit" href="{{route('edit_training', $training->id)}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                              

                              <div class="dropdown" style="">
                                <button class="btn btn-default dropdown-toggle" type="button" id="{{$training->id}}" data-toggle="dropdown" style="font-size:9px">
                                <span class="caret"></span>
                                </button>
                                 <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="">
                                  <li>
                                    <a class="my-btn btn-sm text-dark" id="{{$training->id}}" onclick="showAddUser({{$training->id}});" style="cursor: pointer;"><i class="fa fa-plus" aria-hidden="true"> Add User</i></a>
                                  </li>
                                  <li>  </li>
                                  <li>
                                    <a class="my-btn btn-sm text-dark" id="{{$training->id}}" onclick="showAddGroup({{$training->id}});" onmousedown="setId({{$training->id}})" style="cursor: pointer;"><i class="fa fa-plus" aria-hidden="true"> Add Group</i></a>
                                  </li>
                                </ul> 
                               </div>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                </table>
              {{-- {!! $trainings->appends(Request::capture()->except('page'))->render() !!} --}}
            </div>
          </div>


          </div>




          {{-- Budget --}}
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title"></h3>
                <div class="panel-actions">
                  <a href="{{ route('groups.create') }}"></a>
                </div>
              </div>

             

            </div>
              
          
            </div>
            
          </div>
        </div>



      </div>


<!-- Modal -->
      @include('training.modals.addUserToTraining')
      @include('training.modals.addGroupToTraining')
      @include('training.modals.addBudgetToTraining')
   
@endsection
@section('scripts')
  {{-- <script type="text/javascript" src="{{ asset('datatables/DataTables-1.10.16/js/dataTables.bootstrap.min.js')}}"></script> --}}
  <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

  <script type="text/javascript">
    $(function() 
    { 

        $(document).on('submit','#addUserTrainingForm',function(event)
        {
         event.preventDefault();
         var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url         : '{{ route('save_training_user') }}',
                data        : formdata ? formdata : form.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                success     : function(data, textStatus, jqXHR){

                    toastr.success("Changes saved successfully",'Success');
                   $('#addUserTrainingModal').modal('toggle');
              
              // console.log(data);

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

        $(document).on('submit','#addGroupTrainingForm',function(event)
        {
         event.preventDefault();
         var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url         : '{{ route('save_training_group') }}',
                data        : formdata ? formdata : form.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                success     : function(data, textStatus, jqXHR){

                    toastr.success("Changes saved successfully", 'Success');
                   $('#addGroupTrainingModal').modal('toggle');
              
              // console.log(data);

                },
                error:function(data, textStatus, jqXHR){
                   jQuery.each( data['responseJSON'], function( i, val ) 
                   {
                    jQuery.each( val, function( i, valchild ) {
                    toastr.error(valchild[0]);
                  });  
                  });
                }
            });
          
        });

    });


    $(function() 
    {
        $('.select2').select2();
        $('.input-daterange').datepicker(
        {
          autoclose: true
        });


        $('.tr_table').DataTable();
    });


    //function to process form data
    function approveForm(formid, route)
    {
       formdata= new FormData($('#'+formid)[0]);
       formdata.append('_token','{{csrf_token()}}');
      
        $.ajax(
        {
            // Your server script to process the upload
            url: route,
            type: 'POST',
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data, status, xhr)
            {
                if(data.status=='ok')
                {
                    $('#trainee_id').empty();     $('#purpose').val('');    $('#amount').val('');    $('#status_id').empty();   
                    //toastr.success(data.success, {timeOut:60000});
                    return;

                    alert(data.success);
                }

                //return toastr.error(data.success, {timeOut:60000});
                if(data.status == 'error')
                {
                     alert(data.success);
                }             

            },
        })

    }


    $(function()
    {
        $("#budgetForm").on('submit',function(e)
        { 
            e.preventDefault();
            approveForm('budgetForm', "{{url('/training/budget/save_budget')}}");
        });
    });

    function showAddUser(training_id)
    {
      $.get('{{ url('/training/info') }}/',{ training_id: training_id },function(data){
        
       $('#edittrainingid').val(data.id);
        
    
      
       jQuery.each( data.users, function( i, val ) {
       $('#trainees').find('option:eq('+val.id+')').attr('selected', true);
        // $("#trainees").append($('<option>', {value:val.id, text:val.name,selected:'selected'}));
         // console.log(val.name);
                }); 
      });
      $('#addUserTrainingModal').modal();
    }

    function showAddGroup(training_id)
    {
      $.get('{{ url('/training/info') }}/',{ training_id: training_id }, function(data)
      {        
       $('#edittrainingid').val(data.id);
        
       jQuery.each( data.budgets, function( i, val ) 
       {
          $('#groups').find('option:eq('+val.id+')').attr('selected', true);
        }); 
      });
      $('#addGroupTrainingModal').modal();
    }




    function putId(id)
    {
      $('#training_id').val(id);   
    }

    function setId(id)
    {
      $('#t_id').val(id);   
    }

  </script>


@endsection
