@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">{{__('Leave Request')}}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active">{{__('Leave Request')}}</li>
      </ol>
      <div class="page-header-actions">
        <div class="row no-space w-250 hidden-sm-down">

          <div class="col-sm-6 col-xs-12">
            <div class="counter">
              <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

            </div>
          </div>
          <div class="col-sm-6 col-xs-12">
            <div class="counter">
              <span class="counter-number font-weight-medium" id="time"></span>
            </div>
          </div>
        </div>
      </div>
  </div>
    
      <div class="page-content container-fluid">
        <div class="row" data-plugin="matchHeight" data-by-row="true">
  <!-- First Row -->
  
  
  <!-- End First Row -->
  {{-- second row --}}
  <div class="col-ms-12 col-xs-12 col-md-12">
    <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Leave Spillovers for {{currentYear()}}</h3>
                  
                  </div>
                <div class="panel-body">
                  <div class="table-reponsive">
         <table class="table table striped" id="spill-table">
          <thead>
            <tr>
            <th>Employee</th>
            <th>Employee ID</th>
            <th>Actual Days</th>
            <th>Days Assigned</th>
            <th>Days Used</th>
            <th>Last Modified by</th>
            <th>Modification Reason</th>
            
            <th>Action</th>
          </tr>
          </thead>
          <tbody>

          
          @foreach($spillovers as $spillover)
           
              <tr>

           
            <td>{{$spillover->user->name}}</td>
            <td>{{$spillover->user->emp_num}}</td>
             <td>{{$spillover->actual_days}}</td>
          <td>{{$spillover->days}}</td>
          <td>{{$spillover->used}}</td>
          <td>{{$spillover->modifier?$spillover->modifier->name:''}}</td>
          <td>{{$spillover->modification_reason}}</td>
              
              <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                    data-toggle="dropdown" aria-expanded="false">
                      Action
                    </button>
                  <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                    <a style="cursor:pointer;"class="dropdown-item modifybtn" id="{{$spillover->id}}" ><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Modify</a>
                    
                  </div>
                </div>
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
</div>
  <!-- End Page -->
   @include('leave.modals.modify_leavespill')
   {{-- Leave Request Details Modal --}}
   <div class="modal fade in modal-3d-flip-horizontal modal-info" id="leaveDetailsModal" aria-hidden="true" aria-labelledby="leaveDetailsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Leave Request Details</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12" id="detailLoader"> 
                    
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                
                  
                  <!-- End Example Textarea -->
                </div>
             </div>
         </div>
      </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
  <script type="text/javascript">
      $(document).ready(function() {
    $('.input-daterange').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd'
});
 $('#spill-table').DataTable()
      $('.modifybtn').click( function(event) {
  id=$(this).attr('id');


  
      $('#leavespill_id').val(id);

    $('#modifyLeaveSpillModal').modal();
});
    
    $(document).on('submit','#modifyLeaveSpillForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('leave.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

                 toastr.success("Changes saved successfully",'Success');
                $('#modifyLeaveSpillModal').modal('toggle');
                location.reload();
          
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