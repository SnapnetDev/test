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
            <h1 class="page-title" style="text-transform: uppercase;">Evaluation Intervals ({{ $year->year }}) / ({{ $current_interval }})</h1>
        </div>


        <div class="page-content container-fluid" style="padding: 0;">

            <div class="col-sm-12" style="
    background-color: #fff;
    /*padding: 31px;*/
">

                @include('kpi.interval.create')

                @foreach ($list as $k=>$v)
                     @include('kpi.interval.edit')
                @endforeach

                <div class="col-md-12" style="text-align: right;">
                    <a href="{{ route('app.get',['fetch-kpi-years']) }}" style="margin-bottom: 11px;" class="btn btn-info btn-sm">Back</a>
                    <button style="margin-bottom: 11px;" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#create-kpi-interval">Add KPI Interval</button>
                </div>


                <table class="table">

                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Interval Start
                        </th>
                        <th>
                            Interval Stop
                        </th>
                        <th>
                            Date Created
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>

                    @foreach ($list as $k=>$v)

                        <tr>
                            <td>
                                {{ $v->name }}
                            </td>
                            <td>
                                {{ $v->interval_start }}
                            </td>
                            <td>
                                {{ $v->interval_stop }}
                            </td>
                            <td>
                                {{ $v->created_at }}
                            </td>
                            <td>
                                <a  data-toggle="modal" data-target="#edit-kpi-interval{{ $v->id }}" href="#" class="btn btn-sm btn-info" >Edit</a>
                                <a href="{{ route('app.get',['fetch-kpi-data']) }}?type=dep&scope=public&kpi_interval_id={{ $v->id }}" class="btn btn-sm btn-warning">Kpi Data</a>
                                <a href="{{ route('app.get',['make-current']) }}?kpi_interval_id={{ $v->id }}" class="btn btn-info">Make Current</a>

                                <form onsubmit="return confirm('Do You want to confirm this action?')" action="{{ route('app.exec',['remove-kpi-interval']) }}" style="display: inline-block" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $v->id }}" />
                                    <button class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>

                    @endforeach


                </table>



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
@endsection



