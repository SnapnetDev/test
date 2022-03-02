@extends('layouts.master')
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{ $name }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{ $name }}</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-lg-8">
                </div>
                <div class="col-lg-4" style="float:right;">
                   @if($name=='Polls') <a href="{{ route('view.my.polls') }}" class="btn btn-primary">View My Polls</a>@endif
                    <form class="form-inline"  action="{{ route('view.polls') }}" method="get" style="float:right;">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" autocomplete="off" placeholder="Search" value="{{ \Illuminate\Support\Facades\Request::get('search') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <br>
           <div class="row is-flex">
               @foreach($polls as $poll)
                   <div class="col-lg-4 masonry-item">
                       <div class="card card-shadow">
                           <div class="card-header bg-blue-300 vertical-align py-20 px-25">
                               <div class="row">
                                   <div class="col-lg-10">
                                       <h2 class="white font-size-16">{{ $poll->name }}</h2>
                                   </div>

                                   @if($poll->status=='active' && $poll->end_date>=\Carbon\Carbon::today()->format('Y-m-d'))
                                       <div class="col-lg-2">
                                           <a  href="{{ route('respond.poll',$poll->id) }}" style="margin-top: 15px" type="button" class="btn btn-sm btn-icon btn-inverse btn-round" data-toggle="tooltip" data-original-title="Respond">
                                               <i class="fa fa-send" aria-hidden="true"></i>
                                           </a>
                                       </div>
                                   @endif
                               </div>

                           </div>
                           <div class="card-block pt-0" style="padding-bottom: 0px;">
                               <div class="info">
                                   <dl class="dl-horizontal row">
                                       <dt class="col-sm-3">Created By</dt>
                                       <dd class="col-sm-9">{{ $poll->user->name }}</dd>
                                       <dt class="col-sm-3">End Date</dt>
                                       <dd class="col-sm-9">{{ $poll->end_date }}</dd>
                                       <dt class="col-sm-3">Status</dt>
                                       <dd class="col-sm-9">{{ $poll->status }}</dd>
                                       <dt class="col-sm-3">Type</dt>
                                       <dd class="col-sm-9">{{ $poll->type }}</dd>
                                       <dt class="col-sm-3">Description</dt>
                                       <dd class="col-sm-9">{{ $poll->description }}</dd>
                                   </dl>
                               </div>
                           </div>
                           <div class="card-footer bg-blue-grey-400">
                               <div class="row no-space">
                                   <div class="col-lg-4">
                                       <div class="counter counter-inverse">
                                           <span class="counter-number">{{ count($poll->questions) }}</span>
                                           <div class="counter-label inline-block ml-5">Questions</div>
                                       </div>
                                   </div>
                                   <div class="col-lg-4">
                                       @if(Auth::id()==$poll->user_id)
                                           <div class="btn-group btn-block" role="group">
                                               <button type="button" class="btn btn-primary btn-block dropdown-toggle" id="exampleGroupDrop1" data-toggle="dropdown" aria-expanded="false">
                                                   Action
                                               </button>
                                               <div class="dropdown-menu" aria-labelledby="exampleGroupDrop1" role="menu">
                                                   <a class="dropdown-item" href="{{ route('poll.responses',$poll->id) }}" role="menuitem">View Responses</a>
                                                   @if($poll->status!='active')
                                                       <a class="dropdown-item" onclick="return changeStatus({{$poll->id}},'active');"   role="menuitem">Enable</a>
                                                       <a class="dropdown-item" href="{{ route('edit.poll',$poll->id) }}" role="menuitem">Edit Poll</a>
                                                   @else
                                                       <a class="dropdown-item" onclick="return changeStatus({{$poll->id}},'disable');"  role="menuitem">Disable</a>
                                                   @endif
                                               </div>
                                           </div>
                                       @else
                                           @if($poll->status!='pending')
                                               <div class="btn-group btn-block" role="group">
                                                   <button type="button" class="btn btn-primary btn-block dropdown-toggle" id="exampleGroupDrop1" data-toggle="dropdown" aria-expanded="false">
                                                       Action
                                                   </button>
                                                   <div class="dropdown-menu" aria-labelledby="exampleGroupDrop1" role="menu">
                                                       <a class="dropdown-item" href="{{ route('poll.responses',$poll->id) }}" role="menuitem">View Responses</a>
                                                   </div>
                                               </div>
                                           @endif
                                       @endif
                                   </div>
                                   <div class="col-lg-4">
                                       <div class="counter counter-inverse">
                                           <span class="counter-number">{{ count($poll->responses) }}</span>
                                           <div class="counter-label inline-block ml-5">Responses</div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               @endforeach
           </div>
            <div class="row">
                <div class="col-lg-12">
                    {{ $polls->links() }}
                </div>
            </div>
        </div>

        <div class="site-action" data-plugin="actionBtn">
            <a type="button" href="{{ route('create.poll') }}" class="btn btn-floating btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function changeStatus(poll,status){
            alertify.confirm('Are you sure you want to ' +status, function () {
                alertify.success('Processing this request. Please wait...');
                $.ajax({
                    url: '{{url('/poll/change-status/')}}/'+poll+'/'+status,
                    type: 'GET',
                    success: function (data, textStatus, jqXHR) {
                        alertify.success(data);
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                    },
                    error: function (data, textStatus, jqXHR) {
                        alertify.error('Something went wrong')
                    }
                });
            }, function () {
                alertify.error('Cancelled')
            })
        }
    </script>
    @if(Session::has('success'))
        <script>
            toastr.success("{{ Session::get('success') }}", 'Success');
        </script>
    @endif
    @if(Session::has('fail'))
        <script>
            toastr.error("{{ Session::get('fail') }}", 'Error');
        </script>
    @endif
@endsection
@section('stylesheets')
    @include('polls.flex_row')
@endsection
