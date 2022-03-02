@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">

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
      <h1 class="page-title">360 Degree Review- Employee Home</h1>
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
                <h3 class="panel-title">Evaluation Information</h3>
              </div>
              <form action="{{ url('e360/department_employees') }}" method="GET">

              <div class="panel-body">

              <div class="col-md-6">
                      <div class="card card-block p-30 " style="border: 1px solid #ddd;">
                          <div class="pull-right " style="font-size: 128px!important; color: #330099;"><i style="color: #330099;" class="mdi mdi-target-account" aria-hidden="true"></i></div>
                          <div class="counter counter-md counter-inverse text-xs-left">
                              <div class="counter-number-group">

                                  <span class="counter-number" style="color: #330099;font-size: 50px">Self<br> Assessment</span>
                              </div>
                              <div class="counter-label " style="color: #330099; font-size: 16px">Assess yourself on the different review categories.</div>
                          </div>
                          <div class="clearfix"></div>
                          <div class="pull-right"><a style="text-transform: uppercase;text-decoration: underline;"  href="{{url('e360/get_evaluation?mp='.$mp->id.'&employee='.Auth::user()->id)}}">Go to Assessment</a></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="card card-block p-30 " style="border: 1px solid #ddd;">
                          <div class="pull-right " style="font-size: 128px!important; color: #330099;"><i style="color: #003333;" class="mdi mdi-account-switch" aria-hidden="true"></i></div>
                          <div class="counter counter-md counter-inverse text-xs-left">
                              <div class="counter-number-group">

                                  <span class="counter-number" style="color: #003333;font-size: 50px">Peer<br> Assessment</span>
                              </div>
                              <div class="counter-label " style="color: #003333; font-size: 16px">Assess your colleagues on the different review categories.</div>
                          </div>
                          <div class="clearfix"></div>
                          <div class="pull-right"><a style="text-transform: uppercase;text-decoration: underline;" href="{{url('e360/nominate_peer?mp='.$mp->id.'&employee='.Auth::user()->id)}}">Nominate my Peers</a>&nbsp;&nbsp;<a style="text-transform: uppercase;text-decoration: underline;" href="{{url('e360/approve_nominations?mp='.$mp->id.'&employee='.Auth::user()->id)}}">View my Nominations</a>&nbsp;&nbsp;<a style="text-transform: uppercase;text-decoration: underline;" href="{{url('e360/peer_review_list?mp='.$mp->id)}}">View my Peers</a></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="card card-block p-30 " style="border: 1px solid #ddd;">
                          <div class="pull-right " style="font-size: 128px!important; color: #003300;"><i style="color: #003300;" class="mdi mdi-account-network" aria-hidden="true"></i></div>
                          <div class="counter counter-md counter-inverse text-xs-left">
                              <div class="counter-number-group">

                                  <span class="counter-number" style="color: #003300;font-size: 50px">Direct Report<br> Assessment</span>
                              </div>
                              <div class="counter-label " style="color: #003300; font-size: 16px">Assess your subordinates on the different review categories.</div>
                          </div>
                          <div class="clearfix"></div>
                          @if(Auth::user()->pdreports)
                          <div class="pull-right"><a style="text-transform: uppercase;text-decoration: underline;" href="{{url('e360/dr_review_list?mp='.$mp->id)}}">View My direct reports</a></div>
                              @endif
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="card card-block p-30 " style="border: 1px solid #ddd;">
                          <div class="pull-right " style="font-size: 128px!important; color: #330000;"><i style="color: #330000;" class="mdi mdi-account-tie" aria-hidden="true"></i></div>
                          <div class="counter counter-md counter-inverse text-xs-left">
                              <div class="counter-number-group">

                                  <span class="counter-number" style="color: #330000;font-size: 50px">Manager<br> Assessment</span>
                              </div>
                              <div class="counter-label " style="color: #330000; font-size: 16px">Assess your manager on the different review categories.</div>
                          </div>
                          <div class="clearfix"></div>
                          @if(Auth::user()->plmanager)
                          <div class="pull-right"><a style="text-transform: uppercase;text-decoration: underline;" href="{{url('e360/get_evaluation?mp='.$mp->id.'&employee='.Auth::user()->plmanager->id)}}">Go to Assessment</a></div>
                        @endif
                      </div>
                  </div>
               <div class="col-md-6">

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

} );
  </script>
@endsection
