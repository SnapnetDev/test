{{--modal start--}}

<div id="create-loan-type-form" class="modal fade" role="dialog">
    <div class="modal-dialog modal-info modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Loan Type</h4>
            </div>
            <div class="modal-body" >


                <div id="form-capture">


                    <div class="row">

                        <div class="col-md-6"></div>
                        <div class="col-md-6"></div>

                    </div>



                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Name</h4>
                                <input type="text" name="name" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Required Duration In Months</h4>
                                <input type="text" name="required_duration_in_months" class="form-control" />
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Comparator</h4>

                                <label for="">
                                    Duration Comparator

                                    <select name="duration_comparator" id="">
                                        <option value=">="> > </option>
                                        <option value="<="> < </option>
                                        {{--<option value=">="> >= </option>--}}
                                        {{--<option value="<="> <= </option>--}}
                                    </select>

                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Confirmation</h4>
                                <label for="">Requires Confirmation
                                  <input type="checkbox" name="requires_confirmation" />
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Multiplier Index</h4>
                                <input type="text" name="multiplier_index" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Select Salary Component</h4>
                                <select name="pace_salary_component_id" class="form-control">
                                    <option value="loan">Use Loan Amount</option>
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Repayment Period (In Months)</h4>
                                <input type="text" name="repayment_period" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Interest Rate</h4>
                                <input type="text" name="interest_rate" class="form-control" />
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        {{--ajax-get-component/SpecificSalaryComponentType?company_id=8--}}
                        <div class="col-md-6">
                            <div class="form-group" >
                                <h4>Required Grade</h4>
                                <div id="grade"></div>
                                {{--<select name="open_to_grade_id" class="form-control">--}}
                                    {{--<option value="any">Any Grade</option>--}}
                                {{--</select>--}}
                            </div>
                        </div>
                        {{--<div class="col-md-6"></div>--}}


                        <div class="col-md-6">
                            <div class="form-group">

                                <h4>Specific Salary Component Type</h4>
                                <div id="specific_salary_component_type_id"></div>
                                {{--<select name="open_to_grade_id" class="form-control">--}}
                                {{--<option value="any">Any Grade</option>--}}
                                {{--</select>--}}
                            </div>
                        </div>


                    </div>
                    <div class="row">

                        <div class="col-md-6"></div>
                        <div class="col-md-6"></div>

                    </div>
                    <div class="row">

                        <div class="col-md-6"></div>
                        <div class="col-md-6"></div>

                    </div>


                    {{--<div class="form-group">--}}
                        {{--<button type="button" class="btn btn-sm btn-success">Add</button>--}}
                    {{--</div>--}}

                </div>


            </div>
            <div class="modal-footer">
                <button id="create-loan-type-button" type="button" class="btn btn-success btn-sm">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

{{--modal stop--}}
