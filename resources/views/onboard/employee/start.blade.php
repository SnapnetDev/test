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
            <h1 class="page-title" style="text-transform: uppercase;">Onboard Checklist
                @if ($parent_id > 0)
                  / {{ $parent->action }}
                @endif
                ({{ $employee_email  }})
            </h1>
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
            @include('onboard.employee.check-detail')
            {{--            @include('onboard.employee.detail')--}}
        @endforeach

        <div class="page-content container-fluid" style="padding-top: 29px;">

            @if ($has_parent_request)

                        <div class="col-md-12" align="right" style="margin-bottom: 11px;">
                            <a href="{{ route('onboard.start') }}?employee_id={{ request('employee_id') }}" class="btn btn-sm btn-primary"> Back </a>
                        </div>

            @endif

            <div class="col-sm-12" style="
    /*background-color: #fff;*/
    /*padding: 31px;*/
">

   <div class="col-md-12" style="padding: 0;">
       <div class="progress progress-md" style="margin:0;/* border: 1px solid #999; */border-radius: 0;">
           <div class="progress-bar progress-bar-info" style="width: {{ $percentage_progress }}%;" role="progressbar">
               <b>Progress : &nbsp;{{ $percentage_progress }}%</b>
           </div>
       </div>
   </div>



                <table class="table table-hover table-bordered" style="background-color: #fff;border-top: 3px solid #445 !important;">
                   <thead>
                   <tr>
                       <th>
                           Steps
                       </th>
                       <th>
                           Actions
                       </th>
                       <th>
                           Document Template
                       </th>
                       <th>
                           Approver
                       </th>
                       <th>
                           Created
                       </th>
                       <th>
                           Options
                       </th>
                   </tr>
                   </thead>


                @foreach ($list as $index=>$item)


                        <tr>
                            <td>
                                {{ $item->step }}
                            </td>
                            <td>
                                {{ $item->action }}
                            </td>
                            <td>
                                @if ($item->document_template_is_empty)
                                   No-document required
                                @else
                                    <a href="{{ asset('uploads/' . $item->document_template) }}">Download</a>
                                @endif
                            </td>
                            <td>
                                {{ $item->assigned_personnel->name }}({{ $item->assigned_personnel->email }})
                            </td>

                            <td>
                                {{ $item->created_at }}
                            </td>
                            <td>
                               @if ($item->has_completed_previous_step)

                                   @if ($item->has_child_steps)
                                        <a href="{{ route('onboard.start')  }}?parent_id={{ $item->id }}&employee_id={{ $userId }}" class="btn btn-xs btn-warning">Review</a>
                                    @else
                                        <a data-toggle="modal" data-target="#start{{ $item->id }}" href="#" class="">
                                         @if ($item->user_has_submitted_feedback && $item->approved)
                                            <i class="fa fa-check"></i>
                                         @else

                                           @if ($item->user_has_submitted_feedback)
                                               Review
                                           @else
                                               Start Step
                                           @endif

                                         @endif
                                        </a>
                                   @endif
                                   @php

                                   @endphp
                               @else
                                  <div>
                                      Please complete previous step!
                                  </div>
                               @endif
                            </td>
                        </tr>

                    @endforeach




                </table>

                <div>

                </div>



            </div>
        </div>

    </div>

    <!-- End Page -->

@endsection

@section('scripts')



    {{--    @include('onboard.message.toast')--}}

@endsection