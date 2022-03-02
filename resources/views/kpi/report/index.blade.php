@extends('layouts.app')

@section('content')

    @include('kpi.style')

    <style>
        .page-content{
            background-color: #fff;
        }


        .router-link-exact-active , .router-link-exact-active:hover{
            /** background-color: #000; **/
        }

    </style>




    <!-- Page -->


    <div class="page-header" style="padding-bottom: 0;">
        <h1 class="page-title" style="text-transform: uppercase;">KPI User Report ({{ $user->name }})</h1>
    </div>


    <div class="page-content container-fluid" style="padding: 0;">

        <div class="col-sm-12" style="
    background-color: #fff;
    /*padding: 31px;*/
 ">



            <div style="
    margin-top: 18px;
">
                <label for="">
                    Select Year&nbsp;<select name="" id="years">
                        <option value="">--select year---</option>

                    </select>
                </label>



                <label for="">
                    Select Interval&nbsp;<select name="" id="intervals">
                        <option value="">--All Intervals---</option>

                    </select>
                </label>


            </div>


            <div class="col-md-12" style="margin-top: 17px;">


                <table class="table">
                    <tr>
                        <th>
                            Personal Score
                        </th>
                        <th>
                            Line Manager Score
                        </th>
                        <th>
                            HR Score
                        </th>
                    </tr>
                    <tr>
                        <td id="personal">
                            0.0
                        </td>
                        <td id="manager">
                            0.0
                        </td>
                        <td id="hr">
                            0.0
                        </td>
                    </tr>
                </table>


            </div>


            <div>
                Average Score : <span id="avg">0.0</span>
            </div>










        </div>
    </div>



    <!-- End Page -->





    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <script>
        jQuery.fn.magnificPopup = function(){};
    </script>
@endsection

@section('scripts')
    @include('kpi.toast_response')
    <script>
        (function($){
            $(function(){




                $('[data-value]').each(function(){
                    $(this).val($(this).attr('data-value'));
                    $(this).on('change',function(){
                        if ($(this).is('[name=type]')){
                            //location.href = '{{ route('app.get',['fetch-kpi-users']) }}?type=' + $(this).val() + '&scope={{ request()->get('scope') }}&kpi_interval_id={{ request()->get('kpi_interval_id') }}&dep_id={{ request()->get('dep_id') }}';
                        }else if ($(this).is('[name=dep_id]')){
                            location.href = '{{ route('app.get',['fetch-kpi-users']) }}?workdept_id=' + $(this).val();
                        }
                    });
                });



                function getYears() {
                    var $el = $('#years');
                    $.ajax({
                        url:'{{ route('get.kpi.year')  }}',
                        type:'GET',
                        success:function (response) {
                            $el.html('');
                            $el.append('<option value="">--Select Year--</option>');
                            response.list.forEach(function (item) {
                                $el.append('<option value="' + item.id + '">' + item.year + '</option>');
                            });
                        }
                    });

                    $el.on('change',function () {
                        getIntervals($(this).val());
                        getComputedScoreReport($(this).val());
                    });
                }

                function getIntervals(id) {
                   var $el = $('#intervals');
                   console.log(id);

                   $.ajax({
                       url:'{{ route('get.kpi.intervals',['']) }}/' + id,
                       type:'GET',
                       success:function(response){

                           $el.html('');
                           $el.append('<option value="">--All Intervals--</option>');
                           response.list.forEach(function (item) {
                               $el.append('<option value="' + item.id + '">' + item.name + '</option>');
                           });


                       }
                   });
                }

                $('#intervals').on('change',function () {

                    if ($(this).val())
                        getComputedScoreReport($('#years').val(),$(this).val());
                    else {
                        getComputedScoreReport($('#years').val());
                    }

                });

                function getComputedScoreReport(kpiYearId,kpiIntervalId){
                    $.ajax({
                        url:'{{ route('app.get',['kpi-get-user-score-report']) }}',
                        type:'GET',
                        data:{
                          user_id:'{{ $user->id }}',
                          kpi_year_id:kpiYearId,
                          kpi_interval_id:kpiIntervalId
                        },
                        success:function(response){
                            console.log(response);
                            $('#personal').html(response.personal + '%');
                            $('#manager').html(response.manager + '%');
                            $('#hr').html(response.hr + '%');
                            $('#avg').html(response.avg + '%');
                        }
                    });
                }


                getYears();



            });
        })(jQuery);
    </script>
@endsection
