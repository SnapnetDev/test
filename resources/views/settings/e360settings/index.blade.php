	<style type="text/css">
  .head>tr> th{
    color: #fff;
  }
  .my-btn.btn-sm {
    font-size: 0.7.5rem;
    width: 1.5rem;
    height: 1.5rem;
    padding: 0;
}
</style>
	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('360 Review')}}</li>
		    <li class="breadcrumb-item active">{{__('You are Here')}}</li>
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
      	<div class="row">
        	<div class="col-md-12 col-xs-12">

		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Measurement Period</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addMeasurementPeriodModal">Add Measurement Period</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped datatable"   >
		                    <thead>
		                      <tr>
		                        <th >From</th>
		                        <th>To</th>
		                        <th>Created On</th>
		                        <th>Action</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($measurement_periods as $measurement_period)



	            					<tr>
				            		<td >{{date('F-Y',strtotime($measurement_period->from))}}</td>
				            		<td >{{date('F-Y',strtotime($measurement_period->to))}}</td>
				            		<td>{{date("F j, Y",strtotime($measurement_period->created_at))}}</td>
				            		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item editmp" id="{{$measurement_period->id}}" ><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Measurement Period</a>

				                    </div>
				                  </div></td>
	            					</tr>
		                    	@empty
		                    	@endforelse

		                    </tbody>
	                  </table>
	          		</div>

		          </div>
		          {{-- start balance scorecard perspective --}}

		           {{-- end balance scorecard perspective --}}
		           <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Question Categories</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addQuestionCategoryModal">Add Question Category</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped datatable"   >
		                    <thead>
		                      <tr>
		                        <th >Name</th>

		                        <th>Created On</th>
		                        <th>Action</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($question_categories as $question_category)



	            					<tr>
				            		<td >{{$question_category->name}}</td>
				            		<td>{{date("F j, Y",strtotime($question_category->created_at))}}</td>
				            		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item editqc" id="{{$question_category->id}}" ><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Question Category</a>

				                    </div>
				                  </div></td>
	            					</tr>
		                    	@empty
		                    	@endforelse

		                    </tbody>
	                  </table>
	          		</div>

		          </div>
		            {{-- start balance scorecard measurement period --}}
		             {{-- end balance scorecard measurement period--}}
	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">

	    	</div>
		</div>
	  </div>
	  {{-- Add Company Modal --}}

	   @include('settings.e360settings.modals.addmeasurementperiod')
	   @include('settings.e360settings.modals.editmeasurementperiod')
	    @include('settings.e360settings.modals.addquestioncategory')
	   @include('settings.e360settings.modals.editquestioncategory')

	  <!-- End Page -->
	    <script type="text/javascript">
  	$(function() {

  		// var DateCreated = new Date(Date.parse('2019-01-11')).format("MM/dd/yyyy");

   $('.datatable').DataTable();

    $('.datepicker').datepicker({
    autoclose: true,
    format:'mm-yyyy',
     viewMode: "months",
    minViewMode: "months"
});




$('.editmp').on('click', function(event) {
	id=$(this).attr('id');


	$.get('{{ url('e360settings/get_measurement_period') }}',{mp_id:id},function(data){


     $('#editmpfrom').val(formatMPDate(data.from));
     $('#editmpid').val(data.id);
      $('#editmpto').val(formatMPDate(data.to));
    });
    $('#editMeasurementPeriodModal').modal();
});

$('.editqc').on('click', function(event) {
	id=$(this).attr('id');


	$.get('{{ url('e360settings/get_question_category') }}',{qc_id:id},function(data){


     $('#editqcname').val(data.name);
        $('#editqcdescription').val(data.description);
     $('#editqcid').val(data.id);
    });
    $('#editQuestionCategoryModal').modal();
});


  	$(document).on('submit','#addMeasurementPeriodForm',function(event){
		 // event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('e360settings')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#addMeasurementPeriodModal').modal('toggle');
					$( "#ldr" ).load('{{url('e360settings')}}');
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});
							});
		        }
		    });
        return event.preventDefault();
		});
  	$(document).on('submit','#editMeasurementPeriodForm',function(event){
		 // event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('e360settings')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#editMeasurementPeriodModal').modal('toggle');
					$( "#ldr" ).load('{{url('e360settings')}}');
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});
							});
		        }
		    });
        return event.preventDefault();
		});

  		$(document).on('submit','#addQuestionCategoryForm',function(event){
		 // event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('e360settings')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#addQuestionCategoryModal').modal('toggle');
					$( "#ldr" ).load('{{url('e360settings')}}');
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});
							});
		        }
		    });
            return event.preventDefault();
		});
  	$(document).on('submit','#editQuestionCategoryForm',function(event){
		 // event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('e360settings')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#editQuestionCategoryModal').modal('toggle');
					$( "#ldr" ).load('{{url('e360settings')}}');
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});
							});
		        }
		    });
        return event.preventDefault();
		});
  	});

  function formatMPDate(date){
  	var d = new Date(date);
         month = '' + (d.getMonth() + 1);
         day = '' + d.getDate();
         year = d.getFullYear();

     if (month.length < 2) month = '0' + month;
     if (day.length < 2) day = '0' + day;

    return [month,year].join('-');

  }


  </script>

