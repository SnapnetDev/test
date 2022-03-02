<div class="modal fade" id="recommend-offline-modal" role="dialog">
    <div class="modal-dialog modal-info modal-md modal-simple modal-sidebar" b-context="RecommendOfflineTraining()">

        <span data-store="training=[]"></span>
        <span data-store="caption='Recommend Offline Trainning.'"></span>


        <!-- Modal content-->
        <div class="modal-content" style="
    min-height: 618px;
    background-color: #eee;
">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-transform: uppercase;" data-text="caption">Recommend Offline Trainning</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12" style="
    background-color: #fff;
    margin-bottom: 7px;
    border: 1px solid #ddd;
">

                       <div class="checkbox-custom checkbox-primary" style="display: inline-block;margin-left: 13px;">
                           <input name="filter_user_dep" type="checkbox" b-sync="filterDep" valueNotNull="this.filterByCategory()" valueNull="this.filterByCategoryReset()" />
                           <label for="filter_user_dep">
                               Department
                           </label>
                       </div>



                        <div class="checkbox-custom checkbox-primary" style="display: inline-block;margin-left: 13px;">
                            <input name="filter_user_role" type="checkbox" b-sync="filterRole" valueNotNull="this.filterByRole()" valueNull="this.filterByRoleReset()" />
                            <label for="filter_user_role">
                                Role
                            </label>
                        </div>



                        <div class="checkbox-custom checkbox-primary" style="display: inline-block;margin-left: 13px;">
                            <input name="filter_user_grp" type="checkbox" b-sync="filterGroup" valueNotNull="this.filterByGroup()" valueNull="this.filterByGroupReset()" />
                            <label for="filter_user_grp">
                                Group
                            </label>
                        </div>

                    </div>

                    <div style="padding: 0" class="col-md-12">

                        <div class="col-md-12" style="padding: 0;" b-loop="list">
                            <script type="text/html" id="template">

                               <div b-context="UserTrainingMixin()">
                                   <span data-store="hasFeedback=false"></span>
                                   <span data-store="feedback='...'"></span>
                                   <span data-store="upload1='link'"></span>
                                   <span data-store="rating='0'"></span>
                                   <div class="col-md-12" style="margin-bottom: 8px;padding: 0;background-color: #fff;padding-bottom: 11px;">
                                       <h4 b-text="name" style="margin-top: 0;color: #fff;background-color: #75a9d6;padding: 11px;"></h4>
                                       <div class="row" style="padding: 5px;">

                                           <div class="col-md-6">
                                               <b>Cost Per Head</b>
                                           </div>
                                           <div b-text="cost_per_head" class="col-md-6">
                                               0.0
                                           </div>

                                           <div class="col-md-6">
                                               <b>Max Applicants</b>
                                           </div>
                                           <div class="col-md-6" b-text="number_of_enrollees">
                                               11
                                           </div>

                                           <div class="col-md-6">
                                               <b>Applicants Enrolled</b>
                                           </div>
                                           <div class="col-md-6">
                                               4
                                           </div>

                                           <div class="col-md-6">
                                               <b>Enroll Status</b>
                                           </div>

                                           <span data-store="enroll_status='Loading...'"></span>
                                           <div class="col-md-6" b-text="enroll_status">
                                               Not Enrolled
                                           </div>

                                           <span data-store="progress_status='Pending'"></span>
                                           <div class="col-md-6" data-ajax-get="getUserTrainingMetta">
                                               <b>Progress Status</b>
                                           </div>
                                           {{--getUserTrainingMetta--}}
                                           <div class="col-md-6" b-text="progress_status">
                                               Pending
                                           </div>

                                           <span data-store="is_eligible='Yes.'"></span>
                                           <div class="col-md-6">
                                               <b>Is Eligible</b>
                                           </div>
                                           <div class="col-md-6" b-text="is_eligible" >
                                               Yes
                                           </div>



                                               <div class="col-md-6">
                                                   <div>Department:</div>
                                               </div>


                                               <div class="col-md-6">
                                                   <div b-text="(department.name == '')? department.name : '&nbsp;'"></div>
                                               </div>


                                           <div class="col-md-6">
                                               <div>Groups:</div>
                                           </div>


                                           <div class="col-md-6">
                                               <div b-html="(this.getGroupTags())? this.getGroupTags() : '&nbsp;'"></div>
                                           </div>



                                           <div class="col-md-6" b-show="role.name">
                                               <div>Role:</div>
                                           </div>


                                           <div class="col-md-6" b-show="role.name">
                                               <div style="font-style: italic;font-weight: bold;" b-html="(role.name == undefined)? role.name : '&nbsp;'"></div>
                                           </div>



                                           <div class="col-md-6">
                                               <div>Type:</div>

                                           </div>


                                           <div class="col-md-6">
                                               <div b-text="type"></div>
                                           </div>



                                           <div class="col-md-12" data-show="hasFeedback" data-custom="handleVerticalSlide">

                                               <div b-show="has_feedback" class="checkbox-custom checkbox-primary">
                                                   <input  b-checked-state="show_feedback"  type="checkbox" b-id="'inputUnchecked' + id" name="completed" value="1" />
                                                   <label style="
    font-weight: bold;
    text-transform: uppercase;
" b-for="'inputUnchecked' + id">View Feedback</label>
                                               </div>

                                               {{--Feedback...--}}


                                               <div class="mcontent" b-slide-down="show_feedback" style="display: none;
    border: 2px solid #bbb;
    padding: 9px;
    background-color: #eee;
    color: #000;
">
                                                   <div>
                                                       <b>Feedback:</b>
                                                       <div b-text="feedback">
                                                       </div>
                                                   </div>
                                                   <div>
                                                       <b>Rating:</b>
                                                       <div>
                                                           <div b-rating="rating"  class="mrating" style="text-align: left;">

                                                               <span data-rate="100">☆</span><span data-rate="80">☆</span><span class="selected" data-rate="60">☆</span><span data-rate="40">☆</span><span data-rate="20">☆</span>

                                                           </div>
                                                       </div>
                                                   </div>
                                                   <div>
                                                       <b>Download Document:</b>
                                                       <div>
                                                           <a b-href="upload1" href="">Download</a>
                                                       </div>
                                                   </div>

                                               </div>

                                           </div>



                                           <div class="col-md-12" align="right" style="margin-top: 12px;">
                                               <button b-on="['click',enrollUser]" class="btn btn-sm btn-success">Enroll</button>
                                               <button b-on="['click',unEnrollUser]" class="btn btn-sm btn-danger">UnEnroll</button>

                                           </div>

                                       </div>
                                   </div>
                               </div>

                             </script>
                             {{--<div id="outlet"></div>--}}
                        </div>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
@include('training_new.rating_style')
@include('training_new.js_plugin.recommend_offline_training_js')



