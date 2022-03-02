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
            <h1 class="page-title">{{ __('Off Payroll Item Computation Details') }} {{$computation->item->name}} for {{$computation->year}}</h1>
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
                            <h3 class="panel-title">{{ __('Off Payroll Item Computation Details') }} {{$computation->item->name}} for {{$computation->year}}</h3>
                            <div class="panel-actions">

                                <a class="btn btn-info" href="#" onclick="recompute({{$computation->off_payroll_item_id}},{{$computation->year}})">
                                   Recompute
                                </a>

                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                        {{-- ['off_payroll_type_id','name','source','salary_component_constant','payroll_constant','amount','is_prorated','proration_type','percentage'] --}}
                                    <tr>
                                        <th>Employee</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach ($computation_details as $detail)
                                            <td>{{ $detail->user ? $detail->user->name : '' }}
                                            </td>
                                            <td>{{ $detail->amount }}</td>
                                            
                                            

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                            id="exampleIconDropdown1" data-toggle="dropdown"
                                                            aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                         role="menu">
                                                         <a data-id="{{ $computation->id }}"
                                                           style="cursor:pointer;" class="dropdown-item "
                                                           id="{{ $detail->id }}"
                                                           onclick="Recompute(this.id)"><i class="fa fa-penciil"
                                                                                                     aria-hidden="true"></i>&nbsp;Recompute</a>
                                                            <a data-id="{{ $computation->id }}"
                                                                style="cursor:pointer;" class="dropdown-item "
                                                                id="{{ $detail->id }}"
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
    
    
@endsection
@section('scripts')
<script>
    function recompute(item_id,year){
   


   $.get('{{ url('recompute') }}',{item_id:item_id,year:year},function(data){


   location.reload()
   });


}
</script>

@endsection