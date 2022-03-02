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
      <h1 class="page-title">Off Payrol Type</h1>
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
                <h3 class="panel-title">Types</h3>

               
              </div>

              <div class="panel-body">
            <table class="table table-stripped" id="">
              <thead>
                <tr>
                  <th>Name</th>
                   
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($types as $type)
                    <tr>
                      <td>{{ $type->name  }}</td>
                      
                      <td>

                <span  data-toggle="tooltip" title="View Items"><a href=""  class="btn-sm btn btn-info   "><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                <span  data-toggle="tooltip" title="Edit"><a href=""  class="btn-sm btn btn-info   "><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                <span  data-toggle="tooltip" title="Delete"><a href=""  class="btn-sm btn btn-danger   "><i class="fa fa-trash" aria-hidden="true"></i></a></span>
            </td>

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
 @include('empmgt.modals.editsuspension')
 @endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('.datepicker').datepicker();

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
  function prepareEditData(suspension_id)
    {
    $.get('{{ url('/separation/suspension') }}/',{ suspension_id: suspension_id },function(data){
      
     $('#edit_dos').val(data.startdate);
     $('#edit_suspension_ends').val(data.enddate);
     $('#edit_suspension_type').val(data.suspension_type_id);
     $('#edit_commment').val(data.comment);
  
     
    });
    $('#editSuspensionModal').modal();
  }
  </script>
@endsection