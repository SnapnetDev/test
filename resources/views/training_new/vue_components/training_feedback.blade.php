<script>
    Vue.component('training-feedback',{

        props:['url','user_id','training_plan_id'],

        data:function(){
            return {
               has_feedback:false,
               slide_down:true,
               feedback:'',
               upload1:'',
               rating:0,
               rateList:[100,80,60,40,20]
            };
        },

        mounted:function(){


            // this.fetchFeedBack();
            this.slide_down = false;

            var $this = this;
            this.$root.$on('refresh',function(){

                $this.fetchFeedBack();
            });
            // console.log(this.$refs.b_slide_down);

        },

        watch:{

            slide_down:function(val){
                if (val){
                    $(this.$refs.b_slide_down).slideDown();
                }else{
                    $(this.$refs.b_slide_down).slideUp();
                }
            }

        },

        methods:{

            fetchFeedBack:function(){
                this.has_feedback = false;
                this.callAjax({
                    url:this.url,
                    type:'GET',
                    data:{
                        user_id:this.user_id,
                        training_plan_id:this.training_plan_id
                    },
                    success:function(response){

                        if (response.completed == 'Completed'){
                            // alert('ok');
                            this.has_feedback = true;
                            this.feedback = response.data.feedback;
                            this.upload1 = '{{ asset('uploads/') }}/' + response.data.upload1;
                            this.rating = response.data.rating;
                        }else{
                            this.has_feedback = false;
                        }


                    }
                });
            }

        },

        template:`<span>

<div class="col-md-12" v-show="has_feedback">

            <div class="checkbox-custom checkbox-primary">
            <input v-model="slide_down"  type="checkbox"  name="completed"  />
            <label style="
            font-weight: bold;
            text-transform: uppercase;
            " b-for="'inputUnchecked' + id">View Feedback</label>
            </div>



            <div class="mcontent" ref="b_slide_down" b-slide-down="show_feedback" style=";
            border: 2px solid #bbb;
            padding: 9px;
            background-color: #eee;
            color: #000;
            ">
            <div>
            <b>Feedback:</b>
            <div v-text="feedback">
            </div>
            </div>
            <div>
            <b>Rating:</b>
            <div>
            <div  class="mrating" style="text-align: left;">

               <span v-for="rl in rateList" v-bind:class="{selected: (rating == rl)}">☆</span>

            </div>
            </div>
            </div>
            <div>
            <b>Download Document:</b>
            <div>
            <a :href="upload1" >Download</a>
            </div>
            </div>

            </div>

            </div>
</span>`



    });
</script>