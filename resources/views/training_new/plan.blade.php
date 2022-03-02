@extends('ui.basic_crud')

@section('page-header')
    Manage Offline Training Plans
@endsection

@section('create-target')
 #create-training-plan
@endsection

@section('create-label')
 Add Training Plan
@endsection

@section('crud-table')

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
                Status
            </th>
            <th>
                Created By
            </th>
            <th>
                Department
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

                    <span class="tag {{ $tags[$v->status] }}">
                     {{ $dict['status'][$v->status] }}
                    </span>

                </td>
                <td>
                    {{ $v->line_manager->name }}
                </td>
                <td>
                    {{ $v->line_manager->getDepartmentName_() }}
                </td>
                <td>
                    {{ $v->type }}
                </td>
                <td>
                    {{ $v->created_at }}
                </td>
                <td>
                    <a data-toggle="modal" data-target="#update-training-plan{{ $v->id }}" style="color: #fff;" class="btn btn-sm btn-info">{{ ($v->status == 1)? 'View' : 'Edit' }}</a>

                    @if ($v->status == 0)
                    <form onsubmit="return confirm('Do you want to remove this training?')" method="post" action="{{ route('process.action.command',['delete-training-plan']) }}" style="display: inline-block;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $v->id }}" />
                        <button type="submit" style="color: #fff;" class="btn btn-sm btn-danger">Remove</button>
                    </form>
                    @endif
                </td>
            </tr>


        @endforeach


    </table>

@endsection

@section('edit-modals')

    @foreach ($list as $item)
       @include('training_new.modals.plan_edit')
    @endforeach

@endsection

@section('create-modal')
 @include('training_new.modals.plan_create')
@endsection

@section('crud-script')

    {{--@include('training_new.js_framework.binder')--}}
    {{--@include('training_new.js_framework.binder_v2')--}}
    @include('training_new.js_framework.binder_v2')


@endsection