@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Company Documents')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Company Documents')}}</li>
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
      	<div class="row" data-plugin="matchHeight" data-by-row="true">
  <!-- First Row -->


  <!-- End First Row -->
  {{-- second row --}}
  <div class="col-ms-12 col-xs-12 col-md-12">
    <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Company Documents</h3>
                        <div class="panel-actions">

                            <button class="btn btn-info" data-toggle="modal" data-target="#addDocumentModal">Add Document</button>

                        </div>
		            	</div>
		            <div class="panel-body">
		            	<div class="table-reponsive">
         <table class="table table striped">
         	<thead>
         		<tr>
            <th>Title</th>
         		<th>Description</th>
            <th>Created By</th>
         		<th>Action</th>
         	</tr>
         	</thead>
         	<tbody>


          @foreach($documents as $document)

              <tr>


            <td>{{$document->title}}</td>
            <td>{{$document->description}}</td>
             <td>{{$document->user?$document->user->name:"Not stated"}}</td>

              <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                    data-toggle="dropdown" aria-expanded="false">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                        <a data-id="{{ $document->id }}" style="cursor:pointer;"class="dropdown-item view-approval" id="{{$document->id}}" onclick="deleted(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Document</a>



                            <a style="cursor:pointer;" class="dropdown-item"
                               id="{{$document->id}}" onclick="edit(this.id)"><i
                                    class="fa fa-upload" aria-hidden="true"></i>&nbsp;&nbsp;Edit
                                Document</a>
                            @if($document->file!='')
                                <a style="cursor:pointer;"class="dropdown-item" id="" href="{{url('company_documents/download?document_id='.$document->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Document</a>
                            @endif

                    </div>
                </div>
              </td>

          </tr>

        @endforeach


         	</tbody>

         </table>
         </div>


      </div>
  </div>

    </div>

	</div>
</div>
</div>
  <!-- End Page -->
   @include('company_documents.modals.adddocument')
@include('company_documents.modals.editdocument')
   {{-- Leave Request Details Modal --}}

@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">
  	  $(document).ready(function() {
    $('.input-daterange').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd'
});

    $(document).on('submit','#addDocumentForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('company_documents')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		             toastr.success("Changes saved successfully",'Success');
		            $('#addDocumentModal').modal('toggle');
                location.reload();

		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr.error(valchild[0]);
							});
							});
		        }
		    });

		});
          $(document).on('submit','#EditDocumentForm',function(event){
              event.preventDefault();
              var form = $(this);
              var formdata = false;
              if (window.FormData){
                  formdata = new FormData(form[0]);
              }
              $.ajax({
                  url         : '{{url('company_documents')}}',
                  data        : formdata ? formdata : form.serialize(),
                  cache       : false,
                  contentType : false,
                  processData : false,
                  type        : 'POST',
                  success     : function(data, textStatus, jqXHR){

                      toastr.success("Changes saved successfully",'Success');
                      $('#editDocumentModal').modal('toggle');
                      location.reload();

                  },
                  error:function(data, textStatus, jqXHR){
                      jQuery.each( data['responseJSON'], function( i, val ) {
                          jQuery.each( val, function( i, valchild ) {
                              toastr.error(valchild[0]);
                          });
                      });
                  }
              });

          });
    });

      function deleted(document_id){
          alertify.confirm('Are you sure you want to delete this Document ?', function () {


              $.get('{{ url('company_documents/delete_company_document') }}',{document_id:document_id},function(data){
                  if (data=='success') {
                      toastr["success"]("Document deleted successfully",'Success');
                      location.reload();
                  }else{
                      toastr["error"]("Error deleting Document ",'Success');
                  }
              });
          }, function () {
              alertify.error('Document not deleted');
          });
      }
      function edit(document_id) {
          $.get('{{ url('company_documents/get_company_document') }}',{document_id:document_id},function(data){


              $('#editcdtitle').val(data.title);
              $('#editcddescription').val(data.description);
              $('#editcdid').val(data.id);
          });
          $('#editDocumentModal').modal();

      }

  </script>
@endsection
