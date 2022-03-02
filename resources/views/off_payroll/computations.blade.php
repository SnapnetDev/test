@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('global/vendor/webui-popover/webui-popover.min.css') }}">
    <style type="text/css">
        .btn-floating.btn-sm {

            width: 4rem;
            height: 4rem;

        }

    </style>
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{ __('Off Payroll Item Computations') }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Off Payroll Item Computations') }}</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{ date('Y-m-d') }}</span>

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
                <div class="col-ms-12 col-xs-12 col-md-12">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Off Payroll Item Computations for {{$item->name}}</h3>
                            <div class="panel-actions">

                                <button class="btn btn-info" data-toggle="modal" data-target="#addComputationModal">Create New Computation</button>

                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                        {{-- ['off_payroll_type_id','name','source','salary_component_constant','payroll_constant','amount','is_prorated','proration_type','percentage'] --}}
                                    <tr>
                                        <th>Off Payroll Item</th>
                                        <th>Year</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach ($computations as $computation)
                                            <td>{{ $computation->item ? $computation->item->name : '' }}
                                            </td>
                                            <td>{{ $computation->year }}</td>
                                            
                                            

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                            id="exampleIconDropdown1" data-toggle="dropdown"
                                                            aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                         role="menu">
                                                         <a 
                                                            style="cursor:pointer;" class="dropdown-item "
                                                            id="{{ $computation->id }}" href="{{url('computation-details/'.$computation->id)}}"
                                                            ><i class="fa fa-penciil"
                                                                                                      aria-hidden="true"></i>&nbsp;View Computations</a>
                                                        <a data-id="{{ $computation->id }}"
                                                           style="cursor:pointer;" class="dropdown-item "
                                                           id="{{ $computation->id }}"
                                                           onclick="recompute({{$computation->off_payroll_item_id}},{{$computation->year}})"><i class="fa fa-penciil"
                                                                                                     aria-hidden="true"></i>&nbsp;Recompute</a>
                                                            <a data-id="{{ $computation->id }}"
                                                                style="cursor:pointer;" class="dropdown-item "
                                                                id="{{ $computation->id }}"
                                                                onclick="deleteComputation(this.id)"><i class="fa fa-trash"
                                                                                                          aria-hidden="true"></i>&nbsp;Delete Computation</a>

                                                       
                                                    </div>
                                                </div>
                                            </td>
                                    </tr>
                                    @endforeach

                                    </tbody>

                                </table>
                            </div>


                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- End Page -->
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="addComputationModal" aria-hidden="true" aria-labelledby="addComputationModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addComputationForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Create Computation</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                    
                    <div class="form-group">
                      <h4 class="example-title">Year</h4>
                      <input type="hidden" name="item_id" value="{{$item->id}}">
                    <input readonly type="text" class="input-sm form-control yearpicker" name="year" placeholder="Year" value=""/>
                    
                    
                    </div>
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	
                  <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>
    
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script>
     $('.yearpicker').datepicker({
    autoclose: true,
    format:'yyyy',
     viewMode: "years",
    minViewMode: "years"
});
addComputationForm.onsubmit = async (e) => {
e.preventDefault();

let response = await fetch('{{url('/compute_all')}}', {
  method: 'POST',
  body: new FormData(addComputationForm)
});

let result = await response.json();
if(result.success){
        console.log('success');
        location.reload()
    }else{
        console.log('error');
    }
};
function recompute(item_id,year){
   


	$.get('{{ url('recompute') }}',{item_id:item_id,year:year},function(data){


    location.reload()
    });
 

}

</script>

@endsection