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


        table th{

            color: #888;
            font-weight: bold !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;


        }

    </style>


    <!-- Page -->
    <div class="page ">

        <div class="page-header" style="padding-bottom: 0;">
            <h1 class="page-title" style="text-transform: uppercase;">Onboard Settings {!! $sideLabel !!}</h1>
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
        </style>

        @include('onboard.settings.create')
        @foreach ($list as $item)
            @include('onboard.settings.edit')
        @endforeach

        <div class="page-content container-fluid" style="">


            <div class="col-md-12" align="right" style="margin-bottom: 11px;">
                @if (request()->filled('parent_id'))
                    <a href="{{ route('checklistSettings.index') }}"  class="btn btn-sm btn-info">Back</a>
                @endif
                <a href="#" data-toggle="modal" data-target="#create" class="btn btn-sm btn-success">+ New Checklist</a>
            </div>

            <div class="col-sm-12" style="
    /*background-color: #fff;*/
    /*padding: 31px;*/
">

                <div class="col-md-12" align="right">
                    <i><b style="font-weight: bold;">N.B:</b> The checklist is ordered by steps</i>
                </div>


{{--                $table->integer('step')->nullable();--}}
{{--                $table->string('action')->nullable();--}}
{{--                $table->integer('assigned_personnel_id')->nullable();--}}
{{--                $table->string('time')->nullable();--}}
{{--                $table->string('duration')->nullable();--}}
{{--                $table->string('document_template')->nullable();--}}


                <table class="table table-hover table-bordered" style="background-color: #fff;border-top: 3px solid #445 !important;">
                    <thead>
                    <tr>
                        <th>
                            Step
                        </th>
                        <th>
                            Action
                        </th>
                        <th>
                            Assigned Personnel
                        </th>
                        <th>
                            Time
                        </th>
                        <th>
                            Duration
                        </th>
                        <th>
                            Document Template
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

                    @foreach ($list as $item)

                        <tr>
                            <td>
                                {{ $item->step }}
                            </td>
                            <td>
                                {{ $item->action }}
                            </td>
                            <td>
                                {{ $item->assigned_personnel->name }} ({{ $item->assigned_personnel->email }})
                            </td>
                            <td>
                                {{ $item->time }}
                            </td>
                            <td>
                                {{ $item->duration }}
                            </td>
                            <td>
                                {!! $item->document_template_link !!}
                            </td>
                            <td>
                                {{ $item->created_at }}
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#edit{{ $item->id  }}">
                                    <i class="fa fa-pencil"></i></a>
                                &nbsp;
                                <a href="{{ route('checklistSettings.index') }}?parent_id={{ $item->id }}" class="btn btn-xs btn-info">
                                    <i class="fa fa-plus"></i>
                                </a>

                                &nbsp;

                                <form onsubmit="return confirm('Do you want to confirm this action?')" action="{{ route('checklistSettings.destroy',[$item->id]) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @endforeach




                </table>



            </div>
        </div>

    </div>

    <!-- End Page -->

@endsection

@section('scripts')



{{--    @include('onboard.message.toast')--}}

@endsection