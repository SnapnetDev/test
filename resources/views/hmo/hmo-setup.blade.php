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
  .cardbox{
    background-color: #eee !important; margin: 10px; border-radius: 5px; box-shadow: 5px 5px #ccc; margin-right: 30px;
  }
  .cardbox2{
    background-color: #eee !important; padding-top: 20px;
  }

  .bold{
    font-weight: bold !important;
  }

  .collapsible {
    background-color: #eee;
    color: #777;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
  }
 .collapsible:active, .collapsible:hover {
    background-color: #ccc;
  }

  .content {
    padding: 0 18px;
    display: none;
    overflow: hidden;
    background-color: #ffffff;
  }
</style>

@endsection
@section('content')

<div class="page ">
  <div class="page-header">
    <a href="/hmo-setup"> <h1 class="page-title">HMO Setup</h1> </a>
    <div class="page-header-actions">
      <div class="row no-space w-250 hidden-sm-down">

        <div class="col-sm-6 col-xs-12">
          <div class="counter">
 <!--            <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

 -->

 @if(isset($allHMO))
 <div class="panel-heading main-color-bg" style="color: #fff;">
  <div class="panel-actions">
    <a class="btn btn-primary" data-toggle="modal" data-target="#newHMOModal">New HMO</a>
  </div>
</div>
@endif


</div>
</div>

</div>
</div>
</div>
<div class="page-content container-fluid">





  <div class="row">
    <div class="col-md-12">

      @if(isset($allHMO))
      <div class="panel panel-info ">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Available  HMO </h3></div>

        <div class="panel-body">
          <div class="col-md-12"> 
            
            <div style="margin-left: 10px;" class="row">  
              @foreach($allHMO as $key => $value)
              <div class="col-sm-3 cardbox">
                <div class="card cardbox2">
                  <div class="card-body">
                    <h3 class="card-title" style="color: #666">{{$loop->iteration}}. {{$value->hmo}}</h3>
                    <p class="card-text"> [ {{ $value->withCount('CountHospital')->get()[$key]->count_hospital_count }} ] {{$value->withCount('CountHospital')->get()[$key]->count_hospital_count > 1 ? 'Hospitals' : 'Hospital' }} </p>
                    <a href="/hmo/hospital/{{$value->hmo}}/{{$value->id}}" class="btn btn-primary">Access HMO <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                    </a>
                  </div>
                </div>
              </div>
              @endforeach
            </div>


            @if(count($allHMO) == '0')
              <h4 style="padding: 30px;">No HMO found.</h3>
            @endif

          </div>
        </div>

        @endif



        @if(isset($HMOHospitalsPreview))
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-info ">

              <div class="panel-heading main-color-bg">
                <h3 class="panel-title"> <strong> {{request()->route()->parameters['hmoName']}} </strong> - Hospital and Band List</h3>
              </div>


              @foreach($bandsCategory as $key => $Bandvalue)
              <button type="button" class="collapsible"><strong>  {{ $Bandvalue->band ? $Bandvalue->band ." Band"  : 'Untitled Band' }} <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></strong></button>

              <div class="content">

                <table class="table table-striped" id="emptable" >
                  <thead>
                    <tr>
                      <th class="bold" style="width:2%;">S/N</th>
                      <th class="bold" style="width:30%;">Hospital Name</th>
                      <!-- <th class="bold" style="width:10%;">Band</th> -->
                      <th class="bold" style="width:10%;">Category</th>
                      <!-- <th class="bold" style="width:10%;">HMO</th> -->
                      <th class="bold" style="width:10%;">Contact</th>
                      <th class="bold" style="width:40%;">Address</th>
                      <th class="bold" style="width:40%;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                    @php
                      $counter = 0;
                    @endphp
                    <!-- Hosopital Filter -->
                    @foreach($HMOHospitalsPreview as $key => $value)
                    @php
                    if($Bandvalue->band <> $value->band )
                    continue;
                     ++$counter;
                    @endphp
                    <tr>
                      <td> {{  $counter }}. </td>
                      <td> {{ $value->hospital }} </td>
                      <!-- <td> {{ $value->band }} </td> -->
                      <td> {{ $value->category }} </td>
                      <!-- <td> {{ $value->FindHMO->hmo }} </td> -->
                      <td> {{ $value->contact }} </td>
                      <td> {{ $value->address }} </td>
                      <td>
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                          data-toggle="dropdown" aria-expanded="false">
                          Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                          <a style="cursor:pointer;"class="dropdown-item" id=" $user->id " onclick="prepareLEditData( {{$value->id}} , '{{ $value->FindHMO->hmo }}', '{{ $value->hmo }}'  )"><i class="fa fa-edit" aria-hidden="true" ></i>&nbsp;Edit</a>

                          <a style="cursor:pointer;"class="dropdown-item" id=" $user->id " ><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete</a>
                        </div>
                      </div>
                    </td>
                    <td>


                    </td>
                  </tr>
                  @endforeach

                </tbody>
              </table>

            </div>

            @endforeach


            @if(count($HMOHospitalsPreview) == '0')
              <h4 style="padding: 30px;">No hospitals found</h3>
            @endif

          </div>

          @endif






        </div>
      </div>

    </div>


  </div>


  @include('hmo.modals')



  @endsection
  @section('scripts')

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">



    function prepareLEditData(id, hmoName, hmoId) {
      $('#hospitalId').val(id);

      $.get('{{ url('/hmo/getHospital') }}/' + id, function (data) {
        $('#hospital').val(data.hospital);
        $('#band').val(data.band);
        $('#category').val(data.category);
        $('#contact').val(data.contact);
        $('#address').val(data.address);

        $('#hmoName').val(hmoName); 
        $('#hmoId').val(hmoId); 
      });

      $('#editHMOHospitalModal').modal();
    }


    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
      coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.display === "block") {
          content.style.display = "none";
        } else {
          content.style.display = "block";
        }
      });
    }


/*        function deleteHMOHospital(userId) {
        alertify.confirm('Are you sure you want to delete this Enrollee?', function () {
            $.get('{{ url('/hmo/deleteUserHMO') }}/' + userId, function (data) {
                if (data == 'success') {
                    toastr["success"]("Enrollee deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Enrollee", 'Success');
                }
            });
        }, function () {
            alertify.error('Enrollee not deleted');
        });
    }*/

  </script>
  @endsection
