<script>


    function RecommendOfflineTraining(){

        return {

            list:[],
            data:{},

            init:function(){

                this.listen('fetchTraining',function(data){
                    this.data = data;
                    this.fetchTraining();
                },this);

                this.listen('fetchEnrolledTraining',function(data){
                    this.fetchEnrolledTraining(data);
                },this);


                this.listen('fetchCompletedTraining',function(data){
                    this.fetchCompletedTraining(data);
                },this);


                this.listen('fetchTrainingInProgress',function(data){
                    this.fetchTrainingInProgress(data);
                },this);


                // this.listen('filterBy');

                this.data.mp = '{{ request()->get('mp') }}';

                this.fetchTraining();

            },

            filterDep: false,

            filterByCategory:function(){
              this.data['filterDepartmentUserId'] = {{ request()->get('employee') }};
              this.fetchTraining();
            },

            filterByCategoryReset:function(){
                this.data['filterDepartmentUserId'] = 0; // {{ request()->get('employee') }};
                this.fetchTraining();
            },


            filterRole: false,

            filterByRole:function(){
                this.data['filterRoleUserId'] = {{ request()->get('employee') }};
                this.fetchTraining();
            },

            filterByRoleReset:function(){
                this.data['filterRoleUserId'] = 0; // {{ request()->get('employee') }};
                this.fetchTraining();
            },

            filterGroup: false,

            filterByGroup:function(){
                this.data['filterGroupUserId'] = {{ request()->get('employee') }};
                this.fetchTraining();
            },

            filterByGroupReset:function(){
                this.data['filterGroupUserId'] = 0; // {{ request()->get('employee') }};
                this.fetchTraining();
            },


//filterGroupUserId
            fetchTraining:function(){
                // alert(1);
                // this.data = data;
               this.ajax({
                   url:'{{ route('app.get',['fetch-training-plan-approved']) }}',
                   type:'get',
                   data:this.data,
                   success:function(response){
                      this.list = response.list;
                      console.log(this.list);
                      // alert(234);
                   }
               });
            },
            fetchEnrolledTraining:function(data){

                this.data = {
                    user_id:data.user_id,
                    enroll_status:1,
                    mp:'{{ request()->get('mp') }}'
                };

                this.fetchTraining();

            },
            fetchCompletedTraining:function(data){
                this.data = data;
                this.fetchTraining();
            },
            fetchTrainingInProgress:function(data){
                this.data = data;
                this.fetchTraining();
            }

        };

    }


    function UserTrainingMixin(){
        return {
            is_eligible: 'User is eligible',
            has_feedback:false,
            user_id:'{{ request()->get('employee') }}',
            show_feedback:false,
            rating:20,
            enroll_status:'...',
            progress_status:'...',
            feedback:'',
            upload1:'...',
            getGroupTags:function(){
                var r = [];
                this.training_groups.forEach(function(v,k){
                    r.push(v.group.name);
                });
                return '<span class="tag tag-info">' + r.join('</span>&nbsp;<span class="tag tag-info">') + '</span>';
                // return JSON.stringify(this);
            },
            init:function(){
              this.fetchEnrollStatus();
              this.fetchEnrollMetta();
              this.fetchEligibility();
            },
            setRatingOnClick:function(){
               // this.rating = 80;
            },
            enrollUser:function(){
              this.ajax({
                  url:'{{ route('app.get',['create-user-training-plan']) }}',
                  type:'get',
                  data:{
                      user_id:this.user_id,
                      training_plan_id:this.id
                  },
                  success:function(response){
                      this.init();
                      toastr.success(response.message);
                  }
              });
            },
            unEnrollUser:function(){
                this.ajax({
                    url:'{{ route('app.get',['remove-user-training-plan']) }}',
                    type:'get',
                    data:{
                        user_id:this.user_id,
                        training_plan_id:this.id
                    },
                    success:function(response){
                        this.init();
                        toastr.success(response.message);
                    }
                });
            },
            fetchEligibility:function(){
              this.ajax({
                  url:'{{ route('app.get',['get-user-is-eligible']) }}',
                  type:'get',
                  data:{
                      userId:this.user_id,
                      trainingPlanId:this.id
                  },
                  success:function(response){
                      this.is_eligible = response.message;
                  }
              });
            },
            fetchEnrollStatus:function(){
                //user_id:'{{ request()->get('employee') }}',
                  //  training_plan_id:data.id.get()


                this.ajax({
                  url:'{{ route('app.get',['user-Training-Is-Enrolled']) }}',
                  type:'get',
                  data:{
                    user_id:this.user_id,
                    training_plan_id:this.id
                  },
                  success:function(response){
                    this.enroll_status = response.message;
                  }
              });
            },
            fetchEnrollMetta:function(){

                this.ajax({
                    url:'{{ route('app.get',['get-User-Training-Metta']) }}',
                    type:'get',
                    data:{
                        user_id:this.user_id,
                        training_plan_id:this.id
                    },
                    success:function(response){

                        this.progress_status = response.completed;

                        if (response.completed == 'Completed'){
                            this.has_feedback = true;
                            this.feedback = response.data.feedback;
                            this.upload1 = '{{ asset('uploads/') }}/' + response.data.upload1;
                            this.rating = response.data.rating;
                        }

                    }
                });

            }
        };
    }


    function UserStats(){
        return {

            all:'...',
            enrolled:'...',
            completed:'...',
            insession:'...',
            user_id:'{{ request()->get('employee') }}',

            init:function(){

              this.ajax({
                  url:'{{ route('app.get',['fetch-training-plan-counts']) }}',
                  type:'get',
                  data:{
                      user_id:this.user_id,
                      mp:'{{ request()->get('mp') }}'
                  },
                  success:function(response){

                      this.all = response.all;
                      this.completed = response.completed;
                      this.enrolled = response.enrolled;
                      this.insession = response.insession;


                  }
              });

            },
            fetchAllTraining:function(){
                // alert('fetchAllTraining');
                this.publish('fetchTraining',{
                    none:'none',
                    mp:'{{ request()->get('mp') }}'
                });
            },
            fetchEnrolledTraining:function(){
                // console.log(this);
               this.publish('fetchEnrolledTraining',{
                   user_id:this.user_id,
                   mp:'{{ request()->get('mp') }}'
               });
            },
            fetchCompletedTraining:function(){ //fetchCompletedTraining
                // $self.data = {
                //     user_id:data_.user_id,
                //     completed:data_.completed,
                //     enroll_status:data_.enroll_status
                // };

                this.publish('fetchCompletedTraining',{
                    user_id:this.user_id,
                    completed:1,
                    enroll_status:1,
                    mp:'{{ request()->get('mp') }}'
                });

            },
            fetchTrainingInProgress:function(){

                this.publish('fetchTrainingInProgress',{
                   user_id:this.user_id,
                   completed:0,
                   enroll_status:1,
                   mp:'{{ request()->get('mp') }}'
                });
            }

        };
    }

</script>