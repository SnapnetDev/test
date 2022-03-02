@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">Comparative Report for {{$user->name}} on 360 Review for {{date('F-Y',strtotime($mp->from))}} to {{date('F-Y',strtotime($mp->to))}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('360 Review Graph Report')}}</li>
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

                        @php
                        $sn=1;
                        @endphp
                        @foreach($question_categories as $category)
                        <div class="col-ms-3 col-xs-3 col-md-3">
                            <div class="panel panel-primary ">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{$category->name}}</h3>

                                </div>
                                <div class="panel-body">
		      <canvas id="category_chart_{{$category->id}}" height="auto" width="200"></canvas>
                        </div>
                            </div>
                        </div>
                        @if($sn%4==0)
                            <div class="clearfix"></div>
                            @endif
                            @php
                                $sn++;
                            @endphp

                        @endforeach










	</div>
</div>
</div>
  <!-- End Page -->

@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ asset('global/vendor/chart-js/Chart.js')}}"></script>
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script> --}}
  <script type="text/javascript">
  	  $(document).ready(function() {
    $('.input-daterange').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd'
});

    });


      function getRandomColor() {
      var letters = '0123456789ABCDEF';
      var color = '#';
      for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    }

  </script>

@foreach($question_categories as $category)
<script>
    var ctx = document.getElementById('category_chart_{{$category->id}}').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: ['Self','Manager','Peer','Subordinate','Combined'],
            datasets: [{
                label: '{{$category->name}}',
                data: [{{number_format($result_array['user']['average'][$category->id],2)}},
                    {{number_format($result_array['manager']['average'][$category->id],2)}},
                    {{number_format($result_array['peer']['average'][$category->id],2)}},
                    {{number_format($result_array['subordinate']['average'][$category->id],2)}},
                    {{number_format($result_array['combined']['average'][$category->id],2)}},
                ],
                backgroundColor: [

                    'rgba(57, 166, 81, 1)',
                    'rgba(66, 125, 225, 1)',
                    'rgba(242, 138, 0, 1)',
                    'rgba(143, 72, 154, 1)',
                    'rgba(78, 88, 90, 1)',
                ],
                borderColor: [

                    'rgba(57, 166, 81, 1)',
                    'rgba(66, 125, 225, 1)',
                    'rgba(242, 138, 0, 1)',
                    'rgba(143, 72, 154, 1)',
                    'rgba(78, 88, 90, 1)',

                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },legend: {
                display: false,
            }
        }
    });
</script>
@endforeach

@endsection
