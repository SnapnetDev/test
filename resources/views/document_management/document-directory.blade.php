@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">

@endsection


@section('content')
<div class="page ">
  <div class="page-header">
    <a href=""> <h1 class="page-title">Document Management</h1> </a>

    <div class="panel-heading main-color-bg" style="color: #fff;">
      <div class="panel-actions">
        <a class="btn btn-primary" data-toggle="modal" data-target="#newHMOModal">New Document</a>
      </div>
    </div>
  </div>


  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-md-12">

        @if(isset($allDocument))
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-info ">

              <div class="panel-heading main-color-bg" style="margin-bottom: 10px;">
                <h3 class="panel-title"> Document Directory </h3>
              </div>

              <div class="panel-body">
                <div class="col-md-13">  


                  <style type="text/css">
                   .upper, td, th{
                      font-size: 12px;
                      text-transform: uppercase;
                   }
                 </style>
                 <table id="data_table" class="table">
                  <thead>
                    <tr>
                      <th style="width:1%;">S/N</th>
                      <th style="width:12%;">Date</th>
                      <th style="width:20%;">Sender</th>
                      <th style="width:30%;">Subject</th>
                      <th style="width:10%;">Email</th>
                      <th style="width:10%;">Phone</th>
                      <th style="width:4%;">Direction</th>
                      <th style="width:4%;">Status</th>
                      <th style="width:20%;">Receiver</th>
                      <th class="ot-export-col" style="width:4%;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>


                    @foreach($allDocument as $key => $value)
                    <tr>
                      <td align="center"> {{ $loop->iteration }}. </td>
                      <td> {{  Carbon\Carbon::parse($value->created_at)->format('d-M-Y h:iA')  }} </td>
                      <td> {{ $value->sender }} </td>
                      <td> {{ $value->subject }} </td>
                      <td> {{ $value->email }} </td>
                      <td> {{ $value->phone }} </td>
                      <td align="center">  {!! ($value->direction == '1') ? '<i style="color: #d00" class="fa fa-chevron-circle-up" aria-hidden="true"></i> Egress' : '<i style="color: #008000;" class="fa fa-chevron-circle-down" aria-hidden="true"></i> Ingress' !!} </td>
                      
                      <td align="center"> {!! ($value->status == '1') ? '<i title="Delivered" style="color: #008000" class="fa fa-check-square" aria-hidden="true"></i>' : '<i title="Sent" style="color: #FFA500;" class="fa fa-check-square" aria-hidden="true"></i>' !!}  </td>

                      <td> {{ isset($value->resolveName->name) ? $value->resolveName->name : '' }} </td>
                      <td>
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                          data-toggle="dropdown" aria-expanded="false">
                          Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                          <a href="#" onclick="prepareLEditData( {{$value->id}} )" style="cursor:pointer;"class="dropdown-item"><i class="fa fa-edit" aria-hidden="true" ></i>&nbsp;Modify</a>

                          <a style="cursor:pointer;" class="dropdown-item" onclick="deleteMail({{$value->id}} );" ><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete</a>
                        </div>
                      </div>
                    </td>


                  </tr>

                  @endforeach
                  @if(count($allDocument) == '0')
                  <tr>
                    <td colspan="9">  <h4 style="padding: 30px;">No Document found.</h4> </td>
                  </h4>
                  @endif

                </tbody>
              </table>

            </div>
          </div>
        </div>
        @endif

      </div>
    </div>

  </div>
</div>








<div class="modal fade in modal-3d-flip-horizontal modal-info" id="newHMOModal" aria-hidden="true" aria-labelledby="addHolidayModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" action="/new-document" method="post" enctype="multipart/form-data">
                    @csrf
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">New Document</h4>
          </div>

          <style type="text/css">
            .form-control{
              width: 110%;
            }
            .form-group h5{
              margin: 2px 0px 2px 0px; font-size: 12px;
            }
          </style>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    
                    <div class="form-group">
                      <h5 class="example-title">Sender Fullname</h5>
                      <input required type="text" name="sender" class="form-control" >
                    </div>
                    
                    <div class="form-group">
                      <h5 class="example-title">Subject</h5>
                      <textarea required name="subject" class="form-control"></textarea>
                    </div>
                    
                    <div class="form-group">
                      <h5 class="example-title">Sender Email</h5>
                      <input type="email" name="email" class="form-control" placeholder="user@domain.com" >
                    </div>
                    
                    <div class="form-group">
                      <h5 class="example-title">Sender Phone</h5>
                      <input type="text" name="phone" class="form-control" >
                    </div>
                    
                    <div class="form-group">
                      <h5 class="example-title">Direction</h5>
                      <select required class="form-control" name="direction">
                      <option value=""> ------------- </option>
                      <option value="0">  Ingress </option>
                      <option value="1">  Egress </option>
                    </select>
                    </div>


                    <div class="form-group">
                      <h5 class="example-title">Receiver Fullname</h5>
                      <select name="receiver" id="" required class="form-control select2" style="width:110%;">
                        <option value=""> ------------- </option>
                        @foreach($allUser as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                      </select> 
                    </div>

                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                
                  <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
         </div>
        </form>
      </div>
    </div>









  <div class="modal fade in modal-3d-flip-horizontal modal-info" id="editDocumentModal" aria-hidden="true" aria-labelledby="editHMOModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" action="/edit-mail" method="post" enctype="multipart/form-data">
          <input type="hidden" id="MailId" name="id">
                    @csrf
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Modify Document</h4>
          </div>

          <style type="text/css">
            .form-control{
              width: 110%;
            }
            .form-group h5{
              margin: 2px 0px 2px 0px; font-size: 12px;
            }
          </style>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    
                    <div class="form-group">
                      <h5 class="example-title">Sender Fullname</h5>
                      <input required type="text" id="sender" name="sender" class="form-control" >
                    </div>
                    
                    <div class="form-group">
                      <h5 class="example-title">Subject</h5>
                      <textarea required id="subject" name="subject" class="form-control"></textarea>
                    </div>
                    
                    <div class="form-group">
                      <h5 class="example-title">Sender Email</h5>
                      <input type="email" id="email" name="email" class="form-control" placeholder="user@domain.com" >
                    </div>
                    
                    <div class="form-group">
                      <h5 class="example-title">Sender Phone</h5>
                      <input type="text" id="phone" name="phone" class="form-control" >
                    </div>
                    
                    <div class="form-group">
                      <h5 class="example-title">Direction</h5>
                      <select required class="form-control" id="direction" name="direction">
                      <option value=""> ------------- </option>
                      <option value="0">  Ingress </option>
                      <option value="1">  Egress </option>
                    </select>
                    </div>


 <!--                    <div class="form-group">
                      <h5 class="example-title">Receiver Fullname</h5>
                      <select name="receiver" id="receiver" required class="form-control select2" style="width:110%;">
                        <option value=""> ------------- </option>
                        @foreach($allUser as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                      </select> 
                    </div> -->

                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                
                  <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
         </div>
        </form>
      </div>
    </div>






@endsection
@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

<script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>


<script type="text/javascript">
  $("#data_table").DataTable( {
    dom: 'Bfrtip',
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });


  function deleteMail(id) {
    alertify.confirm('Are you sure you want to delete this Document?', function () {
      $.get('{{ url('/mail-delete/') }}/' + id, function (data) {
        if (data == 'success') {
          toastr["success"]("Mail deleted successfully", 'Success');
          location.reload();
        } else {
          toastr["error"]("Error deleting Document", 'Success');
        }
      });
    }, function () {
      alertify.error('Mail not deleted');
    });
  }




    function prepareLEditData(id) {
      $('#MailId').val(id);
      $.get('{{ url('/mail-get/') }}/' + id, function (data) {
        $('#sender').val(data.sender);
        $('#subject').val(data.subject);
        $('#email').val(data.email);
        $('#phone').val(data.phone);
        $('#direction').val(data.direction);
        $('#receiver').val(data.receiver);
        //console.log(data);
      });

      $('#editMailModal').modal();
    }



$('.select2').select2();
</script>
@endsection
