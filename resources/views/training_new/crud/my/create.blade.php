 <div id="budget-create" class="modal fade" role="dialog">
     <form action="" method="post" enctype="multipart/form-data">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Budget</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Select Grade
                                    </label>
                                    <select name="grade_id" class="form-control" id="">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Budget Name
                                    </label>
                                    <input  autocomplete="off"  type="text" name="training_budget_name" class="form-control" placeholder="Budget Name" />
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Allocation Total
                                    </label>
                                    <input  autocomplete="off"  type="text" name="allocation_total" class="form-control" placeholder="Allocation Total" />
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Year Of Allocation
                                    </label>
                                    <input readonly value="{{ date('Y') }}"  type="text" name="year_of_allocation" class="form-control" placeholder="Year Of Allocation" />
                                </div>
                            </div>



                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        OR Upload Excel (<a href="{{ asset('budget_template.xlsx') }}">Download Template</a>)
                                    </label>
                                    <input readonly  type="file" name="excel_file" class="form-control" placeholder="Excel File" />
                                </div>
                            </div>




                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                </div>
                            </div>


                        </div>


                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
     </form>
</div>