@extends('ui.basic_crud')

@section('page-header')
    Approve Offline Training Plans
@endsection

@section('create-target')
    #create-training-plan
@endsection

@section('create-label')
    Add Training Plan
@endsection

@section('crud-table')

    <style>
        /*.approval1{*/

            /*background-color: rgba(0,255,0,0.1);*/
            /*color: #000;*/

        /*}*/
    </style>

@include('training_new.searchbar')

    <table class="table">
        <tr>
            <th>
                Name
            </th>
            <th>
                Year Of Training
            </th>
            <th>
                Number Of Enrollees
            </th>
            <th>
                Cost Per Head
            </th>
            <th>
                Total Cost
            </th>
            <th>
                Status
            </th>
            <th>
                Type
            </th>
            <th>
                Date Created
            </th>
            <th>
                Actions
            </th>
        </tr>

        @php

            $tags = [];
            $tags[0] = 'tag-warning';
            $tags[1] = 'tag-success';
            $tags[2] = 'tag-danger';

        @endphp

        @foreach ($list as $k=>$v)


            <tr class="approval{{ $v->status }}">
                <td>
                    {{ $v->name }}
                </td>
                <td>
                    {{ $v->year_of_training }}
                </td>
                <td>
                    {{ $v->number_of_enrollees }}
                </td>
                <td>
                    {{ $v->cost_per_head }}
                </td>
                <td>
                    {{ number_format($v->grand_total) }}
                </td>
                <td>

                    <span class="tag {{ $tags[$v->status] }}">
                     {{ $dict['status'][$v->status] }}
                    </span>

                </td>
                <td>
                    {{ $v->type }}
                </td>
                <td>
                    {{ $v->created_at }}
                </td>
                <th>

                    @php

                      $text = '';
                      if ($v->status == 0){
                       $text = 'Approve';
                      }else if ($v->status == 1){
                        $text = 'View';
                      }else if ($v->status == 2){
                        $text = 'Review And Approve';
                      }

                    @endphp

                    <a data-toggle="modal" data-target="#update-training-plan{{ $v->id }}" style="color: #fff;" class="btn btn-sm btn-info">{{ $text }}</a>
                </th>
            </tr>


        @endforeach


    </table>

@endsection

@section('edit-modals')

    @foreach ($list as $item)
        @php
         $hr = true;
        @endphp
        @include('training_new.modals.plan_edit')
    @endforeach

@endsection

@section('create-modal')
    @include('training_new.modals.plan_create')
@endsection

@section('crud-script')

    {{--@include('training_new.js_framework.binder')--}}
    @include('training_new.js_framework.binder_v2')


@endsection