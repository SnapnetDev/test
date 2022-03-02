@extends('layouts.master')
@section('stylesheets')
    <style type="text/css">
        .margin{
            margin-bottom:5px;
        }
        .hide{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-markdown/bootstrap-markdown.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/switchery/switchery.css')}}">

    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-table/bootstrap-table.css')}}">

    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.css')}}">

@endsection

@section('content')

    <style>
        .approval1{

            background-color: rgba(0,255,0,0.1);
            color: #000;

        }

        .approval2{
            background-color: rgba(255,0,0,0.1);
            color: #000;
        }

        .approval0{
            background-color: rgba(255,255,0,0.2);
            color: #000;
        }
    </style>


    <!-- Page -->
    <div class="page ">

        <div class="page-header" style="padding-bottom: 0;">
            <h1 class="page-title" style="text-transform: uppercase;">Onboarded Employees</h1>
        </div>

        <style>
            .info1 p{
                margin: 0;
            }

            .my-badge{

                display: inline-block;
                background-color: #ddd;
                padding: 2px;
                border-radius: 20%;
                padding-left: 6px;
                padding-right: 7px;

            }

            table th{

                color: #888;
                font-weight: bold !important;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 1px;


            }
        </style>

        @include('onboard.employee.style_inc')

{{--        @include('onboard.settings.create')--}}
        @foreach ($list as $item)
{{--            @include('onboard.employee.detail')--}}
        @endforeach

        <div class="page-content container-fluid" style="padding-top: 29px;">


{{--            <div class="col-md-12" align="right" style="margin-bottom: 11px;">--}}
{{--                <a href="#" data-toggle="modal" data-target="#create" class="btn btn-sm btn-success">+ Add Checklist</a>--}}
{{--            </div>--}}

            <div class="col-sm-12" style="
    /*background-color: #fff;*/
    /*padding: 31px;*/
    margin: 0;
">

{{--                <div class="col-md-12" align="right">--}}
{{--                    <i><b style="font-weight: bold;">N.B:</b> The checklist is ordered by steps</i>--}}
{{--                </div>--}}




                <table class="table table-hover table-bordered" style="background-color: #fff;border-top: 3px solid #445 !important;">
                    <thead>
                    <tr>
                        <th>
                            Employee
                        </th>
                        <th>
                            Stage
                        </th>
                        <th>
                            Progress
                        </th>
                        <th>
                            Current Handler
                        </th>
                        <th>
                            Current Uploaded Document
                        </th>
                        <th>
                            Created
                        </th>
                        <th>
                            Options
                        </th>
                    </tr>
                    </thead>

                    {{--                    $list--}}

                    @foreach ($records as $item)

                        <tr>
                            <td>
                                {{ $item->name }}
                            </td>
                            <td>
                                {{ $item->stage }}
                            </td>
                            <td>

                                <div class="progress progress-lg" style="margin:0;">
                                    <div class="progress-bar progress-bar-success" style="width: {{ $item->percentage_progress }}%;" role="progressbar">&nbsp;{{ $item->percentage_progress }}%</div>
                                </div>
                                {{--{{ $item->percentage_progress }}--}}
                            </td>
                            <td>
                              {{ $item->current_handler }}
                            </td>
                            <td>
                                @if ($item->has_document)
                                    <a href="{{ asset('uploads/' . $item->current_document) }}">Download Document</a>
                                @else
                                    Not Uploaded.
                                @endif
                            </td>
                            <td>
                                {{ $item->created_at }}
                            </td>
                            <td>
                                <a href="{{ route('onboard.start') }}?employee_id={{ $item->id }}" class="">
                                    <i class="fa fa-pencil"></i>
                                </a>
{{--                                <a href="" class="btn btn-xs btn-success">View History</a>--}}
                            </td>
                        </tr>

                    @endforeach




                </table>


                <div>
                    {{ $list->links() }}
                </div>



            </div>
        </div>

    </div>

    <!-- End Page -->

@endsection

@section('scripts')



    {{--    @include('onboard.message.toast')--}}

@endsection