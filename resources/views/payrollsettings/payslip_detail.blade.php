	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Payroll Settings')}}</li>
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
		              <h3 class="panel-title">Payslip Details Settings</h3>
		              <div class="panel-actions">
                			

              			</div>
		            	</div>
		            	<form id="editPayslipForm" enctype="multipart/form-data">
		            <div class="panel-body">
		            <div class="col-md-6">
		            	@csrf
		            		<div class="form-group">
	          					<h4>Logo</h4>
	          					 <img class=" img-bordered  text-center" width="150" src="{{ asset('storage/'.$payslip_detail->logo) }}" id='img-upload'>
					      
					        <div class="input-group">
					            <span class="input-group-btn">
					                <span class="btn btn-default btn-file">
					                    Browse… <input type="file" id="imgInp" name="logo" accept="image/*">
					                </span>
					            </span>
					           
					        </div>
	          				</div>
	          				<div class="form-group" >
	          					<h4>Watermark Text</h4>
	          					<input type="text" name="watermark_text" class="form-control" value="{{$payslip_detail->watermark_text}}">
	          				</div>
	          				<input type="hidden" name=" type" value="payslip">
	          					            	
		            </div>
	                 
	          		</div>
	          		<div class="panel-footer">
	          			<div class="form-group">
	          					<button class="btn btn-info" >Save Changes</button>
	          				</div>
	          		</div>
	          		</form>
		          </div>
	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	  
	  <!-- End Page -->
	    <script type="text/javascript">
  	$(function() {
  
$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
		});

		// $('.btn-file :file').on('fileselect', function(event, label) {
		    
		//     var input = $(this).parents('.input-group').find(':text'),
		//         log = label;
		    
		//     if( input.length ) {
		//         input.val(log);
		//     } else {
		//         if( log ) alert(log);
		//     }
	    
		// });

		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#img-upload').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$("#imgInp").change(function(){
		    readURL(this);
		}); 

  	$('#editPayslipForm').submit(function(event){
  	
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

		            // toastr["success"]("Changes saved successfully",'Success');
		          console.log(data);
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

