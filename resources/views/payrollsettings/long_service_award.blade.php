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
                    <h3 class="panel-title">Long Service Awards</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addAwardModal">Add Award</button>

                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable" id="">
                            <thead>
                                <tr>
                                    <th>Max Year:</th>
                                    <th>Amount:</th>
                                    <th>Difference:</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($lsas as $award)

                                <tr style="cursor: pointer;" title="drag to re-order" data-toggle="tooltip"
                                    data-placement="top">
                                    <td>{{$award->max_year}}</td>
                                    <td>{{$award->amount}}</td>
                                    <td>{{$award->difference}}</td>


                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                role="menu">
                                                <a class="dropdown-item" id="{{$award->id}}"
                                                    onclick="prepareEditData(this.id)"><i class="fa fa-pencil"
                                                        aria-hidden="true"></i>&nbsp;Edit Award</a>
                                                <a class="dropdown-item" id="{{$award->id}}"
                                                    onclick="deleteAward(this.id)"><i class="fa fa-trash"
                                                        aria-hidden="true"></i>&nbsp;Delete Award</a>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Employees Due For Long Service Award</h3>
                    <div class="panel-actions">


                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable" id="">
                            <thead>
                                <tr>
                                    <th>Staff ID:</th>
                                    <th>Employee Name:</th>
                                    <th>Current Award Category:</th>
                                    <th>Length of Service:</th>
                                    <th>Awards Due:</th>

                                </tr>
                            </thead>
                            <tbody>

                                @forelse($users as $user)

                                <tr>
                                    <td>{{$user->emp_num}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>
                                    @foreach($lsas as $award)
                                    @if ($user->months_of_service <= ($award->max_year*12))
                                        {{$award->amount}} 
                                        @php
                                        break;
                                    @endphp
                                    @endif

                                    
                                    @endforeach
                                </td>
                                        <td>{{--{{$user->years_of_service>0?$user->years_of_service:0}} Year(s),--}}
                                             {{$user->months_of_service>0 && $user->years_of_service>0?$user->years_of_service.' Year(s),'.$user->months_of_service%$user->years_of_service:$user->months_of_service}}
                                            month(s)</td>

                                        <td>
                                            @foreach($lsas as $award)
                                            @if ($user->months_of_service >= ($award->max_year*12))
                                            @if ($loop->last)
                                            {{$award->amount}}
                                            @else
                                            {{$award->amount}},
                                            @endif


                                            @endif

                                            @endforeach
                                        </td>

                                        
                                </tr>
                                @empty
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Payroll Policy Settings</h3>
                    <div class="panel-actions">


                    </div>
                </div>

                <div class="panel-body">
                    <div class="col-md-6">

                        <div class="form-group">
                            <h4>Select Preferences For Long service Award Display </h4>
                            <input type="checkbox" class="active-toggle display_status" id="nav_export_display"
                                {{$pp->display_lsa_on_nav_export==1?'checked':''}}> Display on Nav Export
                            <input type="checkbox" class="active-toggle display_status" id="payroll_export_display"
                                {{$pp->display_lsa_on_payroll_export==1?'checked':''}}> Display on Payroll Export

                        </div>
                        <input type="hidden" name=" type" value="payroll_policy">

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
@include('payrollsettings.modals.addaward')
{{-- edit grade modal --}}
@include('payrollsettings.modals.editaward')
<!-- End Page -->
<script type="text/javascript">
    $(document).ready( function () {
    $('.dataTable').DataTable();
    $('.display_status').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });

} );
  	$(function() {

        $('#AddAwardForm').submit(function(event){
		
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
		           $('#addAwardModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/chart_of_accounts')}}');
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
            
            return  event.preventDefault();

		});
  });
  	$(function() {
        $('#EditAwardForm').submit(function(event){
  	
		 
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
		            $('#editAwardModal').modal('toggle');
				// 	$( "#ldr" ).load('{{url('payrollsettings/chart_of_accounts')}}');
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
            
            return event.preventDefault();

        });

        $('#nav_export_display').on('change', function() {

        $.get('{{ url('/payrollsettings/switch_nav_export_display') }}/',function(data){
            if (data==1) {
                toastr.success("Nav Export Display Enabled",'Success');
            }
            if(data==2){
                toastr.warning("Nav Export Display Disabled",'Success');
            }
            $( "#ldr" ).load('{{url('payrollsettings/long_service_awards')}}');
        });
        });
        $('#payroll_export_display').on('change', function() {

        $.get('{{ url('/payrollsettings/switch_payroll_export_display') }}/',function(data){
            if (data==1) {
                toastr.success("Payroll Export Enabled",'Success');
            }
            if(data==2){
                toastr.warning("Payroll Export Disabled",'Success');
            }
            $( "#ldr" ).load('{{url('payrollsettings/long_service_awards')}}');
        });
        });
  });



  	function prepareEditData(award_id){
    $.get('{{ url('/payrollsettings/long_service_award') }}/',{ award_id: award_id },function(data){

     $('#editmax_year').val(data.max_year);
     $('#editamount').val(data.amount);
     $('#editdifference').val(data.difference);

     $('#editid').val(data.id);
    });
    $('#editAwardModal').modal();
  }

  function deleteAward(award_id){
    $.get('{{ url('/payrollsettings/delete_long_service_award') }}/',{award_id:award_id},function(data){
    	if (data=='success') {
 		toastr.success("Award deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/long_service_awards')}}');
    	}else{
    		toastr.error("Error deleting Award",'Success');
    	}

    });
  }
</script>