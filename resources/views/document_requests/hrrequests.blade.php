@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
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
            <h1 class="page-title">{{__('Document Request')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Document Request')}}</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

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
                            <h3 class="panel-title"> Document Requests</h3>
                            <div class="panel-actions">



                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                    <tr>
                                        <th>Document request Type</th>
                                        <th>Title</th>
                                        <th>Due Date</th>
                                        <th>Comments</th>
                                        <th>Approval Status</th>
                                        <th>File Upload Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach($document_requests as $document_request)
                                            <td>{{$document_request->document_request_type?$document_request->document_request_type->name:""}}</td>
                                            <td>{{$document_request->title}}</td>
                                            <td>{{date("F j, Y", strtotime($document_request->due_date))}}</td>

                                            <td>{{$document_request->comment}}</td>
                                            <td><span class=" tag   {{$document_request->status==0?'tag-warning':($document_request->status==1?'tag-success':'tag-danger')}}">{{$document_request->status==0?'pending':($document_request->status==1?'approved':'rejected')}}</span></td>
                                            <td>{{$document_request->file!=''?'File Uploaded':'File Not uploaded'}}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                        <a data-id="{{ $document_request->id }}" style="cursor:pointer;"class="dropdown-item view-approval" id="{{$document_request->id}}" onclick="viewRequestApproval(this.id)"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Approval</a>

                                                        @if( $document_request->status==1)

                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               id="{{$document_request->id}}" onclick="upload(this.id)"><i
                                                                    class="fa fa-upload" aria-hidden="true"></i>&nbsp;&nbsp;Upload
                                                                Document</a>
                                                        @if($document_request->file!='')
                                                                <a style="cursor:pointer;"class="dropdown-item" id="" href="{{url('document_requests/download?document_request_id='.$document_request->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Document</a>
                                                        @endif
                                                        @endif
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
    @include('document_requests.modals.uploadrequestfile')
    {{-- Document Request Details Modal --}}
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="documentRequestDetailsModal" aria-hidden="true" aria-labelledby="documentRequestDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Document Request Details</h4>
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
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{ asset('global/vendor/webui-popover/jquery.webui-popover.min.js') }}"></script>

    <script type="text/javascript">


        (function($){

            $(function(){

                $('.selecttwo').select2();

                ///module start///

                let check = 0;
                let vl = 0;

                function doValidate(v){
                    vl = v || vl;
                    check = vl - $('#leave_days_requested').val();
                    if (check < 0){

                        toastr.error('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');

                        //alert('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');
                    }
                    return {
                        check: check > 0,
                        value: check
                    };
                }


                function ajaxStart(){
                    toastr.info('Processing ...');
                }

                function ajaxStop(){
                    toastr.info('Done.');
                }


                function postForm($form,$api){

                    let promise = {
                        success:function(cb,inValue){
                            // cb(inValue);
                            console.log(this);
                            this._success = cb;
                            return promise;
                        },
                        before:function(cb){
                            this._before = cb;
                            return promise;
                        },
                        _before:function(){
                            return true;
                        },
                        _success:function(){
                            //
                        },///
                        _error:function(){
                            //
                        },
                        error:function(cb,inError){
                            // cb(inError);
                            this._error = cb;
                            return promise;
                        },
                        processSuccess:function(response){
                            this._success(response);
                        },
                        processError:function(responseError){
                            this._error(responseError);
                        },
                        processBefore:function(content){
                            return this._before(content);
                        }
                    };

                    if (promise.processBefore({})){

                        ajaxStart();

                        var form = $form;
                        var formdata = false;
                        if (window.FormData){
                            formdata = new FormData(form[0]);
                        }
                        $.ajax({
                            url         : $api,
                            data        : formdata ? formdata : form.serialize(),
                            cache       : false,
                            contentType : false,
                            processData : false,
                            type        : 'POST',
                            success     : function(data, textStatus, jqXHR){

                                // promise.success();
                                promise.processSuccess({
                                    data:data,
                                    textStatus:textStatus,
                                    jqXHR:jqXHR
                                });

                                ajaxStop();

                                // toastr.success("Changes saved successfully..",'Success');
                                // $('#addLeaveRequestModal').modal('toggle');

                                // location.reload();

                            },
                            error:function(data, textStatus, jqXHR){

                                promise.processSuccess({
                                    data:data,
                                    textStatus:textStatus,
                                    jqXHR:jqXHR
                                });

                                ajaxStop();

                                //  jQuery.each( data['responseJSON'], function( i, val ) {
                                //   jQuery.each( val, function( i, valchild ) {
                                //   toastr.error(valchild[0]);
                                // });
                                // });

                            }
                        });

                    }

                    return promise;
                }








                $('.input-daterange').datepicker({
                    autoclose: true,
                    format:'yyyy-mm-dd'
                });


                $(document).on('submit','#addLeaveRequestForm',function(event){
                    event.preventDefault();
                    initAddLeaveForm();
                });
                $(document).on('submit','#addLeavePlanForm',function(event){
                    event.preventDefault();
                    initAddLeavePlanForm();
                });


                function viewRequestApproval(document_request_id)
                {
                    $(document).ready(function() {
                        $("#detailLoader").load('{{ url('/document_requests/get_details') }}?document_request_id='+document_request_id);
                        $('#documentRequestDetailsModal').modal();
                    });

                }

                window.viewRequestApproval = viewRequestApproval;  //Export to global windows object.
                ///module stop///

            });

        })(jQuery);

        ////End - JQuery ...

    </script>

    <script type="text/javascript">

        function datePicker(){
            $('.date_picker').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd'
            });
        }

        $(document).ready(function() {

            datePicker();
            $('#UploadDocumentRequestFileForm').submit(function(e){
                e.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('document_requests')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){

                        toastr["success"]("Changes saved successfully",'Success');
                        $('#UploadDocumentRequestFileModal').modal('toggle');
                        location.reload();
                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });



        });
        function upload(document_request_id) {
            $(document).ready(function () {
                $('#document_request_id').val(document_request_id);
                $('#UploadDocumentRequestFileModal').modal();
            });

        }


    </script>
@endsection
