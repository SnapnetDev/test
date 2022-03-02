<form  action="{{ route('process.action.command',['update-training-budget']) }}" method="post">

    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}" />

    <div id="update-training-budget{{ $item->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Training Budget</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Select Grade
                                    </label>
                                    <select data-value="{{ $item->grade_id }}" name="grade_id" id="" class="form-control">
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}">{{ $grade->level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Name Of Training
                                    </label>
                                    <input value="{{ $item->training_budget_name }}" type="text" name="training_budget_name" class="form-control" placeholder="Name Of Training" />
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Allocation Total
                                    </label>
                                    <input value="{{ $item->allocation_total }}" type="text" name="allocation_total" class="form-control" placeholder="Allocation Total" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Year Of Allocation
                                    </label>
                                    <label for="" class="form-control">
                                        {{ $item->year_of_allocation }}
                                    </label>

                                </div>
                            </div>
                        </div>




                    </div>



                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</form>
