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
        <h1 class="page-title" style="text-transform: uppercase;font-size: 14px;">Send General Notification</h1>
    </div>


    <div class="page-content container-fluid" style="padding: 0;">

        <div class="col-sm-12" style="
    background-color: #fff;
    /*padding: 31px;*/
 ">


            <div class="col-md-12" style="margin-top: 17px;">


                <form action="{{ route('app.exec',['send-general-notification']) }}" method="post">

                    {{ csrf_field() }}

                    <div class="row">

                        <div class="col-md-12">

                            <label for="">
                                Title
                            </label>

                        </div>

                        <div class="col-md-12">
                            <input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title') }}"/>
                        </div>


                        <div class="col-md-12" style="margin-top: 12px;">

                            <label for="">
                                Message
                            </label>

                        </div>

                        <div class="col-md-12">
                            <textarea placeholder="Message" name="message" class="form-control" id="" cols="30" rows="10">{{ old('message') }}</textarea>
                        </div>


                        <div class="col-md-12" align="right" style="margin-top: 12px;">
                            <input type="submit" class="btn btn-sm btn-success" value="Send" />
                        </div>

                    </div>

                </form>




            </div>










        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'message' );
    </script>

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




            });
        })(jQuery);
    </script>
@endsection
