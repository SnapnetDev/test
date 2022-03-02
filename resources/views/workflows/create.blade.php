@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
          @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('success') }}
                    </div>
                @endif
            <form class="form-horizontal" method="POST" action="{{ route('workflows.save') }}">
              {{ csrf_field() }}
              <div class="panel panel-info panel-line" >
                <div class="panel-heading main-color-bg">
                  <h3 class="panel-title">Workflow Details</h3>
                </div>

                <div class="panel-body">


                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="">
                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                  </div>

                </div>

                </div>

                <div class="panel  panel-info panel-line">
                  <div class="panel-heading ">
                    <h3 class="panel-title">Stage Details</h3>
                  </div>

                  <div class="panel-body">
                    <ul id="stgcont">

                    </ul>
                    <button type="button" id="addStage" name="button" class="btn btn-primary">New Stage</button>
                  </div>
                  </div>
                  <button type="submit" class="btn btn-primary">
                      Create Workflow
                  </button>
                </form>

  </div>
</div>

@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.users').select2();
  $('#stgcont').sortable();

  var stgcont = $('#stgcont');
        var i = $('#stgcont li').length + 1;

        $('#addStage').on('click', function() {
          //console.log('working');
                $(' <li><div class="form-cont" > <div class="form-group"> <label for="">Name</label> <input type="text" class="form-control" name="stagename[]" id="" placeholder="" required> </div><div class="form-group type"> <label for="">Type</label> <select class="form-control select-type " name="type[]" >  <option value="1">User</option> <option value="2">Role</option> <option value="3">User Group</option></select> </div> <div class="form-group users-div"> <label for="">Users</label> <select class="form-control users" name="user_id[]" > @forelse ($users as $user) <option value="{{$user->id}}">{{$user->name}}</option> @empty <option value="">No Users Created</option> @endforelse </select> </div> <div class="form-group roles-div"> <label for="">Roles</label> <select class="form-control roles" name="role[]" >   @forelse ($roles as $role) <option value="{{$role->id}}">{{$role->name}}</option> @empty <option value="">No Roles Created</option> @endforelse </select> </div><div class="form-group groups-div"> <label for="">User Groups</label> <select class="form-control groups" name="group[]" >   @forelse ($groups as $group) <option value="{{$group->id}}">{{$group->name}}</option> @empty <option value="">No User Group Created</option> @endforelse </select> </div> <div class="form-group"> <button type="button" class="btn btn-primary " id="remStage">Remove Stage</button> </div> </div> </li>').appendTo(stgcont);
                //console.log('working'+i);
               $('#stgcont li').last().find('.roles-div').hide();
               $('#stgcont li').last().find('.roles-div').find('.roles').attr("disabled",true);
                $('#stgcont li').last().find('.groups-div').hide();
               $('#stgcont li').last().find('.groups-div').find('.groups').attr("disabled",true);
                i++;
                return false;
        });

        $(document).on('click',"#remStage",function() {
          //console.log('working'+i);
                if( i > 1 ) {
                   console.log('working'+i);
                        $(this).parents('li').remove();
                        i--;
                }
                return false;
    });
        $(document).on('change',".select-type",function() {


         if (this.value==3)
            {
              $(this).parents('li').find('.users-div').find('.users').attr("disabled",true);
              $(this).parents('li').find('.users-div').hide();
              $(this).parents('li').find('.roles-div').find('.roles').attr("disabled",true);
              $(this).parents('li').find('.roles-div').hide();
               $(this).parents('li').find('.groups-div').find('.groups').removeAttr("disabled");
             $(this).parents('li').find('.groups-div').show();


            }

          if (this.value==2)
            {
              $(this).parents('li').find('.users-div').find('.users').attr("disabled",true);
              $(this).parents('li').find('.users-div').hide();
              $(this).parents('li').find('.groups-div').find('.groups').attr("disabled",true);
              $(this).parents('li').find('.groups-div').hide();
               $(this).parents('li').find('.roles-div').find('.roles').removeAttr("disabled");
             $(this).parents('li').find('.roles-div').show();


            }
            if (this.value==1)
            {
              $(this).parents('li').find('.groups-div').find('.groups').attr("disabled",true);
              $(this).parents('li').find('.groups-div').hide();
              $(this).parents('li').find('.roles-div').find('.roles').attr("disabled",true);

                $(this).parents('li').find('.users-div').find('.users').removeAttr("disabled");
                $(this).parents('li').find('.users-div').show();
             $(this).parents('li').find('.roles-div').hide();


            }


          });
});
</script>

@endsection
