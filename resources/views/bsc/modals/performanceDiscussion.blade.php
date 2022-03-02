<div class="modal fade" id="performanceDiscussion" aria-labelledby="examplePositionSidebar" role="dialog" tabindex="-1"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-simple modal-sidebar modal-lg">
        <div class="modal-content" style="margin-top: -1%">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Performance Discussion</h4>
            </div>
            <div class="modal-body">
                <div id="performanceDiscussionAjax">
                </div>
                @if(!request()->is('bsc/get_hr_evaluation') && !request()->is('bsc/get_my_evaluation') )
                    <div id="addDiscussion">

                        <hr>
                        <div class="col-md-12">
                            <h4><strong>Enter Performance Discussion Below : </strong><br><br></h4>
                            Title: <br>
                            <input placeholder="Enter Discussion Title" type="text" class="form form-control"
                                   id="Disctitle"><br>
                            Discussion: <br>
                            <textarea id="perfromanceDiscussionText" class="summernote"></textarea><br>

                            <button type="button" onclick="savePerformance({{$evaluation->id}})" style="width:150px"
                                    class="btn btn-primary btn-block">Save
                            </button>

                        </div>
                        <hr>
                        <div class="col-md-12">
                            <form action="" id="pdd_form">
                                @csrf
                                <h4><strong>Edit Performance Discussion Detail : </strong><br><br></h4>
                                KPI: <br>
                                <input readonly type="text" class=" form-control"
                                       id="dd_kpi"><br>
                                Action/Updates: <br>
                                <input  type="text" name="action_update" class=" form-control"
                                       id="dd_action"><br>
                                Challenge: <br>
                                <input  type="text" name="challenges" class="form form-control"
                                       id="dd_challenge"><br>

                                <input type="hidden" id="dd_id" name="discussion_detail_id">
                                <input type="hidden" id="evaluation_id" name="evaluation_id" value="{{$evaluation->id}}">

                                <input type="hidden" id="type" name="type" value="saveDiscussionDetail">
                                Comment: <br>
                                <textarea id="dd_comment" class="form-control" name="comment"></textarea><br>

                                <button type="submit"  style="width:150px"
                                        class="btn btn-primary btn-block">Save
                                </button>
                            </form>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button> -->
            </div>
        </div>
        @endif
    </div>
</div>

<script>

    function loadPerformanceDiscussion(evaluation_id) {

        $.get('{{url('bsc')}}/performanceDiscussion', {evaluation_id: evaluation_id}, function (data) {
            $('#performanceDiscussionAjax').html(data);
            $('#performanceDiscussion').modal('toggle');
        })

    }

    function savePerformance(evaluation_id) {
        title = $('#Disctitle').val();
        discussion = $('.summernote').summernote('code');
        if (title == '' || discussion == '') {
            return toastr.error('Some Fields Empty');

        }
        formData = {
            evaluation_id: evaluation_id,
            type: 'saveDiscussion',
            title: title,
            discussion: discussion,
            _token: '{{csrf_token()}}'
        }
        $('.btn').attr('disabled', true);

        var r = confirm("Are you Sure you want to save Performance Discussion ? !");
        if (r == true) {
            $.post('{{url('bsc')}}', formData, function (data) {
                $('.btn').attr('disabled', false);
                $('#performanceDiscussionAjax').html(data);
                return toastr.success('Discussion Successfully Saved');
            });
        } else {
            $('.btn').attr('disabled', false);
            return toastr.info('Operation Cancelled');
        }


    }

    function EditPDD(discussion_detail, evaluation_detail) {
        $('#dd_id').val(discussion_detail.id);
        $('#dd_action').val(discussion_detail.action_update);
        $('#dd_challenge').val(discussion_detail.challenges);
        $('#dd_comment').val(discussion_detail.comment);
        $('#dd_kpi').val(evaluation_detail.key_deliverable);
        document.getElementById("dd_kpi").focus();
    }
    pdd_form.onsubmit = async (e) => {
        e.preventDefault();
        console.log

        let response = await fetch('{{url('bsc')}}', {
            method: 'POST',
            body: new FormData(pdd_form)
        });

        let data = await response;
        // $('#performanceDiscussionAjax').html(data);
        // toastr["success"](result.message);
        return toastr.success('Discussion Successfully Saved');
        // console.log(result.success);
    };


</script>
