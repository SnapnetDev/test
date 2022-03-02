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


@section('script-footer')

    @include('performance.training.scripts.course_script')

@endsection

@section('content')


    <!-- Page -->
    <div class="page ">

        {{--<div class="page-header" style="padding-bottom: 0;">--}}
            {{--<h1 class="page-title">Course Trainings</h1>--}}
        {{--</div>--}}

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

        <div class="page-content container-fluid" style="padding-top: 29px;">

            <div class="col-lg-3 col-xs-3 masonry-item" style="padding: 0;">
                <div class="card card-shadow">
                    <div class="card-header bg-blue-600" style="text-align: center;background-color: #03a9f4 !important;">
                        <a class="" href="javascript:void(0)">
                            <img style="border-radius: 50%;" src="{{ file_exists(public_path('uploads/avatar'.$employee->image))?asset('uploads/avatar'.$employee->image):($employee->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="">
                        </a>
{{--                        <div class="font-size-18">{{$employee->name}}</div>--}}
                        <div class="grey-300 font-size-14 info1">
                           @php
                             $genderDict = [
                               'm'=>'Male',
                               'f'=>'Female',
                               'n'=>'No Gender'
                             ];
                           @endphp
                             <p style="margin-top: 11px;">{{ucfirst($employee->name)}}</p>
                            <p>ID : {{$employee->emp_num}}</p>
                            <p>{{ $genderDict[strtolower($employee->sex)] }}</p>
                             <p>{{ !is_null($employee->job)? $employee->job->title : 'N/A'  }}</p>
                             <p>Hired : {{$employee->hiredate->diffForHumans()}}</p>

                        </div>
                    </div>
                    <ul class="list-group list-group-bordered m-b-0">

                        <li class="list-group-item" style="padding-top: 8px;padding-bottom: 8px;">

<div class="row" style="margin: 0">

    <div class="col-md-6">
        Enrolled <span class="my-badge" id="count-enrolled">0</span>
    </div>

    <div class="col-md-6" style="border-left: 1px solid #bbb;">
        Completed <span class="my-badge" id="count-completed">0</span>
    </div>

</div>

                        </li>


                    </ul>
                </div>
            </div>

            <div class="col-sm-9">



                <div class="panel nav-tabs-horizontal" data-plugin="tabs">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Course Trainings
                        </h3>


                        {{--<div class="panel-actions panel-actions-keep">--}}

                            {{--<div class="btn-group" role="group" data-toggle="tooltip" data-original-title="Click to dropdown">--}}
                                {{--<button type="button" class="btn btn-primary dropdown-toggle waves-effect" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">--}}
                                    {{--<i class="icon md-apps" aria-hidden="true"></i>--}}
                                {{--</button>--}}
                                {{--<div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">--}}
                                    {{--<a class="dropdown-item" onclick="showModal('addGoal')" href="javascript:void(0)" role="menuitem">Add Goal</a>--}}
                                    {{--@if(Auth::user()->role->permissions->contains('constant', 'add_kpi') && Auth::user()->id!=$employee->id)--}}
                                        {{--<a class="dropdown-item" data-target="#addkpi" onclick="unhide()" title="add Kpi" class="btn btn-outline btn-pure btn-success" data-toggle="modal" href="javascript:void(0)" role="menuitem">Add Kpi</a>--}}
                                    {{--@endif--}}
                                    {{--<a class="dropdown-item" data-target="#changeQuarter"   title="Change Quarter" class="btn btn-outline btn-pure btn-success" data-toggle="modal" href="javascript:void(0)" role="menuitem">Change Quarter</a>--}}

                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}



                    </div>
                    <ul class="nav nav-tabs nav-tabs-line" id="arcordion" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#available-courses" onclick="saveState('pilotapp')" aria-controls="" role="tab" aria-expanded="true">
                                <i class="icon wb-stats-bars" aria-hidden="true"></i> Available Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab"  href="#on-going" aria-controls="" role="tab">
                                <i class="icon wb-arrow-shrink"></i>Ongoing Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#completed" aria-controls="" role="tab">
                                <i class="icon wb-user"></i>Completed Courses
                            </a>
                        </li>
                    </ul>
                    <div class="panel-body">
                        <div class="tab-content">



                            <div class="tab-pane row active" id="available-courses" role="tabpanel" aria-expanded="false">
                                <!-- START HERE -->




                            <!-- END HERE -->
                            </div>



                            <div class="tab-pane" id="on-going" role="tabpanel" aria-expanded="false">
                                <!-- START HERE -->

                            <!-- END HERE -->
                            </div>
                            <div class="tab-pane" id="completed" role="tabpanel" aria-expanded="false">
                                <!-- START HERE -->

                            <!-- END HERE -->
                            </div>
                            <div class="tab-pane" id="idpapp" role="tabpanel" aria-expanded="true">
                                <!-- START HERE -->


                            <!-- END HERE -->
                            </div>
                            <div class="tab-pane" id="carapp" role="tabpanel">
                                <!-- START HERE -->


                            <!-- END HERE -->
                            </div>
                            <div class="tab-pane" id="kpis" role="tabpanel">
                                <!-- START HERE -->


                            <!-- END HERE -->
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
  @include('training_new.script');
@endsection