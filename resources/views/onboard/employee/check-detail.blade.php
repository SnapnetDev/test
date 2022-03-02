@if ($item->user_has_submitted_feedback)
    <form  action="{{ route('employeeChecklists.update',[$item->employee_checklist_id]) }}" method="post" enctype="multipart/form-data">
        @method('PUT')
@else
    <form  action="{{ route('employeeChecklists.store') }}" method="post" enctype="multipart/form-data">
@endif
        @csrf

{{--    employeeChecklists--}}
    <input type="hidden" name="onboarding_check_list_id" value="{{ $item->id }}" />
    <input type="hidden" name="employee_id" value="{{ $userId }}" />

    <div id="start{{ $item->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Checklist: {{ $item->action }} ({{ $item->approval_status }})</h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Upload Matching Document
                                    </label>
                                    <input  type="file" name="support_document" class="form-control" placeholder="Upload Document" />
                                </div>

                                @if (!$item->document_template_is_empty)
                                    <div>
                                        <div>
                                            <div>
                                                <a href="{{ asset('uploads/' . $item->document_template) }}">Download Document Template</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Assigned Personnel
                                    </label>

                                    <div>
                                        {{ $item->personnel_name }}
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="">
                                        Time / Duration
                                    </label>

                                    <div>
                                        {{ $item->time }} / {{ $item->duration }} minutes
                                    </div>

                                </div>
                            </div>




                            <div style="clear: both"></div>

{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="">--}}
{{--                                        Employee Comment--}}
{{--                                    </label>--}}
{{--                                    <textarea name="comment_employee"  class="form-control"></textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Comments
                                    </label>
                                    @if ($item->can_approve)
                                        <textarea name="comments" readonly  class="form-control">{{ $item->comments }}</textarea>
                                    @else
                                        <textarea name="comments"  class="form-control">{{ $item->comments }}</textarea>
                                    @endif

                                </div>

                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Supervisor's Comment
                                    </label>
                                    @if ($item->can_approve)
                                      <textarea name="comment_supervisor"  class="form-control">{{ $item->comment_supervisor }}</textarea>
                                    @else
                                        <textarea name="comment_supervisor"  class="form-control" readonly>{{ $item->comment_supervisor }}</textarea>
                                    @endif

                                </div>
                            </div>


                            @if ($item->has_support_document)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        <a href="{{ asset('uploads/' . $item->support_document) }}">Download Scanned Document</a>
                                    </label>
                                </div>
                                {{--       onboarding_check_list_id--}}
                            </div>
                            @endif


                            @if ($item->is_approved)
                                <div class="col-md-12">
                                    <label class="alert alert-success col-md-12" for="">
                                        Approved
                                    </label>
                                </div>
                            @else
                               @if ($item->user_has_submitted_feedback)
                                <div class="col-md-12">
                                    <div class="alert alert-warning" for="">
                                        Pending Approval
                                    </div>
                                </div>
                               @endif
                            @endif



                            @if ($item->can_approve)

                                <div class="col-md-12" style="text-align: right;">
                                    <label for="">
                                        Approve
                                        <input type="checkbox" name="status" value="1" />
                                    </label>
                                </div>


                            @endif


                        </div>



                    </div>


                </div>

                <div class="modal-footer">

                 @if ($item->can_post)

                   @if ($item->can_approve)

                     @if ($item->user_has_submitted_feedback)



                                <button type="submit">
                                    <i class="fa fa-reply"></i>
                                </button>
                     @else
                               <i>User has not started this step!</i>
                         
                     @endif

                   @else
{{--                            class="btn btn-success btn-sm"--}}
                        <button type="submit">
                          <i class="fa fa-reply"></i>
{{--                            Send Feedback--}}
                        </button>
                   @endif

                 @else

                        <i>Not authorized to modify this checklist!</i>

                 @endif

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>

            </div>

        </div>
    </div>
</form>
