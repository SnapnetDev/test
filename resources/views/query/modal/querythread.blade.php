<div class="modal fade" id="queryThread" aria-labelledby="examplePositionSidebar" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-simple modal-sidebar modal-lg" style="margin-top: -1%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Query Thread</h4>
            </div>
            <div class="modal-body">
                <div class="h-300" data-skin="scrollable-shadow" data-plugin="scrollable">
                    <div data-role="container">
                        <div data-role="content">
                            <div class="media">
                                <div class="pr-20">

                                </div>
                                <div class="media-body">
                                    <h4 class="mt-0 mb-5" id="query_title_display"></h4>
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <input type="hidden" id="query_thread_id">
                                                <input type="hidden" id="query_thread_id">
                                                <a class="avatar avatar-lg" href="javascript:void(0)">
                                                    <img id="query_image" alt="...">
                                                </a>
                                            </td>
                                           <td id="query_parent">

                                           </td>
                                        </tr>
                                        <tbody class="query_thread">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table query_response_table">

                    <tr>
                        <td colspan="2">
                            <textarea class="query_response">

                            </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>

                            <button style="width: 200px" type="button" class="btn btn-primary btn-block " onclick="replyQuery($('#query_thread_id').val())">Save changes</button>
                        </td>
                        <td>
                            <button style="width: 100px" type="button" class="btn btn-default btn-block pull-right" data-dismiss="modal">Close</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

