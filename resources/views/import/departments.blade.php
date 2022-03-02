@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
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
      <h1 class="page-title">Import Departments</h1>
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
            <form enctype="multipart/form-data" id="emp-import" method="POST" action="{{url('import')}}">
              {{ csrf_field() }}
              <div class="panel panel-info panel-line" >
                <div class="panel-heading main-color-bg">
                  <h3 class="panel-title">Import Departments</h3>
                </div>

                <div class="panel-body">
                  <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Company</label>
                   <select class="form-control" id="company_id" name="company_id">
                        @foreach ($companies as $comp)
                          <option value="{{$comp->id}}">{{$comp->name}}</option>
                        @endforeach
                      </select>
                   
                  </div>
                  </div>
                   <div class="col-md-4">
                    <div class="form-group">
                    <label for="">File to Upload</label>
                    <input type="file" class="form-control"  name="template" >
                   
                  </div>
                  </div>
                  </div>

                  
                
                <input type="hidden" name="type" value="departments" >
                   
                  


                </div>

                </div>

                
                  <button type="submit" class="btn btn-primary">
                     Import Departments
                  </button>
                </form>

  </div>
</div>
@endsection

@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
 <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
 
     $('.datepicker').datepicker({
    autoclose: true,
    format:'yyyy-mm',
     viewMode: "months", 
    minViewMode: "months"
});
});
$(document).on('submit', '#interest', function(event) {
      event.preventDefault();
    var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          // console.log(formdata);
          // return;
          //var formAction = form.attr('action');
          $.ajax({
              url         : '{{url('import')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){
                  // toastr["success"]("Loan Requested successfully",'Success');
                  if (data=='success'){
                    toastr.success("Loan Requested successfully",'Success');
                  
                  }else{
                    toastr.error("Loan Requested could not be submitted",'Error');
                  }
              },
              error:function(data, textStatus, jqXHR){
                  // toastr["success"]("Loan Requested successfully",'Success');
                 
              }
          });
          
    }); 


</script>

@endsection