@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('assets/examples/css/apps/mailbox.css')}}">
@endsection

@section('content')
<div>
  <div class="page bg-white">
    <!-- Mailbox Sidebar -->
    <div class="page-aside">
      <div class="page-aside-switch">
        <i class="icon md-chevron-left" aria-hidden="true"></i>
        <i class="icon md-chevron-right" aria-hidden="true"></i>
      </div>
      <div class="page-aside-inner page-aside-scroll">
        <div data-role="container">
          <div data-role="content">
            <div class="page-aside-section">
              <div class="list-group">
                 <a class="list-group-item" href="{{url('userprofile/notifications')}}"><i class="icon md-chart" aria-hidden="true"></i>All Notifications</a>
              </div>
            </div>
           
          </div>
        </div>
      </div>
    </div>
    <!-- Mailbox Content -->
    <div class="page-main">
      <!-- Mailbox Header -->
      <div class="page-header">
        <h1 class="page-title">Notification Center</h1>
        <div class="page-header-actions">
          <form>
            <div class="input-search input-search-dark">
              <i class="input-search-icon md-search" aria-hidden="true"></i>
              <input type="text" class="form-control" name="" placeholder="Search...">
            </div>
          </form>
        </div>
      </div>
      <!-- Mailbox Content -->
      <div id="mailContent" class="page-content page-content-table" data-plugin="asSelectable">
        <!-- Actions -->
        <div class="page-content-actions">
          
          <div class="actions-main">
            <span class="checkbox-custom checkbox-primary checkbox-lg inline-block vertical-align-bottom">
              <input type="checkbox" class="mailbox-checkbox selectable-all" id="select_all"
              />
              <label for="select_all"></label>
            </span>
            
          </div>
        </div>
        @if(count(Auth::user()->notifications)>0)
        <!-- Mailbox -->
        <table id="mailboxTable" class="table" data-plugin="animateList" data-animate="fade"
        data-child="tr">
          <tbody>
          	@foreach(Auth::user()->notifications as $notification)
            <tr id="mid_1" data-url="{{url('userprofile/notification').'?notification_id='.$notification->id}}" data-toggle="slidePanel" style="{{ $notification->read_at==''?"background:#dad9ed;":''}}">
              <td class="cell-60">
                <span class="checkbox-custom checkbox-primary checkbox-lg">
                  <input type="checkbox" class="mailbox-checkbox selectable-item" id="mail_mid_1"
                  />
                  <label for="mail_mid_1"></label>
                </span>
              </td>
              <td class="cell-30 responsive-hide">
                <span class="checkbox-important checkbox-default">
                  <input type="checkbox" class="mailbox-checkbox mailbox-important" id="mail_mid_1_important"
                  />
                  <label for="mail_mid_1_important"></label>
                </span>
              </td>
              <td class="cell-60 responsive-hide ">
                <a class="avatar" href="javascript:void(0)">
                  <i class="icon {{isset($notification->data['icon'])?$notification->data['icon']:'md-notifications'}}  " ></i>
                </a>
              </td>
              <td>
                <div class="content">
                  <div class="title">{!! $notification->read_at==''?"<strong>":''!!}{{isset($notification->data['type'])?$notification->data['type']:""}}{!! $notification->read_at==''?"</strong>":''!!}</div>
                  <div class="abstract">{!! $notification->read_at==''?"<strong>":''!!}{{isset($notification->data['subject'])?$notification->data['subject']:''}}{!! $notification->read_at==''?"</strong>":''!!}</div>
                </div>
              </td>
              <td class="cell-30 responsive-hide">
              </td>
              <td class="cell-130">
                <div class="time">{{$notification->created_at->diffForHumans()}}</div>
               {{--  <div class="identity"><i class="md-circle red-600" aria-hidden="true"></i>Work</div> --}}
              </td>
            </tr>
           @endforeach
          </tbody>
        </table>
        @else
<h3 style="margin-left:30%"> No Notifications Found !!! </h3>
        @endif
    
  <!-- End Add Label Form -->
@endsection
@section('scripts')
  <script src="{{ asset('assets/js/App/Mailbox.js')}}"></script>
  <!-- <script src="{{ asset('assets/examples/js/apps/mailbox.js')}}"></script> -->
@endsection