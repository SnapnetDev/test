<div class="modal fade in modal-3d-flip-horizontal modal-info" id="uploadEmeasuresModal" aria-hidden="true" aria-labelledby="uploadEmeasuresModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">

	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Upload Evaluation Template</h4>
	        </div>
            <div class="modal-body">

        <form class="form-horizontal" id="uploadEmeasuresForm" enctype="multipart/form-data" >
            @csrf
            <a href="{{url('bscsettings/download_template')}}">Download Template for upload</a>
          <div class="form-group">
            <label for="">Template</label>
            <input type="file" name="template" class="form-control" required>
          </div>
          <input type="hidden" name="type" value="import_emeasures">
           <input type="hidden" name="evaluation_id" value="{{$evaluation->id}}">
                <div class="col-xs-12">

                    <div class="form-group">

                        <button type="submit" class="btn btn-info pull-left">Upload</button>

                    </div>
                    <!-- End Example Textarea -->
                </div>
          </form>
                <div style="text-align: center;">-- or --</div>
                <div class="form-group">
                    <label for="">Select your prepared template</label>
                    <select name="" id="" onchange="useDepartmentTemplate(this.value);" class="form-control">
                        <option value="0">Select a template</option>
                        @foreach($templates as $template)
                        <option value="{{$template->id}}">{{$template->title}}</option>
                            @endforeach
                    </select>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
             </div>
	       </div>

	    </div>
	  </div>