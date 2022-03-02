@extends('layouts.master')
@section('stylesheets')


    <link rel="stylesheet" href="{{ asset('global/vendor/fullcal/fullcalendar.min.css') }}"/>
    <style type="text/css">
        a.list-group-item:hover {
            text-decoration: none;
            background-color: #3f51b5;
        }
    </style>
@endsection
@section('content')
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">User Attendance for {{$user->name}}</h1>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content container-fluid">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">User Attendances</h3>
                        <div class="panel-actions"></div>
                </div>
                <div class="panel-body">

                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Site Action -->

    <!-- End Page -->
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="attendanceDetailsModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Clock In History</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12" id="detailLoader">

                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">


                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')


    <script src="{{ asset('global/vendor/fullcal/lib/moment.min.js') }}"></script>
    <script src="{{ asset('global/vendor/fullcal/fullcalendar.min.js') }}"></script>
    {{-- {!! $calendar->script() !!} --}}
    <script type="text/javascript">
        $(function () {
            $('#calendar').fullCalendar({
                noEventsMessage: '{{__('No Attendance For today')}}',
                allDayText: '{{__('Attendance for Today')}}',
                eventLimit: true,
                defaultView: 'month',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },
                events: {
                    url: '{{url('user_attendance_calendar/'.$user->id)}}',
                    error: function () {
                        $('#script-warning').show();
                    },
                    color: '#263238',     // an option!
                    textColor: '#ffffff' // an option!

                },
                eventClick: function (eventObj) {
                    if (eventObj.id != '') {
                        $("#detailLoader").load('{{ url('/attendance/getdetails') }}/' + eventObj.id);
                        $('#attendanceDetailsModal').modal();

                    }


                }

            });

        });


    </script>
@endsection