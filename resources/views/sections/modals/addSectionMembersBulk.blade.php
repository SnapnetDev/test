<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addSectionMembersBulkModal" aria-hidden="true" aria-labelledby="addSectionMembersBulkModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Add Bulk Section Members</h4>
          </div>
          
            <div class="modal-body"> 
            <form class="form-horizontal" id="uploadSectionMembersBulkForm"  method="POST" enctype="multipart/form-data">
             
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Upload Excel Sheet</h4>
                      <a href="{{url('/sections/download_section_members_template')}}">Download excel template here</a>
                      <input type="file" name="section_template" class="form-control">
                      <input type="hidden" name="type" value="import_section_users">
                    </div>
                    <div class="form-group">
                    <button type="submit" class="btn btn-info pull-left ">Upload</button>
                    </div>
                  
                  
            </form>
            
                <br>
                <br>   
            </div>
           
             
         </div>
        
      </div>
    </div>