 <div class="modal fade" id="changeQuarter" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
 	<div class="modal-dialog modal-center">
 		<div class="modal-content">
 			<div class="modal-header">
 				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
 					<span aria-hidden="true">×</span>
 				</button>
 				<h4 class="modal-title">Change Quarter</h4>
 			</div>
 			<div class="modal-body">
 				<div class="form-group" >  

 					<label for="ratingStar">Select Quarter</label>
 					<select class="form-control rate_quarter" id="">
 						<option value="0">-Select Quarter-</option>
 						@for($i = 1; $i <= $employee->getquarter(); $i++)
 						<option value="{{$i}}" {{isset($_GET['quarter']) && $_GET['quarter']==$i ? 'selected' :'' }}>{{ $employee->quarterName($i) }} Quarter</option>                    @endfor
 					</select>
 				</div>
 			</div>
 			<div class="modal-footer">
 				<button type="button" class="btn btn-default btn-pure waves-effect" data-dismiss="modal">Close</button> 
 			</div>
 		</div>
 	</div>
 </div>
 