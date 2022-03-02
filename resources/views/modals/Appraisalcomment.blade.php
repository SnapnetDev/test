<div class="modal fade in" id="commentModal" aria-labelledby="examplePositionSidebar" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Comment</h4>
      </div>
      <div class="modal-body">
        <!--    <img class="img-rounded img-bordered img-bordered-primary" width="150" height="150" src="http://hcmatrix/storage/upload/avatar.jpg"> -->
      <div class="form-group" >  

          <label for="ratingStar">Select Quarter</label>
                 <select class="form-control" id="rate_quarter">
                    <option value="0">-Select Quarter-</option>
                        @for($i = 1; $i <= $employee->getquarter(); $i++)
                     <option value="{{$i}}">{{ $employee->quarterName($i) }} Quarter</option>                    @endfor
                        </select>
                  </div>
        <br>
        <div class="form-group" id="rate_quarterPack">
          <label for="ratingStar">Rating</label>
          <input type="number" max="5" min="1" class="form-control" name="rating" id="ratingStar" value="">
        </div>
        <div class="form-group">
          <label for="commentMsg">Comment</label>
          <textarea class="form-control" id="commentMsg" style="height: 200px;resize: none;" placeholder="Enter comments and appraisal here."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-block" onclick="saverating()">
          <i class="fa fa-star"></i> 
          <i class="fa fa-star"></i> 
          Add Comment 
          <i class="fa fa-star"></i> 
          <i class="fa fa-star"></i>
        </button>
        <!--<button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>-->
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  // add comfirm 
            function saverating()
            {
        
              var token = '{{csrf_token()}}';
              var goalid = sessionStorage.getItem('pilotid');
              var empid = sessionStorage.getItem('employeeid');
              var comment = $('#commentMsg').val();
              var score = $('#ratingStar').val();
              var quarter = $("#rate_quarter").val();
              if(score==''){
                score=0;
              }
              var formData = {'_token':token, 'rating':score, 'goalid':goalid, 'empid':empid, 'comment':comment,quarter:quarter,'type':'saveComment'};

              if(quarter == 0)
              {
                toastr.warning("Please select quarter before rating.");
                
                return false;
              }
                      swal({
              title: "Warning!",
              text: "Are you Sure you want to continue ?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#f96868",
              confirmButtonText: "Yes!",
              cancelButtonText: "No, Go back.",
              closeOnConfirm: false,
              closeOnCancel: false
            }, function(isConfirm){
              if(isConfirm)
              {
                 $.post('{{url('performance')}}', formData, function(data,status,xhr){ 
              
                if(data.status=='success')
                {
                  swal('Success','Rating Successful.','success');
                  toastr.success('Rating Successful.'); 
                  window.location.reload();
                }
                else
                {
                  swal('Error',data.message,'error');
                  toastr.warning(data.message);
                  
                }
              });
              }
              else{
                  swal('Error','Operation Cancelled','error');
              }
            });
             
            }
</script>