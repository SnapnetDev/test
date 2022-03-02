@extends('layouts.master')
@section('stylesheets')

    <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/mailbox.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
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
                                    <a class="list-group-item active" href="{{url('userprofile/change_approval')}}"><i class="icon md-accounts-add" aria-hidden="true"></i>Employee Information Changes</a>
                                    <a class="list-group-item " href="{{url('userprofile/nok_change_approval')}}"><i class="icon md-lock-open" aria-hidden="true"></i>Next of Kin Changes</a>
                                    <a class="list-group-item " href="{{url('userprofile/educational_history_change_approval')}}"><i class="icon md-accounts-list
" aria-hidden="true"></i>Academic History</a>
                                    <a class="list-group-item " href="{{url('userprofile/employment_history_change_approval')}}"><i class="icon md-accounts-list
" aria-hidden="true"></i>Employment History</a>
                                    <a class="list-group-item " href="{{url('userprofile/dependant_change_approval')}}"><i class="icon md-accounts-list
" aria-hidden="true"></i>Dependant Change</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Mailbox Content -->
            <div class="page-main" style="min-height:700px;">
                <!-- Mailbox Header -->
                <div class="page-header">
                    <h1 class="page-title">Employee Profile Change Approval</h1>

                </div>
                <!-- Mailbox Content -->
                <div id="mailContent" class="page-content page-content-table" data-plugin="asSelectable">
                    <!-- Actions -->
                    <div class="page-content-actions">

                        <div class="actions-main">
            <span class="checkbox-custom checkbox-primary checkbox-lg inline-block vertical-align-bottom">
              <input type="checkbox" class="mailbox-checkbox selectable-all" id="select_all"
              />
              <label for="select_all"></label>
            </span>


                        </div>

                    </div>
                @if(count($changes)>0)
                    <!-- Mailbox -->
                        <table id="mailboxTable" class="table" data-plugin="animateList" data-animate="fade"
                               data-child="tr">
                            <tbody>
                            @foreach($changes as $change)

                                    <tr id="mid_1" data-url="{{url('userprofile/view_profile_change').'?change_id='.$change->id.'&user_id='.$change->user_id}}" data-toggle="slidePanel" style="">

                                        <td class="cell-60">
                <span class="checkbox-custom checkbox-primary checkbox-lg">
                  <input type="checkbox" class="mailbox-checkbox selectable-item" id="mail_mid_1"
                  />
                  <label for="mail_mid_1"></label>
                </span>
                                        </td>

                                        <td class="cell-60 responsive-hide ">
                                            <a class="avatar" href="javascript:void(0)">
                                                <i class="icon md-search-replace  " ></i>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="content">
                                                <div class="title">{{$change->name}} is requesting approval for a change in their profile  </div>

                                                <div class="abstract"></div>
                                            </div>
                                        </td>
                                        <td class="cell-30 responsive-hide">
                                        </td>
                                        <td class="cell-130">
                                            <div class="time">{{$change->updated_at->diffForHumans()}}</div>
                                            {{--  <div class="identity"><i class="md-circle red-600" aria-hidden="true"></i>Work</div> --}}
                                        </td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    @else
                        <h3 style="margin-left:30%"> No Changes Found !!! </h3>
                    @endif
                </div>
            @if (request()->pagi!='all'&& count($changes)>1)
                {!! $changes->appends(Request::capture()->except('page'))->render() !!}
            @endif
            <!-- End Add Label Form -->
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/js/App/Mailbox.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
    <!-- <script src="{{ asset('assets/examples/js/apps/mailbox.js')}}"></script> -->
    <script>
        $(document).ready(function() {

            $('.input-daterange').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd'
            });
            $(document).on('click','#selectAll',function(){
                $("#panel input[type=checkbox]").prop("checked", $(this).prop("checked")).change();
            });
            $(document).on('click','#panel input[type=checkbox]',function(){
                if (!$(this).prop("checked")) {
                    $("#selectAll").prop("checked", false);
                }
            });
            $(document).on('change','.chk',function(){
                var id= $(this).attr('id');
                if($(this). is(":checked")){
                    $('.'+id).prop("disabled", false);
                }
                else if($(this). is(":not(:checked)")){
                    $('.'+id).prop("disabled", true);
                }
            });
            $(document).on('submit','#approveChangeForm',function(event){
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('userprofile')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){

                        toastr.success("Changes saved successfully",'Success');
                        // $('#addDocumentModal').modal('toggle');
                        location.reload();

                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr.error(valchild[0]);
                            });
                        });
                    }
                });

            });
        });
        function rejectChange(user_id){
            alertify.confirm('Are you sure you want to reject this change ?', function () {


                $.get('{{ url('userprofile/reject_profile_changes') }}',{user_id:user_id},function(data){
                    if (data=='success') {
                        toastr["success"]("Change rejected successfully",'Success');
                        location.reload();
                    }else{
                        toastr["error"]("Error rejecting change ",'Success');
                    }
                });
            }, function () {
                alertify.error('Changes to profile could not be rejected');
            });
        }
    </script>
@endsection
