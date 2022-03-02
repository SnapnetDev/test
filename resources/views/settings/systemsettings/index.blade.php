<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('System Settings')}}</li>
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
		              <h3 class="panel-title"> Integrations</h3>
		              <div class="panel-actions">


              			</div>
		            	</div>
                     <form id="integrationPolicyForm" enctype="multipart/form-data">
                         @csrf
		            <div class="panel-body">
                        <div class="form-group">
                            <h4>Hcmatrix Application Key</h4>

                            <div class="input-group">
                                <input type="text" readonly class="form-control"
                                       value="{{$integration_policy->app_key}}" name="app_key" id="app_key">
                                <span class="input-group-btn">
                      <button type="button" onclick="generateAppKey()" class="btn btn-info waves-effect waves-light waves-round">Generate Key!</button>
                    </span>
                            </div>
                            <h4>HC Recruit Url</h4>
                            <input type="text" name="hcrecruit_url" class="form-control"
                                   value="{{$integration_policy->hcrecruit_url}}">
                            <h4>HC Recruit Application Key</h4>
                            <input type="text" name="hcrecruit_app_key" class="form-control"
                                   value="{{$integration_policy->hcrecruit_app_key}}">
                        </div>
                        <input type="hidden" name=" type" value="integration_policy">

	          		</div>
                     <div class="panel-footer">
                         <div class="form-group">
                             <button class="btn btn-info" type="submit">Save Changes</button>
                         </div>
                     </div>
                     </form>

		          </div>
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Use Parent Company Settings Across</h3>
		              <div class="panel-actions">
                			<input type="checkbox" class="active-toggle" id="useParent" {{-- $use_parent_setting->value==1?'checked':'' --}}>

              			</div>
		            	</div>
		            <div class="panel-body">
		            <p>Enable if you have subsidiaries and disable if you do not have subsidiaries</p>

	          		</div>

		          </div>
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Configure Allowed IP Address</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addIPModal">Add IP Address</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th >S/N:</th>
		                        <th >Address:</th>
		                        <th >Action:</th>
		                      </tr>
		                    </thead>
		                    <tbody>


		                    </tbody>
	                  </table>
	          		</div>
	          		</div>
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Log Policy Settings</h3>
                        <div class="panel-actions">


                        </div>
                    </div>

                        <div class="panel-body">
                            <div class="col-md-6">



                                <div class="form-group" >
                                    <h4>What Log should be saved?</h4>
                                    <input type="checkbox" class="active-toggle log_status" id="profile" {{$lp->firstWhere('name', 'profile')->value==1?'checked':''}} > Employee Profile
                                    <input type="checkbox" class="active-toggle log_status" id="payroll" {{$lp->firstWhere('name', 'payroll')->value==1?'checked':''}} > Payroll
                                    <input type="checkbox" class="active-toggle log_status" id="salary_component" {{$lp->firstWhere('name', 'salary_component')->value==1?'checked':''}} > Salary Component
                                </div>
                                <input type="hidden" name=" type" value="log_policy">

                            </div>

                        </div>


                </div>

	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">

	    	</div>
		</div>
	  </div>
{{-- Add IP Modal --}}
	   @include('settings.systemsettings.modals.addip')
	  {{-- edit IP modal --}}
	   @include('settings.systemsettings.modals.editip')
<script type="text/javascript">
  $(function() {
    $('#hasSubsidiaries').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });

    $('#useParent').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });
      $('.log_status').bootstrapToggle({
          on: 'Enabled',
          off: 'Disabled',
          onstyle:'info',
          offstyle:'default'
      });




    $('#hasSubsidiaries').change(function() {
    	if ($(this).prop('checked')==true) {

    		$('#useParent').bootstrapToggle('enable');

    	}else{
    		$('#useParent').bootstrapToggle({onstyle:'default'});
    		$('#useParent').bootstrapToggle('disable');
    	}

    	$.get('{{ url('/settings/system/switchhassub') }}/',function(data){
  		 	if (data==1) {
  		 		toastr.success("Has Subsidiary Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Has Subsidiary Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{route('systemsettings')}}');
  		 });

    })

    $('#useParent').on('change', function() {

		 $.get('{{ url('/settings/system/switchuseparent') }}/',function(data){
  		 	if (data==1) {
  		 		toastr.success("Parent Setting Use Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Parent Setting Use Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{route('systemsettings')}}');
  		 });
		});
      $('.log_status').on('change', function() {
        let log_type=$(this).attr('id');
        console.log(log_type);
          $.get('{{ url('/settings/system/switchlogpolicy') }}/',{log_type:log_type},function(data){
              if (data==1) {
                  toastr.success("Log Save Enabled",'Success');
              }
              if(data==2){
                  toastr.warning("Log Save Disabled",'Success');
              }
              $( "#ldr" ).load('{{route('systemsettings')}}');
          });
      });
      $(document).on('submit', '#integrationPolicyForm', function (event) {
          event.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData) {
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url: '{{route('integration_policy.store')}}',
              data: formdata ? formdata : form.serialize(),
              cache: false,
              contentType: false,
              processData: false,
              type: 'POST',
              success: function (data, textStatus, jqXHR) {

                  toastr.success("Changes saved successfully", 'Success');

              },
              error: function (data, textStatus, jqXHR) {
                  jQuery.each(data['responseJSON'], function (i, val) {
                      jQuery.each(val, function (i, valchild) {
                          toastr.error(valchild[0]);
                      });
                  });
              }
          });

      });

  });
  function generateAppKey(){
      $.get('{{ url('/systemsettings/generate_app_key') }}/',function(data) {
          console.log(data);
          $('#app_key').val(data);
      });

  }

</script>
