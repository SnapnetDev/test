@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
      <link href="{{ asset('global/vendor/treant-js/treant.css') }}" rel="stylesheet" />
  <style media="screen">
    body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td { margin:0; padding:0; }
table { border-collapse:collapse; border-spacing:0; }
fieldset,img { border:0; }
address,caption,cite,code,dfn,em,strong,th,var { font-style:normal; font-weight:normal; }
caption,th { text-align:left; }
h1,h2,h3,h4,h5,h6 { font-size:100%; font-weight:normal; }
q:before,q:after { content:''; }
abbr,acronym { border:0; }

body { background: #fff; }
/* optional Container STYLES */
.chart { height:auto; margin: 5px; width:auto; }
.Treant > .node { padding: 3px; border: 1px solid #484848; border-radius: 3px;  }
.Treant > p { font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; font-weight: bold; font-size: 12px; }
.Treant > .node img { }

.Treant .collapse-switch { width: 100%; height: 100%; border: none; }
.Treant .node.collapsed { background-color: #DEF82D; }
.Treant .node.collapsed .collapse-switch { background: none; }
.node-name { font-weight: bold;}

.nodeExample1 {
    padding: 2px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    background-color: #ffffff;
    border: 1px solid #000;
    width: 200px;
    font-family: Tahoma;
    font-size: 12px;
}

.nodeExample1 img {
    margin-right:  10px;
}
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Organogram</h1>
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
                <h3 class="panel-title">Employee Information</h3>
              </div>
              
              <div class="panel-body">

               <div class="chart" id="basic-example"></div>
          </div>
          <div class="panel-footer">
           
           
          </div>
          
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
<script type="text/javascript" src="{{ asset('global/vendor/raphael/raphael.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/treant-js/treant.js')}}"></script>
  <script type="text/javascript">

  var chart_config = {
        chart: {
            container: "#basic-example",
            
            connectors: {
                type: 'step'
            },
            node: {
                HTMLclass: 'nodeExample1',
                collapsable: true
            }
        },
        nodeStructure: {
          HTMLid:{{$company->manager?$company->manager->id:''}},
            text: {
                name: "{{$company->manager?$company->manager->name:''}}",
                title: "{{$company->manager?($company->manager->job?$company->manager->job->title:''):''}}",
                contact: "",
            }, @if ($company->manager->pdreports)
             children: [
             @foreach ($company->manager->pdreports as $dr)
               {
                    HTMLid:{{$dr->id}},
                    text:{
                        name: "{{$dr->name}}",
                        title: "{{$dr->job?($dr->job?$dr->job->title:""):''}}",
                         contact: "",
                    }
                    
                },
             @endforeach
                
            ]
            @endif
            
           
           
        }
    };
    var chart = new Treant(chart_config, null, $); // initialises the tree when the page loads
var $oNodes = $('.Treant .node');
$('body').on('click', '.Treant .node', function() {
var $oNode = $(this);
var tid=$(this).attr('id');
// console.log(tid);
$oNodes.removeClass('selected');
$oNode.addClass('selected');
let pnode = $oNode.data('treenode');
console.log(pnode.children.length);
if (pnode.children.length== 0) {
   $.get('{{ url('/user/dr') }}/',{ user_id:tid },function(data){
  
      $.each(data, function (i, node) {
      var hid = node.id ;
//       var node_info = $(pnode.parent).data('treenode'); // assigns data to each node foreach element in the json array
        if(node.job){
            var detail = { 'text': {'name': node.name,'title':node.job.title,'contact':''}, HTMLid: hid};
        }else{
            var detail = { 'text': {'name': node.name,'title':'','contact':''}, HTMLid: hid};
        }
      
chart.tree.addNode(pnode, detail); // loads the data into and adds to the tree dynamically
});
     
    });
} 

});

//gets an array with data that you want to dynamically load to the tree
$.ajax({
url :'',
method : 'GET', // form submit method get/post
data : {},
ContentType : 'application/json',
dataType: 'json',
success: function(data){

$.each(data, function (i, node) {
      var hid = node.id ;
      var node_info = $(node.parent).data('treenode'); // assigns data to each node foreach element in the json array
      var detail = { 'text': {'name': data.name}, HTMLid: hid};
chart.tree.addNode(node_info, detail); // loads the data into and adds to the tree dynamically
});
}
});
 // new Treant( chart_config );

  </script>
@endsection