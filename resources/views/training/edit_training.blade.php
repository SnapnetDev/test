@extends('layouts.master')
@section('stylesheets')
  {{-- <link rel="stylesheet" href="{{ asset('datatables/DataTables-1.10.16/css/dataTables.bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('datatables/datatables.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('global/vendor/alertify/alertify.css') }}" rel="stylesheet" />
@endsection

@section('content')
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">Trainings</h1>
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
          <div class="col-md-6">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Edit Training</h3>
                <div class="panel-actions">
                  <a href="{{ route('training') }}" class="btn btn-info">List Training</a>
                </div>
              </div>


                <form class="form-horizontal" method="POST" action="{{ route('save_training') }}">
                {{ csrf_field() }}                
                <input type="hidden" class="form-control" placeholder="" id="id" name="id" value="{{$id}}">

                  <div class="panel panel-info panel-line" style="">

                    <div class="panel-body">
                      <div class="form-group">
                          <div class="col-md-6">
                              <label for="training_name" class="label-marg">* Training Name</label>
                              <input type="text" class="form-control" placeholder="" id="training_name" name="training_name" value="{{$train->training_name}}" required="">
                          </div>

                          <div class="col-md-6"> 
                          <label for="training_mode" class="label-marg">* Training Mode</label>
                              <select class="form-control" name="training_mode" id="training_mode" required>
                                @if($train)<option value="{{$train->training_mode}}"> {{$train->training_mode}} </option> @else <option value=""> </option> @endif
                                <option value="Classroom"> Classroom </option> 
                                <option value="Hands-on"> Hands-on </option>
                                <option value="Online"> Online </option>
                              </select>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-6">
                              <label for="duration" class="label-marg">Duration</label>
                              <input type="text" class="form-control" placeholder="" id="duration" name="duration" value="{{$train->duration}}">
                          </div>

                          <div class="col-md-6">
                          <label for="department_id" class="label-marg">Department</label>
                              <select class="form-control" name="department_id" id="department_id">
                                @if($one_dept)<option value="{{$one_dept->id}}"> {{$one_dept->name}} </option> @else <option value=""> </option> @endif
                                @if($dept)
                                    @foreach($dept as $dept)
                                        <option value="{{$dept->id}}"> {{$dept->name}} </option>                            
                                    @endforeach
                                @endif
                              </select>
                          </div>                         
                      </div>

                      <div class="form-group">
                          <div class="col-md-12">
                              <label for="remark" class="label-marg">Remark</label>
                              <textarea rows="2" class="form-control" placeholder="" id="remark" name="remark">{{$train->remark}}</textarea>
                          </div>
                      </div>

                      
                    </div>


                    <div class="panel-footer" style="margin-bottom: 20px; margin-right: 15px">
                      <button type="submit" class="btn btn-success pull-right" onclick="return confirm('Are you sure you want to UPDATE Training Details?')"> Save Training </button>
                    </div> <br>
                  </div>
              </form>

          </div>
          </div>


          {{-- Users --}}
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Users Listed for Training 
                  <button type="button" class="btn btn-info btn-sm pull-right" data-toggle="tooltip" title="Add New User To Training" onclick="showAddUser({{$id}});"><i class="fa fa-plus" aria-hidden="true"
                    style="font-size: 10px"></i> 
                  </button>
                </h3>
                <div class="panel-actions">
                  <a href="#"></a>
                </div>
              </div>
              <table class="table table-stripped" id="tr_table">
                  <thead>
                    <tr>
                      <th>Trainees</th>
                      <th style="text-align: center;"> Action </th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($train->users as $train_user)
                        <tr>
                          <td>{{ $train_user->name }}</td>
                          <td>
                            <div class="dropdown" style="">
                                <button class="btn btn-default dropdown-toggle pull-right" type="button" id="" data-toggle="dropdown" style="font-size:9px">
                                <span class="caret"></span>
                                </button>
                                 <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="">
                                  <li>
                                    <a data-toggle="tooltip" id="{{$train_user->id}}" class="my-btn btn-sm text-danger" style="cursor: pointer;" onclick="deleteTrainingUser(this.id)"><i class="fa fa-trash" aria-hidden="true"> Delete User</i>   </a>
                                  </li>
                                  <li>  </li>
                                  <li>
                                    <a data-toggle="tooltip" id="{{$train_user->id}}" class="my-btn btn-sm text-warning" style="cursor: pointer;" onclick="declineTrainingUser(this.id)"><i class="fa fa-remove" aria-hidden="true"> Decline Training</i>    </a>
                                  </li>
                                  <li>  </li>
                                  <li>
                                    <a data-toggle="tooltip" id="{{$train_user->id}}" class="my-btn btn-sm text-success" style="cursor: pointer;" onclick="approveTrainingUser(this.id)"><i class="fa fa-check" aria-hidden="true"> Approve Training</i>   </a>
                                  </li>
                                </ul> 
                              </div>
                        </tr>
                      @endforeach
                  </tbody>
                </table>
              
              </div>
          </div>


          {{-- Budget --}}
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Group Training
                  <button type="button" class="btn btn-info btn-sm pull-right" data-toggle="tooltip" title="Add New Group To Training" onclick="showAddGroup({{$id}});"><i class="fa fa-plus" aria-hidden="true"
                    style="font-size: 10px"></i> 
                  </button>
                </h3>
                <div class="panel-actions">
                  <a href="#"></a>
                </div>
              </div>

                <table class="table table-stripped" id="tr_table">
                  <thead>
                    <tr>
                      <th>Group</th>
                      <th style="text-align: right;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($train->groups as $train_group)
                        <tr>
                          <td>{{ $train_group->name }}</td>
                          <td>
                            <div class="dropdown" style="">
                                <button class="btn btn-default dropdown-toggle pull-right" type="button" id="" data-toggle="dropdown" style="font-size:9px">
                                <span class="caret"></span>
                                </button>
                                 <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="">
                                  <li>
                                    <a data-toggle="tooltip" id="{{$train_group->id}}" class="my-btn btn-sm text-danger" style="cursor: pointer;" onclick="deleteTrainingUser(this.id)"><i class="fa fa-trash" aria-hidden="true"> Delete User</i>   </a>
                                  </li>
                                  <li>  </li>
                                  <li>
                                    <a data-toggle="tooltip" id="{{$train_group->id}}" class="my-btn btn-sm text-warning" style="cursor: pointer;" onclick="declineTrainingUser(this.id)"><i class="fa fa-remove" aria-hidden="true"> Decline Training</i>    </a>
                                  </li>
                                  <li>  </li>
                                  <li>
                                    <a data-toggle="tooltip" id="{{$train_group->id}}" class="my-btn btn-sm text-success" style="cursor: pointer;" onclick="approveTrainingUser(this.id)"><i class="fa fa-check" aria-hidden="true"> Approve Training</i>   </a>
                                  </li>
                                </ul> 
                              </div>
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




<!-- Modal -->
      @include('training.modals.addUserToTraining')
      @include('training.modals.addGroupToTraining')

   
@endsection
@section('scripts')
  {{-- <script{{ asset('datatables/DataTables-1.10.16/js/dataTables.bootstrap.min.js')}}"></script> --}}
  <script src="{{ asset('datatables/datatables.min.js')}}"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
  <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/alertify/alertify.js')}}"></script>

  <script type="text/javascript">
    $(function() 
    {

        $('.select2').select2();
        $('.input-daterange').datepicker(
        {
          autoclose: true
        });


        $('.tr_table').DataTable();

        $('#proposed_start_date').datepicker(
        {
          autoclose: true, format: "yyyy-mm-dd"
        });

        $('#proposed_end_date').datepicker(
        {
          autoclose: true, format: "yyyy-mm-dd"
        });
    });



    function deleteTrainingUser(user_id)
    {
      alertify.confirm("Are You Sure You Want To Remove User From Training?",
        function()
        {
          $.get('{{ url('/training/delete_training_user') }}/',{ user_id: user_id, training_id:{{$train->id}} },
              function(data)
              {
              if (data=='success') 
              {
                toastr.success("User in Training Deleted Successfully", 'Success');
              }
              else
              {
                toastr.error("Error Deleting Training User", 'danger');
              }
             
            });
        },
        function()
        {
          alertify.error('Cancelled Approval');
        });       
    } 


    function approveTrainingUser(user_id)
    {
      alertify.confirm("Approve Training For This User ?",
        function()
        {
          $.get('{{ url('/training/approve_training_user') }}/',{ user_id: user_id, training_id:{{$train->id}} },
              function(data)
              {
              if (data=='success') 
              {
                toastr.success("Training For This User Approved Successfully", 'Success');
              }
              else
              {
                toastr.error("Error Approving Training For User", 'danger');
              }
             
            });
        },
        function()
        {
          alertify.error('Cancelled Delete');
        });       
    }


    function declineTrainingUser(user_id)
    {
      alertify.confirm("Decline Training For This User ?",
        function()
        {
          $.get('{{ url('/training/decline_training_user') }}/',{ user_id: user_id, training_id:{{$train->id}} },
              function(data)
              {
              if (data=='success') 
              {
                toastr.success("Training For This User Declined Successfully", 'Success');
              }
              else
              {
                toastr.error("Error Declining Training For User", 'danger');
              }
             
            });
        },
        function()
        {
          alertify.error('Cancelled Delete');
        });       
    }




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
      $.get('{{ url('/training/info') }}/',{ training_id: training_id },function(data)
      {       
       $('#edittrainingid').val(data.id);  
      
       jQuery.each( data.users, function( i, val ) 
       {
          $('#trainees').find('option:eq('+val.id+')').attr('selected', true);
       }); 
      });
      $('#addGroupTrainingModal').modal();
    }
  </script>


@endsection