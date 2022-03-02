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
      <h1 class="page-title">Workflows</h1>
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
         @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">{{$workflow->name}} Info</h3>
              </div>

              <div class="panel-body">
            <ul class="list-group">
              <li class="list-group-item">Name: {{$workflow->name}}</li>
              <li class="list-group-item"> Created At: {{$workflow->created_at}}</li>
              <li class="list-group-item"> Stages in Workflow: {{$workflow->stages->count()}}</li>
              <li class="list-group-item"><input type="checkbox" class="active-toggle" id="{{$workflow->id}}" {{$workflow->status==1?'checked':''}} data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger"></li>

            </ul>
          </div>
          <div class="panel-footer">
            <a href="{{ route('workflows.edit',$workflow->id) }}" class="btn btn-primary">
                Edit Workflow
            </a>

          </div>
        </div>
        <div class="panel panel-info panel-line">
          <div class="panel-heading main-color-bg">
            <h3 class="panel-title">{{$workflow->name}}  Stages</h3>
          </div>

          <div class="panel-body">
        <ul class="list-group">
          @forelse ($workflow->stages as $stage)
          <li class="list-group-item"><strong>Name:</strong> {{$stage->name}}</li>
                <li class="list-group-item"><strong>Type:</strong> {{$stage->type==1?"User":($stage->type==2?"Role":($stage->type==3?"Group":""))}}</li>
          <li class="list-group-item"><strong>User/Group/Role Name:</strong> {{$stage->type==1?$stage->user->name:($stage->type==2?$stage->role->name:($stage->type==3?$stage->group->name:""))}}</li>
          <li class="list-group-item"><strong>Position:</strong> {{$stage->position+1}}</li>
          <br>
          @empty
          There are no stages in this workflow
          @endforelse


        </ul>
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
<script type="text/javascript">
  $(document).ready(function() {
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
           }else{
             toastr.error('Disabled!', 'Workflow Status')
           }


         }
       );

   });
 });
</script>
@endsection
