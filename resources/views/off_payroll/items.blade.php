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
            <h1 class="page-title">{{ __('Off Payroll Items') }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Off Payroll Items') }}</li>
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
                            <h3 class="panel-title">Off Payroll Items</h3>
                            <div class="panel-actions">

                                <a class="btn btn-info" href="{{url('/items/create')}}">
                                    Create New Item
                                </a>

                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                        {{-- ['off_payroll_type_id','name','source','salary_component_constant','payroll_constant','amount','is_prorated','proration_type','percentage'] --}}
                                    <tr>
                                        <th>Off Payroll Type</th>
                                        <th>Name</th>
                                        <th>Source</th>
                                        <th>Component/Amount</th>
                                        <th>Is Prorated</th>
                                        <th>Percentage</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach ($items as $item)
                                            <td>{{ $item->type ? $item->type->name : '' }}
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->source == 'payroll_constant' ? 'Payroll Component' :( $item->source == 'salary_component'? '  Salary Component ':($item->source == 'amount'? ' Amount':'') ) }}</td>
                                            <td>
                                                @if ($item->source=='payroll_constant')
                                                    {{$item->payroll_constant}}
                                                @elseif($item->source=='salary_component')
                                                    {{$item->salary_component_constant}}
                                                @elseif($item->source=='amount')
                                                    {{$item->amount}}
                                                @endif
                                            </td>
                                            <td>{{$item->is_prorated==1?'Yes':'No'}}
                                                <td>{{ $item->percentage }}</td>
                                            <td>{{ date('F j, Y', strtotime($item->created_at)) }}</td>

                                            

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
                                                            style="cursor:pointer;" class="dropdown-item " href="{{url('/computations/'.$item->id)}}"
                                                            id="{{ $item->id }}"
                                                            ><i class="fa fa-penciil"
                                                                                                      aria-hidden="true"></i>&nbsp;View Computations</a>
                                                        <a data-id="{{ $item->id }}"
                                                           style="cursor:pointer;" class="dropdown-item "
                                                           id="{{ $item->id }}"
                                                           onclick="editItem(this.id)"><i class="fa fa-penciil"
                                                                                                     aria-hidden="true"></i>&nbsp;Edit Item</a>
                                                            <a data-id="{{ $item->id }}"
                                                                style="cursor:pointer;" class="dropdown-item "
                                                                id="{{ $item->id }}"
                                                                onclick="deleteItem(this.id)"><i class="fa fa-trash"
                                                                                                          aria-hidden="true"></i>&nbsp;Delete Item</a>

                                                       
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

@endsection