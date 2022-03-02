@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
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
<div class="page">
    <div class="page-header">
      <h1 class="page-title">Loan Requests</h1>
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
          @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('success') }}
                    </div>
                @endif
            <form class="form-horizontal" method="POST" id="interest" action="{{ url('') }}">
              {{ csrf_field() }}
              <div class="panel panel-info panel-line" >
                <div class="panel-heading main-color-bg">
                  <h3 class="panel-title">Loan Application</h3>
                </div>

                <div class="panel-body">
                  <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Amount</label>

                        {{--//max="{{$maximum_allowed}}"--}}
                    <input type="number" class="form-control" onkeyup="loan()" name="amount"  required id="amount"  placeholder="">

                  </div>
                  </div>
                   <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Period (Months)(Maximum of 18 months)</label>
                    <input type="number" class="form-control" onkeyup="loan()" name="period" required max="18" id="months" placeholder="">

                  </div>
                  </div>
                   <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Annual Interest rate(%)</label>
                        {{--value="{{$annual_interest}}"--}}
                    <input type="text" class="form-control" readonly name="rate"  id="rate" name="rate" placeholder="">

                  </div>
                  </div>
                  </div>

                  <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Monthly Payment</label>
                    <input type="number" class="form-control" readonly name="payment"  id="payment" max="" placeholder="">

                  </div>
                  </div>
                   <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Total Repayment</label>
                    <input type="text" class="form-control" readonly name="totrepay"  id="totrepay" placeholder="">

                  </div>
                  </div>
                   <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Total Interest</label>
                    <input type="text" class="form-control" readonly name="totint"  id="totint" placeholder="">

                  </div>
                  </div>
                  </div>
                  <div class="row">
                      {{--loanTypes--}}
                  <div class="col-md-4">
                    <div class="form-group">
                    <label for="">Repayment Starts</label>
                    <input type="text" class="form-control datepicker" required name="starts"   id="starts" >
                  </div>
                  </div>


                      <div class="col-md-4">
                          <div class="form-group">

                              <label for="">Loan Type</label>

                              <span id="loan_type_id">Loading ... </span>

                              {{--<select name="loan_type_id" class="form-control" id="">\--}}

                                  {{--@foreach ($loanTypes as $loanType)--}}

                                      {{--<option value="{{ $loanType->id }}">{{ $loanType->name }}</option>--}}

                                  {{--@endforeach--}}
                              {{--</select>--}}

                          </div>
                      </div>



                      <div class="col-md-4">
                          <div class="form-group">

                              <label for="">Guarantor</label>

                              <select name="guarantor_id" class="form-control" id="guarantor_id">\

                                 <option value="">--Select--</option>

                              </select>

                          </div>
                      </div>




                </div>
                <input type="hidden" name="netpay" value="">
                    {{--{{$maximum_allowed}}--}}
                <input type="hidden" name="type" value="loan_request" name="">




                </div>

                </div>


                  <button type="submit" class="btn btn-primary">
                     Apply for Loan
                  </button>
                </form>

  </div>
</div>
@include('js.rxjs')
<script>



let getUsers = (email) => {

    let subj = new Rx.Subject;

    fetch('{{ route('helpers.users') }}',{
        method:'GET'
    }).then(res=>res.json()).then(response=>{

        var $list = response.list;

        $list.forEach((v,k)=>{

            subj.next(v);

        });

    });


    return subj;

};

getUsers('').subscribe(response=>{

  $('#guarantor_id').append(`<option value="${response.id}">${response.email}</option>`);

});






</script>


@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
 <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

@include('payrollsettings.loan_type.loan_type_select_js')
<script>
    $(function(){

        let $loanTypeSelect = LoanTypeSelect();
        $loanTypeSelect.mount($('#loan_type_id'));

    });

</script>

<script type="text/javascript">
$(document).ready(function() {

     $('.datepicker').datepicker({
    autoclose: true,
    format:'yyyy-mm',
     viewMode: "months",
    minViewMode: "months"
});
});
$(document).on('submit', '#interest', function(event) {
      event.preventDefault();
    var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          // console.log(formdata);
          // return;
          //var formAction = form.attr('action');
          $.ajax({
              url         : '{{url('loan')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){
                  // toastr["success"]("Loan Requested successfully",'Success');
                  if (data=='success'){
                    toastr.success("Loan Requested successfully",'Success');
                      window.location.href = "{{url('loan/my_loan_requests')}}";

                  }else{
                    toastr.error("Loan Requested could not be submitted",'Error');
                  }
              },
              error:function(data, textStatus, jqXHR){
                  // toastr["success"]("Loan Requested successfully",'Success');

              }
          });

    });

function loan()
{
  var monthly = $('#rate').val() / 12 / 100;
  var start = 1;
  var length = 1 + monthly;
  for (i=0; i<$('#months').val(); i++)
  { start = start * length
  }
var payment = Number($('#amount').val() * monthly / ( 1 - (1/start)));
 $('#payment').val(Number($('#amount').val() * monthly / ( 1 - (1/start))))
$('#payment').val(payment.toFixed(2));
var totrepay = Number($('#payment').val()) * i;
$('#totrepay').val(totrepay.toFixed(2));
var totint = totrepay - $('#amount').val();
$('#totint').val(totint.toFixed(2)) ;
}
</script>

@endsection
