@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-table/bootstrap-table.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/ascolorpicker/asColorPicker.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('global/vendor/clockpicker/clockpicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/switchery/switchery.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/editable-table/editable-table.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css')}}">
    <style type="text/css">
        .btn[disabled] {
            pointer-events: none;
            cursor: not-allowed;
        }

        .hide {
            display: none;
        }

        .block {
            display: block;
        }

    </style>
@endsection

@section('content')
    <!-- Page -->
    <div class="page " style="min-height: 1200px;">
        <div class="page-aside">
            <!-- Contacts Sidebar -->
            <div class="page-aside-switch">
                <i class="icon md-chevron-left" aria-hidden="true"></i>
                <i class="icon md-chevron-right" aria-hidden="true"></i>
            </div>
            <div class="page-aside-inner page-aside-scroll">
                <div data-role="container">
                    <div data-role="content">
                        <div class="page-aside-section">
                            <div class="list-group">
                                @if (Auth::user()->role->permissions->contains('constant', 'group_access'))
                                    <a class="list-group-item active setting-linker" href="{{ route('companies') }}" title="Company Settings">
                                        <i class="icon fa fa-building" aria-hidden="true"></i>{{__('Company Settings')}}
                                    </a>
                                    <a class="list-group-item setting-linker" href="{{ route('employeesettings') }}" title="Company Settings">
                                        <i class="icon fa fa-users" aria-hidden="true"></i>{{__('Employee Settings')}}
                                    </a>
                                @endif
                                {{-- <a class="list-group-item setting-linker" href="{{route('employeedesignationsettings')}}" title="Employee Designation Settings">
                                 <i class="icon fa fa-address-card" aria-hidden="true"></i>{{__('Employee Designation Settings')}}
                                </a> --}}
                                <a class="list-group-item setting-linker" href="{{ route('attendancesettings') }}" title="Attendance Settings">
                                    <i class="icon fa fa-calendar" aria-hidden="true"></i>{{__('Attendance Settings')}}
                                </a>
                                <a class="list-group-item setting-linker" href="{{ route('shiftsettings') }}" title="Shift Settings">
                                    <i class="icon fa fa-calendar-o" aria-hidden="true"></i>{{__('Shift Settings')}}
                                </a>
                                <a class="list-group-item setting-linker" href="{{ route('leavesettings') }}" title="Leave Settings">
                                    <i class="icon fa fa-calendar-o" aria-hidden="true"></i>{{__('Leave Settings')}}
                                </a>
                                <a class="list-group-item setting-linker" href="{{ url('notifications') }}/index" title="Notification & Alert Settings" >
                                    <i class="icon fa fa-bell"
                                       aria-hidden="true"></i>{{__('Notification & Alert Settings')}}
                                </a>
                                <a class="list-group-item setting-linker" href="{{ url('document_requests/settings')}}" title="Document Request Settings">
                                    <i class="icon fa fa-wrench"
                                       aria-hidden="true"></i>{{__('Document Request Settings')}}
                                </a>
                                <a class="list-group-item setting-linker" href="{{ url('employee_reimbursements/settings')}}" title="Employee Reimbursement Settings">
                        <i class="icon fa fa-wrench" aria-hidden="true"></i>{{__('Employee Reimbursement Settings')}}
                    </a>@if (Auth::user()->role->permissions->contains('constant', 'group_access'))
                                    <a class="list-group-item setting-linker" href="{{ route('systemsettings') }}" title="System Settings">
                                        <i class="icon fa fa-wrench" aria-hidden="true"></i>{{__('System Settings')}}
                                    </a>
                                @endif
                                <a class="list-group-item setting-linker" href="{{ url('e360settings')}}" title="360 Review Settings">
                                    <i class="icon fa fa-circle-o-notch"
                                       aria-hidden="true"></i>{{__('360 Review Settings')}}
                                </a>
                                <!--<a class="list-group-item setting-linker" href="{{ url('performances')}}/settings" title="Performance Settings">-->
                                <!--    <i class="icon fa fa-wrench" aria-hidden="true"></i>{{__('Performance Settings')}}-->
                                <!--</a>-->
                                <a class="list-group-item setting-linker" href="{{ url('bscsettings')}}" title="Balance Score Card Settings">
                                  <i class="icon fa fa-wrench"
                                     aria-hidden="true"></i>{{__('Balance Score Card Settings')}}
                                -</a>
                                <a class="list-group-item setting-linker" href="{{ url('query')}}/settings" title="Query Settings">
                                    <i class="icon fa fa-wrench" aria-hidden="true"></i>{{__('Query Settings')}}
                                </a>
                                    <a class="list-group-item setting-linker" href="{{ url('separation/settings')}}" title="Separation Settings">
                                        <i class="icon fa fa-wrench" aria-hidden="true"></i>{{__('Separation Settings')}}
                                    </a>
                                    <a class="list-group-item setting-linker" href="{{ url('confirmation/settings')}}" title="Confirmation Settings">
                                        <i class="icon fa fa-wrench" aria-hidden="true"></i>{{__('Confirmation Settings')}}
                                    </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-main">
            <div id="ldr">

            </div>
        </div>
    </div>
    <!-- End Page -->

@endsection
@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('global/vendor/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
    <script src="{{ asset('global/vendor/switchery/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{ asset('global/vendor/editable-table/mindmup-editabletable.js')}}"></script>
    <script src="{{ asset('global/vendor/editable-table/numeric-input-example.js')}}"></script>
    <script src="{{ asset('global/vendor/jscolor/jscolor.js')}}"></script>
    <script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>

    <script src="{{asset('global/vendor/ascolorpicker/jquery-asColorPicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/ascolor/jquery-asColor.min.js')}}"></script>
    <script src="{{asset('global/vendor/asgradient/jquery-asGradient.min.js')}}"></script>
    <script src="{{asset('global/vendor/ascolorpicker/jquery-asColorPicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>

    <script src="{{asset('global/js/Plugin/ascolorpicker.js')}}"></script>

    <script type="text/javascript">
        $(function () {


            $('#rolestable').DataTable();

            // $( "#ldr" ).load( "{{route('companies')}}" );

        });


        $(function () {

            url = sessionStorage.getItem('href') != null ? sessionStorage.getItem('href') : "{{ url('notifications') }}/index";
            // console.log(url);
            $(".setting-linker").each(function () {
                $(this).attr("href") == sessionStorage.getItem('href') ? $(this).addClass("active") : $(this).removeClass("active");
            });

            href = $(this).attr('href');
            $("#ldr").load(url);

        });
        $(document).on('click', '.linker', function (event) {
            event.preventDefault();
            href = $(this).attr('href');
            sessionStorage.setItem('href', href);
            // console.log(href);
            $("#ldr").load(href);
        });


        $(document).on('click', '.setting-linker', function (event) {
            event.preventDefault();
            $(".setting-linker").each(function () {
                $(this).removeClass("active");
            });
            $(this).addClass("active");
            href = $(this).attr('href');
            sessionStorage.setItem('href', href);
            $("#ldr").load(href);
        });
    </script>


@endsection
