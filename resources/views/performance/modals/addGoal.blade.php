        <!--JUST ADDED -->
        @php $disable='' @endphp
        <div class="modal fade modal-3d-flip-horizontal" id="addGoal" aria-labelledby="examplePositionSidebar" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
         <div class="modal-dialog modal-sidebar modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title">Add  {{Auth::user()->id==$employee->id ? '' :'Stretch' }} Goal(s)</h4>
            </div>

            <div class="modal-body">
              <form class="" id="goalform">
                  @if(Auth::user()->id==$employee->id)

                 <b>Goal Type</b>:<br><br>
                        <select class="form-control" id="goalType">
                          <option value="">-Select Goal Type-</option>
                          <option value="3">Individual Development Plan</option>
                          <option value="4">Career Aspiration</option>
                        </select>
                        <br>
                 @endif
                <b>Objectives</b>:<br><br>

                <textarea {{$disable}} data-provide="markdown" data-iconlibrary="fa" id="objective" class="md-input" rows="5" style="width: 100%; resize: none;" ></textarea><br>

                <b>Commitment</b>:<br><br>
                <textarea {{$disable}} data-provide="markdown" data-iconlibrary="fa"  id="commitment" class="md-input" rows="5" style="width: 100%; resize: none;" ></textarea>


              </form>
              <div class="modal-footer">
               <div  style="margin-right:6%;">
                <button {{$disable}} type="button" id="addesc" class=" btn btn-squared  btn-success" onclick="addaspiration()"><i class="wb wb-plus"></i>&nbsp;&nbsp;Add </button>
                <button type="button" class="btn btn-warning btn-squared " data-dismiss="modal"><i class="wb wb-close"></i>&nbsp;&nbsp;Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--ENDED -->
    </div>

    <script type="text/javascript">
 
        function addaspiration(){

          objective=$('#objective').val();
          commitment=$('#commitment').val();
          if(objective=='' || commitment==''){
            return toastr.warning('Some Fields Empty');
          }
            @if(Auth::user()->id==$employee->id)
              goalType=$('#goalType').val();
              if(goalType==''){ return toastr.error('Please Select Goal Type'); }
            @endif
          formData={
            objective:objective,
            commitment,commitment,
            _token:'{{csrf_token()}}',
            emp_id:'{{$employee->id}}',
            type:'addStretch'
              @if(Auth::user()->id==$employee->id)
             , goalType:goalType
            @endif
          };

          $.post('{{url('performances')}}',formData,function(data){
                          if(data.status=='success'){
                            setTimeout(function(){
                              window.location.reload();
                            },2000);
                            return toastr.success(data.message);
                          }
                          return  toastr.error(data.message); 

                                  })

        }
 
    </script>