@extends('layouts.master')
@section('stylesheets')

    
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div>
        <div class="page">
            <!-- Mailbox Sidebar -->
            
            <!-- Mailbox Content -->
            <div class="page-main">
                <!-- Mailbox Header -->
                <div class="page-header">
                    <h1 class="page-title">Timesheet Report for {{$project->name}} in {{date('M-Y',strtotime($date))}} </h1>
                    <div class="page-header-actions">


                    </div>
                </div>
                <!-- Mailbox Content -->
                <div class="page-content container-fluid">
                    <!-- Actions -->
                    <center>
                    <div style="width: 200px">
                    <form id="monthForm" method="GET" action="{{url('projects/project_hours_report')}}" >
                        <div class="input-group">
   
   
                           <input type="text" id="" placeholder="mm-yyyy" name="month" class="form-control datepicker">
                            <input type="hidden" value="{{$project->id}}" name="project_id">
                           <span class="input-group-btn">
                             <button type="submit" class="btn btn-primary"><i class="icon fa fa-search" aria-hidden="true"></i></button>
                           </span>
   
   
                         </div>
                         </form>
                        </div>
                    </center>

                    <table id="data_table" class="table">
                        <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Fund Code</th>
                            <th>Employee Name</th>
                            <th>Employee Id</th>
                            <th>Employee Department Code</th>
                            <th>Office Code</th>
                            <th>Lin Code</th>
                            <th>Total Work Hours</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($report as $report_info)
                            <tr>
                                <td>{{$project->name}}</td>
                                <td>{{$project->fund_code}}</td>
                                <td>{{$report_info['name']}}</td>
                                <td>{{$report_info['employee_id']}}</td>
                                <td>{{$report_info['department_code']}}</td>
                                <td>{{$report_info['office_code']}}</td>
                                <td>{{$report_info['lin_code']}}</td>
                                
                                    <td>{{$report_info['total_hours']}}</td>
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
            $('.datepicker').datepicker({
    autoclose: true,
    format:'mm-yyyy',
     viewMode: "months",
    minViewMode: "months"
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
