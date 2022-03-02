@extends('layouts.master')
@section('stylesheets')
<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
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

          <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">{{$group->name}} Group Info</h3>
                  <div class="panel-actions">
                      <button class="btn btn-info" data-toggle="modal" data-target="#changeRoleModal">Assign Role to Members</button>

                  </div>
              </div>

              <div class="panel-body">
            <ul class="list-group">
              <li class="list-group-item">Name: {{$group->name}}</li>
              <li class="list-group-item"> Created At: {{$group->created_at}}</li>
              <li class="list-group-item"> Members in Group: {{$group->users->count()}}</li>

            </ul>
          </div>
          <div class="panel-footer">
            <a href="{{ route('groups.edit',$group->id) }}" class="btn btn-primary">
                Edit Group
            </a>

          </div>
        </div>
        <div class="panel panel-info panel-line">
          <div class="panel-heading main-color-bg">
            <h3 class="panel-title">{{$group->name}} Group Members</h3>
          </div>

          <div class="panel-body">
       <div class="table-responsive">
        <table class="table table-striped" >
          <thead>
            <tr>
              <th>Employee Name</th>
              <th>Email Address</th>
              <th>Phone</th>
                <th>Current Role</th>
            </tr>
          </thead>
          <tbody>
           @foreach ($group->users as $user)
            <tr>
              <td>{{$user->name}}</td>
              <td>{{$user->email}}</td>
              <td>{{$user->phone}}</td>
                <td>{{$user->role?$user->role->name:''}}</td>
            </tr>
          </tbody>
          @endforeach
        </table>
      </div>
    </div>


          </div>
        </div>
          <div class="col-md-3">

          </div>
          </div>



      </div>
    </div>
  @include('empmgt.modals.changeRole')
@endsection
@section('scripts')
<script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#usertable').DataTable( {
        "paging":   false,
        "searching": false,
        "info":     false
    } );
} );
  $(document).on('click','#assignRole',function(event){
      event.preventDefault();

      role_id=$("#roles").val();
      $.get('{{ url('/groups/assignrole') }}/',{ role_id: role_id,group_id:'{{$group->id}}' },function(data){
          toastr.success("Role Changed Successfully",'Success');
          $('#changeRoleModal').modal('toggle');
          location.reload();
      });
  });
</script>

@endsection
