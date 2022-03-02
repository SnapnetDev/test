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

@section('content')

    <style>
        .approval1{

            background-color: rgba(0,255,0,0.1);
            color: #000;

        }

        .approval2{
            background-color: rgba(255,0,0,0.1);
            color: #000;
        }

        .approval0{
            background-color: rgba(255,255,0,0.2);
            color: #000;
        }
    </style>


    <!-- Page -->
    <div class="page ">

        <div class="page-header" style="padding-bottom: 0;">
            <h1 class="page-title" style="text-transform: uppercase;">My Offline Trainings</h1>
        </div>

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

        <div class="page-content container-fluid" style="padding-top: 29px;">

            <div class="col-sm-12" style="
    background-color: #fff;
    padding: 31px;
">



                <table class="table">
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Year Of Training
                        </th>
                        <th>
                            Completed
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Date Created
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>

                    @php

                     $statDict = [
                       0=>'Paused',
                       1=>'Enrolled'
                     ];

                    $completedDict = [
                      0=>'No',
                      1=>'Yes'
                    ];

                    @endphp

                    @foreach ($list as $k=>$v)


                        @php
                          $item = $v;
                        @endphp

                        @include('training_new.modals.my_training_plan_edit')

                        <tr class="approval{{ $v->status }}">
                            <td>
                                {{ $v->training->name }}
                            </td>
                            <td>
                                {{ $v->training->year_of_training }}
                            </td>
                            <td>
                                {{ $completedDict[$v->completed] }}
                            </td>
                            <td>
                               {{ $statDict[$v->status] }}
                            </td>
                            <td>
                                {{ $v->created_at }}
                            </td>
                            <td>
                               @if ($v->status == 0)
                                    <div class="tag tag-info">
                                        Training Paused.
                                    </div>
                               @else
                                    <a data-toggle="modal" data-target="#update-my-training-plan{{ $v->id }}" style="color: #fff;" class="btn btn-sm btn-success">Complete Training</a>
                                @endif
                            </td>
                        </tr>


                    @endforeach


                </table>



            </div>
        </div>

    </div>

    <!-- End Page -->

@endsection

@section('scripts')

    <script>
        (function($){
            $(function(){

                // alert('rate');

                $('[data-rate-box]').each(function(){

                    var $this = $(this);
                    var rateValue = $this.attr('data-rate-box');

                    var $rate = $this.find('[name="rating"]');
                    var $stars = [];
                    function resetStars(){
                        $stars.forEach(function($el,k){
                            $el.removeClass('selected');
                        });
                    }
                    $this.find('[data-rate]').each(function(){

                       $stars.push($(this));

                       if ($(this).attr('data-rate') == rateValue){
                           $(this).addClass('selected');
                       }

                       var perc = $(this).attr('data-rate');
                        $(this).on('click',function(){
                           $rate.val(perc);
                           resetStars();
                           $(this).addClass('selected');
                        });
                    });

                });

            });
        })(jQuery);
    </script>

@endsection
