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
            <h1 class="page-title" style="text-transform: uppercase;">KPI User List</h1>
        </div>


        <div class="page-content container-fluid" style="padding: 0;">

            <div class="col-sm-12" style="
    background-color: #fff;
    /*padding: 31px;*/
 ">



                @foreach ($users as $k=>$v)
                     @include('kpi.userlist.edit')
                @endforeach

                <div class="col-md-12" style="text-align: right;">
                    {{--<a href="{{ route('app.get',['fetch-kpi-intervals']) }}?kpi_year_id={{ $interval->kpi_year_id }}" style="margin-bottom: 11px;" class="btn btn-info btn-sm">Back</a>--}}
                    {{--<button style="margin-bottom: 11px;" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#create-kpi-data">Add KPI Data</button>--}}
                </div>


                <div class="col-md-12" style="margin-top: 17px;">

                    {{--<label for="">Select Type--}}
                        {{--<select name="type" data-value="{{ request()->get('type') }}" id="kpi_type" style="margin-bottom: 11px;padding: 6px;">--}}
                            {{--<option value="org">Organisational</option>--}}
                            {{--<option value="dep">Departmental</option>--}}
                        {{--</select>--}}
                    {{--</label>--}}


                    <label for="">
                        Select Roles
                        <select name="dep_id" data-value="{{ $workdept_id }}" id="dep_id" style="margin-bottom: 11px;padding: 6px;">
                            <option value="">--Select--</option>
                            @foreach ($jobs as $k=>$v)
                             <option value="{{ $v->id }}">{{ $v->title }}</option>
                            @endforeach
                        </select>
                    </label>



                </div>





                <table class="table table-stripped">

                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            E-mail
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            Date Created
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>

                    @foreach ($users as $k=>$v)

                        <tr>
                            <td>
                                {{ $v->name }}
                            </td>
                            <td>
                                {{ $v->email }}
                            </td>
                            <td>
                                {{ $v->getDepartmentName() }}
                            </td>
                            <td>
                                {{ $v->created_at }}
                            </td>
                            <td>
                                <a target="_blank" href="{{ route('app.get',['fetch-user-kpi-evaluation']) }}?user_id={{ $v->id }}" class="btn btn-sm btn-info" >Evaluate User</a>
                                @if (Auth::user()->isHr())
                                  <a target="_blank" href="{{ route('app.get',['fetch-individual-kpi']) }}?user_id={{ $v->id }}&type=dep&workdept_id={{ $v->job_id }}" class="btn btn-sm btn-warning" >Individual KPIs</a>
                                    <a target="_blank" href="{{ route('app.get',['kpi-user-report'])  }}?user_id={{ $v->id  }}" class="btn btn-sm btn-success" >Report</a>
                                @endif
                            </td>
                        </tr>

                    @endforeach


                </table>

                @if (!is_array($users))
                <div>
                    {{ $users->appends($_GET)->links() }}
                </div>
                @endif



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
         });
     })(jQuery);
 </script>
@endsection



