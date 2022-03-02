<script>
    Vue.component('training-eligibility',{

        props:['url','user_id','training_plan_id'],

        mounted:function(){
           this.fetchEligibility();
        },


        data:function(){
            return {
              status:''
            };
        },

        methods:{

          fetchEligibility:function(){

              this.callAjax({
                url:this.url,
                type:'GET',
                data:{
                    userId:this.user_id,
                    trainingPlanId: this.training_plan_id
                },
                success:function(response){
                    this.status = response.message;
                }
              });

          }

        },

        template:`
         <span>
           <div v-text="status"></div>
         </span>`

    });
</script>