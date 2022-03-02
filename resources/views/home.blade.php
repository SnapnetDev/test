@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/examples/css/charts/chartjs.css')}}">
<style type="text/css">
  a.list-group-item:hover {
    text-decoration: none;
    background-color: #3f51b5;
}
</style>
@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Dashboard</h1> 
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
      <div class="row" data-plugin="matchHeight" data-by-row="true">
        
        <div class="col-xl-12 col-md-12">
        <div class="panel panel-bordered panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Tiles</h3>
            </div>
            <div class="panel-body">
            <div class="col-xl-4 col-md-6">
              <div class="card card-block p-30 bg-yellow-700 ">
                <div class="counter counter-md text-xs-left">
                  <div class="counter-label text-uppercase m-b-5 grey-50"> Employees Present Today</div>
                  <div class="counter-number-group m-b-10">
                    <span class="counter-number grey-50">{{$usersPresent}}</span>
                  </div>
                  <div class="counter-label">
                     @if($usersPresent==$yesterday_usersPresent)
                     <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar">
                        <span class="sr-only">0%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-flat"></i></span>
                        <span class="counter-number grey-50">0%</span>
                        <span class="counter-number-related grey-50">From this Yesterday</span>
                      </div>
                    </div>
                    @elseif($usersPresent>$yesterday_usersPresent)
                    <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{round((($usersPresent-$yesterday_usersPresent)/$usersPresent)*100,0)}}%" role="progressbar">
                        <span class="sr-only">{{round((($usersPresent-$yesterday_usersPresent)/$usersPresent)*100,0)}}%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-up"></i></span>
                        <span class="counter-number grey-50">{{round((($usersPresent-$yesterday_usersPresent)/$usersPresent)*100,0)}}%</span>
                        <span class="counter-number-related grey-50">Up From this Yesterday</span>
                      </div>
                    </div>
                    @elseif($usersPresent<$yesterday_usersPresent)
                     <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{round((($yesterday_usersPresent-$usersPresent)/$yesterday_usersPresent)*100,0)}}%" role="progressbar">
                        <span class="sr-only">{{round((($yesterday_usersPresent-$usersPresent)/$yesterday_usersPresent)*100,0)}}%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-down"></i></span>
                        <span class="counter-number grey-50">{{round((($yesterday_usersPresent-$usersPresent)/$yesterday_usersPresent)*100,0)}}%</span>
                        <span class="counter-number-related grey-50">Down From this Yesterday</span>
                      </div>
                    </div>
                    @endif
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-md-6">
              <div class="card card-block p-30 bg-light-green-700 ">
                <div class="counter counter-md text-xs-left">
                  <div class="counter-label text-uppercase m-b-5 grey-50"> Employees Early Today</div>
                  <div class="counter-number-group m-b-10">
                    <span class="counter-number grey-50">{{$earlys}}</span>
                  </div>
                  <div class="counter-label">
                     @if($earlys==$yesterday_earlys)
                     <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar">
                        <span class="sr-only">0%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-flat"></i></span>
                        <span class="counter-number grey-50">0%</span>
                        <span class="counter-number-related grey-50">From this Yesterday</span>
                      </div>
                    </div>
                    @elseif($earlys>$yesterday_earlys)
                    <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{round((($earlys-$yesterday_earlys)/$earlys)*100,0)}}%" role="progressbar">
                        <span class="sr-only">{{round((($earlys-$yesterday_earlys)/$earlys)*100,0)}}%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-up"></i></span>
                        <span class="counter-number grey-50">{{round((($earlys-$yesterday_earlys)/$earlys)*100,0)}}%</span>
                        <span class="counter-number-related grey-50"> Up From this Yesterday</span>
                      </div>
                    </div>
                    @elseif($earlys<$yesterday_earlys)
                     <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{round((($yesterday_earlys-$earlys)/$yesterday_earlys)*100,0)}}%" role="progressbar">
                        <span class="sr-only">{{round((($yesterday_earlys-$earlys)/$yesterday_earlys)*100,0)}}%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-down"></i></span>
                        <span class="counter-number grey-50">{{round((($yesterday_earlys-$earlys)/$yesterday_earlys)*100,0)}}%</span>
                        <span class="counter-number-related grey-50"> Down From this Yesterday</span>
                      </div>
                    </div>
                    @endif
                    
                  </div>
                </div>
              </div>
            </div>
           <div class="col-xl-4 col-md-6">
              <div class="card card-block p-30 bg-red-300 ">
                <div class="counter counter-md text-xs-left">
                  <div class="counter-label text-uppercase m-b-5 grey-50"> Employees Late Today</div>
                  <div class="counter-number-group m-b-10">
                    <span class="counter-number grey-50">{{$lates}}</span>
                  </div>
                  <div class="counter-label">
                     @if($lates==$yesterday_lates)
                     <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar">
                        <span class="sr-only">0%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-flat"></i></span>
                        <span class="counter-number grey-50">0%</span>
                        <span class="counter-number-related grey-50">From this Yesterday</span>
                      </div>
                    </div>
                    @elseif($lates>$yesterday_lates)
                    <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{round((($lates-$yesterday_lates)/$lates)*100,0)}}%" role="progressbar">
                        <span class="sr-only">{{round((($lates-$yesterday_lates)/$lates)*100,0)}}%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-up"></i></span>
                        <span class="counter-number grey-50">{{round((($lates-$yesterday_lates)/$lates)*100,0)}}%</span>
                        <span class="counter-number-related grey-50">Up From this Yesterday</span>
                      </div>
                    </div>
                    @elseif($lates<$yesterday_lates)
                     <div class="progress progress-xs m-b-10">
                      <div class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{round((($yesterday_lates-$lates)/$yesterday_lates)*100,0)}}%" role="progressbar">
                        <span class="sr-only">{{round((($yesterday_lates-$lates)/$yesterday_lates)*100,0)}}%</span>
                      </div>
                    </div>
                    <div class="counter counter-sm text-xs-left">
                      <div class="counter-number-group">
                        <span class="counter-icon blue-600 m-r-5"><i class="md-trending-down"></i></span>
                        <span class="counter-number grey-50">{{round((($yesterday_lates-$lates)/$yesterday_lates)*100,0)}}%</span>
                        <span class="counter-number-related grey-50">Down From this Yesterday</span>
                      </div>
                    </div>
                    @endif
                    
                  </div>
                </div>
              </div>
            </div>
       
        <div class="clearfix"></div>
            </div>
          </div>
          
        </div>
        <div class="col-xl-6 col-md-6">
          <div class="panel panel-bordered panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Last Month Top 5 Employees - Punctuality</h3>
            </div>
            <div class="panel-body">
              <div><canvas id="earlyChart"></canvas></div>
            </div>
          </div>
          
          
        </div>
        <div class="col-xl-6 col-md-6">
          <div class="panel panel-bordered panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Last Month Top 5 Employees - Lateness</h3>
            </div>
            <div class="panel-body">
              <div><canvas id="lateChart"></canvas></div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-md-4">
          <div class="panel panel-bordered panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Pending Requests</h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                  <a class="list-group-item" href="javascript:void(0)"><h4 class="list-group-item-heading "><span class="tag tag-default">{{$pending_leave_requests}}</span> Leave Requests</h4></a>
                  <a class="list-group-item" href="javascript:void(0)"><h4 class="list-group-item-heading "><span class="tag tag-default">6</span> Profile Change Requests</h4></a>
                </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8 col-md-8">
          <div class="panel panel-bordered panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Key Performance Indicators</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3"></div>
                <div class="col-md-3"></div>
                <div class="col-md-3"></div>
              </div>
            </div>
          </div>
        </div>

      
  </div>
  </div>
  <!-- Site Action -->
<div class="modal fade in modal-3d-flip-horizontal modal-info" id="requestsModal" aria-hidden="true" aria-labelledby="requestsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" >Pending Requests</h4>
          </div>
            <div class="modal-body">         
              <div class="col-xs-12 "> 
                <div class="list-group list-group-gap">
                  <a class="list-group-item list-group-item-warning" href="javascript:void(0)">
                    <h4 class="list-group-item-heading grey-50">Leave Requests</h4>
                    <p class="list-group-item-text grey-50">5 requests</p>
                  </a>
                  <a class="list-group-item list-group-item-success" href="javascript:void(0)">
                    <h4 class="list-group-item-heading grey-50">Profile Change Requests</h4>
                    <p class="list-group-item-text grey-50">2 requests</p>
                  </a>
                  <a class="list-group-item list-group-item-info" href="javascript:void(0)">
                    <h4 class="list-group-item-heading grey-50">Expenses Requests</h4>
                    <p class="list-group-item-text grey-50">5 requests</p>
                  </a>
                </div>
               </div>        
            </div>
            <div class="modal-footer">
             </div>
         </div>
        
      </div>
    </div>
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="usersModal" aria-hidden="true" aria-labelledby="requestsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" >All Users</h4>
          </div>
            <div class="modal-body">         
              <div class="col-xs-12 "> 
                @php
                  $arrX = ['red-900','pink-900','deep-orange-900','teal-900','lime-900','cyan-900','blue-900','purple-900','deep-purple-900','light-blue-900','amber-900','yellow-900','orange-900','blue-grey-900','brown-900'];   
                @endphp
                <div class="list-group list-group-gap">
                  @foreach($companies as $company)
                  @php
                    $randIndex = array_rand($arrX);
                  @endphp
                  <a class="list-group-item bg-{{$arrX[$randIndex]}}" href="javascript:void(0)">
                    <h4 class="list-group-item-heading grey-50">{{$company->name}}</h4>
                    <p class="list-group-item-text grey-50">{{mt_rand(100,900)}} employees</p>
                  </a>
                  @endforeach
                  
                </div>
               </div>        
            </div>
            <div class="modal-footer">
             </div>
         </div>
        
      </div>
    </div>
  
  <!-- End Add User Form -->

@section('scripts')
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
  <script src="{{ asset('global/vendor/chart-js/Chart.js') }}"></script>
  <script src="{{ asset('global/vendor/moment/moment.min.js') }}"></script>
  <script src="{{ asset('global/vendor/moment/moment-duration-format.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#vacancytable').DataTable( {
        "paging":   false,
        "searching": false,
        "info":     false
    } );
} );

var ctx = document.getElementById('earlyChart').getContext('2d');
    var myLineChart = new Chart(ctx, {
  type: "bar",
  data: 
        {
            datasets: 
            [
                {
                    labels: 'Top Five early Comers',
                    backgroundColor: [ '#483C32', '#5B92E5', '#77c949', '#FFC1CC', '#ffbb44', '#f32f53', '#67a8e4'],
                    borderColor: '#ffffff',
                    data: [@foreach($last_month_early_users as $last_month_early_user) "{{time_to_seconds($last_month_early_user->average_first_clock_in)}}", @endforeach],
                }
            ],
            labels: [@foreach($last_month_early_users as $last_month_early_user)"{{$last_month_early_user->user->name}}", @endforeach],
        },
  options: {
    elements: {
      line: {
        tension: 0
      }
    },
    scales: {
      yAxes: [
        {
          ticks: {
            stepSize: 50,
            callback: function(tickValue, index, ticks) {
              if (!(index % parseInt(ticks.length / 5))) {
                 return moment.duration(tickValue, 's').format('h:mm a', { trim: false });
              }
            }
          }
        }
      ]
    }
  }
});
    
    var ctx = document.getElementById('lateChart').getContext('2d');

var myLineChart = new Chart(ctx, {
  type: "bar",

  data: 
        {
            datasets: 
            [
                {
                    labels: 'Top Five late Comers',
                    backgroundColor: [ '#483C32', '#5B92E5', '#77c949', '#FFC1CC', '#ffbb44', '#f32f53', '#67a8e4'],
                    borderColor: '#ffffff',
                    data: [@foreach($last_month_late_users as $last_month_late_user) "{{time_to_seconds($last_month_late_user->average_first_clock_in)}}", @endforeach],
                }
            ],
            labels: [@foreach($last_month_late_users as $last_month_late_user)"{{$last_month_late_user->user->name}}", @endforeach],
        },

  options: {
    elements: {
      line: {
        tension: 0
      }
    },
    scales: {
      yAxes: [
        {
          ticks: {
            stepSize: 50,
            callback: function(tickValue, index, ticks) {
              if (!(index % parseInt(ticks.length / 5))) {
                 return moment.duration(tickValue, 's').format('h:mm a', { trim: false });
              }
            }
          }
        }
      ]
    }
  }
});

  function showRequests() {
    $('#requestsModal').modal();
  }
  function showUsers() {
    $('#usersModal').modal();
  }
</script>
@endsection