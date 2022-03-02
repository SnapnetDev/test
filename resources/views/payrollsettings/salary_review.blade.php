	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Employee Settings')}}</li>
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
		              <h3 class="panel-title">Salary Reviews</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addSalaryReviewModal">Add Salary Review</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            	<button class="btn btn-danger" id='multi-delete'>Delete Selected Items</button>
		            <div class="table-responsive">
	                  <table  class="table table-striped " id="dataTable">
		                    <thead>
		                      <tr>
		                          <th>Created In:</th>
		                        <th>Employee Name:</th>
		                        <th>Review Month:</th>
		                        <th>Payment Month:</th>
                                  <th>Previous Monthly Gross:</th>
		                        <th>Used:</th>
	                          	<th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($reviews as $review)
		                    	<tr id="{{$review->id}}">
		                    	    <td>{{ date("F, Y", strtotime($review->created_at)) }}</td>
                                    <td>{{$review->employee ? $review->employee->name :''}}</td>
                                    <td>{{ date("F, Y", strtotime($review->review_month)) }}</td>
                                    <td>{{ date("F, Y", strtotime($review->payment_month)) }}</td>
                                    <td>{{ round($review->previous_gross,2) }}</td>
		                    		<td>{{$review->used==1?'Used':'Not Used'}}</td>



		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                    	@if($review->used==0)
				                    	<a class="dropdown-item" id="{{$review->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Salary Review</a>
				                    	@endif
				                       <a class="dropdown-item" id="{{$review->id}}" onclick="deleteSalaryReview(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Salary Review</a>

				                    </div>
				                  </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse

		                    </tbody>
	                  </table>
	                  </div>
	          		</div>

		          </div>



	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">

	    	</div>
		</div>
	  </div>
	  {{-- Add Grade Modal --}}
	   @include('payrollsettings.modals.addsalaryreview')
	   @include('payrollsettings.modals.editsalaryreview')

	  <!-- End Page -->
	    <script type="text/javascript">
	    	 $(document).ready( function () {

	    	 	$('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
    $('#dataTable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    var table = $('#dataTable').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        "pageLength": 50,
        "ordering": false
    } );
    // $('#dataTable').DataTable();
    $('.sc-status').bootstrapToggle({
      on: 'on',
      off: 'off',
      onstyle:'info',
      offstyle:'default'
    });
     $('.sc-taxable').bootstrapToggle({
      on: 'on',
      off: 'off',
      onstyle:'info',
      offstyle:'default'
    });
                 $('.monthpicker').datepicker({
                     autoclose: true,
                     format:'mm-yyyy',
                     viewMode: "months",
                     minViewMode: "months"
                 });

} );
  	$(function() {

  		$('#addSalaryReviewForm').submit(function(event){

		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('payrollsettings.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           $('#addSalaryReviewModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/salary_reviews')}}');
				location.reload();

		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr.error(valchild[0]);
							});
							});
		        }
		    });

		});
		$('#editSalaryReviewItemForm').submit(function(event){

		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('payrollsettings.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           $('#editSalaryReviewModal').modal('hide');
				// 	$( "#ldr" ).load('{{url('payrollsettings/salary_reviews')}}');
				location.reload();

		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr.error(valchild[0]);
							});
							});
		        }
		    });

		});


  });





  	function prepareEditData(salary_review_id){
    $.get('{{ url('/payrollsettings/salary_review') }}/',{ salary_review_id: salary_review_id },function(data){

     $('#srreview_month').val(data.review_month);
     $('#srpayment_month').val(data.payment_month);
        $('#srprevious_gross').val(data.previous_gross);
      $("#eemps").find('option').remove();

    $("#eemps").append($('<option>', {value:data.user.id, text:data.user.name,selected:'selected'}));

     $('#eid').val(data.id);
    });
    $('#editSalaryReviewModal').modal();
  }
  function deleteSalaryReview(salary_review_id){
    $.get('{{ url('/payrollsettings/delete_salary_review') }}/',{ salary_review_id: salary_review_id },function(data){
    	if (data=='success') {
 		toastr.success("Salary Review deleted successfully",'Success');
//  		$( "#ldr" ).load('{{url('payrollsettings/salary_reviews')}}');
location.reload();
    	}else{
    		toastr.error("Error deleting Salary Review",'Success');
    	}

    });
  }



    /* Add a click handler to the rows - this could be used as a callback */
    $("#dataTable tbody tr").click( function( e ) {
    	console.log($(this).attr('id'));
        if ( $(this).hasClass('row_selected') ) {
            $(this).removeClass('row_selected');
        }
        else {
            oTable.$('tr.row_selected')//.removeClass('row_selected');
            $(this).addClass('row_selected');
        }
    });


    /* Add a click handler for the delete row */
    $('#multi-delete').click( function() {
    	 alertify.confirm('Are you sure you want to delete these itens?', function () {
    	 	oTable.$('tr.row_selected').each(function() {
    	 		var review_id= $( this ).attr('id');
			   $.get('{{ url('/payrollsettings/delete_salary_review') }}/',{ review_id: review_id },function(data){
				    });
			});
			var anSelected = fnGetSelected( oTable );
       		 $(anSelected).remove();
              alertify.success('Salary Review deleted succesfully');
              location.reload();
            }, function () {
              alertify.error('Components not deleted');
            });


    } );






    oTable = $('#dataTable').dataTable( );



/* Get the rows which are currently selected */
function fnGetSelected( oTableLocal )
{
    return oTableLocal.$('tr.row_selected');
}




 $(function(){

  $('#emps').select2({
  	placeholder: "Employee Name",
  	 multiple: false,
  	id: function(bond){ return bond._id; },
	   ajax: {
		 delay: 250,
		 processResults: function (data) {
					return {
		results: data
			};
		},
		url: function (params) {
		return '{{url('users')}}/search';
		}
		}

	});

  });



  </script>

