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
                <h3 class="panel-title">Workflow Details</h3>

                <div class="panel-actions">
                  <a href="{{ route('workflows.create') }}" class="btn btn-info">Create Workflow</a>
                </div>
              </div>

              <div class="panel-body">
            <table class="table table-stripped" id="">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Active</th>
                  <th>Created At</th>
                  <th>No. of Stages</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($workflows as $workflow)
                    <tr>
                      <td>{{ $workflow->name }}</td>
                      <td><input type="checkbox" class="active-toggle" id="{{$workflow->id}}" {{$workflow->status==1?'checked':''}} data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"></td>
                      <td>{{ $workflow->created_at }}</td>
                      <td><span class="badge">{{ $workflow->stages_count }}</span></td>
                      <td><span  data-toggle="tooltip" title="Edit"><a  class="btn btn-sm btn-info" id="{{$workflow->id}}" href="{{ route('workflows.edit',$workflow->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>


                <span  data-toggle="tooltip" title="View"><a href="{{ route('workflows.view',$workflow->id) }}"  class="btn-sm btn btn-info   "><i class="fa fa-eye" aria-hidden="true"></i></a></span>
            </td>

                    </tr>
                  @endforeach

              </tbody>

            </table>
              {!! $workflows->appends(Request::capture()->except('page'))->render() !!}
          </div>
        </div>


          </div>
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                  <h3 class="panel-title">Filters</h3>
                </div>
              <form class="" action="{{route('workflows')}}" method="get" >


                <div class="panel-body">
                  <div class="form-group">
                    <label for="">Name Contains</label>

                    <input type="text" name="name_contains" class="form-control col-lg-6" id="name_contains" placeholder="" value="{{ request()->name_contains }}">

                  </div>
                  <div class="form-group" >
                    <label for="">Stages</label>

                    <select id="role_f" class=" select2 form-control col-lg-6" name="stage_id" >
                      <option value="">Select Stage</option>
                      @forelse ($stages as $stage)
                        <option value="{{$stage->id}}">{{$stage->name}}</option>
                      @empty
                        <option value="">No Stages Created</option>
                      @endforelse
                    </select>


                  </div >
                  <br>
                  <br>
                  <div class="form-group">
                    <button type="submit" class="btn btn-info" >Filter</button>
                    <button type="reset" class="btn btn-warning pull-right" >Clear Filters</button>
                  </div>
                </div>
              </form>

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
  </script>
@endsection
