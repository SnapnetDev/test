  @extends('layouts.master')
  @section('stylesheets')

  <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.css')}}">
  <link rel="stylesheet" href="{{url('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
  <link href="{{url('global/vendor/select2/select2.css')}}" rel="stylesheet" >
  <style type="text/css">
    .hide{
      display: none;
    }
  </style>
  @endsection
  @section('content')
  <div class="page">

    <div class="page-header">
      <h1 class="page-title">@if(isset($_GET['foldername'])) {{$_GET['foldername']}}  ({{$documents->total()}})@endif</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{url('document/mydocument')}}">{{('All folder')}}</a></li>
        <li class="breadcrumb-item active">{{('You are Here')}}</li>
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
              <span class="counter-number font-weight-medium" id="time">08:32:56 am</span>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="page-main">

      <div class="col-md-12 col-xs-12">
        <div class="panel panel-primary panel-line">
          <div class="panel-heading">
            <div class="panel-title ">

              <div style="margin-top:-13px;" class="col-md-4"> <div class="form-group">
                <form method="get" action="{{url('document')}}/files">
                  <input type="hidden" name="folder_id"  value="{{isset($_GET['folder_id']) ? $_GET['folder_id'] : ''}}">
                  <input type="hidden" name="foldername"  value="{{$_GET['foldername']}}">
                  <div class="input-group">

                    <input required type="text" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : '' }}" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-default btn-outline" onclick="searchq()" id="searchbtn">Go!</button>
                    </span>


                  </div>
                </form>
              </div></div></div>
              <div class="panel-actions panel-actions-keep ">


                 <div class="btn-group" role="group" style="margin-top: 7%; z-index: 999999; margin-left: -13%">
                      <button type="button" class="btn btn-primary dropdown-toggle waves-effect" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon md-apps" aria-hidden="true"></i>Action
                      </button>
                      <div  class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">

                <a href="Javascript::void()" class="dropdown-item icon md-delete" onclick="deletedocs()" data-toggle="tooltip" data-original-title="delete" data-container="body" title=""><span></span>Delete</a>
                <a href="Javascript::void()" class="dropdown-item icon md-square-right " data-toggle="modal" data-target="#moveto"   data-container="body" title=""><span data-toggle="tooltip" data-original-title="Move File"></span>Move</a>
                <a href="Javascript::void()" class="dropdown-item icon md-plus" data-toggle="modal" data-target="#adddocument"   data-container="body" title=""><span data-toggle="tooltip" data-original-title="Add File"></span>Add</a>
                      </div>
                    </div>

              </div>
            </div>
            <div class="panel-body">

              <div class="table-responsive col-md-12">
                @if(count($documents)>0)
                <table class="table table-striped"  >
                  <thead class="bg-blue-grey-100">
                    <tr>
                      <th>
                        <span class="checkbox-custom checkbox-primary">
                          <input class="selectable-all"  type="checkbox">
                          <label></label>
                        </span>
                      </th>
                      <th>
                        Name
                      </th>
                      <th>
                        Owner
                      </th>
                      <th>
                        Category
                      </th>

                      <th>
                        Created
                      </th>
                      <th>
                       Expires
                     </th>
                     <th>
                       Last Viewed By
                     </th>
                     <th>Action</th>
                   </tr>
                 </thead>
                 <tbody>
                  @foreach($documents as $document)
                  <tr>
                    <td>
                      <span class="checkbox-custom checkbox-primary">
                        <input class="doclist" value="{{$document->id}}" id="delete{{$document->id}}" type="checkbox">
                        <label></label>
                      </span>
                    </td>
                    <td><a href="javascript:void(0)">{{$document->document_name}}</a>

                    </td>
                    <td>{{$document->user->name}}
                    </td>
                    <td>{{$document->folder->docname}}
                    </td>

                    <td>
                      <i class="icon wb-time m-l-10" aria-hidden="true"></i>
                      <span> {{$document->created_at->diffForHumans()}}</span>

                    </td>
                    <td>
                      @if($document->expiry=="")
                      N/A

                      @else
                      {{$document->expiry->diffForHumans()}}
                      @endif
                    </td>
                    <td>
                      @if($document->last_mod_id!=0)  {{$document->user_modified->name }} @ {{$document->updated_at->diffForHumans()}}
                      @else
                      None
                      @endif
                    </td>
                    <td>
                     <div class="btn-group" role="group">
                      <button type="button" class="btn btn-primary dropdown-toggle waves-effect" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon md-apps" aria-hidden="true"></i>
                      </button>
                      <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                        <a class="dropdown-item" href="{{url('document')}}/download?id={{$document->id}}" role="menuitem">Download Document</a>

                        <a class="dropdown-item" href="Javascript::void(0)"  onclick="deleteIndividualDoc('delete{{$document->id}}')"  role="menuitem">Delete Document</a>
                      </div>
                    </div>

                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {!! $documents->appends(Request::capture()->except('page'))->render() !!}
            @else
            <div style="margin-top:10px;" class="alert alert-danger"><h4>Folder Empty</h4></div>
            @endif

          </div>
        </div>
      </div>

    </div></div></div>

    <div class="modal fade modal-slide-in-right" id="moveto" aria-labelledby="exampleModalTitle" role="dialog"  aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-warning">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Move Doc</h4>
          </div>
          <div class="modal-body">
            <p>Select Folder To Move Selected Document To</p>
            <select  class="form-control"  id="folderid">
              <option value="" >--Select Folder--</option>
              @foreach($folders as $folder)
              <option value="{{$folder->id}}" >{{$folder->docname}}</option>

              @endforeach
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" onclick="movefile()" class="btn btn-primary"><i class="icon wb-move"></i>&nbsp;&nbsp;Move</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade modal-3d-flip-horizontal" id="adddocument" aria-labelledby="exampleModalTitle" role="dialog"   aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">{{('Upload Document')}}</h4>
    </div>
    <div class="modal-body">
                        <!-- <div class="col-xs-12 col-xl-12 form-group">
                        {{('Document Name')}} :<br>
                        <input type="text" class="form-control" id="docname" />
                    </div> -->
                    <form id="uplodaDocument">
                    <div class="col-xs-12 col-xl-12 form-group">
                        {{('Has Expiry ?')}} :<br>
                        <select class="form-control" id="expirydecide" style="width: 100%">
                            <option value='1'> Yes </option>
                            <option value='2' selected> No </option>
                        </select>
                    </div>
                 @if(Auth::user()->role->permissions->contains('constant', 'upload_document'))
                    <div class="col-xs-12 col-xl-12 form-group">
                        {{('Upload For')}} :<br>
                        <select class="form-control"   id="employeeid" name="user_id" style="width: 100%">
                            <option value="{{Auth::user()->id}}" selected> Myself </option>

                        </select>
                    </div>
                  <input type="hidden" name="last_mod_id" value="{{Auth::user()->id}}">
                    @elseif(Auth::user()->role->permissions->contains('constant', 'upload_own_document'))

                     <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                    @endif

                      <div class="col-xs-12 col-xl-12 form-group">

                      <p>{{('Folder')}}</p>
                      <select class="form-control" data-plugin="select2" id="upfolderid" name="type_id" style="width: 100%">
                          <option value="" >--{{('Select Folder')}}--</option>
                          @foreach($folders as $folder)
                       <option value="{{$folder->id}}" {{isset($_GET['folder_id']) && $_GET['folder_id']==$folder->id ? 'selected' : ''  }} >{{$folder->docname}}</option>

                          @endforeach
                      </select>
                  </div>

                    <div id="toggleexpiry" class="hide col-xs-12 col-xl-12 form-group">
                        {{('Expiry Date')}} :<br>

                        <input type="text" name="expiry" readonly="" data-plugin="datepicker" class="form-control" id="expirydate" />
                    </div>
                    <input type="hidden" value="upload" name="type">
                    <br>
                    <div class="col-xs-12 col-xl-12 form-group" id="dropboxpane">
                     <b>{{('Upload Document')}}:</b><br>
                                          <input type="file" name="document" >
                    </div><br>
                     <div class="col-xs-12 col-xl-12 form-group" >
                    <div class="progress">
                      <div id="progress-bar" class="progress-bar progress-bar-striped active" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar">
                        <span class="sr-only" id="progress_text">0% Complete</span>
                      </div>
                    </div>
                  </div>

             </div>
             <div class="modal-footer">

              <button type="submit" id="uploaddocument" class="btn btn-primary"><i class="fa fa-upload" ></i>&nbsp;&nbsp;{{('Upload')}}</button>
               </form>
          </div>
      </div>
  </div>
</div>


    @endsection
    @section('scripts')

  <script src="{{asset('js/sweetalert.min.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.full.min.js')}}"></script>

  <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript">


      $(function(){

           $('#employeeid').select2({
           ajax: {
               delay: 250,
               processResults: function (data) {

                    return {

              results: data
                };
              },


              url: function (params) {
              return '{{url('document')}}/listEmp';
              }

          }
        });

            //toggle expirydate
        $('#toggleexpiry').addClass('hide');
            $('#expirydecide').change(function(){
                              // alert('ch');
                              decision=$('#expirydecide').val();
                              if(decision==1){

                               $('#toggleexpiry').removeClass('hide');
                               return;
                             }
                             $('#toggleexpiry').addClass('hide');


                           });
          });

        $('#uplodaDocument').submit(function(e){

            e.preventDefault();
               if($('input[name="file"]').val()==''){
              return toastr.error("Error, Please Upload a file");
            }
              processForm('uplodaDocument', '{{url("document")}}',  'progress');

        });

function processForm(formid, route,  progress)
{

   formdata= new FormData($('#'+formid)[0]);
   formdata.append('_token','{{csrf_token()}}');

    $.ajax(
    {
        // Your server script to process the upload
        url: route,
        type: 'POST',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        success:function(data, status, xhr)
        {
            if(data.status=='success')
            {
              toastr.success(data.message);
              window.location.reload();
              return;
            }
            return   toastr.error(data.message);

        },
        // Custom XMLHttpRequest
        xhr: function()
        {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload)
            {
                // For handling the progress of the upload
                myXhr.upload.addEventListener('progress', function(e)
                {
                    if (e.lengthComputable)
                    {
                        percent=Math.round((e.loaded/e.total)*100,2);
                        $('#'+progress+'-bar').css('width',percent+'%');
                        $('#'+progress+'_text').text(percent+'%');
                    }
                }, false);
            }
            return myXhr;
        }
    });


  }


      function updateview(id,docid){
        $.get('{{url('view')}}/'+id+'/edit',{
          docid:docid
        },function(){

        });
      }

      function deleteIndividualDoc(id){
        $('#'+id).attr('checked', true);
        deletedocs();
      }

      function showModal(documents){
        alert(documents);
        $('#'+adddocument).modal('show');
      }

      function movefile(){

        if($('.doclist').is(':checked')){

        }
        else{

          toastr.error('Please Select Docment(s) to Move');
          return ;
        }

        var foldid=$('#folderid').val();
        var valuearr=$('.doclist:checked').map(function() {return this.value;}).get();

        var i=0;
        //$.each(valuearr,function(index,element){
          for( i=0; i<valuearr.length; i++){
                //console.log(valuearr[i]);
                $.get('{{url('document')}}/movefile?id='+valuearr[i]+'&destination='+foldid,function(data,status,xhr){

                  if(data.status=='success'){

                    toastr.success(data.message);
                    if(i==valuearr.length){

                     setTimeout(function(){

                      window.location.reload();

                    },2000);
                   }
                 }
                 else{
                   toastr.error(data.message);

                 }
               });
              }




            }

            function deletedocs(){


             if($('.doclist').is(':checked')){

             }
             else{

              toastr.error('Please Select Docment(s) to delete');
              return ;
            }

            swal({
              title: "Are you sure?",
              text: "You will not be able to recover this file if deleted!",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
            },
            function(){

              var valuearr=$('.doclist:checked').map(function() {return this.value;}).get();
        // console.log(valuearr);
        var i=0;
        //$.each(valuearr,function(index,element){

          for( i=0; i<valuearr.length; i++){
                //console.log(valuearr[i]);
                formData ={
                  type:'delete',
                  id:valuearr[i],
                  _token:'{{csrf_token()}}'
                }

                $.post("{{url('document')}}",formData,function(data,status,xhr){

                  if(data.status=='success'){

                    toastr.success('Document Successfully Deleted');
                    if(i==valuearr.length){

                     setTimeout(function(){

                      window.location.reload();

                    },2000);
                   }
                  }
                  else{
                     toastr.error(data.message);
                    swal("Deleted!", data.message, "error");
                  }



                });
              }

            });


          }




          $(function (){


            setInterval(function(){
             $('#time').html(new Date(new Date().getTime()).toLocaleTimeString());
           },1000);



            $('.selectable-all').click(function(){

              $('.doclist').prop('checked',this.checked);



            });


            $('.table-responsive').on('show.bs.dropdown', function () {
             $('.table-responsive').css( "overflow", "inherit" );
           });

            $('.table-responsive').on('hide.bs.dropdown', function () {
             $('.table-responsive').css( "overflow", "auto" );
           })

          });



        </script>
        @endsection
