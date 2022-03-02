<!--- Add Subject modal start -->
  <div class="modal fade in modal-3d-flip-horizontal modal-primary" id="editWeightModal" aria-hidden="true" aria-labelledby="editWeightModal"
  role="dialog" tabindex="-1">
   
    <div class="modal-dialog ">
      <form class="form-horizontal" id="editWeightForm" role="form" method="POST">
        <div class="modal-content">        
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
          <h4 class="modal-title" id="training_title">Edit Weight Metric</h4>
        </div>
        <div class="modal-body">         
               
          <div class="row row-lg col-xs-12">            
            <div class="col-xs-12"> 
              
              
              <div class="form-group">
                <h4 class="example-title">Weight Percentage</h4>
             
                <input type="text" class="form-control" id="editwpercentage" name="percentage" placeholder="Weight Percentage" value="" >
              </div>
              <div class="form-group">
                <h4 class="example-title">Company</h4>
             
                <input type="text" class="form-control" id="editwcompany"  value="" disabled>
              </div>
              <div class="form-group">
                <h4 class="example-title">Department</h4>
             
                <input type="text" class="form-control" id="editwdepartment"  value="" disabled>
              </div>
              <div class="form-group">
                <h4 class="example-title">Performance Category</h4>
             
                <input type="text" class="form-control" id="editwperformancecategory"  value="" disabled>
              </div>  
               <div class="form-group">
                <h4 class="example-title">Perspective</h4>
             
                <input type="text" class="form-control" id="editwperspective"  value="" disabled>
              </div>
              <input type="hidden" id="editwid" name="weight_id">

              <input type="hidden" name="type" value="editweight">
                
              
            </div>
            <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
          </div>        
        </div>
        <div class="modal-footer">
          <div class="col-xs-12">
              <!-- Example Textarea -->
              <div class="form-group">
               
                {{ csrf_field() }}
                <div class="text-xs-left"><span class="no-left-padding" id="btn_div"><input type="submit" class="btn btn-primary waves-effect" id="sugtraining_btn" value="Save" ></span>
                <span class="no-left-padding"><input type="button" class="btn btn-default waves-effect" value="Cancel"  data-dismiss="modal"></span></div>
              </div>
              <!-- End Example Textarea -->
            </div>
         </div>
       </div>
      </form>
    </div>
  </div>
  <!-- Add subject modal end -->