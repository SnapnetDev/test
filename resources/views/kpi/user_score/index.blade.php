@extends('layouts.app')

@section('content')

    @php


      $user_required = '';
      $manager_required = '';
      $hr_required = '';



      $role = 'user';
      $sendText = 'Send Notification';


      if (Auth::user()->isDefaultStaff()){
        $role = 'user';
        $sendText = 'Notify Line Manager';
        $user_required = ' required ';
      }

      if (Auth::user()->isLineManager()){
        $role = 'manager';
        $sendText = 'Notify Hr';
        $manager_required = ' required ';
      }

      if (Auth::user()->isAdmin()){
        $role = 'hr';
        //$sendText = '';
        $hr_required = ' required ';
      }


      if (isset($override_role)){
         $role = 'user';
         $sendText = 'Notify Line Manager';
      }

    @endphp



    @include('kpi.style')

    <style>
        .page-content{
            background-color: #fff;
        }


        .router-link-exact-active , .router-link-exact-active:hover{
            /** background-color: #000; **/
        }

        h1{
            font-size: 17px !important;
        }


    </style>




    <!-- Page -->


        <div class="page-header" style="padding-bottom: 0px;padding-left: 24px;font-size: 11px;text-align: center;">
            <h1 class="page-title" style="font-size: 17px;text-transform: uppercase;">organisational and Functional Kpi Evaluation ( {{ $user->name }} ) / {{ $user->getDepartmentName() }}</h1>
            <h1 style="font-size: 17px;">{{ $currentInterval }}</h1>
        </div>


        <div class="page-content container-fluid" style="padding: 0;">

            <div class="col-sm-12" style="
    background-color: #fff;
    /*padding: 31px;*/
 ">


                @if (!$expired)
                <div class="col-md-12" style="text-align: right;">
                    <a href="{{ route('app.get',['send-kpi-notification']) }}?role={{ $role }}&user_id={{ $user->id }}" class="btn btn-sm btn-success">{{ $sendText }}</a>
                </div>
                @endif

                @if ($expired)
                    <div class="col-md-12">
                        <div class="alert alert-danger" style="text-align: center;font-size: 17px;font-weight: bold;margin-top: 6px;">
                            KPI is locked for this period!
                        </div>
                    </div>
                @endif


                @if (in_array($role,['manager','hr']))
                        {{--'user'--}}
                  <div class="col-md-12">
                    <div class="row">

                        <div class="col-md-9">&nbsp;</div>

                        <div class="col-md-3">
                            <div style="margin-top: 17px;font-weight: bold;">
                                <label for="">Get Report By Quarter</label>
                            </div>
                            <select name="" id="qreport" class="form-control">
                                <option value="">--Select Quarter</option>
                                @foreach ($intervals as $interval)
                                    <option value="{{ $interval->id }}">{{ $interval->name }}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>
                </div>

                @endif

                <div class="col-md-12">
                    <h4 style="
    border-bottom: 2px solid #62a8ea;
    padding-bottom: 7px;
">Functional KPIs</h4>

                    @php
                        $items = $department_list;
                    @endphp

                    @include('kpi.user_score.list_include')
                </div>

                <div class="col-md-12">
                    <h4>(Personal)</h4>

                    @php
                        $items = $individual_department_list;
                    @endphp

                    @include('kpi.user_score.list_include')
                </div>





                <div class="col-md-12">
                    <h4 style="
    border-bottom: 2px solid #62a8ea;
    padding-bottom: 7px;
">Organisational KPIs</h4>

                    @php
                        $items = $organisation_list;
                    @endphp

                    @include('kpi.user_score.list_include')
                </div>

                <div class="col-md-12">
                    <h4>(Personal)</h4>

                    @php
                        $items = $individual_organisation_list;
                    @endphp

                    @include('kpi.user_score.list_include')
                </div>


                @if ($hasAgreed)
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <p>You have accepted this evaluation</p>
                            </div>
                        </div>
                @endif

                @if ($hasInterval && !$hasAgreed)
                <div class="col-md-12" id="agreement">
                    <div class="alert alert-info" style="text-align: center;font-weight: bold;color: #000;">
                        <p>Do you accept the above evaluation criteria?</p>
                        <div>
                            <a href="{{ route('app.get',['accept-kpi-agreement']) }}?user_id={{ $user_id }}&kpi_interval_id={{ $kpi_interval_id }}" class="btn btn-sm btn-success">
                                Accept
                            </a>
                            <a href="{{ route('app.get',['deny-kpi-agreement']) }}?user_id={{ $user_id }}&kpi_interval_id={{ $kpi_interval_id }}" class="btn btn-sm btn-danger">
                                Deny
                            </a>
                        </div>
                    </div>
                </div>
                @endif


                @if (in_array($role,['hr']) || request()->exists('or'))
                        {{--manager--}}
                <div class="col-md-12" style="text-align: right">
                    <a href="{{ route('app.get',['user-report']) }}?id={{ $user_id }}" class="btn btn-sm btn-warning">Send Evaluation Report</a>
                </div>

                @endif

            </div>
        </div>



    <!-- End Page -->


    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    {{--<script>--}}
        {{--jQuery.fn.magnificPopup = function(){};--}}
    {{--</script>--}}
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
 @include('kpi.toast_response')
 <script>
     (function($){
         $(function(){

             jconfirm.pluginDefaults.useBootstrap = false;


            var listeners = {};
            var oneTime = false;

            function attach(subject,cb) {
                listeners[subject] = listeners[subject] || [];
                listeners[subject].push(cb);
            }

            function notify(subject,data) {
                listeners[subject] = listeners[subject] || [];
                listeners[subject].forEach(function(v,k){
                    v(data);
                });
                // console.log(listeners);
            }

            function getAttributeSelection(attr,cb){
                $('[' + attr + ']').each(function(){
                    cb($(this),$(this).attr(attr));
                });
            }

            function checkPercentLimit($el){
               var limit = $el.attr('data-limit')  * 1;
               $el.on('keyup',function(){
                   var t = $(this).val() * 1;
                   // console.log(t,limit);
                   if (t > limit){
                       $(this).val(limit);
                       $el.css('border','2px solid red');
                       setTimeout(function(){
                           $el.css('border','1px solid #eee');
                       },2000);
                   }
               });
            }


            function xConfirmAgreement(cbConfirm,cbCancel){

            }

            function xConfirm(cbConfirm,cbCancel){
                $.confirm({
                    title: 'Confirmation',
                    content: 'Do you want to proceed with this action?',
                    buttons: {
                        confirm:{
                            text:'Confirm',
                            btnClass:'btn btn-sm btn-success',
                            action:function () {
                                // $.alert('Confirmed!');
                                cbConfirm();
                            }
                        },
                        cancel: function () {
                            $.alert('Canceled!');
                            // cbCancel();
                        }
                    }
                });
            }

            getAttributeSelection('data-limit',function($el,tag){

                checkPercentLimit($el);

            })


            var role = '{{ $role }}';
            getAttributeSelection('user-role',function($el,tag){
               if (tag != role){
                   $el.attr('disabled','disabled');
                   $el.attr('readonly','readonly');
               }
            });

            getAttributeSelection('data-value-set',function($el,tag){

                var kpi_data_id = $el.attr('data-kpi-data-id');

                attach(tag,function(data){

                    for (var i in data){
                        if (data.hasOwnProperty(i)){
                            if (data[i] != '' && data[i] != null){
                                // $el.html(data);
                                // if ($el.find('[' + i + ']').is('input') && $el.find('[' + i + ']').is('[data-limit]')){
                                //     console.log('lim');
                                //     checkPercentLimit($el.find('[' + i + ']'));
                                // }
                                if ($el.find('[' + i + ']').is('input') || $el.find('[' + i + ']').is('textarea')){
                                    $el.find('[' + i + ']').val(data[i]);
                                }else{
                                   if ($el.is('tr'))
                                    $el.find('[' + i + ']').html(data[i]);
                                }
                            }
                        }
                    }

                });


                $.ajax({
                    url:'{{ route('app.get',['kpi-get-user-score']) }}',
                    type:'GET',
                    data:{
                     user_id:'{{ $user_id }}',
                     kpi_data_id:kpi_data_id
                    },
                    success:function(response){

                       notify('tag' + kpi_data_id,response.data);
                      // console.log(response);
                    }
                });

            });





            getAttributeSelection('data-kpi-user-score-form',function($el,tag){

                var action = $el.attr('action');
                var $activity = $el.find('#user-score-indicator');
                var $data_modal_ref = $('#' +  $el.attr('data-modal-ref'));

                $el.on('submit',function(){

                    @if (!$expired)

                    xConfirm(function(){

                        var formData = $el.serialize();
                        if (!oneTime){
                            oneTime = true;
                            formData+='&send_notification=1&role={{ $role }}';
                        }
                        console.log(formData);
                        $activity.html('Submitting Evaluation ...');
                        $.ajax({
                            url:action,
                            type:'POST',
                            data:formData,
                            success:function(response){

                                if (response.message){
                                    toastr.success(response.message);
                                }

                                // console.log(response);

                                $activity.html('Update Evaluation');

                                notify('tag' + response.data.kpi_data_id,response.data);

                                $data_modal_ref.modal('hide');

                            },
                            error:function(){
                                toastr.success('Check your network settings!');
                                $activity.html('Update Evaluation');
                                $data_modal_ref.modal('hide');
                            }
                        });


                    },function(){
                        $.alert('Cancelled');
                    });

                  @else

                       toastr.error('KPI is locked for this period!');

                  @endif


                    return false;

                });

            });


            //$("html, body").animate({ scrollTop: $('#agreement').offset().top }, 1000);
             $('[data-scroll-to]').each(function(){
                 var $to = $($(this).attr('data-scroll-to'));
                 $(this).on('click',function(){
                     $("html, body").animate({ scrollTop: $to.offset().top }, 1000);
                 });
             });



             $('#qreport').on('change',function(){
                 if ($(this).val() == ''){
                     alert('Invalid selection!');
                     return;
                 }
                 var userId = '{{ $user_id }}';
                 window.open('{{ route('app.get',['inline-kpi-report']) }}?ls=EvaluationReport&userId=' + userId + '&intervalId=' + $(this).val(),"","width=1000,height=350");
             });



         });
     })(jQuery);
 </script>
@endsection