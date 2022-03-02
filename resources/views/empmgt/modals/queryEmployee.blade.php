

<div class="modal fade modal-3d-flip-vertical" id="queryEmployee" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-info modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Query <span id="employee_name"></span></h4>
            </div>
            <div class="modal-body">
                <input id="queried_user_id" type="hidden">

                @foreach($query_types as $query_type)

                    <input type="hidden" value="{{$query_type->content}}" id="query_content{{$query_type->id}}">
                @endforeach
                <table>
                    <tr>
                        <td colspan="2">
                            <b> Constants  :=> fullname = %name% , Date of Birth = %dob% , HireDate = %hiredate% ,Day = %day% , Month = %month% , Year = %year%
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Query Types:
                        </td>
                        <td>
                            <select class="query_type form-control" id="query_type_select">
                                <option value="">- Select Query Type -</option>
                            @foreach($query_types as $query_type)
                                    <option value="{{$query_type->id}}">{{$query_type->title}}</option>
                                    @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <br>
                            <textarea class="query_content">

                            </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">

                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary issue_query pull-right" >Issue changes</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


