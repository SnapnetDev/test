<style>
    .list-group{

        overflow:scroll;
    }
</style>
<div class="col-lg-5">
    <div class="panel panel-info panel-line" id="messgedd">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $measurement_period->name }} Users  &nbsp;{{--<button onclick="bulkUploadKPI()" type="button" class="btn btn-icon btn-primary btn-outline btn-xs">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>--}}</h3>
        </div>
        <div class="panel-body">
            <ul class="list-group list-group-full h-500" data-plugin="scrollable">
                <div data-role="container">
                    <div data-role="content" id="table2">
                        <input type="text" id="search2" name="example-input3-group2" class="form-control" placeholder="Search">
                        <br>
                        @foreach($users as $user)
                            <a href="javascript:void(0)" onclick="loadUserKPIs({{$user->id}})" class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <h5 class="list-group-item-heading mt-0 mb-0">
                                            {{ $user->user->name }}
                                        </h5>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </ul>
        </div>
    </div>
</div>
<div class="col-lg-7" id="measurement_period_kpis_setup"></div>

<div class="modal fade in modal-3d-flip-horizontal modal-info modal-md" id="bulkUploadExcelModal" aria-hidden="true"
     aria-labelledby="enterDetails" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="training_title">Bulk Upload Excel Multiple User KPI</h4>
            </div>
            <div class="modal-body">
                <form id="bulkUploadMultipleUserExcel">
                    @csrf
                    <div class="col col-md-12">
                        <a href="">Download Multiple User KPI Template</a>
                    </div>
                    <div class="col col-md-12">
                        <div class="form-group">
                            <label class="form-control-label" for="inputBasicEmail">Upload</label>
                            <input type="file" class="form-control" name="from" id="from">
                        </div>
                    </div>

                    <div class="col col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    function loadUserKPIs(np_user) {
        url = '{{url('user/kpi/measurement_period/setup')}}?user='+np_user,
            $("#measurement_period_kpis_setup").load(url, function() {
                console.log('done');
            });
    }
    $(document).ready(function(){
        $("#search2").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#table2 a").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function bulkUploadKPI() {
        $("#bulkUploadExcelModal").modal();
    }
</script>
