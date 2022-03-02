@extends('layouts.master')
@section('stylesheets')

    <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/mailbox.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div>
        <div class="page bg-white">
            <!-- Mailbox Sidebar -->
            <div class="page-aside">
                <div class="page-aside-switch">
                    <i class="icon md-chevron-left" aria-hidden="true"></i>
                    <i class="icon md-chevron-right" aria-hidden="true"></i>
                </div>
                <div class="page-aside-inner page-aside-scroll">
                    <div data-role="container">
                        <div data-role="content">
                            <div class="page-aside-section">
                                <div class="list-group">
                                    <a class="list-group-item" href="{{url('audits/index')}}"><i class="icon md-accounts-add" aria-hidden="true"></i>Profile Changes</a>
                                    <a class="list-group-item active" href="{{url('audits/view_login_activity')}}"><i class="icon md-lock-open" aria-hidden="true"></i>Login Activity</a>
                                    <a class="list-group-item " href="{{url('audits/view_payroll_activity')}}"><i class="icon md-money-box" aria-hidden="true"></i>Payroll Activity</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Mailbox Content -->
            <div class="page-main">
                <!-- Mailbox Header -->
                <div class="page-header">
                    <h1 class="page-title">Employees Last Login</h1>
                    <div class="page-header-actions">


                    </div>
                </div>
                <!-- Mailbox Content -->
                <div id="mailContent" class="page-content page-content-table" data-plugin="asSelectable">
                    <!-- Actions -->

                    <table id="data_table" class="table">
                        <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee Id</th>
                            <th>Employee Email</th>
                            <th>Account Created</th>
                            <th>Last Login</th>
                            <th>Last IP Address</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->emp_num}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{date('F j, Y', strtotime($user->created_at))}}</td>
                                <td>{{$user->last_login_at?date('F j, Y H:i:s', strtotime($user->last_login_at)):""}}</td>
                                <td>{{$user->last_login_ip}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Label Form -->
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/App/Mailbox.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <!-- <script src="{{ asset('assets/examples/js/apps/mailbox.js')}}"></script> -->
    <script>
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd'
            });
        });
    </script>
    
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script type="text/javascript">

        $("#data_table").DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', {
                    extend:'excel',
                    exportOptions: {
                        columns: ':visible(.export-col)'
                }}, 'pdf', 'print'
            ]
        });

        function fnSubmit(arg)
        {
            $("#successor_id").val(arg);
            $("#update_form").submit();
        }

        function deleteEmployee($id){
            // deleteEmployee
        }

    </script>
@endsection
