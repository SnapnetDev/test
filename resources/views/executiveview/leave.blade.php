@extends('layouts.master')
@section('stylesheets')
  
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('People Analytics')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('People Analytics')}}</li>
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
      	<iframe width="100%" height="800" src="https://app.powerbi.com/view?r=eyJrIjoiNmEzOTk2NTEtZGEzOC00NjdlLWFlNWYtNDg0YzQyNGIxNWI3IiwidCI6ImJhMTMwZWNhLTMwMzAtNDhlMS05MDg5LWM5NzkyOTNhZWI3MCIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>

    </div>
  	
	</div>
  <!-- End Page -->
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
@endsection