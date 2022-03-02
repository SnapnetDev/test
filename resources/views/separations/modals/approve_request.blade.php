<div class="modal fade in modal-3d-flip-horizontal modal-info" id="approveSeparationModal" aria-hidden="true" aria-labelledby="approveSeparationModal" role="dialog" >
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="approveSeparationForm" enctype="multipart/form-data">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Approve Separation (Stage- {{$approval->stage->name}})</h4>
	        </div>
            <div class="modal-body">

            @csrf

          <div class="form-group">
            <label for="">Approve/Reject</label>
            <select class="form-control" id="approval" name="approval"  data-allow-clear="true">

              <option value="1">Approve</option>
              <option value="2">Reject</option>
            </select>
          </div>
                <div class="form-group">
                    <label for="">Approval List Items</label><small>Please select the items the employee is cleared for</small>

                    <select class="form-control select2" id="approval" name="approval_list[]"  style="width: 100%;" multiple>
                        @foreach($sals as $list_item)
                        <option value="{{$list_item->id}}">{{$list_item->name}}</option>
                        @endforeach
                    </select>
                </div>

          <div class="form-group">
            <label for="">Comment</label>
            <textarea class="form-control" id="comment" name="comment" style="height: 100px;resize: none;" placeholder="Comment" ></textarea>
          </div>
{{--                <div class="card-block">--}}
{{--                    <div class="card-body" id="view_rated">--}}

{{--                        <div id="signArea" >--}}
{{--                            <h2 class="tag-ingo">Put signature below,</h2>--}}
{{--                            <div class="sig sigWrapper" id="holder" style="height:auto;">--}}
{{--                                <div class="typed"></div>--}}
{{--                                <canvas class="sign-pad" id="sign-pad" width="300" height="100"></canvas>--}}
{{--                            </div>--}}
{{--                        </div>--}}



{{--                        <div class="sign-container">--}}
{{--                            @php--}}
{{--                                $image_list = glob("./doc_signs/*.png");--}}
{{--                                foreach($image_list as $image)--}}
{{--                                {--}}
{{--                                  //echo $image;--}}
{{--                            @endphp--}}
{{--                            <img src="@php echo $image; @endphp" class="sign-preview" />--}}
{{--                            @php--}}
{{--                                }--}}
{{--                            @endphp--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
          <input type="hidden" name="type" value="save_approval">
          <input type="hidden" name="separation_approval_id" id="approval_id" >

            </div>
            <div class="modal-footer">
              <div class="col-xs-12">

                  <div class="form-group">

                    <button type="submit" class="btn btn-info pull-left">Approve/Reject</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>
