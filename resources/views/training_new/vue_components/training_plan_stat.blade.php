<script>

    Vue.component('training-plan-stat',{

        props:['emp_num','user_name','job_title','department_name','user_id','mp','url'],
        mounted:function(){
           this.fetchStat();
            var $this = this;
            this.$root.$on('refresh',function(){

                // alert('called refresh');
                $this.fetchStat();

            });

        },
        data:function(){
            return {
              all:0,
              completed:0,
              enrolled:0,
              insession:0
            }
        },
        methods:{
          fetchStat:function(){
            // alert('stat...');
             this.callAjax({
                 url:this.url,
                 type:'GET',
                 data:{
                     mp:this.mp,
                     user_id:this.user_id
                 },
                 success:function(response){
                     this.all = response.all;
                     this.completed = response.completed;
                     this.enrolled = response.enrolled;
                     this.insession = response.insession;
                 }
             });

          },
          fetchAll:function(){
            // this.callAjax({});
              this.$root.$emit('fetchAll');
          },
          fetchEnrolled:function(){
              this.$root.$emit('fetchEnrolled');
          },
          fetchCompleted:function(){
              this.$root.$emit('fetchCompleted');
          },
          fetchTrainingInProgress:function(){
              this.$root.$emit('fetchTrainingInProgress');
          }
        },
        template:`<span>

                         <div class="col-md-4">


                  <ul class="list-group list-group-bordered">
                  <li class="list-group-item ">Employee Number:<span class="pull-right" v-text="emp_num"></span></li>
                  <li class="list-group-item ">Name:<span class="pull-right" v-text="user_name"></span></li>
                  <li class="list-group-item ">Job Role:<span class="pull-right" v-text="job_title"></span></li>
                  <li class="list-group-item ">Department:<span class="pull-right" v-text="department_name"></span></li>


                      <li  data-toggle="modal" data-target="#recommend-offline-modal" class="list-group-item " @click.prevent="fetchAll()">Available Trainings:<span  class="pull-right tag tag-info" v-text="all">0</span></li>
                      <li  data-toggle="modal" data-target="#recommend-offline-modal" class="list-group-item " @click.prevent="fetchEnrolled()">Enrolled Trainings:<span  class="pull-right tag tag-info" v-text="enrolled">0</span></li>
                      <li  data-toggle="modal" data-target="#recommend-offline-modal" class="list-group-item " @click.prevent="fetchCompleted()">Completed Trainings:<span class="pull-right tag tag-info" v-text="completed">0</span></li>
                      <li  data-toggle="modal" data-target="#recommend-offline-modal" class="list-group-item " @click.prevent="fetchTrainingInProgress()">Trainings In Progress:<span class="pull-right tag tag-info" v-text="insession">0</span></li>

                 </ul>
                </div>



          </span>`

    });


</script>