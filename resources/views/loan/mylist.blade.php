@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <style media="screen">
    .form-cont{
      border: 1px solid #cccccc;
      padding: 10px;
      border-radius: 5px;
    }
    #stgcont {
      list-style: none;
    }
    #stgcont li{
      margin-bottom: 10px;
    }
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">My Loan Requests</h1>
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


          {{--js addins--}}


<div id="approval-list-outlet"></div>
<div id="approval-modal"></div>


          <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">My Loan Requests</h3>
                <div class="panel-actions">
                      <a class="btn btn-info" href="{{url('loan')}}" >New Loan Request</a>

                    </div>
              </div>

              <div class="panel-body">
            <table class="table table-stripped" id="">
              <thead>
                <tr>
                  <th>Employee <br>Name</th>
                  <th>Amount</th>
                  <th>Annual <br> Net Pay</th>
                  <th>Payment<br> Period</th>
                  <th>Monthly <br>Deductions</th>
                  <th>Rate</th>
                  <th>Repayment<br> Starts</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th>Approved By</th>
                  <th>
                    Actions
                  </th>

                </tr>
              </thead>
              <tbody>
              @if(count($loan_requests)>0)
                  @foreach ($loan_requests as $request)
                    <tr>
                      <td>{{ $request->user->name }}</td>
                      <td>{{ $request->amount }}</td>
                      <td>{{ $request->netpay }}</td>
                      <td>{{ $request->period }}</td>
                      <td>{{$request->monthly_deduction}}</td>
                      <td>{{ $request->current_rate}}%</td>
                      <td>{{ date('M, Y', strtotime($request->repayment_starts))}}</td>
                      <td><span class=" tag tag-outline  {{$request->completed==1?'tag-success':'tag-warning'}}">{{$request->completed==1?'completed':'pending'}}</span></td>
                      <td>{{ $request->created_at }}</td>
                      <td>{{ $request->status==1?$request->approver->name:'Not Yet Approved' }}</td>
                      <td>
                        <a data-loan-request="{{ $request->id }}" style="text-decoration: none;" href="#" class="btn btn-sm btn-success">Approvals</a>
                      </td>


                    </tr>
                  @endforeach
                  @endif

              </tbody>

            </table>
              {!! $loan_requests->appends(Request::capture()->except('page'))->render() !!}
          </div>
        </div>


          </div>

          </div>

  </div>


</div>
@endsection
@section('scripts')
  @include('payrollsettings.loan_type.loan_base_js')
  @include('loan.approvals.approval_modal_js')
  @include('loan.approvals.approval_list_js')


  <script>
      $approvalList.mount($('#approval-list-outlet'));
      approvalForm.mount($('#approval-modal'));
      //approval-modal
  </script>


<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('.input-daterange').datepicker({
    autoclose: true
});
$('.select2').select2();
    var selected = [];



} );

  </script>
@endsection
