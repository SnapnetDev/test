<div class="tab-pane animation-slide-left" id="offline-training-history" role="tabpanel">
    {{--                    PROMOTION HISTORY--}}
    <br>
    <legend style="color:black">Offline Training History</legend><hr>

    <table b-context="HistoryContext()"
           data-height="200"  class="table table-striped">
        <thead>
        <tr>
            <th >Name Of Training</th>
            <th >Completion Status</th>
            <th >Rating</th>

        </tr>
        </thead>
        <tbody b-loop="list">

        <script type="text/html">

            <tr b-context="EachTraining()">
                <td b-text="name"></td>
                <td b-text="progress_status">Completed</td>
                <td>
                    <div b-rating="rating"  class="mrating" style="text-align: left;">

                        <span data-rate="100">☆</span><span data-rate="80">☆</span><span class="selected" data-rate="60">☆</span><span data-rate="40">☆</span><span data-rate="20">☆</span>

                    </div>

                </td>
            </tr>

        </script>

        </tbody>
    </table>
    {{--                    JOB HISTORY --}}
    <br>


</div>
<script>



    function HistoryContext(){

        return {
            list:[],
            init:function(){
               this.ajax({
                   url:'{{ route('app.get',['fetch-training-plan-approved']) }}',
                   type:'get',
                   data:{
                       user_id:'{{ Auth::user()->id }}',
                       enroll_status:1
                   },
                   success:function(response){
                       this.list = response.list;
                   }
               });
            }

        };

    }

    function EachTraining(){
        return {
            name:'',
            progress_status:'...',
            user_id:'{{ Auth::user()->id }}',
            rating:0,
            init:function(){
              this.fetchEnrollMetta(); 
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


</script>
@include('training_new.rating_style')
