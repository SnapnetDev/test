<!--- Add Subject modal start -->
  <div class="modal fade in modal-3d-flip-horizontal modal-primary" id="editBehavioralSubMetricModal" aria-hidden="true" aria-labelledby="editBehavioralSubMetricModal"
  role="dialog" tabindex="-1">
   
    <div class="modal-dialog modal-lg">
      <form class="form-horizontal" id="editBehavioralSubMetricForm" role="form" method="POST">
        <div class="modal-content">        
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
          <h4 class="modal-title" id="training_title">Edit Behavioral Sub Metric</h4>
        </div>
        <div class="modal-body">         
               
          <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4 class="example-title">Objective / Strategic Focus</h4>
                                <textarea name="objective" class="form-control" id="editbsm_objective"  required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4 class="example-title">KPI/Measure</h4>
                                <textarea name="measure" class="form-control" id="editbsm_measure"  required></textarea>
                              </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4 class="example-title">Weighting</h4>
                                <input required type="text" name="weighting" id="editbsm_weighting" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="example-title" for="status">Status</label>

                                <select required name="status" style="width:100%;" id="editbsm_status" class="form-control " >
                                    <option  value='1'>Active</option>
                                    <option  value='0'>Inactive</option></select>
                            </div>
                        </div>
                    </div>


                    <input type="hidden" name="type" value="behavioral_sub_metric"> 
                    <input type="hidden" name="bsm_id" id="editbsm_id">    
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