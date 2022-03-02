@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/examples/css/apps/message.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
      <link href="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid.min.css') }}" rel="stylesheet" />
      <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
      <link href="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid-theme.min.css') }}" rel="stylesheet" />
        {{-- <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
  <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" /> --}}
  <link rel="stylesheet" href="{{asset('global/vendor/summernote/summernote.min.css')}}">
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
    .jsgrid-edit-row>.jsgrid-cell{
      background-color: #5cb85c;
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
      .green-column{
          background-color:#5cb85c !important;
          font-size: 1em;
          color: #fff;
          font-weight: normal;
      }
  </style>


  <style>
      [data-toggle]{
          cursor: pointer;
      }

      [data-toggle]:hover{
          background-color: #03a9f4;
          color: #fff;
      }
  </style>
  @include('training_new.rating_style')

@endsection
@section('content')

<div class="page" id="app">
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



                <div class="panel panel-info " id="evaluation-panel">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Balance Scorecard Evaluation</h3>
                <div class="panel-actions">

                        <button  onclick="loadPerformanceDiscussion({{$evaluation->id}})" class="btn btn-default">View Performance Discussion(s)</button>
                   <button class="btn btn-default" data-toggle="modal" data-target="#uploadEmeasuresModal">Upload Template</button>
{{--                      <button class="btn btn-default" onclick="useDepartmentTemplate();">Use Department Template</button>--}}
                    <a  target="_blank" href="{{url('app-get/course-training')}}?id={{Auth::id()}}" class="btn btn-default">Online-Trainings</a>
                    <a href="#" data-toggle="modal" data-target="#recommend-offline-modal" class="btn btn-default">Offline-Training</a>



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
                     <li class="list-group-item ">Scorecard Performance Rating:<span class="pull-right" id="spr">{{$evaluation->scorecard_percentage}}</span></li>
                     {{-- <li class="list-group-item ">Scorecard Penalty Score:<span class="pull-right" id="penalty_score">{{$evaluation->penalty_score}}</span></li> --}}
                     <li class="list-group-item ">Behavioral Performance Rating:<span class="pull-right" id="behavioral_score">{{$evaluation->behavioral_percentage}}</span></li>
                     <li class="list-group-item ">Scorecard Total Score:<span class="pull-right" id="final_score">{{$evaluation->scorecard_percentage+$evaluation->behavioral_percentage}}</span></li>


                     {{-- <li class="list-group-item ">Average Performance Rating:<span class="pull-right" id="avg_score">{{$average>1?round($average,1):"1.0"}}</span></li> --}}
                  <li class="list-group-item">Remark:<span class="pull-right" id="remark">

                  </span></li>
                  <li class="list-group-item">
                  Approval Status: <span class="pull-right tag tag-{{$evaluation->status_color}}">{{$evaluation->approval_status}}</span>
                  </li>
                </ul>
                  </div>

                <div class="col-md-3">

                    <form id="evaluationCommentForm">
                   @csrf
                   <div class="form-group">
                       <label for="">Appraisee's Strength</label>
                     <textarea class="form-control" name="employee_strength" rows="2" placeholder="Appraisee's Strength">{{$evaluation->employee_strength}}</textarea>
                       <label for="">Appraisee's Developmental Areas</label>
                       <textarea class="form-control" name="employee_developmental_area" rows="2" placeholder="Appraisee's Developmental areas">{{$evaluation->employee_developmental_area}}</textarea>
                       <label for="">Special Achievement</label>
                       <textarea class="form-control" name="special_achievement" rows="2" placeholder="Special Achievement">{{$evaluation->special_achievement}}</textarea>
                       <label for="">Approval Comment</label>
                       <textarea class="form-control" name="manager_approval_comment" rows="2" placeholder="Approval Comment">{{$evaluation->manager_approval_comment}}</textarea>
                    <input type="hidden" name="bsc_evaluation_id" value="{{$evaluation->id}}">
                    <input type="hidden" name="type" value="save_evaluation_comment">
                   </div>
@if(($evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'))
<button type="submit" class="btn btn-primary">Save Comment</button>
@endif
{{--<button type="button" class="btn btn-success" onclick="submitKPI();">Submit for Review</button>--}}

</form>

</div>
<div class="col-md-3">
   @if(($evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal')||
   ($evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='accepting')||
   ($evaluation->manager_of_manager_id==Auth::user()->id && $evaluation->approval_status=='manager_of_manager')||
   ($evaluation->head_of_hr_id==Auth::user()->id && $evaluation->approval_status=='head_of_hr')||
   ($evaluation->head_of_strategy_id==Auth::user()->id && $evaluation->approval_status=='head_of_strategy'))
   <button class="btn btn-success" data-toggle="modal" data-target="#approveEvaluationModal">Complete</button>
       @endif

</div>

</div>

<hr>
@foreach($metrics as $metric)
<div class="table-responsive">
<h3 align="center" id="">{{$metric->name}} (<span id="metric_title_{{$metric->id}}"></span>%) </h3><br />
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



@include('bsc.training.template')

</div>
</div>

</div>


</div>


@include('bsc.modals.upload_measure_template')
@include('bsc.modals.performanceDiscussion')
@include('bsc.modals.approval')
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
<script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script> --}}
<script type="text/javascript">
$(document).ready(function() {
@foreach($metrics as $metric)
fetch('{!! url("bsc/get_metric_weight?metric_id=".$metric->id."&evaluation_id=".$evaluation->id) !!}')
.then(response => response.json())
.then(data=>{
document.querySelector("#metric_title_{{$metric->id}}").innerText=data;
});
$('#grid_table_{{$metric->id}}').jsGrid({

width: "100%",
height: "300px",

filtering: false,
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
$.get('{{ url('/bsc/get_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}},metric_id:args.item.metric_id },function(data){
 $('#spr').html(data.evaluation.scorecard_percentage);
 $('#final_score').html(parseInt(data.evaluation.scorecard_percentage)+parseInt(data.evaluation.behavioral_percentage));
 // $('#remark').html(data.remark);
 $("#metric_title_{{$metric->id}}").html(data.metric_weight);

});


lastPrevItem = args.previousItem;


},
onItemInserted: function(args) {
// cancel insertion of the item with empty 'name' field
console.log('inserted');
$.get('{{ url('/bsc/get_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
    $('#spr').html(data.evaluation.scorecard_percentage);
    $('#final_score').html(parseInt(data.evaluation.scorecard_percentage)+parseInt(data.evaluation.behavioral_percentage));
    // $('#remark').html(data.remark);
    $("#metric_title_{{$metric->id}}").html(data.metric_weight);

});
lastPrevItem = args.previousItem;


},
onItemDeleted: function(args) {
// cancel insertion of the item with empty 'name' field
console.log(args.item);
$.get('{{ url('/bsc/get_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
    $('#spr').html(data.evaluation.scorecard_percentage);
    $('#final_score').html(parseInt(data.evaluation.scorecard_percentage)+parseInt(data.evaluation.behavioral_percentage));
    // $('#remark').html(data.remark);
    $("#metric_title_{{$metric->id}}").html(data.metric_weight);

});
lastPrevItem = args.previousItem;


},

fields: [
{
type: "control",
width: 150,
},
{
name: "focus",
type: "text",
width: 150,
title: "Focus",
editing: false,
inserting:false,
},
{
name: "objective",
type: "text",
width: 150,
title: "Objective",
editing: false,
inserting:false,
},
{
name: "key_deliverable",
type: "text",
width: 150,
title: "Key Deliverables",
editing: false,
inserting:false,
},
{
name: "measure_of_success",
type: "text",
width: 150,
title: "Measure of Success",
editing: false,
inserting:false,
},
{
name: "means_of_verification",
type: "text",
width: 150,
title: "Means of <br> verification",
editing: false,
inserting:false,
},
{
name: "weight",
type: "number",
title: "Weight <br> (%)",
width: 150,
editing: false,
inserting:false,
},
{
name: "self_assessment",
type: "number",
width: 150,
title: "Self<br> Assessment",
editing: {{$evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'true':'false'}},
inserting:false,
    @if($evaluation->user_id==Auth::user()->id and  $evaluation->approval_status=='appraisal')
    headercss:'green-column',
    css:'green-column'
    @endif
},
{
name: "manager_assessment",
type: "number",
title: "Manager<br> Assessment",
width: 150,
editing: {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'true':'false'}},
inserting:false,
@if($evaluation->manager_id==Auth::user()->id and  $evaluation->approval_status=='appraisal')
headercss:'green-column',
css:'green-column'
@endif
},
    {
        name: "justification_of_rating",
        type: "text",
        title: "Manager<br> Comment",
        width: 250,
        editing: {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'true':'false'}},
        inserting:false,
        @if($evaluation->manager_id==Auth::user()->id and  $evaluation->approval_status=='appraisal')
        headercss:'green-column',
        css:'green-column'
        @endif

    },
{
title: "Appraisee <br>(to Accept or Reject)",
name: "accept_reject",
type: "select",
items: [
    { Name: "Select", Id:""
    },
    { Name: "Accept", Id: 'accept' },
    { Name: "Reject", Id: 'reject' }
],
valueField: "Id",
textField: "Name",
editing: {{$evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='accepting'?'true':'false'}},
inserting:false,
    @if($evaluation->user_id==Auth::user()->id and  $evaluation->approval_status=='accepting')
    headercss:'green-column',
    css:'green-column'
    @endif
},
{
name: "manager_of_manager_assessment",
type: "text",
title: "Manager's <br>Manager<br> Assessment",
width: 150,
editing: {{$evaluation->manager_of_manager_id==Auth::user()->id && $evaluation->approval_status=='manager_of_manager'?'true':'false'}},
inserting:false,
    @if($evaluation->manager_of_manager_id==Auth::user()->id and  $evaluation->approval_status=='manager_of_manager')
    headercss:'green-column',
    css:'green-column'
    @endif
},
{
name: "modified_date",
type: "text",
title: "Last Updated",
width: 100,
editing: false,
inserting: false

}


]

});
@endforeach
//behavioral evaluation functions
$('#behavioral_evaluation_table').jsGrid({

width: "100%",
height: "400px",

filtering: false,
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

$('#behavioral_score').html(data.evaluation.behavioral_percentage);
 $('#final_score').html(parseInt(data.evaluation.scorecard_percentage)+parseInt(data.evaluation.behavioral_percentage));
// $('#remark').html(data.remark);

});
lastPrevItem = args.previousItem;


},
onIteminserted: function(args) {
// cancel insertion of the item with empty 'name' field
console.log('inserted');
$.get('{{ url('/bsc/get_behavioral_evaluation_wcp') }}/',{ bsc_evaluation_id: {{$evaluation->id}} },function(data){
    $('#behavioral_score').html(data.evaluation.behavioral_percentage);
    $('#final_score').html(parseInt(data.evaluation.scorecard_percentage)+parseInt(data.evaluation.behavioral_percentage));


});
lastPrevItem = args.previousItem;


},

fields: [

    {
        type: "control"
    },
{
name: "business_goal",
type: "text",
width: 150,
title: "Business Goal",
editing: false,
inserting: false
},
{
name: "weighting",
type: "number",
width: 60,
title: "Weighting<br>(%)",
editing: false,
inserting: false
},
{
name: "measure",
type: "text",
width: 150,
title: "Measure/ KPI",
editing: false,
inserting: false
},
{
name: "self_assessment",
type: "number",
width: 150,
title: "Self<br> Assessment",
editing: {{$evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'true':'false'}},
inserting:false,
    @if($evaluation->user_id==Auth::user()->id and  $evaluation->approval_status=='appraisal')
    headercss:'green-column',
    css:'green-column'
    @endif
},
{
name: "manager_assessment",
type: "number",
title: "Manager<br> Assessment",
width: 150,
editing: {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'true':'false'}},
inserting:false,
@if($evaluation->manager_id==Auth::user()->id and  $evaluation->approval_status=='appraisal')
headercss:'green-column',
css:'green-column'
@endif
},
{
title: "Appraisee <br>(to Accept or Reject)",
name: "accept_reject",
type: "select",
items: [
    { Name: "Select", Id:""
    },
    { Name: "Accept", Id: 'accept' },
    { Name: "Reject", Id: 'reject' }
],
valueField: "Id",
textField: "Name",
editing: {{$evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='accepting'?'true':'false'}},
inserting:false,
    @if($evaluation->manager_id==Auth::user()->id and  $evaluation->approval_status=='accepting')
    headercss:'green-column',
    css:'green-column'
    @endif
},
{
name: "manager_of_manager_assessment",
type: "text",
title: "Manager's <br>Manager<br> Assessment",
width: 150,
editing: {{$evaluation->manager_of_manager_id==Auth::user()->id && $evaluation->approval_status=='manager_of_manager'?'true':'false'}},
inserting:false,
    @if($evaluation->manager_of_manager_id==Auth::user()->id and  $evaluation->approval_status=='manager_of_manager')
    headercss:'green-column',
    css:'green-column'
    @endif
},

{
name: "modified_date",
type: "text",
title: "Last Updated",
width: 100,
editing: false,
inserting: false

}


]

});
} );



function useDepartmentTemplate(det_id) {
alertify.confirm('Are you sure you want to use this Template?', function () {
fetch('{!! url("bsc/use_dept_template?bsc_evaluation_id=".$evaluation->id."&det_id=") !!}'+det_id)
.then(response => response.json())
.then(data=>{
if (data=='success') {
toastr.success("Data Import Successful",'Success');
location.reload();
}
});

}, function () {
alertify.error('Template was not used');
});
}
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
$(function() {

$(document).ready(function() {
$('.summernote').summernote({
height: 150,
});
});

$(document).on('submit','#approveEvaluationForm',function(event){
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
$('#approveEvaluationModal').modal('toggle');
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
</script>


{{--@include('training_new.js_plugin.rating_plugin')--}}

{{--@include('training_new.js_framework.binder')--}}

{{--@include('training_new.js_framework.binder_v2')--}}


{{--@include('training_new.js_framework.vue')--}}
{{--@include('training_new.vue_components.training_plan_stat')--}}
{{--@include('training_new.vue_components.training_eligibility')--}}
{{--@include('training_new.vue_components.training_enroll_status')--}}
{{--@include('training_new.vue_components.training_progress_status')--}}
{{--@include('training_new.vue_components.training_feedback')--}}
{{--@include('training_new.vue_components.ajax_text')--}}
{{--@include('training_new.vue_components.training_plan_recommendation')--}}
{{--@include('training_new.vue_components.vue_root')--}}


@include('training_new.crud.js')

@include('bsc.training.js')


@endsection
