<script>

    //training-stat-outlet

    {{--<training-plan-stat--}}
            {{--url="{{ route('app.get',['fetch-training-plan-counts']) }}"--}}
            {{--mp="{{ request()->get('mp') }}"--}}
            {{--user_id ="{{ request()->get('employee') }}"--}}
            {{--emp_num="{{$evaluation->user->emp_num}}"--}}
            {{--user_name="{{$evaluation->user->name}}"--}}
            {{--job_title="{{$evaluation->user->job->title}}"--}}
            {{--department_name="{{$evaluation->department->name}}">--}}
            {{--</training-plan-stat>--}}


    $.fn.handleTraining = function(settings){


        settings = $.extend({

             $mountStat:$('#'),
             mp:'',
             user_id:'',
             emp_num:'',
             user_name:'',
             job_title:'',
             department_name:'',
             onGetStat:function(data){return data;},
             onsetItemData:function($el,data){}


        },settings);


        var $statTemplate = $($('#training-stat').html());

        $statTemplate.find('#emp_num').html(settings.emp_num);
        $statTemplate.find('#user_name').html(settings.user_name);
        $statTemplate.find('#job_title').html(settings.job_title);
        $statTemplate.find('#department_name').html(settings.department_name);



        settings.$mountStat.html($statTemplate);

        // 'list'=>$this->filterEngine($prefs)->get(),
        //     'available_training'=>$this->filterEngine([])->count(),
        //     'enrolled_training'=>$this->filterEngine(['enrolled'=>$user_id])->count(),
        //     'completed_training'=>$this->filterEngine(['completed'=>$user_id])->count(),
        //     'in_progress_training'=>$this->filterEngine(['in_progress'=>$user_id])->count()


        var $url = '';
        var $filters = {};

        function fetchAll(filters){
            filters = filters || '';
            // $filters[filters] = $filters[filters] || '';
            $url = '{{ route('user_training.index') }}?user_id=' + settings.user_id + '&' + filters;
            requeryAll();
            // fetch($url).then((res)=>res.json()).then((res)=>{
            //
            //    $statTemplate.find('#available-training').html(res.available_training);
            //    $statTemplate.find('#enrolled-training').html(res.enrolled_training);
            //    $statTemplate.find('#completed-training').html(res.completed_training);
            //    $statTemplate.find('#in-progress-training').html(res.in_progress_training);
            //
            //    transformList(res.list);
            //
            // });
        }

        function requeryAll(){
            fetch($url).then((res)=>res.json()).then((res)=>{

                $statTemplate.find('#available-training').html(res.available_training);
                $statTemplate.find('#enrolled-training').html(res.enrolled_training);
                $statTemplate.find('#completed-training').html(res.completed_training);
                $statTemplate.find('#in-progress-training').html(res.in_progress_training);

                $statTemplate.find('[data-user-id]').each(function () {
                    $(this).attr('user_id',settings.user_id);
                });

                transformList(res.list);

            });
        }

        var $course_list = $('#course-list');


        function transformList(list){

            $course_list.html('');

            //training-item

            list.forEach(function(v,k){

                var $el = $($('#training-item').html());

                settings.onsetItemData($el,v,settings.user_id);

                $course_list.append($el);

            });


        }

        fetchAll();

        return {
            reQuery:requeryAll,
            fetchAll:fetchAll
        };


    };



     window.$globalTraining = $('#recommend-offline-modal').handleTraining({

        $mountStat:$('#training-stat-outlet'),
        mp:"{{ request()->get('mp') }}",
        user_id:"{{ request()->get('employee') }}",
        emp_num:"{{$evaluation->user->emp_num}}",
        user_name:"{{$evaluation->user->name}}",
        job_title:"{{$evaluation->user->job->title}}",
        department_name:"{{$evaluation->department->name}}",

        onsetItemData:function($el,data,userId){

            $el.find('#name').html(data.name);
            $el.find('#cost_per_head').html(data.cost_per_head);
            $el.find('#number_of_enrollees').html(data.number_of_enrollees);
            $el.find('#type').html(data.type);

            $el.find('[name=user_id]').val(userId);
            $el.find('[name=training_plan_id]').val(data.id);


            $el.find('#enroll').on('click',function(){

                $el.find('#enroll-form'). formSubmit({
                    method:'POST',
                    url:'{{ route('user_training.store') }}',
                    loading:function(){
                      toastr.info('Enrolling ... ');
                    },
                    loaded:function(){

                        //toastr.info('Enroll ... ');
                        $globalTraining.reQuery();

                    }
                });

                return false;


            });




            $el.find('#unenroll').on('click',function(){

                $el.find('#unenroll-form'). formSubmit({
                    method:'POST',
                    url:'{{ route('user_training.store') }}',
                    loading:function(){
                        toastr.info('Un-Enrolling ... ');
                    },
                    loaded:function(){

                        //toastr.info('Enroll ... ');
                        $globalTraining.reQuery();

                    }
                });


                return false;

            });


            // training_groups
            // <span class='tag tag-info' style="margin-right: 1px;display: inline-block;">Group 1</span>
            // <span class='tag tag-info' style="margin-right: 1px;display: inline-block;">Group 2</span>
            //training_groups

            data.training_groups.forEach(function($v,$k){
                $el.find('#group-container').append($(`
                  <span class='tag tag-info' style="margin-right: 1px;display: inline-block;">${$v.group.name}</span>
                `));
            });

            $el.find('#department_name').html(data.department.name);
            $el.find('#role_name').html(data.role.name);


            $el.find('#status').html('Not - Assigned');

            if (data.user && data.user.status == 1){
                $el.find('#status').html('Enrolled');
            }


            if (data.user && data.user.status == 0){
                $el.find('#status').html('Enrollement Paused');
            }

            $el.find('#feedback').hide();

            if (data.user){
                // alert('called.');
                $el.find('#feedback').show();
                $el.find('#row-slide').hide();


                $el.find('[name=view-feedback]').on('click',function(){

                    if ($(this).is(':checked')){
                        // alert('checked...');
                        $el.find('#row-slide').slideDown();
                        return
                    }

                    $el.find('#row-slide').slideUp();

                });



                var $formEl = $el;

                if (data.user.upload1){
                    $formEl.find('#download').find('a').show();
                    $formEl.find('#download').find('a').attr('href','{{ asset('uploads/') }}/' + data.user.upload1);
                }

                for (var i in data.user){
                    if (data.user.hasOwnProperty(i)){
                        $formEl.find('[name=' + i + ']').val(data.user[i]);
                        $formEl.find('[name=' + i + ']').attr('readonly',1);
                    }
                }

                if (data.user.completed){
                    $formEl.find('[name=completed]').prop('checked',true);
                }

                $formEl.find('.mrating').find('span').each(function(){

                    if (data.user.rating == $(this).attr('data')){
                        $(this).addClass('selected');
                    }

                    // $(this).on('click',function(){
                    //     // alert($(this).attr('data'));
                    //     $formEl.find('.mrating').find('span').removeClass('selected');
                    //     $('[name=rating]').val($(this).attr('data'));
                    //     $(this).addClass('selected');
                    // });

                });



            }


        }
    });





</script>