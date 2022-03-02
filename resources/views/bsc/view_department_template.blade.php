@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
        <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
  <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
 
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
    .hide{
      display:none;
    }
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Balance Scorecard Template</h1>
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
                 @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('error') }}
                </div>
            @endif
            <div class="panel panel-info ">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Balance Scorecard Evaluation Template for {{$det->title}} </h3>
                 <div class="panel-actions">
                      <button class="btn btn-default" data-toggle="modal" data-target="#uploadDETModal">Upload Template</button>

                    </div>
              </div>
              
              <div class="panel-body">
                <br>
                
               
                @foreach($metrics as $metric)
                <div class="table-responsive">  
                <h3 align="center">{{$metric->name}} (<span id="metric_title_{{$metric->id}}"></span>%)</h3><br />
                <div id="grid_table_{{$metric->id}}"></div>
               </div> 
               @endforeach
             
          </div>
          <div class="panel-footer">
            
          </div>
          
        </div>


          </div>
          </div>

  </div>


</div>
@include('bsc.modals.upload_template')
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
     @foreach($metrics as $metric)
     fetch('{!! url("bscsettings/get_template_metric_weight?metric_id=".$metric->id."&det_id=".$det->id)!!}')
         .then(response => response.json())
         .then(data=>{
             document.querySelector("#metric_title_{{$metric->id}}").innerText=data;
         });
    $('#grid_table_{{$metric->id}}').jsGrid({

             width: "100%",
             height: "300px",

             filtering: false,
             inserting:true,
             editing: true,
             sorting: true,
             paging: true,
             autoload: true,
             pageSize: 10,
             pageButtonCount: 5,
             deleteConfirm: "Do you really want to delete data?",

             controller: {
              loadData: function(filter){
               return $.ajax({
                type: "GET",
                url: "{{url("bscsettings/get_det_details")}}",
                data: { 
                        "bsc_det_id": "{{$det->id}}", 
                        "bsc_metric_id": "{{$metric->id}}"
                    }
               });
              },
              insertItem: function(item){
               return $.ajax({
                type: "POST",
                url: "{{url("bscsettings")}}",
                data:item
               });
              },
              updateItem: function(item){
               return $.ajax({
                type: "POST",
                url: "{{url("bscsetings")}}",
                data: item
               });
              },
              deleteItem: function(item){
               return $.ajax({
                type: "GET",
                url: "{{url("bsc/delete_det_detail")}}",
                data: item
               });
              },
             }, onItemInserting: function(args) {
        
             
                  args.item._token="{{csrf_token()}}";
                  args.item.bsc_metric_id="{{$metric->id}}";
                  args.item.type="save_det_detail";
                  args.item.bsc_det_id="{{$det->id}}";
                  
             
          },onItemUpdating: function(args) {
        // cancel insertion of the item with empty 'name' field
             
                  args.item._token="{{csrf_token()}}";
                  args.item.type="save_det_detail";
             
          },
          
          

             fields: [
              
                 
                  {
                   name: "business_goal", 
                type: "text", 
                width: 150,
                title: "Business Goal", 
                validate: "required"
                  },
                  {
                   name: "performance_metric_description",
                type: "text", 
                width: 150, 
                 title: "Performance <br>Metrics <br>Description",
                validate: "required"
                  },
                 {
                     name: "target",
                     type: "number",
                     title: "Target",
                     width: 50,
                     validate: function(value)
                     {
                         if(value > 0)
                         {
                             return true;
                         }
                     }
                 },

                  {
                   name: "weight",
                type: "number", 
                width: 50,
                 title: "Weight<br>(%)",
                  },
                  

                  {
                   type: "control"
                  }
                 ]

    });
    @endforeach
} );

    $(function() {
    $(document).on('submit','#uploadDETForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('bscsettings.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

                toastr.success("Data Import Successful",'Success');
                $('#uploadDETModal').modal('toggle');
                location.reload();
            },
            error:function(data, textStatus, jqXHR){
               jQuery.each( data['responseJSON'], function( i, val ) {
                jQuery.each( val, function( i, valchild ) {
                toastr["error"](valchild[0]);
              });  
              });
            }
        });
      
    });
  });
  </script>
@endsection