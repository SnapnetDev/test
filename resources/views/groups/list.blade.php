@extends('layouts.master')
@section('stylesheets')
  {{-- <link rel="stylesheet" href="{{ asset('datatables/DataTables-1.10.16/css/dataTables.bootstrap.min.css')}}"> --}}
  <link rel="stylesheet" href="{{ asset('datatables/datatables.min.css')}}">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Groups Details</h3>
                <div class="panel-actions">
                  <a href="{{ route('groups.create') }}" class="btn btn-info">Create Group</a>
                </div>
              </div>

              <div class="panel-body">
            <table class="table table-stripped" id="gtable">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Created At</th>
                  <th>No. of Users</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($groups as $group)
                    <tr>
                      <td>{{ $group->name }}</td>
                      <td>{{ $group->created_at }}</td>
                      <td><span class="badge">{{ $group->users_count }}</span></td>
                      <td><span  data-toggle="tooltip" title="Edit"><a  class="my-btn   btn-sm text-info" id="{{$group->id}}" href="{{ route('groups.edit',$group->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
            		<span  data-toggle="tooltip" title="Delete"><a  id="{{$group->id}}" class="my-btn   btn-sm text-danger" onclick="deletesubject(this.id)"><i class="fa fa-trash" aria-hidden="true"></i></a></span>

                <span  data-toggle="tooltip" title="View"><a href="{{ route('groups.view',$group->id) }}"  class="my-btn   btn-sm text-success"><i class="fa fa-eye" aria-hidden="true"></i></a></span>
            </td>

                    </tr>
                  @endforeach

              </tbody>

            </table>
            {!! $groups->appends(Request::capture()->except('page'))->render() !!}
          </div>
        </div>


          </div>
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Filters</h3>
                <div class="panel-actions">
                  <a href="{{ route('groups.create') }}"></a>
                </div>
              </div>
              <form class="" action="{{route('groups')}}" method="get" >


              <div class="panel-body">
                <div class="form-group">
                  <label for="">Name Contains</label>

                  <input type="text" name="name_contains" class="form-control col-lg-6" id="email_t" placeholder="" value="{{ request()->name_contains }}">

                </div>
                <div class="form-group">
                  <label for="">Users</label>
                  <select class="select2 form-control" name="userftype">
                    <option value="or">OR</option>
                    <option value="and">AND</option>
                  </select>
                  <select id="role_f" class=" select2 form-control col-lg-6" name="user[]" multiple>
                    @forelse ($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                    @empty
                      <option value="">No Users Created</option>
                    @endforelse
                  </select>


                </div>
                <div class="form-group">
                  <label for="">Created At</label>
                  <div class="input-daterange input-group" id="datepicker">
                    <input type="text" class="input-sm form-control" name="created_from" placeholder="From date" value="{{ request()->created_from }}"/>
                    <span class="input-group-addon">to</span>
                    <input type="text" class="input-sm form-control" name="created_to" placeholder="To date" value="{{ request()->created_to }}"/>
                </div>
                </div>
                <div class="form-group">
                  <label for="">Updated At</label>
                  <div class="input-daterange input-group" id="datepicker">
                    <input type="text" class="input-sm form-control" name="created_from" placeholder="From date" value="{{ request()->updated_from }}"/>
                    <span class="input-group-addon">to</span>
                    <input type="text" class="input-sm form-control" name="created_to" placeholder="To date" value="{{ request()->updated_to }}"/>
                </div>
                </div>
                <button type="submit" class="btn btn-info" >Filter</button>
                <button type="reset" class="btn btn-warning pull-right" >Clear Filters</button>
              </div>
              </form>
              </div>
              <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Support</h3>
              </div>

              <div class="panel-body">
                Need help? Email us at support@snapnet.com.ng
              </div>
            </div>
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Group Help</h3>
              </div>

              <div class="panel-body">
                <ul>
                  <li></li>
                </ul>
              </div>
            </div>
            </div>
            
          </div>
          </div>



      </div>
   
@endsection
@section('scripts')
  {{-- <script type="text/javascript" src="{{ asset('datatables/DataTables-1.10.16/js/dataTables.bootstrap.min.js')}}"></script> --}}
  <script type="text/javascript" src="{{ asset('datatables/datatables.min.js')}}"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

  <script type="text/javascript">
  $(document).ready(function() {
    $('.select2').select2();
    $('.input-daterange').datepicker({
    autoclose: true
});



} );
  </script>


@endsection
