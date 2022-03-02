<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addUnionMembersBulkModal" aria-hidden="true" aria-labelledby="addUnionMembersBulkModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Add Bulk Union Members</h4>
          </div>
          
            <div class="modal-body"> 
            <form class="form-horizontal" id="uploadUnionMembersBulkForm"  method="POST" enctype="multipart/form-data">
             
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Upload Excel Sheet</h4>
                      <a href="{{url('/userprofile/download_union_members_template')}}">Download excel template here</a>
                      <input type="file" name="union_template" class="form-control">
                      <input type="hidden" name="type" value="import_union_users">
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