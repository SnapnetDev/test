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
            <h1 class="page-title" style="text-transform: uppercase;">Organisational and Functional Kpi ({{ $user->name }})</h1>
        </div>


        <div class="page-content container-fluid" style="padding: 0;">

            <div class="col-sm-12" style="
    background-color: #fff;
    /*padding: 31px;*/
">

                @include('kpi.individual_kpi.create')

                @foreach ($kpis as $k=>$v)
                     @include('kpi.data.edit')
                @endforeach

                <div class="col-md-12" style="text-align: right;">
                    <a href="{{ route('app.get',['download-individual-kpi-data']) }}?user_id={{ $user_id }}&type={{ $type }}&workdept_id={{ $workdept_id }}" style="margin-bottom: 11px;" class="btn btn-info btn-sm">Export To Excel</a>
                    <button style="margin-bottom: 11px;" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#create-individual-kpi-interval">Add Individual KPI</button>
                </div>



                <div class="col-md-12">

                    <label for="">Select Type
                        <select name="type" data-value="{{ $type }}" id="kpi_type" style="margin-bottom: 11px;padding: 6px;">
                            <option value="org">Organisational</option>
                            <option value="dep">Functional</option>
                        </select>
                    </label>

                </div>

                @php
                 $sum = 0;
                @endphp
                <table class="table">

                    <tr>
                        <th>
                            Requirement
                        </th>
                        <th>
                            Percentage
                        </th>
                        <th>
                            Date Created
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>

                    @foreach ($kpis as $k=>$v)
                        @php
                         $sum+=$v->percentage;
                        @endphp

                        <tr>
                            <td>
                                {{ $v->requirement }}
                            </td>
                            <td>
                                {{ $v->percentage }} %
                            </td>
                            <td>
                                {{ $v->created_at }}
                            </td>
                            <td>
                                <a  data-toggle="modal" data-target="#edit-kpi-data{{ $v->id }}" href="#" class="btn btn-sm btn-info" >Edit</a>
                                <form onsubmit="return confirm('Do You want to confirm this action?')" action="{{ route('app.exec',['remove-individual-kpi']) }}" style="display: inline-block" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $v->id }}" />
                                    <button class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>

                    @endforeach


                    <tr>
                        <td>

                        </td>
                        <td>
                            <b>Total: {{ $sum }} %</b>
                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                    </tr>


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
    <script>
        (function($){
            $(function(){
                $('[data-value]').each(function(){
                    $(this).val($(this).attr('data-value'));
                    $(this).on('change',function(){
                        if ($(this).is('[name=type]')){
                            location.href = '{{ route('app.get',['fetch-individual-kpi']) }}?type=' + $(this).val() + '&scope={{ request()->get('scope') }}&user_id={{ $user_id }}&workdept_id={{ $workdept_id }}';
                        }else if ($(this).is('[name=dep_id]')){
                            //location.href = '{{ route('app.get',['fetch-individual-kpi']) }}?type={{ request()->get('type') }}&scope={{ request()->get('scope') }}&kpi_interval_id={{ request()->get('kpi_interval_id') }}&dep_id=' + $(this).val();
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endsection






