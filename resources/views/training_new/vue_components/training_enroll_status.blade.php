<script>
    Vue.component('training-enroll-status',{

        props:['url','user_id','training_plan_id'],

        data:function(){
            return {
               status:''
            };
        },

        mounted:function(){

            // this.fetchEnrollStatus();

            var $this = this;

            this.$root.$on('refresh',function(){

                // alert('called refresh');
                $this.fetchEnrollStatus();

            });

        },

        updated:function(){
            console.log('updated...');
        },

        methods:{

            fetchEnrollStatus:function(){
                // alert(1);
                console.log('stat.');
               this.callAjax({
                   url:this.url,
                   type:'GET',
                   data:{
                       user_id:this.user_id,
                       training_plan_id:this.training_plan_id
                   },
                   success:function(response){
                        // alert(2);
                       this.status = response.message;
                   }
               });
            }

        },

        template:`<span>
                    <div v-text="status"></div>
                  </span>`


    });
</script>