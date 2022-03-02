@extends('layouts.master')
@section('stylesheets')
 <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">User Groups</h1>
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

          <div class="col-md-9">
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
            <form class="form-horizontal" method="POST" action="{{ route('groups.update',$group->id) }}">
              {{ csrf_field() }}
              @method('PUT')
              <div class="panel panel-info panel-line">
                <div class="panel-heading main-color-bg">
                  <h3 class="panel-title">Group Details</h3>
                </div>

                <div class="panel-body">


                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{$group->name}}";>
                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                  </div>

                  <div class="form-group">
                    <label for="">Users in Group</label>
                    <select class="form-control group-multiple" name="user_id[]" multiple>
                      @forelse ($users as $user)
                        <option value="{{$user->id}}" {{ $group->users->contains('id',$user->id)?'selected':'' }}>{{$user->name}}</option>
                      @empty
                        <option value="">No Users Created</option>
                      @endforelse
                    </select>
                    <!-- <p class="help-block">Help text here.</p> -->
                  </div>
                </div>
                <div class="panel-footer">
                  <button type="submit" class="btn btn-primary">
                      Save Changes
                  </button>

                </div>
                </div>
                </form>





              <!-- Latest Users -->

          </div>
        <div class="col-md-3">
          <div class="panel panel-info panel-line">
            <div class="panel-heading main-color-bg">
              <h3 class="panel-title">Groups</h3>
            </div>
            <div class="panel-body">
              <div id="data" class="demo"></div>

            </div>
            </div>
        </div>
        </div>



      </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.group-multiple').select2();
});
</script>
@endsection
