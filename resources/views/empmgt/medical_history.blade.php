
<style>
    .row.is-flex {
        display: flex;
        flex-wrap: wrap;
    }
    .row.is-flex > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }

    /*
    * And with max cross-browser enabled.
    * Nobody should ever write this by hand.
    * Use a preprocesser with autoprefixing.
    */
    .row.is-flex {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }

    .row.is-flex > [class*='col-'] {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
    }
    .row.make-columns {
        -moz-column-width: 19em;
        -webkit-column-width: 19em;
        -moz-column-gap: 1em;
        -webkit-column-gap:1em;
    }

    .row.make-columns > div {
        display: inline-block;
        padding:  .5rem;
        width:  100%;
    }

</style>

<div class="tab-pane animation-slide-left"  id="medical_history" role="tabpanel">
    <br>
    <div class="row is-flex">
        <div class="col-lg-4 masonry-item">
            <div class="card card-shadow">
                <div class="card-header bg-blue-600 white p-15 clearfix">
                    <div class="font-size-18">Current Medical Conditions
                        <a  href="#cmcCollapse" type="button"
                            class="btn btn-sm btn-icon btn-inverse btn-round"
                            data-toggle="collapse" style="float: right;">
                          <span data-toggle="tooltip" data-original-title="Add Current Medical Condition">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                          </span>
                        </a>
                        <div class="collapse" id="cmcCollapse">
                            <div class="card card-default">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Name</h4>
                                                <input type="text" name="cmc_name" id="cmc_name" class="form-control" autocomplete="off" placeholder="Medical Condition Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Date</h4>
                                                <input type="text" class="form-control datepair-date datepair-start"
                                                       data-plugin="datepicker" name="cmc_date" id="cmc_date" autocomplete="off"
                                                       placeholder="MM/DD/YY">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button onclick="saveMedicalHistory('cmc')" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Those that you are currently experiencing and/or receiving treatment for (such as diabetes, high blood pressure, migraine)</p>
                </div>
                <ul class="list-group list-group-bordered mb-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Conditions</th>
                            <th>Date of Onset</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($medical_history->current_medical_conditions as $cmc)
                            <tr>
                                <td> {{ $cmc['name'] }} </td>
                                <td>{{ $cmc['date'] }}</td>
                                <td><a onclick="return deleteMedicalHistory('cmc',{{$cmc['id']}})" style="color: red">Del</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </ul>
            </div>
        </div>

        <div class="col-lg-4 masonry-item">
            <div class="card card-shadow">
                <div class="card-header bg-blue-600 white p-15 clearfix">
                    <div class="font-size-18">Past Medical Conditions
                        <a  href="#pmcCollapse" type="button"
                            class="btn btn-sm btn-icon btn-inverse btn-round"
                            data-toggle="collapse" style="float: right;">
                          <span data-toggle="tooltip" data-original-title="Add Past Medical Conditions">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                          </span>
                        </a>
                        <div class="collapse" id="pmcCollapse">
                            <div class="card card-default">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Name</h4>
                                                <input type="text" name="pmc_name" id="pmc_name" class="form-control" autocomplete="off" placeholder="Medical Condition Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Date</h4>
                                                <input type="text" class="form-control datepair-date datepair-start"
                                                       data-plugin="datepicker" name="pmc_date" id="pmc_date" autocomplete="off"
                                                       placeholder="MM/DD/YY">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button onclick="saveMedicalHistory('pmc')" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Those that you have had in the past but have recovered from (such as childhood asthma, gestational diabetes)</p>
                </div>
                <ul class="list-group list-group-bordered mb-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Conditions</th>
                            <th>Date of Onset</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($medical_history->past_medical_conditions as $pmc)
                            <tr>
                                <td> {{ $pmc['name'] }} </td>
                                <td>{{ $pmc['date'] }}</td>
                                <td><a onclick="return deleteMedicalHistory('pmc',{{$pmc['id']}})" style="color: red">Del</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </ul>
            </div>
        </div>

        <div class="col-lg-4 masonry-item">
            <div class="card card-shadow">
                <div class="card-header bg-blue-600 white p-15 clearfix">
                    <div class="font-size-18">Surgeries/Hospitalizations
                        <a  href="#shCollapse" type="button"
                            class="btn btn-sm btn-icon btn-inverse btn-round"
                            data-toggle="collapse" style="float: right;">
                          <span data-toggle="tooltip" data-original-title="Add Surgeries/Hospitalizations">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                          </span>
                        </a>
                        <div class="collapse" id="shCollapse">
                            <div class="card card-default">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Name</h4>
                                                <input type="text" name="sh_name" id="sh_name" class="form-control" autocomplete="off" placeholder="Surgery or Hospitalizationtion">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Date</h4>
                                                <input type="text" class="form-control datepair-date datepair-start"
                                                       data-plugin="datepicker" name="sh_date" id="sh_date" autocomplete="off"
                                                       placeholder="MM/DD/YY">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button onclick="saveMedicalHistory('sh')" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>List type of surgery (such as gall bladder) or condition for which you were hospitalized (such as heart attack, pneumonia)</p>
                </div>
                <ul class="list-group list-group-bordered mb-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Surgery</th>
                            <th>Date</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($medical_history->surgeries_hospitalizations as $sh)
                            <tr>
                                <td> {{ $sh['name'] }} </td>
                                <td>{{ $sh['date'] }}</td>
                                <td><a onclick="return deleteMedicalHistory('sh',{{$sh['id']}})" style="color: red">Del</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </ul>
            </div>
        </div>

        <div class="col-lg-4 masonry-item">
            <div class="card card-shadow">
                <div class="card-header bg-blue-600 white p-15 clearfix">
                    <div class="font-size-18">Medications
                        <a  href="#medCollapse" type="button"
                            class="btn btn-sm btn-icon btn-inverse btn-round"
                            data-toggle="collapse" style="float: right;">
                          <span data-toggle="tooltip" data-original-title="Add Medications">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                          </span>
                        </a>
                        <div class="collapse" id="medCollapse">
                            <div class="card card-default">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Medication Name</h4>
                                                <input type="text" name="med_name" id="med_name" class="form-control" autocomplete="off" placeholder="Medication">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button onclick="saveMedicalHistory('med')" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Please include non-prescription medications, vitamins, and herbal supplements in addition to prescription medications</p>
                </div>
                <ul class="list-group list-group-bordered mb-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Medication</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($medical_history->medications as $med)
                            <tr>
                                <td> {{ $med }} </td>
                                <td><a onclick="return deleteMedicalHistory('med','{{$med}}')" style="color: red">Del</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </ul>
            </div>
        </div>


        <div class="col-lg-4 masonry-item">
            <div class="card card-shadow">
                <div class="card-header bg-blue-600 white p-15 clearfix">
                    <div class="font-size-18">Medications Allergies
                        <a  href="#medallCollapse" type="button"
                            class="btn btn-sm btn-icon btn-inverse btn-round"
                            data-toggle="collapse" style="float: right;">
                          <span data-toggle="tooltip" data-original-title="Add Medication Allergies">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                          </span>
                        </a>
                        <div class="collapse" id="medallCollapse">
                            <div class="card card-default">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Allergy Name</h4>
                                                <input type="text" name="medall_name" id="medall_name" class="form-control" autocomplete="off" placeholder="Medication Allergy">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button onclick="saveMedicalHistory('medall')" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Medication Allergies</p>
                </div>
                <ul class="list-group list-group-bordered mb-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Allergy</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($medical_history->medication_allergies as $medall)
                            <tr>
                                <td> {{ $medall }} </td>
                                <td><a onclick="return deleteMedicalHistory('medall','{{$medall}}')" style="color: red">Del</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </ul>
            </div>
        </div>


        <div class="col-lg-4 masonry-item">
            <div class="card card-shadow">
                <div class="card-header bg-blue-600 white p-15 clearfix">
                    <div class="font-size-18">Family History
                        <a  href="#famCollapse" type="button"
                            class="btn btn-sm btn-icon btn-inverse btn-round"
                            data-toggle="collapse" style="float: right;">
                          <span data-toggle="tooltip" data-original-title="Add Family History">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                          </span>
                        </a>
                        <div class="collapse" id="famCollapse">
                            <div class="card card-default">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Name</h4>
                                                <input type="text" name="fam_name" id="fam_name" class="form-control" autocomplete="off" placeholder="Name">
                                            </div>
                                            <div class="form-group">
                                                <h4 class="example-title">Member</h4>
                                                <select class="form-control" name="fam_member" id="fam_member">
                                                    <option value="father">Father</option>
                                                    <option value="mother">Mother</option>
                                                    <option value="sister">Sister</option>
                                                    <option value="brother">Brother</option>
                                                    <option value="child">Child</option>
                                                    <option value="grandmother">Grandmother</option>
                                                    <option value="grandfather">Grandfather</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button onclick="saveMedicalHistory('fam')" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Please list any conditions that run in your biological family (even if relative is deceased)</p>
                </div>
                <ul class="list-group list-group-bordered mb-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Condition Name</th>
                            <th>Member</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($medical_history->family_history as $fam)
                            <tr>
                                <td> {{ $fam['name'] }} </td>
                                <td>{{ $fam['member'] }}</td>
                                <td><a onclick="return deleteMedicalHistory('fam',{{$fam['id']}})" style="color: red">Del</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </ul>
            </div>
        </div>


        <div class="col-lg-12 masonry-item">
            <div class="card card-shadow">
                <div class="card-header bg-blue-600 white p-15 clearfix">
                    <div class="font-size-18">Social History
                    </div>
                    <p>Social History</p>
                </div>
                <div class="card card-white border border-secondary">
                    <div class="card-block">
                        <br>
                        <div class="row" >
                            <div class="col-lg-6">
                                <input type="checkbox" class="active-toggle" id="so_do_you_smoke" name="so_do_you_smoke" {{ (@$medical_history->social_history['do_you_smoke']=='yes')? "checked" : '' }}>
                            </div>

                            <div class="col-lg-6">
                                <input type="checkbox" class="active-toggle" id="so_illegal_drugs" name="so_illegal_drugs" {{ (@$medical_history->social_history['illegal_drugs']=='yes')? "checked" : '' }}>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Cigarettes per day</h4>
                                    <input type="number" name="so_cigarettes_per_day" id="so_cigarettes_per_day" class="form-control" autocomplete="off" placeholder="Cigarettes per day" value="{{ @$medical_history->social_history['cigarettes_per_day'] }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Cigarettes per week</h4>
                                    <input type="number" name="so_cigarettes_per_week" id="so_cigarettes_per_week" class="form-control" autocomplete="off" placeholder="Cigarettes per week" value="{{ @$medical_history->social_history['cigarettes_per_week'] }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Alcoholic drinks per Day</h4>
                                    <input type="number" name="so_alcoholic_drinks_per_day" id="so_alcoholic_drinks_per_day" class="form-control" autocomplete="off" placeholder="Alcoholic drinks per Day"  value="{{ @$medical_history->social_history['alcoholic_drinks_per_day'] }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Alcoholic drinks per Week</h4>
                                    <input type="number" name="so_alcoholic_drinks_per_week" id="so_alcoholic_drinks_per_week" class="form-control" autocomplete="off" placeholder="Alcoholic drinks per Week" value="{{ @$medical_history->social_history['alcoholic_drinks_per_week'] }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Minutes of Exercise per day</h4>
                                    <input type="number" name="so_minutes_of_exercise_per_day" id="so_minutes_of_exercise_per_day" class="form-control" autocomplete="off" placeholder="Minutes of Exercise per day" value="{{ @$medical_history->social_history['minutes_of_exercise_per_day'] }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Exercise days in a Week</h4>
                                    <input type="number" name="so_exercise_days_week" id="so_exercise_days_week" class="form-control" autocomplete="off" placeholder="Exercise days in a Week" value="{{ @$medical_history->social_history['exercise_days_week'] }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Hours of Television per day</h4>
                                    <input type="number" name="so_hours_of_television_per_day" id="so_hours_of_television_per_day" class="form-control" autocomplete="off" placeholder="Alcoholic drinks per Day" value="{{ @$medical_history->social_history['hours_of_television_per_day'] }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <h4 class="example-title">Fast Food per week</h4>
                                    <input type="number" name="so_fast_food_per_week" id="so_fast_food_per_week" class="form-control" autocomplete="off" placeholder="Fast Food per week" value="{{ @$medical_history->social_history['fast_food_per_week'] }}">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button onclick="saveMedicalHistory('so')" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>
</div>
