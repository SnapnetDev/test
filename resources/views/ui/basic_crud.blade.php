@extends('layouts.master')
@section('stylesheets')
    <style type="text/css">
        .margin{
            margin-bottom:5px;
        }
        .hide{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-markdown/bootstrap-markdown.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/switchery/switchery.css')}}">

    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-table/bootstrap-table.css')}}">

    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.css')}}">

@endsection


{{--@section('script-footer')--}}

    {{--@include('performance.training.scripts.course_script')--}}

{{--@endsection--}}

@section('content')



  <style>
      .info1 p{
          margin: 0;
      }

      .my-badge{

          display: inline-block;
          background-color: #ddd;
          padding: 2px;
          border-radius: 20%;
          padding-left: 6px;
          padding-right: 7px;

      }
  </style>


    <!-- Page -->
    <div class="page" id="app">

        @yield('edit-modals')
        @yield('create-modal')

        <div class="page-header" style="padding-bottom: 0;">
        <h1 class="page-title" style="text-transform: uppercase;">@yield('page-header')</h1>
        </div>


        <div class="page-content container-fluid" style="padding-top: 29px;">

            <div class="col-sm-12" style="
    background-color: #fff;
    padding: 31px;
">


                @if (!isset($disable_create))
               <div class="col-md-12" align="right">
                   <button style="margin-bottom: 11px;" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="@yield('create-target')">@yield('create-label')</button>
               </div>
                @endif

               @yield('crud-table')


            </div>
        </div>

    </div>
    <!-- End Page -->
@endsection

@section('scripts')

    @include('training_new.footer.footer_js')

    @yield('crud-script')


    @include('training_new.js_framework.vue')
    @include('training_new.vue_components.training_plan_form')
    @include('training_new.vue_components.training_plan_stat')

    @include('training_new.vue_components.vue_root')

@endsection