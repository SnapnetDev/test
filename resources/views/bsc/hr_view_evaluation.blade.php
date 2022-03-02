@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
      <link href="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid.min.css') }}" rel="stylesheet" />
      <link href="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid-theme.min.css') }}" rel="stylesheet" />
      <link rel="stylesheet" href="{{asset('global/vendor/summernote/summernote.min.css')}}">
        {{-- <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" /> --}}
  {{-- <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" /> --}}
 
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
    .jsgrid-header-row>.jsgrid-header-cell{
      background-color: #03a9f4;
      font-size: 1em;
      color: #fff;
      font-weight: normal;
    }
    .red-column{
      background-color: #e52b1e !important;
      font-size: 1em;
      color: #fff;
      font-weight: normal;
    }
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Balance Scorecard Evaluation</h1>
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
                <h3 class="panel-title">Balance Scorecard Evaluation</h3>
                <div class="panel-actions">

                    <button data-toggle="modal" onclick="loadPerformanceDiscussion({{$evaluation->id}})" data-target="#performanceDiscussion" class="btn btn-default">View Performance Discussion(s)</button>

                </div>
                
              </div>
              
              <div class="panel-body">
                <br>
                <div class="row">
                <div class="col-md-4">
                  <ul class="list-group list-group-bordered">
                  <li class="list-group-item ">Employee Number:<span class="pull-right" >{{$evaluation->user->emp_num}}</span></li>
                  <li class="list-group-item ">Name:<span class="pull-right" >{{$evaluation->user->name}}</span></li>
                  <li class="list-group-item ">Job Role:<span class="pull-right" >{{$evaluation->user->job->title}}</span></li>
                  <li class="list-group-item ">Department:<span class="pull-right" >{{$evaluation->department->name}}</span></li>
                 </ul>
                </div>
                
                <div class="col-md-4">
                 <ul class="list-group list-group-bordered">
                  <li class="list-group-item ">Measurement Period:<span class="pull-right" >{{date('F-Y',strtotime($evaluation->measurement_period->from))}} to {{date('F-Y',strtotime($evaluation->measurement_period->to))}}</span></li>
                     <li class="list-group-item ">Scorecard Performance Rating:<span class="pull-right" id="spr">{{$evaluation->score}}</span></li>
                     <li class="list-group-item ">Behavioral Performance Rating:<span class="pull-right" id="be_score">{{$evaluation->behavioral_score}}</span></li>
                     @php
                       $average=($evaluation->score+$evaluation->behavioral_score)/2;
                     @endphp
                     <li class="list-group-item ">Average Performance Rating:<span class="pull-right" id="avg_score">{{$average>1?round($average,1):"1.0"}}</span></li>
                  <li class="list-group-item">Remark:<span class="pull-right" id="remark">
                    @php
                     if($evaluation->score<=1.95){
                         echo "Poor Performance";
                        }
                        elseif($evaluation->score<=2.45){
                          echo "Below Expectation";
                        }
                        elseif($evaluation->score>=3.5){
                          echo "Exceeds Expectation";
                        }
                        elseif($evaluation->score<=3.45){
                          echo "Meets Expectation";
                        }
                        else{
                          echo "";
                        }
                    @endphp
                  </span></li>
                  <li class="list-group-item">
                  Approval Status: <span class="pull-right tag tag-{{$evaluation->status_color}}">{{$evaluation->status_text}}</span> 
                  </li>
                </ul>
                  </div>
                <form id="evaluationAcceptanceForm">
                <div class="col-md-4">
                 
                   @csrf
                   <div class="form-group">
                    <label>Line Manager Comment</label>
                     <textarea class="form-control" rows="4" placeholder="Enter Line Manager Comment" disabled>{{$evaluation->comment}}</textarea>
                    <input type="hidden" name="bsc_evaluation_id" value="{{$evaluation->id}}">
                    <input type="hidden" name="type" value="save_evaluation_comment">
                   </div>
                  
                  {{-- <button type="button" class="btn btn-primary" onclick="acceptKPI();">Accept KPIs/Measures</button> --}}
                </div>
                </form>
              </div>
               
                <hr>
                @foreach($metrics as $metric)
                <div class="table-responsive">  
                <h3 align="center">{{$metric->name}} ({{bscweight($evaluation->department_id,$user->grade->bsc_grade_performance_category_id,$metric->id)->percentage}}%)</h3><br />
                <div id="grid_table_{{$metric->id}}"></div>
               </div> 
               @endforeach
             <div class="table-responsive">
                <h3 align="center">Behavioral Evaluation</h3><br />
                <div id="behavioral_evaluation_table"></div>
               </div>
          </div>
          <div class="panel-footer">
            
          </div>
          
        </div>


          </div>
          </div>

  </div>


</div>
@include('bsc.modals.performanceDiscussion')
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid.min.js')}}"></script>
<script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>
 {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script> --}}
  <script type="text/javascript">

  $(document).ready(function() {
    $('.summernote').summernote({
      height: 150, 
    });
  });

  $(document).ready(function() {
     @foreach($metrics as $metric)
    $('#grid_table_{{$metric->id}}').jsGrid({

             width: "100%",
             height: "300px",

             filtering: false,
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
                url: "{{url("bsc/delete_evaluation_detail")}}",
                data: item
               });
              },
             }, onItemInserting: function(args) {
       
             
                  args.item._token="{{csrf_token()}}";
                  args.item.metric_id="{{$metric->id}}";
                  args.item.type="save_evaluation_detail";
                  args.item.bsc_evaluation_id="{{$evaluation->id}}";
                  
             
          },onItemUpdating: function(args) {
        // cancel insertion of the item with empty 'name' field
             
                  args.item._token="{{csrf_token()}}";
                  args.item.type="save_evaluation_detail";
             
          },
          onItemUpdated: function(args) {
        // cancel insertion of the item with empty 'name' field
                  console.log('updated');
                  $.get('{{ url('/bsc/get_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
                    $('#spr').html(data.evaluation.score);
                    $('#remark').html(data.remark);
                   
                   });
                 lastPrevItem = args.previousItem;
                 
             
          },
          onIteminserted: function(args) {
        // cancel insertion of the item with empty 'name' field
                  console.log('inserted');
                  $.get('{{ url('/bsc/get_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
                    $('#spr').html(data.evaluation.score);
                    $('#remark').html(data.remark);
                   
                   });
                 lastPrevItem = args.previousItem;
                 
             
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
                   name: "measure", 
                type: "text", 
                width: 150, 
                 title: "Measure", 
                validate: "required"
                  },
                  {
                   name: "weighting", 
                type: "number", 
                width: 60,
                 title: "Weighting<br>(%)", 
                  },
                  {
                   name: "lower", 
                type: "number",
                title: "Lower<br>Target", 
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
                   name: "mid", 
                type: "number", 
                width: 50,
                 title: "Mid<br>Target",  
                validate: function(value)
                {
                 if(value > 0)
                 {
                  return true;
                 }
                }
                  },
                  {
                   name: "upper", 
                type: "number", 
                width: 50,
                 title: "Upper<br>Target",  
                validate: function(value)
                {
                 if(value > 0)
                 {
                  return true;
                 }
                }
                  },
                  {
                   name: "actual", 
                type: "number", 
                 title: "Actual<br> Result<br> Achieved", 
                width: 60
                
                  },
                  
                  
                  {
                   name: "crra", 
                type: "text", 
                 title: "CRRA", 
                width: 50,
                editing: false,
                headercss:'red-column',
                css:'red-column'
                  },
                   {
                   name: "wcp", 
                type: "text", 
                 title: "WCP", 
                width: 50,
                editing: false,
                 headercss:'red-column',
                css:'red-column'
                  },{
                   name: "comment", 
                type: "text", 
                 title: "Comment", 
                width: 150
                  },
                  {
                   name: "modified_date",
                type: "text",
                 title: "Last Updated",
                width: 100,
                editing: false,
                inserting: false
               
                  },
                  
                 ]

    });
    @endforeach
    $('#behavioral_evaluation_table').jsGrid({

             width: "100%",
             height: "400px",

             filtering: false,
             
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
                url: "{{url("bsc/get_behavioral_evaluation_details")}}",
                data: {
                        "bsc_evaluation_id": "{{$evaluation->id}}"
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
                url: "{{url("bsc/delete_evaluation_detail")}}",
                data: item
               });
              },
             }, onItemInserting: function(args) {


                  args.item._token="{{csrf_token()}}";
                  args.item.type="save_behavioral_evaluation_detail";
                  args.item.bsc_evaluation_id="{{$evaluation->id}}";


          },onItemUpdating: function(args) {
        // cancel insertion of the item with empty 'name' field

                  args.item._token="{{csrf_token()}}";
                  args.item.type="save_behavioral_evaluation_detail";

          },
          onItemUpdated: function(args) {
        // cancel insertion of the item with empty 'name' field
                  console.log('updated');
                  $.get('{{ url('/bsc/get_behavioral_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
                    $('#spr').html(data.evaluation.score);
                    $('#remark').html(data.remark);

                   });
                 lastPrevItem = args.previousItem;


          },
          onIteminserted: function(args) {
        // cancel insertion of the item with empty 'name' field
                  console.log('inserted');
                  $.get('{{ url('/bsc/get_behavioral_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
                    $('#spr').html(data.evaluation.score);
                    $('#remark').html(data.remark);

                   });
                 lastPrevItem = args.previousItem;


          },

             fields: [


                  {
                   name: "objective",
                type: "text",
                width: 150,
                title: "Objective",
                editing: false,
                inserting: false,
                  },
                  {
                   name: "weighting",
                type: "number",
                width: 60,
                 title: "Weighting<br>(%)",
                 editing: false,
                inserting: false,
                  },
                  {
                   name: "measure",
                type: "text",
                width: 150,
                 title: "Measure/ KPI",
                editing: false,
                inserting: false,
                  },

                  
                  {
                   name: "low_target",
                type: "text",
                title: "Lower<br>Target",
                width: 50,
                editing: false,
                inserting: false,
                  },
                  {
                   name: "mid_target",
                type: "text",
                width: 50,
                 title: "Mid<br>Target",
                editing: false,
                inserting: false,
                  },
                  {
                   name: "upper_target",
                type: "text",
                width: 50,
                 title: "Upper<br>Target",
                editing: false,
                inserting: false,
                  },
                  {
                   name: "actual",
                type: "text",
                 title: "Actual<br> Result<br> Achieved",
                width: 60
                  },


                  {
                   name: "crra",
                type: "text",
                 title: "CRRA",
                width: 50,
                editing: false,
                inserting: false,
                headercss:'red-column',
                css:'red-column'
                  },
                   {
                   name: "wcp",
                type: "text",
                 title: "WCP",
                width: 50,
                editing: false,
                inserting: false,
                 headercss:'red-column',
                css:'red-column'
                  },
                  {
                   name: "comment",
                type: "text",
                 title: "Comment",
                width: 150
                  },
                  {
                   name: "modified_date",
                type: "text",
                 title: "Last Updated",
                width: 100,
                editing: false,
                inserting: false
               
                  },

                  
                 ]

    });
} );
function useDepartmentTemplate() {
   $.get('{{ url('/bsc/use_dept_template') }}/',{bsc_evaluation_id:{{$evaluation->id}} },function(data){
    if (data=='success') {
       toastr.success("Data Import Successful",'Success');
              
                location.reload();
    }
      
       });
}
function acceptKPI() {
   $.get('{{ url('/bsc/accept_kpis') }}/',{bsc_evaluation_id:{{$evaluation->id}} },function(data){
    if (data=='success') {
       toastr.success("KPIs Assigned has been accepted",'Success');
              
                location.reload();
    }
      
       });
}
// function rejectKPI() {
//    $.get('{{ url('/bsc/reject_kpis') }}/',{bsc_evaluation_id:{{$evaluation->id}} },function(data){
//     if (data=='success') {
//        toastr.success("KPIs Rejection Reasons have been submitted",'Success');
              
//                 location.reload();
//     }
      
//        });
// }
 $(function() {
    $(document).on('submit','#evaluationCommentForm',function(event){
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

                toastr.success("Comment Saved Successfully",'Success');
                
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