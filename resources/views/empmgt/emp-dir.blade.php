@extends('layouts.master')
@section('stylesheets')
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
      <link rel="stylesheet" href="{{ asset('assets/examples/css/pages/user.css') }}">
  

@endsection
@section('content')
 <div class="page">
  <div class="page-header">
      <h1 class="page-title">Employees Directory</h1>
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
    <div class="page-content">
      <!-- Panel -->
      <div class="panel">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-8">
              <form class="page-search-form" role="search" action="{{ route('users.directory') }}">
                <div class="input-search input-search-dark">
                  <i class="input-search-icon md-search" aria-hidden="true"></i>
                  <input type="text" class="form-control" id="inputSearch" name="q" placeholder="Search Employees" value="{{ request()->q }}">
                  <button type="button" class="input-search-close icon md-close" aria-label="Close" onclick="resetForm();"></button>
                </div>
              </form>
            </div>
            <div class="col-lg-4">


              <form class="page-search-form" method="POST" action="{{route('users.face.post')}}" enctype="multipart/form-data" class="form-inline">
                @csrf
                <div class="input-group">
                  <img class="img-circle img-bordered img-bordered-blue text-center" width="50" height="50"
                       src="{{ isset($url) ? asset($url) : asset('global/portraits/female-user.png')}}" alt="..." id='file-placeholder'>
                  <input id="file" type="file"  name="facefile" style=" display: none;" required/>
                  <span class="input-group-btn">
                      <button type="submit" class="btn btn-primary">Search Face</button>
                    </span>
                </div>
              </form>
            </div>
          </div>
          <hr>

          <div class="nav-tabs-horizontal nav-tabs-animate" data-plugin="tabs">
            
          
           
                <ul class="list-group">
                  <div class="row">
                    @if(isset($response_users))
                    @foreach($response_users as $userr)
                      @php
                        $user=\App\User::find($userr['user']['id'])
                      @endphp
                      @if($user)
                        <div class="col-md-4">
                          <li class="list-group-item">
                            <div class="media">
                              <div class="media-left">
                                <div class="avatar avatar-away">
                                  <img src="{{ file_exists(public_path('uploads/avatar'.$user->image))?asset('uploads/avatar'.$user->image):($user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="...">
                                </div>
                              </div>
                              <div class="media-body">
                                <h4 class="media-heading">
                                  {{$user->newname}}
                                </h4>
                                <p>
                                  Match Confidence: {{ $userr['confidence'] }}
                                </p>
                                
                                <p>
                                  <i class="icon icon-color md-email" aria-hidden="true" title="email address"></i> {{$user->email}}
                                </p>
                                <p>
                                  <i class="icon icon-color fa fa-building" aria-hidden="true"
                                     title="company"></i> {{$user->company->name}}
                                </p>
                                <p>
                                  <i class="icon icon-color fa fa-table" aria-hidden="true"
                                     title="department"></i> {{$user->job?$user->job->department->name:''}}
                                </p>
                               
                                <div>
                                  <a class="text-action" href="sip:{{$user->email}}"
                                     title="Message on Skype for business">
                                    <i class="icon icon-color md-skype" aria-hidden="true"></i>
                                  </a>
                                  <a class="text-action"
                                     href="mailto:{{$user->email}}?Subject=From HCMatrix"
                                     title="Send an Email">
                                    <i class="icon icon-color md-email" aria-hidden="true"></i>
                                  </a>

                                </div>
                              </div>

                            </div>
                          </li>
                          <hr>
                        </div>
                      @endif
                    @endforeach
                    @endif

                  @foreach($users as $user)
                  @if($user->id!=1)
                  <div class="col-md-4">
                  <li class="list-group-item">
                    <div class="media">
                      <div class="media-left">
                        <div class="avatar avatar-away">
                          <img src="{{ file_exists(public_path('uploads/avatar'.$user->image))?asset('uploads/avatar'.$user->image):($user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="...">

                        </div>
                      </div>
                      <div class="media-body">
                        <h4 class="media-heading">
                          {{$user->newname}}

                        </h4>
                        {{--<p>
                          <i class="icon icon-color md-phone" aria-hidden="true" title="phone"></i>                        {{$user->phone}}
                        </p>--}}
                        <p>
                          <i class="icon icon-color md-email" aria-hidden="true" title="email address"></i>                          {{$user->email}}
                        </p>
                        <p>
                          <i class="icon icon-color fa fa-building" aria-hidden="true" title="company"></i>                          {{$user->company->name}}
                        </p>
                        <p>
                          <i class="icon icon-color fa fa-table" aria-hidden="true" title="department"></i>                          {{$user->job?$user->job->department->name:""}}
                        </p>
                        {{--<p>
                          <i class="icon icon-color md-case" aria-hidden="true" title="job title"></i>                          {{$user->job?$user->job->title:""}}
                        </p>--}}
                       
                        <div>
                          <a class="text-action" href="sip:{{$user->email}}" title="Message on Skype for business">
                            <i class="icon icon-color md-skype" aria-hidden="true"></i>
                          </a>
                          <a class="text-action" href="mailto:{{$user->email}}?Subject=From HCMatrix" title="Send an Email">
                            <i class="icon icon-color md-email" aria-hidden="true"></i>
                          </a>

                        </div>
                      </div>

                    </div>
                  </li>
                  <hr>
                  </div>
                  @endif
                  @endforeach
                  </div>
                </ul>
                {!! $users->appends(Request::capture()->except('page'))->render() !!}
              
          </div>
        </div>
      </div>
      <!-- End Panel -->
    </div>
  </div>
  @endsection
@section('scripts')

<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
   
} );
  function resetForm(){
    location.replace("{{url('directory')}}");
  }
  </script>

<script>


  var $el = document.getElementById('file');

  var $el2 = document.getElementById('file-placeholder');

  let $fileReader = new FileReader;

  $el.addEventListener('change', function (e) {
    $fileReader.readAsDataURL(e.target.files[0]);
  });

  $fileReader.onload = function () {
    $el2.src= $fileReader.result;
    //$el2.setAttribute('style', 'background-image:url("' + $fileReader.result + '");');
  };

  $el2.addEventListener('click', function (e) {
    $el.click();
  }, true);

</script>
@endsection