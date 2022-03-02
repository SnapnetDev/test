<script>
    Vue.component('training-progress-status',{

        props:['url','user_id','training_plan_id'],

        data:function(){
            return {
               status:''
            };
        },

        mounted:function(){
          // this.fetchProgressStatus();
            var $this = this;
            this.$root.$on('refresh',function(){

                // alert('called refresh');
                $this.fetchProgressStatus();

            });

        },

        methods:{

            fetchProgressStatus:function(){
              this.callAjax({
                  url:this.url,
                  type:'GET',
                  data:{
                      user_id:this.user_id,
                      training_plan_id:this.training_plan_id
                  },
                  success:function(response){
                      this.status = response.completed;
                      // this.progress_status = response.completed;

                      if (response.completed == 'Completed'){
                          //this.has_feedback = true;
                          //this.feedback = response.data.feedback;
                          //this.upload1 = '{{ asset('uploads/') }}/' + response.data.upload1;
                          //this.rating = response.data.rating;
                      }

                  }
              });
            }

        },


        template:`<span>
                    <div v-text="status"></div>
                 </span>`


    });
</script>