<div class="modal fade modal-3d-flip-vertical" id="addEditModal" aria-labelledby="exampleModalTitle" role="dialog"
     tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-simple modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Query</h4>
            </div>
            <div class="modal-body">
                <table class="table" border="0">
                    <tr>
                        <td>
                            <b> Title :</b>
                        </td>
                        <td>
                            <input type="hidden" name="query_id" class="query_id">
                            <input type="text" name="title" required class="form-control query_title" placeholder="enter query title">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>Constants :=> fullname = %name% , Date of Birth = %dob% , HireDate = %hiredate% ,Day = %day% , Month = %month% , Year = %year%
                            </b>
                            <b>Content :  </b>
                        </td>
                    <tr>
                    <tr>
                        <td colspan="2">
                            <textarea name="content" class="content_note">

                            </textarea>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveQueryTemplate($('.query_title').val(),$('.content_note').summernote('code'),$('.query_id').val())">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>

     function saveQueryTemplate(title, summernote,query_id) {
        if (title == '' || summernote.trim() == '') {
            return toastr.error('Some Fields are empty');
        }
        formData = {
            title: title,
            content: summernote,
            type: 'saveQueryType',
            id:query_id,
            _token: '{{csrf_token()}}'
        };
        return postData(formData, '{{url('query')}}',true);
    }

    $(function () {
        $('.content_note').summernote({height: '300'});
    })
</script>
