<script>
    Vue.component('training-plan-recommendation',{

        props:['url','user_id','mp','url_eligibility','url_enroll_status','url_enroll_progress',
        'url_enroll_user','url_unenroll_user'],

        data:function(){
           return {
             trainings:[],
             data:{},
             caption:'Recommend Offline Training.',
             department_filter:true,
             role_filter:false,
             group_filter:false,
             online_filter:false
           };
        },

        mounted:function(){
          // this.data.user_id = this.user_id;
          this.data.mp = this.mp;

          this.fetchTraining();

          // alert(this.url_eligibility);

           var $this = this;

           this.$root.$on('fetchAll',function(data){

               $this.data = {};
               $this.data.mp = $this.mp;

               $this.caption = 'Recommend Offline Training';

               $this.fetchTraining();

           });


           this.$root.$on('fetchEnrolled',function(data){

               $this.data = {};
               $this.data.mp = $this.mp;

               $this.data.user_id = $this.user_id;
               $this.data.enroll_status = 1;

               $this.caption = 'Enrolled Training';

               $this.fetchTraining();

           });



            this.$root.$on('fetchCompleted',function(data){

                $this.data = {};
                $this.data.mp = $this.mp;

                $this.data.user_id = $this.user_id;
                $this.data.enroll_status = 1;
                $this.data.completed = 1;

                $this.caption = 'Completed Training';

                $this.fetchTraining();

            });



            this.$root.$on('fetchTrainingInProgress',function(data){

                $this.data = {};
                $this.data.mp = $this.mp;

                $this.data.user_id = $this.user_id;
                $this.data.enroll_status = 1;
                $this.data.completed = 0;

                $this.caption = 'Training In Progress';

                $this.fetchTraining();

            });

            this.handleDepartmentFilter();
            this.handleRoleFilter();
            this.handleGroupFilter();
            this.handleOnlineFilter();

        },

        watch:{

            department_filter:function(val){
              this.handleDepartmentFilter();
            },

            role_filter:function(val){
              this.handleRoleFilter();
            },

            group_filter:function(val){
               this.handleGroupFilter();
            },

            online_filter:function(val){
               this.handleOnlineFilter();
            }


        },

        methods:{

            handleDepartmentFilter:function(){
                // alert('dep');
                if (this.department_filter){
                    this.data['filterDepartmentUserId'] = this.user_id;
                }else{
                    delete this.data['filterDepartmentUserId'];
                }

                // $this.caption = 'Training By Department';

                this.fetchTraining();

            },

            handleRoleFilter:function(){
                // alert('role');

                if (this.role_filter){
                    this.data['filterRoleUserId'] = this.user_id;
                }else{
                    delete this.data['filterRoleUserId'];
                }

                // $this.caption = 'Training By Department';

                this.fetchTraining();

            },

            handleGroupFilter:function(){
                // alert('grp');
                if (this.group_filter){
                    this.data['filterGroupUserId'] = this.user_id;
                }else{
                    delete this.data['filterGroupUserId'];
                }

                // $this.caption = 'Training By Department';

                this.fetchTraining();

            },

            handleOnlineFilter:function(){
                // alert('offln');
                if (this.online_filter){
                    this.data['type'] = 'online';
                }else{
                    delete this.data['type'];
                }

                // $this.caption = 'Training By Department';

                this.fetchTraining();

            },

            fetchTraining:function(){
                console.log(this.data);
                this.callAjax({
                    url:this.url,
                    type:'GET',
                    data:this.data,
                    success:function(response){
                        // this.trainings = [];
                        this.trainings = response.list;
                        var $this = this;
                        setTimeout(function(){
                            $this.$root.$emit('refresh');
                        },100);
                        // alert('called');
                    }
                });
            },

            enrollUser:function(trainingObject){

                this.callAjax({
                    url:this.url_enroll_user,
                    type:'GET',
                    data:{
                        user_id:this.user_id,
                        training_plan_id:trainingObject.id
                    },
                    success:function(response){

                        toastr.success(response.message);

                        this.$root.$emit('refresh',{message:response.message});

                    }
                });


            },
            unEnrollUser:function(trainingObject){

                this.callAjax({
                    url:this.url_unenroll_user,
                    type:'GET',
                    data:{
                        user_id:this.user_id,
                        training_plan_id:trainingObject.id
                    },
                    success:function(response){

                        toastr.success(response.message);

                        this.$root.$emit('refresh',{message:response.message});

                    }
                });

            }


        },

        template:`<span>
<div class="modal fade" id="recommend-offline-modal" role="dialog">
    <div class="modal-dialog modal-info modal-md modal-simple modal-sidebar">


            <div class="modal-content" style="
            min-height: 618px;
            background-color: #eee;
            ">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="text-transform: uppercase;" v-text="caption"></h4>
            </div>
            <div class="modal-body">
            <div class="row">

            <div class="col-md-12" style="
            background-color: #fff;
            margin-bottom: 7px;
            border: 1px solid #ddd;
            ">

            <div class="checkbox-custom checkbox-primary" style="display: inline-block;margin-left: 13px;">
            <input v-model="department_filter" name="filter_user_dep" type="checkbox" />
            <label for="filter_user_dep">
            Department
            </label>
            </div>



            <div class="checkbox-custom checkbox-primary" style="display: inline-block;margin-left: 13px;">
            <input v-model="role_filter" type="checkbox" />
            <label for="filter_user_role">
            Role
            </label>
            </div>



            <div class="checkbox-custom checkbox-primary" style="display: inline-block;margin-left: 13px;">
              <input v-model="group_filter" type="checkbox" />
               <label for="filter_user_grp">
                Group
                </label>
            </div>


            <div class="checkbox-custom checkbox-primary" style="display: inline-block;margin-left: 13px;">
              <input v-model="online_filter" type="checkbox" />
               <label for="filter_user_grp">
                Online
                </label>
            </div>


            </div>

            <div style="padding: 0" class="col-md-12">

            <div class="col-md-12" style="padding: 0;" b-loop="list">

            <div v-for="training in trainings" :key="training.id">


            <div class="col-md-12" style="margin-bottom: 8px;padding: 0;background-color: #fff;padding-bottom: 11px;">
            <h4 v-text="training.name" style="margin-top: 0;color: #fff;background-color: #75a9d6;padding: 11px;"></h4>
            <div class="row" style="padding: 5px;">

            <div class="col-md-6">
            <b>Cost Per Head</b>
            </div>
            <div v-text="training.cost_per_head" class="col-md-6">
            0.0
            </div>
            <div style="clear: both;"></div>

            <div class="col-md-6">
            <b>Max Applicants</b>
            </div>
            <div class="col-md-6" v-text="training.number_of_enrollees">
            11
            </div>
            <div style="clear: both;"></div>


            <div class="col-md-6">
            <b>Enroll Status</b>
            </div>


            <div class="col-md-6">
              <training-enroll-status
                :url="url_enroll_status"
                :user_id="user_id"
                :training_plan_id="training.id"
              ></training-enroll-status>
            </div>

            <div style="clear: both;"></div>

            <div class="col-md-6">
            <b>Progress Status</b>
            </div>
            <div class="col-md-6">
             <training-progress-status
               :url="url_enroll_progress"
               :user_id="user_id"
               :training_plan_id="training.id"
             ></training-progress-status>
            </div>

            <div style="clear: both;"></div>


            <div class="col-md-6">
            <b>Is Eligible</b>
            </div>
            <div class="col-md-6" >
               <training-eligibility
                   :url="url_eligibility"
                   :user_id="user_id"
                   :training_plan_id="training.id"
               >
               </training-eligibility>
            </div>
            <div style="clear: both;"></div>



            <span v-show="(training.department !== null)">
                <div class="col-md-6">
                <div>Department:</div>
                </div>


                <div class="col-md-6">
                <div v-text="(training.department !== null)? training.department.name : ' '"></div>
                </div>
            </span>
            <div style="clear: both;"></div>


            <span v-show="(training.training_groups.length >= 1)">
                <div class="col-md-6">
                <div>Groups:</div>
                </div>


                <div class="col-md-6">
                <div>
                  <span v-for="grp in training.training_groups" class='tag tag-info' v-text="grp.group.name" style="margin-right: 1px;display: inline-block;">Group</span>
                </div>
                </div>
            </span>
            <div style="clear: both;"></div>



            <div class="col-md-6" v-show="training.role !== null">
            <div>Role:</div>
            </div>


            <div class="col-md-6" v-show="training.role !== null">
            <div style="font-style: italic;font-weight: bold;" v-html="(training.role !== null)? training.role.name : '&nbsp;'"></div>
            </div>
            <div style="clear: both;"></div>



            <div class="col-md-6">
            <div>Type:</div>
            </div>


            <div class="col-md-6">
            <div>
               <span v-text="training.type" class="tag" v-bind:class="{'tag-success': (training.type == 'online') , 'tag-warning': (training.type == 'offline')}"></span>
            </div>
            </div>

            <div style="clear: both;"></div>



            <training-feedback
               :url="url_enroll_progress"
               :user_id="user_id"
               :training_plan_id="training.id"
            ></training-feedback>


            <div class="col-md-12" align="right" style="margin-top: 12px;">
            <button @click.prevent="enrollUser(training)" class="btn btn-sm btn-success">Enroll</button>
            <button @click.prevent="unEnrollUser(training)" class="btn btn-sm btn-danger">UnEnroll</button>

            </div>

            </div>
            </div>
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
</div>
</span>`

    });

</script>