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
                <h3 class="panel-title">Balance Scorecard Evaluation KPIs for {{$evaluation->user->name}}  </h3>
                 <div class="panel-actions">
                     <button class="btn btn-default" data-toggle="modal" data-target="#uploadEmeasuresModal">Upload Template</button>

                    </div>
              </div>
              
              <div class="panel-body">
                  <br>
                  <div class="row">
                      <div class="col-md-3">
                          <ul class="list-group list-group-bordered">
                              <li class="list-group-item ">Employee Number:<span class="pull-right" >{{$evaluation->user->emp_num}}</span></li>
                              <li class="list-group-item ">Name:<span class="pull-right" >{{$evaluation->user->name}}</span></li>
                              <li class="list-group-item ">Job Role:<span class="pull-right" >{{$evaluation->user->job->title}}</span></li>
                              <li class="list-group-item ">Department:<span class="pull-right" >{{$evaluation->department->name}}</span></li>
                          </ul>
                      </div>

                      <div class="col-md-3">
                          <ul class="list-group list-group-bordered">
                              <li class="list-group-item ">Measurement Period:<span class="pull-right" >{{date('F-Y',strtotime($evaluation->measurement_period->from))}} to {{date('F-Y',strtotime($evaluation->measurement_period->to))}}</span></li>
                              <li class="list-group-item ">Scorecard Total Weight:<span class="pull-right" id="weight">{{$evaluation->weight_sum}}</span></li>
                              <li class="list-group-item">Remark:<span class="pull-right" id="remark">

                  </span></li>
                              <li class="list-group-item">
                                  Approval Status: <span class="pull-right tag tag-{{$evaluation->status_color}}">{{$evaluation->status_text}}</span>
                              </li>
                          </ul>
                      </div>

                      <div class="col-md-3">

                          <form id="evaluationCommentForm">
                              @csrf
                              <div class="form-group">
                                  <label for="">Appraisee's Strength</label>
                                  <textarea class="form-control" name="employee_strength" rows="2" {{$evaluation->user->line_manager_id==Auth::user()->id ?'readonly':''}} placeholder="Appraisee's Strength">{{$evaluation->employee_strength}}</textarea>
                                  <label for="">Appraisee's Developmental Areas</label>
                                  <textarea class="form-control" name="employee_developmental_area" rows="2"  {{$evaluation->user->line_manager_id==Auth::user()->id ?'readonly':''}}placeholder="Appraisee's Developmental areas">{{$evaluation->employee_developmental_area}}</textarea>
                                  <label for="">Special Achievement</label>
                                  <textarea class="form-control" name="special_achievement" rows="2" {{$evaluation->user->line_manager_id==Auth::user()->id ?'readonly':''}} placeholder="Special Achievement">{{$evaluation->special_achievement}}</textarea>
                                  <label for="">Approval Comment</label>
                                  <textarea class="form-control" name="manager_approval_comment" {{$evaluation->user->line_manager_id==Auth::user()->id ?'readonly':''}}  rows="2" placeholder="Approval Comment">{{$evaluation->manager_approval_comment}}</textarea>
                                  <input type="hidden" name="bsc_evaluation_id" value="{{$evaluation->id}}">
                                  <input type="hidden" name="type" value="save_evaluation_comment">
                              </div>
                              @if($evaluation->user->line_manager_id==Auth::user()->id)
                              <button type="submit" class="btn btn-primary">Save Evaluation</button>
                              @endif
                              @if($evaluation->kpi_submitted!=1 and $evaluation->user->line_manager_id==Auth::user()->id)
                              <button type="button" class="btn btn-success" onclick="submitKPI();">Submit for Review</button>
                              @endif
                          </form>

                      </div>
                      <div class="col-md-3">
                      @if(!in_array($evaluation->kpi_accepted,[1,2]) and Auth::user()->id==$evaluation->user_id)
                          <button type="button" class="btn btn-primary" onclick="acceptKPI(1);">Accept KPIs/Measures</button>
                          <button type="button" class="btn btn-danger" onclick="acceptKPI(2);">Reject KPIs/Measures</button>
                      @endif
                      </div>

                  </div>
                <br>
                <hr>
               
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
@include('bsc.modals.upload_measure_template')
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
     fetch('{!! url("bsc/get_metric_weight?metric_id=".$metric->id."&evaluation_id=".$evaluation->id)!!}')
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
                url: "{{url("bsc/get_evaluation_details")}}",
                   data: {
                       "bsc_evaluation_id": "{{$evaluation->id}}",
                       "metric_id": "{{$metric->id}}"
                   }
               });
              },
              insertItem: function(item){
               return $.ajax({
                type: "POST",
                url: "{{url("bsc")}}",
                data:item
               });
              },
              updateItem: function(item){
               return $.ajax({
                type: "POST",
                url: "{{url("bsc")}}",
                data: item
               });
              },
              deleteItem: function(item){
               return $.ajax({
                type: "GET",
                url: "{{url("bsc/delete_employee_kpi_detail")}}",
                data: item
               });
              },
             }, onItemInserting: function(args) {


            args.item._token="{{csrf_token()}}";
            args.item.metric_id="{{$metric->id}}";
            args.item.bsc_evaluation_id="{{$evaluation->id}}";
                  args.item.type="save_employee_kpi_detail";
                  
             
          },onItemUpdating: function(args) {
        // cancel insertion of the item with empty 'name' field
             
                  args.item._token="{{csrf_token()}}";
            args.item.type="save_employee_kpi_detail";
             
          }, onItemUpdated: function(args) {
            // cancel insertion of the item with empty 'name' field
            console.log('updated');
            $.get('{{ url('/bsc/get_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}},metric_id:args.item.metric_id },function(data){
                $('#weight').html(data.evaluation.weight_sum);
                $("#metric_title_{{$metric->id}}").html(data.metric_weight);

            });


            lastPrevItem = args.previousItem;


        },
        onItemInserted: function(args) {
            // cancel insertion of the item with empty 'name' field
            console.log('inserted');
            $.get('{{ url('/bsc/get_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
                $('#weight').html(data.evaluation.weight_sum);
                $("#metric_title_{{$metric->id}}").html(data.metric_weight);

            });
            lastPrevItem = args.previousItem;


        },
        onItemDeleted: function(args) {
            // cancel insertion of the item with empty 'name' field
            console.log(args.item);
            $.get('{{ url('/bsc/get_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
                $('#spr').html(data.evaluation.score);
                $('#penalty_score').html(data.evaluation.penalty_score);
                $('#final_score').html(parseInt(data.evaluation.score)-parseInt(data.evaluation.penalty_score));
                $('#remark').html(data.remark);

            });
            lastPrevItem = args.previousItem;


        },
          
          

             fields: [
              
                 
                  {
                   name: "focus",
                type: "text", 
                width: 150,
                title: "Focus",
                validate: "required"
                  },
                  {
                   name: "objective",
                type: "text", 
                width: 150, 
                 title: "Objective",
                validate: "required"
                  },
                 {
                     name: "key_deliverable",
                     type: "text",
                     width: 150,
                     title: "Key Deliverable",
                     validate: "required"
                 },
                 {
                     name: "measure_of_success",
                     type: "text",
                     width: 150,
                     title: "Measure of Success",
                     validate: "required"
                 },
                 {
                     name: "means_of_verification",
                     type: "text",
                     width: 150,
                     title: "Means of <br> verification",
                     validate: "required"
                 },
                  {
                   name: "weight",
                type: "number", 
                width: 50,
                 title: "Weight<br>(%)",
                      validate: function(value)
                      {
                          if(value > 0)
                          {
                              return true;
                          }
                      }
                  },
                  

                  {
                   type: "control"
                  }
                 ]

    });
    @endforeach
} );

  $(function() {
      $(document).on('submit','#uploadEmeasuresForm',function(event){
          event.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{route('bsc.store')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){
                  if (data=='success') {
                      toastr.success("Data Import Successful",'Success');
                      $('#uploadEmeasuresModal').modal('toggle');
                      location.reload();
                  }else{
                      toastr.error("Error with document uploaded",'Error');
                  }


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
  function submitKPI() {
      alertify.confirm('Are you sure you want to Submit this review?', function () {
          $.get('{{ url('/bsc/submit_kpis_for_review') }}/',{bsc_evaluation_id:{{$evaluation->id}} },function(data){
              if (data=='success') {
                  toastr.success("KPIs submitted successfully",'Success');

                  location.reload();
              }

          });
      }, function () {
          alertify.error('Evaluation was not submitted');
      });
  }
  function acceptKPI(action=1) {
      formData={
          bsc_evaluation_id:{{$evaluation->id}},
          action : action
      }

      alertify.confirm('Are you sure you want to perform Operation ?', function () {
          $.get('{{ url('/bsc/accept_kpis') }}/',formData,function(data){
              if (data=='success') {
                  toastr.success("KPIs Assigned has been accepted",'Success');

                  location.reload();
              }

          });
      }, function () {
          alertify.error('Operation Cancelled');
      });


  }
  </script>

@endsection