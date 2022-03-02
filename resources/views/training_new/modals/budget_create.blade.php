<form action="{{ route('process.action.command',['create-training-budget']) }}" method="post" enctype="multipart/form-data">

    @csrf

    <div id="create-training-budget" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Training Budget</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Select Grade
                                    </label>
                                    <select name="grade_id" id="" class="form-control">
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
                                    <input  type="text" name="training_budget_name" class="form-control" placeholder="Name Of Training" />
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Allocation Total
                                    </label>
                                    <input type="text" name="allocation_total" class="form-control" placeholder="Allocation Total" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Year Of Allocation
                                    </label>
                                    <label for="" class="form-control">
                                        {{ date('Y') }}
                                    </label>

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Upload Excel Document
                                    </label>
                                    <input type="file" class="form-control" name="excel_file"  placeholder="Excel File" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a href="{{ asset('budget_template.xlsx') }}">Download Excel Template</a>
                            </div>
                        </div>



                    </div>



                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm">Create</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</form>
