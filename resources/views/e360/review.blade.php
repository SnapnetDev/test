@extends('layouts.master')
@section('stylesheets')
{{-- <link href="https://surveyjs.azureedge.net/1.0.66/survey.css" type="text/css" rel="stylesheet"/> --}}
<link href="https://surveyjs.azureedge.net/1.1.15/survey.css" type="text/css" rel="stylesheet" />

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title"> 360  Degree Employee Review</h1>
      <div class="page-header-actions">
    <div class="row no-space w-250 hidden-sm-down">

      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

        </div>
      </div>
    </div>
  </div>
    </div>
    <div class="page-content container-fluid">
        <div class="row">

          <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
                 @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('error') }}
                </div>
            @endif
            <div class="panel panel-info ">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Employee review - {{$evaluation->user->name}}</h3>
              </div>


              <div class="panel-body">

              <div id="surveyElement"></div>
              <div id="surveyResult"></div>
          </div>
          {{-- <div class="panel-footer">

            <button type="submit" class="btn btn-info">View template</button>
          </div> --}}

        </div>


          </div>
          </div>

  </div>


</div>
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

  {{-- <script src="https://surveyjs.azureedge.net/1.0.66/survey.jquery.js"></script> --}}
  <script src="https://surveyjs.azureedge.net/1.1.15/survey.jquery.min.js"></script>

    <script type="text/javascript">
 Survey
    .StylesManager
    .applyTheme("default");
    @php
      $per_page=5;
      $count=1;
      $questions_count=count($mp->questions);
    @endphp
    var json = { title: "Questions", pages: [

        { name:"page1", questions: [
            @foreach ($mp->questions as $question)
            { type: "radiogroup",
            choices: [
                @foreach ($question->options as $option)
                    {
                        value: "{{$option->id}}",
                        text: "{{$option->content}}"
                    },
                @endforeach
                    ],
                isRequired: true,
                name: "question_{{$question->id}}",
                title: "{{$evaluation->user_id==Auth::user()->id?$question->self_question:$question->question}}"

                },

            @endforeach

        ]}
   ]
   };


window.survey = new Survey.Model(json);

survey
    .onComplete
    .add(function (result) {
        // document
        //     .querySelector('#surveyResult')
        //     .innerHTML = "result: " + JSON.stringify(result.data);
            $.ajax({
                type: 'POST',
                url: '{{url('e360')}}',
                data: {result: result.data,type:'save_evaluation',_token:"{{csrf_token()}}",mp_id:{{$mp->id}},evaluation_id:{{$evaluation->id}} }
            })
            .done( function( data ) {
                console.log('done');
                console.log(data);
            })
            .fail( function( data ) {
                console.log('fail');
                console.log(data);
            });
                });

    survey.data = {
      @foreach ($evaluation->details as $detail)
        question_{{$detail->question->id}}:"{{$detail->option->id}}",
      @endforeach
};

$("#surveyElement").Survey({model: survey});
  </script>
@endsection
