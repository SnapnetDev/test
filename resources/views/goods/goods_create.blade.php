@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/multi-step.css') }}">
@endsection
@section('content')
    <!-- MultiStep Form -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-9 col-md-7 col-lg-12 col-xl-12 text-center p-0 mt-3 mb-2">
                <div class="card px-5 pt-4 pb-0 mt-3 mb-3">
                    <h2 id="heading">Procurement Plan for Goods</h2>
                    <p>Fill all form field to go to next step</p>
                    <form id="msform" action="{{ route('goods.store') }}">
                        <!-- progressbar -->
                        <ul id="progressbar">
                            <li class="active" id="account"><strong>Procurement Identification</strong></li>
                            <li id="personal"><strong>Procurement Item</strong></li>
                            <li id="payment"><strong>Bidding Period & Evaluation </strong></li>
                            <li id="confirm"><strong> Approval </strong></li>
                            <li id="confirm"><strong> Contract FInalization </strong></li>
                        </ul>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div> <br>
                        <!-- fieldsets Contract Identification-->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Procurement Contract Description</h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 1 - 5</h2>
                                    </div>
                                </div> <label class="fieldlabels">Contract Identification *</label> <input type="text"
                                    name="contract_description" placeholder="E.g Procurement of ICT items" /> <label
                                    class="fieldlabels">Package
                                    Number: *</label> <input type="text" name="package_number"
                                    placeholder="E.g LSETF/G/NS/01/21" />

                            </div>
                            <input type="button" name="next" class="next action-button" value="Next" />
                        </fieldset>

                        <!-- fieldsets Procurement item data -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Procurement Item Data </h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 2 - 5</h2>
                                    </div>
                                </div>
                                <label class="fieldlabels">Title: *</label> 
                                <input type="text" name="title"
                                    placeholder="E.g Laptop Computers " /> 
                                    <label class="fieldlabels">Package Number: *</label> 
                                    <input type="text" name="package_number" placeholder="E.g LSETF/G/NS/01/21" />
                                    <label class="fieldlabels">Item Description: *</label>
                                    <textarea name="item_descriptition" id="item_description" cols="30" rows="5"></textarea>
                                    <label class="fieldlabels">Lot Number: *</label> 
                                    <input type="number"  name="lot_number" min="1" /> <label
                                    class="fieldlabels"> No. Of Unit: *</label>
                                    <input type="number" name="no_of_unit" min="1"/>

                                    <label class="fieldlabels">Budget Available: *</label>
                                    <input type="number" name="budget_available" min="1" step=".01"  placeholder="120000.00"/> 

                                    <label class="fieldlabels">Approval threshold: *</label>
                                    <input type="text" name="approval_threshold" placeholder="E.g <10M" />

                                    <label class="fieldlabels">Procurement Method: *</label>
                                    <input type="text" name="approval_threshold" placeholder="E.g NS" />

                                    <label class="fieldlabels">Pre or Post Qualification: *</label>
                                    <input type="text" name="pre_post_qualification" placeholder="E.g NS" />
                                    <label class="fieldlabels">Pre or Post Qualification: *</label>
                                    <input type="text" name="pre_or_post_qualification" placeholder="E.g POST" />

                                    <label class="fieldlabels">Pre or Post Qualification: *</label>
                                    <input type="text" name="prior_or_post_review" placeholder="E.g POST" />

                            </div>
                            <input type="button" name="next" class="next action-button" value="Next" />
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                        </fieldset>

                        <!-- fieldsets Bidding Periods -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Bidding & Evaluation Period Dates </h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 3 - 5</h2>
                                    </div>
                                </div> 
                                <label class="fieldlabels">Bid Rep & Submisson by MDAs: *</label>
                                <input type="date" name="bid_rep_submission_by_mdas" placeholder="" />

                                <label class="fieldlabels">PPA No Objection Date: *</label>
                                <input type="date" name="ppa_no_objection_date" placeholder="" />

                                <label class="fieldlabels">Bid Rep & Submisson by MDAs: *</label>
                                <input type="date" name="bid_rep_submission_by_mdas" placeholder="" />

                                <label class="fieldlabels">Bid Invitation Date: *</label>
                                <input type="date" name="bid_invitation_date" placeholder="" />

                                <label class="fieldlabels">Bid Closing & Opening: *</label>
                                <input type="date" name="bid_closing_and_opening" placeholder="" />

                                <label class="fieldlabels">Submission Of Bid Evaluation Report: *</label>
                                <input type="date" name="submission_of_bid_evaluation_report" placeholder="" />

                                <label class="fieldlabels">PPA Issue Certificate Of Compliance: *</label>
                                <input type="date" name="ppa_issue_certificate_of_compliance" placeholder="" />

                            </div> 
                            <input type="button" name="next" class="next action-button" value="Next" /> 
                            <input
                                type="button" name="previous" class="previous action-button-previous" value="Previous" />
                        </fieldset>

                      <!-- fieldsets Approval -->  
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Goveronor's Approval</h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 4 - 5</h2>
                                    </div>
                                </div> 

                                <label class="fieldlabels">Mr Goveronor's Approval: *</label>
                                <input type="date" name="governors_approval" placeholder="" />

                                <label class="fieldlabels">Mr Goveronor's Approval With PPA: *</label>
                                <input type="date" name="governors_approval_with_ppa" placeholder="E.g NA" />
                            </div> 
                            <input type="button" name="next" class="next action-button" value="Next" />
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                        </fieldset>

                        <!-- fieldsets Contract Finalizaton -->        
                        
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Contract Finalization </h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 5 - 5</h2>
                                    </div>
                                </div>
                               
                                <label class="fieldlabels">Contract Amount: *</label>
                                <input type="number" name="contract_amount" min="1" step=".01" placeholder="120000.00" />
                        
                                <label class="fieldlabels">Notification Of Award: *</label>
                                <input type="date" name="notificaton_of_award" placeholder="" />
                        
                                <label class="fieldlabels">Mobilization Advance Payment: *</label>
                                <input type="date" name="mobilization_advance_payment" placeholder" />
                        
                                <label class="fieldlabels">Substantial Completion / Iinstall: *</label>
                                <input type="date" name="substantial_completion_install" placeholder" />
                        
                                <label class="fieldlabels">Inspection & Final Acceptance *</label>
                                <input type="date" name="inspection_final_acceptance" placeholder="" />
                    
                            </div>
                            <input type="button" name="next" class="next action-button" value="Submit" />
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                        </fieldset>
                        
                        <fieldset>
                            <div class="form-card" >
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Finish:</h2>
                                    </div>
                                    {{-- <div class="col-5">
                                        <h2 class="steps">Step 5 - 5</h2>
                                    </div> --}}
                                </div> 
                                <br><br>
                                <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2> <br>
                                <div class="row justify-content-center">
                                    <div class="col-1"> <img src="https://i.imgur.com/GwStPmg.png" class="fit-image"> </div>
                                </div> <br><br>
                                <div class="row justify-content-center">
                                    <div class="col-7 text-center">
                                        <h5 class="purple-text text-center">You Have Successfully Signed Up</h5>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;
            var current = 1;
            var steps = $("fieldset").length;

            setProgressBar(current);

            $(".next").click(function() {

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                //Add Class Active
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 500
                });
                setProgressBar(++current);
            });

            $(".previous").click(function() {

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                //Remove class active
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset
                previous_fs.show();

                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        previous_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 500
                });
                setProgressBar(--current);
            });

            function setProgressBar(curStep) {
                var percent = parseFloat(100 / steps) * curStep;
                percent = percent.toFixed();
                $(".progress-bar")
                    .css("width", percent + "%")
            }

            $(".submit").click(function() {
                return false;
            })

        });

    </script>

@endsection
