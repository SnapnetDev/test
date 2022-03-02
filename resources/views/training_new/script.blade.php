<style>
    .scrl::-webkit-scrollbar {
        width: 6px;
    }

    .scrl::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 3px;
    }

    .scrl::-webkit-scrollbar-thumb {
        border-radius: 3px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
    }
</style>
<script type="text/html" id="course-template">



        <div class="col-md-3">

            <div class="col-md-12" style="border: 1px solid #ddd;padding: 16px;margin-right: 2px;height: 250px;">

                {{--<div align="left">--}}
                {{--<img src="" alt="" id="courseimage">--}}
                {{--</div>--}}
                <div id="fullname" style="font-weight: bold;text-transform: uppercase;margin-bottom: 18px;white-space: nowrap;text-overflow: ellipsis;width: 162px;overflow-x: hidden;"></div>


                 <div class="scrl" id="summary" style="height: 100px;overflow-y: scroll;margin-bottom: 5px;"></div>


                {{--style="height: 100px;overflow-y: scroll;margin-bottom: 5px;"--}}
                {{--$('.example').asScrollable();--}}

                <div id="progress-section">
                    <div class="progress">
                        <div id="progress" class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                    </div>
                </div>

                <div class="row" id="btn-section">

                    <div align="left" id="enroll" class="col-md-6">
                        <button class="btn btn-sm btn-info" id="btn-enroll-toggle">Enroll</button>

                    </div>

                    <div align="right" id="access" class="col-md-6">
                        <a target="_blank" href="#" class="btn btn-sm btn-success" id="btn-access">Access</a>
                    </div>


                </div>

            </div>

        </div>






</script>

<script>

    var _bus = {};
    (function($,_){


        var listeners = {};
        var templates = {};


        function doRequest(endpoint,params,cb){
            params.endpoint = endpoint;
            // alert(0);
            $.ajax({
                url:'http://elearning.thehcmatrix.com/hcm_onboard/',
                data:params,
                type:'GET',
                success:function(response){
                    // alert(1);
                    cb(response);
                }
            });
        }


        function notify(evt,data){
            var collection = listeners[evt];
            collection.forEach(function(v,k){
                // alert('called...1');
                v(data);
            });
        }

        function subscribe(evt,cb){
            listeners[evt] = listeners[evt] || [];
            listeners[evt].push(cb);
            console.log(listeners);
        }

        function getTemplate(template){ //returns a jQuery object.
            templates[template] = templates[template] || $('#' + template).html();
            // console.log(templates);
            return $(templates[template]);
        }

        function useTemplate(template,cb){
            var $el = getTemplate(template);
            var $promisse = cb($el);
            setTimeout(function(){
                 $promisse();
            },2000);
            return $el;
        }

        _.notify = notify;
        _.subscribe = subscribe;
        _.useTemplate = useTemplate;
        _.doRequest = doRequest;


    })(jQuery,_bus);



    var moodle = {};
    (function(mdl){

        var onGoingList = [];
        var completedList = [];


        mdl.checkOnboardStatus = function(){

            //http://localhost:2010/hcm_onboard?endpoint=is_onboarded&username=1001
            _bus.doRequest('is_onboarded',{
                username:'{{ $employee->emp_num }}'
            },function(response){
              if (!response.data){
                  if (confirm("Current user has not been on-boarded to E-learning, Do you want to onboard this user?")){

                     toastr.info("Onboarding user ... ");

                     $.ajax({
                         url:'{{ route('process.ajax.command',['enroll-user']) }}?userId={{ $employee->id }}',
                         type:'GET',
                         success:function(response){
                             toastr.success(response.messsage);
                             setTimeout(function(){
                                 location.reload();
                             },2000);
                         }
                     });

                  }else{
                      location.reload();
                  }
              }
            });

        };

        _bus.subscribe('check-onboard',function(){
            mdl.checkOnboardStatus();
        });


        _bus.subscribe('on-going',function(config){

            var $el = $('#on-going');

            $el.html('');

            config.show_access = true;

            config.list.forEach(function(data,k){

                $el.append(renderCourse(data,config));

            });


        });

        _bus.subscribe('completed',function(config){

            var $el = $('#completed');

            $el.html('');

            config.show_access = true;

            config.list.forEach(function(data,k){

                $el.append(renderCourse(data,config));

            });


        });


        mdl.getMyEnrolledCourses = function(config){
            //http://elearning.thehcmatrix.com/hcm_onboard/index.php?endpoint=get_my_courses&username=1001
            _bus.doRequest('get_my_courses',{
                username:config.username
            },function(response){

                var countEnrolled = 0;
                var countCompleted = 0;

                onGoingList = [];
                completedList = [];

                response.data.courses.forEach(function(v,k){
                    countEnrolled+=1;
                    if (v.progress >= 100){
                        countCompleted+=1;
                        completedList.push(v);
                    }else{
                        onGoingList.push(v);
                    }
                });

                _bus.notify('on-going',{
                    list:onGoingList
                });

                _bus.notify('completed',{
                    list:completedList
                });


                $('#count-enrolled').html(countEnrolled);
                $('#count-completed').html(countCompleted);

            });
        };


        _bus.subscribe('get-courses',function(config){
           mdl.getMyEnrolledCourses(config);
        });

        function checkIfUserIsEnrolledForCourse(username,courseId,cb){
            _bus.doRequest('is_enrolled',{
                username:username,
                courseId:courseId
            },function(response){

                 cb(response);

            });
        }

        function actionEnroll($el,username,courseId){
            var $this = $el;
            var oldText = $this.html();

            $this.html('Enrolling...');

            _bus.doRequest('manual_enroll_user',{
                username:'{{ $employee->emp_num }}',
                courseId:courseId
            },function (response) {

                if (response.error){
                    toastr.error(response.message);
                }else{
                    toastr.success(response.message);
                }

                $this.html(oldText);

                _bus.notify('get-courses',{
                    username:'{{ $employee->emp_num }}'
                });

                _bus.notify('get-available-courses',{
                    show_enroll_toggle:true
                });


            });
        }

        function actionUnEnroll($el,username,courseId){
            var $this = $el;
            var oldText = $this.html();

            $this.html('Un-Enrolling...');

            _bus.doRequest('unenroll_user',{
                username:'{{ $employee->emp_num }}',
                courseId:courseId
            },function (response) {

                if (response.error){
                    toastr.error(response.message);
                }else{
                    toastr.success(response.message);
                }

                $this.html(oldText);

                _bus.notify('get-courses',{
                    username:'{{ $employee->emp_num }}'
                });

                _bus.notify('get-available-courses',{
                    show_enroll_toggle:true
                });


            });
        }

        function renderCourse(data,config){

            for (var i in config){
               data[i] = config[i];
            }

            return _bus.useTemplate('course-template',function ($elm) {

                $elm.find('#fullname').html(data.fullname);
                $elm.find('#summary').html(data.summary);
                // $elm.find('#summary').html(data.summary);
                var progress = (data.progress)? data.progress + '%' : '0%';
                //style="width: 25%;" aria-valuenow="25"

                $elm.find('#progress').html(progress);
                $elm.find('#progress').css('width', progress);
                $elm.find('#progress').attr('aria-valuenow', progress);

                if (!data.show_enroll){
                    $elm.find('#btn-enroll').hide();
                }

                if (!data.show_enroll_toggle){
                    $elm.find('#btn-enroll-toggle').hide();
                }


                if (!data.show_access){
                    $elm.find('#btn-access').hide();
                }



                checkIfUserIsEnrolledForCourse('{{ $employee->emp_num }}',data.id,function(response){


                    if (response.data){ //enrolled
                        $elm.find('#btn-enroll-toggle').html('Un-Enroll');
                        $elm.find('#btn-enroll-toggle').removeClass('btn-info');
                        $elm.find('#btn-enroll-toggle').addClass('btn-danger');
                    }else{
                        $elm.find('#btn-enroll-toggle').html('Enroll');
                    }

                    $elm.find('#btn-enroll-toggle').on('click',function(){

                        if (response.data){ //enrolled
                            actionUnEnroll($(this),'{{ $employee->emp_num }}',data.id);
                        }else{
                            actionEnroll($(this),'{{ $employee->emp_num }}',data.id);
                        }

                    });

                });


                $elm.find('#btn-access').attr('href','http://elearning.thehcmatrix.com/hcm_onboard/index.php?endpoint=autologin&username={{ $employee->emp_num }}&courseId=' + data.id);


                return function(){
                    // alert('called back...');
                    // $elm.find('#summary').asScrollable();
                }

            });
        }

        mdl.getAvailableCourses = function(config){

            @if (Auth::user()->role->permissions->contains('constant', 'enroll_users_elearning'))
                 config.show_enroll_toggle = true;
            @endif

            _bus.doRequest('training',{},function(response){

                var countEnrolled = 0;
                var countCompleted = 0;

                var $el = $('#available-courses');
                $el.html('');

                response.data.forEach(function(data,k){


                       $el.append(renderCourse(data,config));

                });


            });
        };

        _bus.subscribe('get-available-courses',function(config){

            mdl.getAvailableCourses(config);

        });



    })(moodle);






    _bus.notify('get-courses',{
        username:'{{ $employee->emp_num }}'
    })

    _bus.notify('get-available-courses',{
        show_enroll_toggle:false
    });

    _bus.notify('check-onboard',{});


</script>