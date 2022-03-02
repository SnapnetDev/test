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



  fieldset {
    margin-bottom: 20px;
  }

  legend {
    background-color: #c0c0c0;
    font-size: 14px;
    font-weight: normal;
    padding: 5px 15px;
    color: #222;
  }
  .required{
    color: #f00;
  }

  .hmoPlaceholder{
    float: right; margin-top: 30px;
  }
</style>

@endsection
@section('content')

<div class="page ">
  <div class="page-header">
   <a href="">  <h1 class="page-title">HMO Service</h1> </a>
   <div class="page-header-actions">
    <div class="row no-space w-250 hidden-sm-down">

    </div>
  </div>
</div>
<div class="page-content container-fluid">


  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-info ">
        <div class="panel-heading main-color-bg">
          <h3 class="panel-title"> Health Corporate Proposal Form. Kindly fill this form and submit.  </h3>
        </div>

        <div class="panel-body">
          <div class="col-md-12">   



            <img class="hmoPlaceholder" src="{{$HMOSelfService->FindUser->image <> 'upload/avatar.jpg' ? asset('/uploads/avatar'.$HMOSelfService->FindUser->image ) : asset('assets/images/hmo-placeholder.png') }}"/>

            <br/>
            <form id="hmoForm" method="post" action="" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="userId" value="{{base64_encode($userId)}}">
              <fieldset>
                <legend>Basic Info:</legend>
                <div class="input-group mb-3">
                  <div class="form-row">

                    <div class="form-group col-md-2">
                      <label>Staff ID:</label>
                      <input type="text" readonly class="form-control" value="{{ $HMOSelfService->FindUser->emp_num }}">
                    </div>

                    <div class="form-group col-md-2">
                      <label>Job Title:</label>
                      <input type="text" readonly class="form-control" value="{{ $HMOSelfService->FindUser->sex == 'M' ? 'Male' : 'Female' }}">
                    </div>
                    <div class="form-group col-md-4">
                      <label>Enrollee Name:</label>
                      <input type="text" class="form-control" readonly class="form-control" value="{{ $HMOSelfService->FindUser->name }}">
                    </div>
                    <div class="form-group col-md-3">

                      <label>Prefered HMO:<span class="required">*</span></label>
                      <select required class="form-control" name="hmoId" onchange="if (this.value) pullHospital(this.value)" id="hmoId">
                        <option value="">---------</option>
                        @foreach($AllHMO as $key => $value)
                        <option {{ @$HMOSelfService->hmo == $value->id  ? 'selected' : ''}}  value="{{ $value->id }}"> {{ $value->hmo ? $value->hmo : '---- ----' }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>





                <div class="input-group mb-3">
                  <div class="form-row">
                    <div class="form-group col-md-2">
                      <label>Sex:</label>
                      <input type="text" readonly class="form-control" value="{{ $HMOSelfService->FindUser->sex == 'M' ? 'Male' : 'Female' }}" >
                    </div>
                    <div class="form-group col-md-2">
                      <label>Marital Status:</label>
                      <input type="text" readonly class="form-control" value="{{ $HMOSelfService->FindUser->marital_status }}" >
                    </div>
                    <div class="form-group col-md-2">
                      <label>Birth Date:</label>
                      <input type="text" readonly class="form-control" value="{{ $HMOSelfService->FindUser->dob }}" >
                    </div>
                  </div>

                  <div class="form-group col-md-2">
                    <label>Religion:</label>
                    <select required class="form-control" name="religion">
                      <option value=""> ------------- </option>
                      <option {{ $HMOSelfService->FindUser->religion == 'C' ? 'selected' : ''  }} value="C">  Christianity </option>
                      <option {{ $HMOSelfService->FindUser->religion == 'I' ? 'selected' : ''  }} value="I">  Islam </option>
                    </select>
                  </div>

                  <div class="form-group col-md-3">
                    <label>Mobile No:</label>
                    <input required type="text" name="userPhone" class="form-control" value="{{ $HMOSelfService->FindUser->phone }}">
                  </div>

                </div>

                <div class="input-group mb-3">
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Email</label>
                      <input required type="text" readonly class="form-control" value="{{ $HMOSelfService->FindUser->email }}">
                    </div>
                    <div class="form-group col-md-7">
                      <label>Address:</label>
                      <input type="text" name="address" class="form-control" value="{{ $HMOSelfService->FindUser->address }}">
                    </div>
                  </div>
                </div>
              </fieldset>



              <fieldset>
                <legend>Medical Info:</legend>
                <div class="input-group mb-3">
                  <div class="form-row">
                    <div class="form-group col-md-5">
                      <label>Choice of Hospital (Primary):<span class="required">*</span></label>
                      <select required class="form-control" name="hospital1" id="hospital1">
                        <option value="{{ @$HMOSelfService->FindHospital1->hospital ? $HMOSelfService->FindHospital1->id : '' }}">{{ @$HMOSelfService->FindHospital1->hospital ? $HMOSelfService->FindHospital1->hospital : 'Choice of Hospital (Primary):' }} </option>
                      </select>
                    </div>

                    <div class="form-group col-md-2">
                      <label>Genotype:</label>
                      <select required class="form-control" name="genotype">
                        <option value="">---------</option>
                        @foreach($genotype as $key => $value)
                        <option {{ @$HMOSelfService->genotype == $value  ? 'selected' : ''}} value="{{$value}}"> {{$value}} </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group col-md-4">
                      <label>Health Plan Type:</label>
                      <select required class="form-control" id="healthplantype" name="healthplantype">
                        <option value="{{ @$HMOSelfService->health_plan_type ? $HMOSelfService->health_plan_type : '' }}">{{ @$HMOSelfService->health_plan_type ? $HMOSelfService->health_plan_type : '------------' }} </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="input-group mb-3">
                  <div class="form-row">
                    <div class="form-group col-md-5">
                      <label>Alternate Hospital (Secondary):<span class="required">*</span></label>
                      <select required class="form-control" name="hospital2" id="hospital2">
                        <option value="{{ @$HMOSelfService->FindHospital2->hospital ? $HMOSelfService->FindHospital2->id : '' }}">{{ @$HMOSelfService->FindHospital2->hospital ? $HMOSelfService->FindHospital2->hospital : 'Alternate Hospital (Secondary):' }} </option>
                      </select>
                    </div>

                    <div class="form-group col-md-2">
                      <label>Blood Group:</label>
                      <select required class="form-control" name="bloodgroup">
                        <option value="">---------</option>
                        @foreach($bloodgroup as $key => $value)
                        <option {{ @$HMOSelfService->bloodgroup == $value  ? 'selected' : ''}} value="{{$value}}"> {{$value}} </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group col-md-4">
                      <label title="(Diabetes, hypertension, Sickle cell, Cancer, Kidney Issue, others….)">Pre-Existing Medical Condition:</label>
                      <input type="text" name="precondition" class="form-control" value="{{ @$HMOSelfService->precondition }}">
                    </div>
                  </div>
                </div>
              </fieldset>


              <fieldset>
                <legend>Dependents Details</legend>
                <table>
                  <tr>
                    @php
                    $dependents = ['Spouse:', 'Child 1:', 'Child 2:', 'Child 3:', 'Child 4:'];
                    @endphp

                    @for($i = 0; $i <= 4; $i++)
                    <td data-dependants>
                     <fieldset>
                      <legend> {{  $dependents[$i] }} </legend>

                      <div class="form-row" align="center" style="background-color: #eee; margin-bottom: 5px; height: 150px;">
                        <table> <tr> <td>
                          <img style="height: 150px;" src="{{@$HMOSelfService->AttachDependant[$i]->passport <> '' ? asset('/assets/hmo/'.$HMOSelfService->AttachDependant[$i]->passport ) : asset('assets/images/hmo-placeholder.png') }}"/>
                        </td>
                        <td>
                          <input type="file" name="dependantPassport[]">
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div class="form-row">
                    <div class=" col-md-12">
                      <input type="hidden" name="type[]" value="{{ $dependents[$i] }}">
                      <input type="text" class="form-control" value="{{ @$HMOSelfService->AttachDependant[$i]->fullname }}" name="dependant[]" placeholder="Fullname">
                    </div>
                    <div class="col-md-12">
                      <label></label>
                      <input autocomplete="off" type="text" class="form-control" data-datepicker name="dob[]" placeholder="Date of Birth" value="{{ @$HMOSelfService->AttachDependant[$i]->date_of_birth }}">
                    </div>
                    <div class="col-md-12">
                      <label></label>
                      <select class="form-control" name="gender[]">
                        <option>Gender</option>
                        <option {{ @$HMOSelfService->AttachDependant[$i]->gender == 'M' ? 'selected' : ''  }} value="M">  Male </option>
                        <option {{ @$HMOSelfService->AttachDependant[$i]->gender == 'F' ? 'selected' : ''  }} value="F">  Female </option>
                      </select>
                    </div>
                    <div class="col-md-12">
                      <label></label>
                      <select class="form-control" name="hospitalLoop1[]" id="hospital1">
                       <option value="{{ @$HMOSelfService->AttachDependant[$i]->FindHospital1->hospital ? $HMOSelfService->AttachDependant[$i]->FindHospital1->id : '' }}">
                        {{ @$HMOSelfService->AttachDependant[$i]->FindHospital1->hospital ? $HMOSelfService->AttachDependant[$i]->FindHospital1->hospital : 'Choice of Hospital (Primary):' }} 
                      </option>
                    </select>
                  </div>

                  <div class="col-md-12">
                    <label></label>
                    <select class="form-control" name="hospitalLoop2[]" id="hospital2">
                     <option value="{{ @$HMOSelfService->AttachDependant[$i]->FindHospital2->hospital ? $HMOSelfService->AttachDependant[$i]->FindHospital1->id : '' }}">
                      {{ @$HMOSelfService->AttachDependant[$i]->FindHospital2->hospital ? $HMOSelfService->AttachDependant[$i]->FindHospital2->hospital : 'Choice of Hospital (Secondary):' }} 
                    </option>
                  </select>
                </div>

                <div class="col-md-12">
                  <label></label>
                  <select class="form-control" dependant-healthplantype id="dependanthealthplantype" name="dependanthealthplantype[]">
                    <option value="{{ @$HMOSelfService->AttachDependant[$i]->health_plan_type ? $HMOSelfService->AttachDependant[$i]->health_plan_type : '' }}">{{ @$HMOSelfService->AttachDependant[$i]->health_plan_type ? $HMOSelfService->AttachDependant[$i]->health_plan_type : 'Health Plan Type' }} </option>
                  </select>
                </div>
              </div>


              <div class="col-md-12">
                <label></label>
                <input type="text" class="form-control" name="preCondition[]" value="{{ @$HMOSelfService->AttachDependant[$i]->pre_condition }}" placeholder="Pre-existing Conditions">
              </div>
              <div class="col-md-12">
                <label></label>
                <input type="text" class="form-control" name="occupation[]" value="{{ @$HMOSelfService->AttachDependant[$i]->occupation }}" placeholder="Occupation">
              </div>
              <div class="col-md-12">
                <label></label>
                <input type="text" class="form-control" name="phone[]" value="{{ @$HMOSelfService->AttachDependant[$i]->phone }}" placeholder="Phone">
              </div>
            </div>
          </fieldset>
        </td>
        @if($i == 2)
      </tr><tr>
        @endif
        @endfor
      </tr>
    </table>


  </fieldset>


  <div>
    <b>DECLARATION</b> <br/>
    <p>I, <u><b>{{ $HMOSelfService->FindUser->name }}</b></u> the assured, do hereby declare that all the foregoing 
      answers are true, that I have not concealed nor withheld anything with which the assurer 
    should be acquainted with in order to assess my eligibility for health insurance. </p>

    <p>Pre-existing/Chronic medical condition is defined as an injury, illness, sickness, disease 
      or other physical, medical, mental or nervous condition, disorder or ailment that with 
      reasonable medical certainty existed at the time of purchase of the policy or prior to the 
      purchase of the policy. In a case of non-disclosure, we reserve the right not to treat or to 
    terminate this policy.</p>

    <p>
     <input required type="checkbox" id="checked"> I agree that these and all statements I have made or shall make to the assurer or 
     to its medical examiner(s) in connection with this or previous proposal(s) shall 
   be the basis of this contract.</p>

 </div>



 <button id="submit" type="submit" class="btn btn-primary">I Accept and Submit</button>
</form>


</div>
</div>
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
<script type="text/javascript">
  function loadDropDown(data,$el,value,text,selected){
   //$el.html('');
   data.forEach((item)=>{
    $el.append(`<option value="${item[value]}">${item[text]}</option>`);
  });
 }

 function loadAllHospitals(data){
  $('[data-dependants]').each(function(){
    loadDropDown(data,$(this).find('#hospital1'),'id','hospital');  
    loadDropDown(data,$(this).find('#hospital2'),'id','hospital');      
  });  
} 

///////Date Picker
$('[data-datepicker]').each(function(){
  var $this = $(this);
  $this.datepicker();
});

function pullHospital(id){
/////////Pull Hospitals Based on HMO//////
$.get('{{ url('/hmo/getHMOHospitalsList/') }}/' + id, function (data) {
  loadDropDown(data,$('#hospital1'),'id','hospital');
  loadDropDown(data,$('#hospital2'),'id','hospital');
  loadAllHospitals(data);
});

///////////get Unique HMO Bands/////
$.get('{{ url('/hmo/getHMOHospitalsBand/') }}/' + id, function (data) {
  $('#healthplantype').html('');
  data.forEach((item)=>{
    $('#healthplantype').append(`<option value="${item.band}">${item.band}</option>`);
  });

    /////Dependant HealthPlans/////
    $('[dependant-healthplantype]').each(function(){
      //$('#dependanthealthplantype').html('');
      data.forEach((item)=>{
        $(this).append(`<option value="${item.band}">${item.band}</option>`);  
      });
    });
  });


}

</script>

<!-- SET admin right Priveliges -->
@if( @$HMOSelfService->status == '1'  && empty( @request()->input('patch') ) )
<script>
  $(':input').attr('disabled','disabled');
  $('#submit').attr('disabled','disabled');
  $('#checked').attr('checked', 'checked');
</script>
@endif


@endsection


