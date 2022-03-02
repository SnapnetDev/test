<style>
    .list-group{

        overflow:scroll;
    }
</style>
<div class="col-lg-5">
    <div class="panel panel-info panel-line" id="messgedd">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $measurement_period->name }} Users</h3>
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

<script>
    
    function loadUserKPIs(np_user) {
        url = '{{url('user/kpi/measurement_period/supervisor')}}?user='+np_user,
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
</script>
