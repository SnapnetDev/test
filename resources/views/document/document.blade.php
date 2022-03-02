 @extends('layouts.master')
@section('stylesheets')
{{--  <link rel="stylesheet" href="{{asset('assets/css/site.min.css')}}">--}}
  <!-- Plugins -->
  <link rel="stylesheet" href="{{asset('global/vendor/switchery/switchery.css')}}">
  <link rel="stylesheet" href="{{asset('assets/examples/css/apps/media.css')}}">


  <!-- Fonts -->
{{--  <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.css')}}">--}}
@endsection
@section('content')
  <div class="page bg-white" style="">
    <!-- Media Sidebar -->
    <div class="page-aside">
      <div class="page-aside-switch">
        <i class="icon md-chevron-left" aria-hidden="true"></i>
        <i class="icon md-chevron-right" aria-hidden="true"></i>
      </div>
      <div class="page-aside-inner page-aside-scroll">
        <div data-role="container">
          <div data-role="content">
            <section class="page-aside-section">
              <h5 class="page-aside-title">Folder List</h5>
                @if(count($folders)>0)
          	@foreach($folders as $folder)
              <div class="list-group">
                <a class="list-group-item" href="{{url('document')}}/files?folder_id={{$folder->id}}&foldername={{$folder->docname}}"><i class="icon md-view-dashboard" target="_blank" aria-hidden="true"></i>{{$folder->docname}}</a>

              </div>

              @endforeach

              @endif
            </section>

          </div>
        </div>
      </div>
    </div>
    <!-- Media Content -->
    <div class="page-main">
      <!-- Media Content Header -->
      <div class="page-header">
        <h1 class="page-title">Document Overview  </h1>
        <button data-toggle="modal" data-target="#addFolder" type="button" class="btn btn-icon btn-info waves-effect"><i class="icon md-plus" aria-hidden="true"></i></button>
        <div class="page-header-actions">

          <form method="get" action="{{url('document')}}/files">
            <div class="input-search input-search-dark">
              <i class="input-search-icon md-search" aria-hidden="true"></i>

                  <input type="hidden" name="foldername"  value="All files">

              <input type="text" class="form-control" name="q" >
            </div>
          </form>


        </div>
      </div>
      <!-- Media Content -->
      <div id="mediaContent" class="page-content page-content-table" data-plugin="asSelectable">
        <!-- Actions -->
        <div class="page-content-actions">
          <div class="pull-xs-right">
            <div class="btn-group media-arrangement" role="group">
              <button class="btn btn-default active" id="arrangement-grid" type="button"><i class="icon md-view-module" aria-hidden="true"></i></button>
              <button class="btn btn-default" id="arrangement-list" type="button"><i class="icon md-view-list" aria-hidden="true"></i></button>
            </div>
          </div>
          <div class="actions-inner">
            <div class="checkbox-custom checkbox-primary checkbox-lg">
              <input type="checkbox" id="media_all" class="selectable-all">
              <label for="media_all"></label>
            </div>
          </div>
        </div>
        <!-- Media -->
        <div class="media-list is-grid p-b-50" data-plugin="animateList" data-animate="fade"
        data-child="li">
          <ul class="blocks blocks-100 blocks-xxl-6 blocks-xl-6 blocks-lg-6 blocks-md-3 blocks-sm-3"
          data-plugin="animateList" data-child=">li">
          @if(count($folders)>0)
          	@foreach($folders as $folder)
            <li>
              <div class="media-item">
              <a href="{{url('document')}}/files?folder_id={{$folder->id}}&foldername={{$folder->docname}}">
                <div class="checkbox-custom checkbox-primary checkbox-lg">
                  <input type="checkbox" class="selectable-item" id="media_1" />
                  <label for="media_1"></label>
                </div>
                <div class="image-wrap">
                  <img   class="image img-rounded" src="../../../../global/photos/placeholder.png"
                  alt="...">
                </div>
                <div class="info-wrap">
                  <div class="dropdown">
                    <span class="icon md-settings" data-toggle="dropdown" aria-expanded="false" role="button"
                    data-animation="scale-up"></span>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                      <a class="dropdown-item" href="javascript:void(0)" onclick="editFolder({{$folder->id}},'{{$folder->docname}}')" data-toggle="modal" data-target="#editFolder"><i class="icon md-edit" aria-hidden="true"></i>Rename</a>
                      <a class="dropdown-item" href="javascript:void(0)" onclick="deleteFolder({{$folder->id}})"><i class="icon md-delete" aria-hidden="true"></i>Delete</a>
                    </div>
                  </div>
                  <div class="title" style="    margin-left: 35%;">{{$folder->docname}}</div>
                  <!-- <div class="time">1 minutes ago</div> -->
                  <div class="media-item-actions btn-group">
                    <button class="btn btn-icon btn-pure btn-default" data-original-title="Edit" data-toggle="tooltip"
                    data-container="body" data-placement="top" data-trigger="hover"
                    type="button">
                      <i class="icon md-edit" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-icon btn-pure btn-default" data-original-title="Download"
                    data-toggle="tooltip" data-container="body" data-placement="top"
                    data-trigger="hover" type="button">
                      <i class="icon md-download" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-icon btn-pure btn-default" data-original-title="Delete"
                    data-toggle="tooltip" data-container="body" data-placement="top"
                    data-trigger="hover" type="button">
                      <i class="icon md-delete" aria-hidden="true"></i>
                    </button>
                  </div>
                </div>
              </a>
              </div>
            </li>

            @endforeach
           @endif
          </ul>
        </div>
      </div>
    </div>
  </div>



<div class="modal fade in" id="editFolder" aria-labelledby="examplePositionCenter" role="dialog" >
                          <div class="modal-dialog modal-center">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Rename Folder</h4>
                              </div>
                              <div class="modal-body">
                                <form id="renameFolder">
                                <input type="hidden"  name="id">
                                <input type="hidden"  name="type" value="renameFolder">
                                <input type="text" class="form-control" name="docname">

                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-primary waves-effect">Save</button>
                                </form>
                                <button type="button" class="btn btn-default btn-pure waves-effect" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>

<div class="modal fade in" id="addFolder" aria-labelledby="examplePositionCenter" role="dialog" >
                          <div class="modal-dialog modal-center">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title">Add Folder</h4>
                              </div>
                              <div class="modal-body">
                                <form id="addFolderForm">
                                  <input type="hidden" name="type" value="addFolder">
                                  <input type="hidden" name="id" value="0">
                                <input type="text" class="form-control" name="docname">

                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-primary waves-effect">Save</button>
                                </form>
                                <button type="button" class="btn btn-default btn-pure waves-effect" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>


  @endsection
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- Core  -->

  <!-- Plugins -->



  <script src="{{asset('global/vendor/switchery/switchery.min.js')}}"></script>
  <script src="{{asset('global/vendor/intro-js/intro.js')}}"></script>
  <script src="{{asset('global/vendor/screenfull/screenfull.js')}}"></script>
  <script src="{{asset('global/vendor/slidepanel/jquery-slidePanel.js')}}"></script>
  <!-- Scripts -->
  <!-- Config -->

  <!-- Page -->
  <script src="{{asset('global/js/Plugin/switchery.js')}}"></script>
  <script src="{{asset('assets/js/App/Media.js')}}"></script>
{{--  <script src="{{asset('assets/examples/js/apps/media.js')}}"></script>--}}


  <script type="text/javascript">
    function editFolder(id,name){
      $('input[name="id"]').val(id);
      $('input[name="docname"]').val(name);
    }

    $(function(){
      $('#renameFolder').submit(function(e){
        e.preventDefault();
        processForm('renameFolder',"{{url('document')}}",'');
      })

      $('#addFolderForm').submit(function(e){
        e.preventDefault();
        processForm('addFolderForm',"{{url('document')}}",'');
      })
    })

function deleteFolder(id){

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
                  if (willDelete) {
                      $.get('{{url('document')}}/deleteFolder?id='+id,function(data,status,xhr){
                          if(data.status=='success'){

                              swal("Success!",data.message,'Success', {
                                  icon: "success",
                              });
                              toastr.success(data.message);
                              setTimeout(function(){
                                  window.location.reload();
                              },2000);
                              return;
                          }
                          swal("Error!",data.message,'error', {
                              icon: "error",
                          });
                          return toastr.error(data.message);


                      });

                  } else {
                      swal("Operation Cancelled");
                  }
              });

}

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
  </script>

@endsection
