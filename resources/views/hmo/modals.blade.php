

<div class="modal fade in modal-3d-flip-horizontal modal-info" id="newHMOModal" aria-hidden="true" aria-labelledby="addHolidayModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" action="/hmo-setup" method="post" enctype="multipart/form-data">
                    @csrf
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Create New HMO</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" name="name" class="form-control" >
                    </div>

                    <div class="form-group">
                      <h4 class="example-title">Excel Dataset</h4>
                    <input type="file" class="input-sm form-control datepicker" name="template" value=""/>
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

















    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="editHMOHospitalModal" aria-hidden="true" aria-labelledby="editHMOModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="addHolidayForm" action="/patchHospital" method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit HMO Hospital</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-13">            
                  <div class="col-xs-12"> 
                      <input type="hidden" id="hospitalId" name="hospitalId" class="form-control"/>
                      <input type="hidden" id="hmoName" name="hmoName" class="form-control"/>
                      <input type="hidden" id="hmoId" name="hmoId" class="form-control"/>
                    @csrf
                    
                    <div class="form-group">
                      <h4 class="example-title">Hospital Name</h4>
                      <input type="text" id="hospital" name="hospital" class="form-control"/>
                    </div>
                    
                    <div class="form-group">
                      <h4 class="example-title">Band</h4>
                      <input autocomplete="off" type="text" id="band" name="band" class="form-control"/>
                    </div>
                    
                    <div class="form-group">
                      <h4 class="example-title">Category</h4>
                      <input autocomplete="off" type="text" id="category" name="category" class="form-control"/>
                    </div>
                    
                    <div class="form-group">
                      <h4 class="example-title">Contact</h4>
                      <input type="text" id="contact" name="contact" class="form-control"/>
                    </div>
                    
                    <div class="form-group">
                      <h4 class="example-title">Address</h4>
                      <textarea id="address" name="address" class="form-control"></textarea>
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


















