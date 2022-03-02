
<div class="page-header">
    <h1 class="page-title">{{__('Notification Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Notification Settings')}}</li>
        <li class="breadcrumb-item active">{{__('You are Here')}}</li>
    </ol>
    <div class="page-header-actions">
        <div class="row no-space w-250 hidden-sm-down">

            <div class="col-sm-6 col-xs-12">
                <div class="counter">
                    <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="counter">
                    <span class="counter-number font-weight-medium" id="time"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-12 col-xs-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Notification Settings</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                    <th>Reminder</th>
                                </tr>

                            </thead>
                            <tbody>
                            @foreach($notification_types as $notification_type)
                                <tr>
                                   <td>{{$notification_type->name}}</td>
                                   <td><input type="checkbox" {{isset($notification_type->notificationstatus->status) && $notification_type->notificationstatus->status =='active' ? 'checked' : ''}} value="{{$notification_type->id}}" data-plugin="toggle" class="not_status" ></td>
                                   <td>
                                        @php $reminder_before=explode(',',$notification_type->notificationstatus->reminder_before) @endphp
                                       <select style="width: 100%" class="reminderBefore" multiple="true" class="form-control">
                                           @foreach($reminders as $slug=>$readable_slug)
                                           <option value="{{$slug}}" {{in_array($slug,$reminder_before) ? 'selected' : ''}}  >{{$readable_slug}}</option>
                                           @endforeach

                                       </select>
                                   </td>
                                </tr>
                                @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td><button class="btn btn-info btn-md pull-right" id="saveNotification">Save Settings</button></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>


        </div>


        <div class="col-md-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Notification Message</h3>
                </div>
                <div class="panel-body">
                <table class="table">
                    <tr>
                        <td colspan="2">
                            Constants :=> Firstname = %first_name% ,Lastname = %last_name%, Date of Birth = %dob% , HireDate = %hiredate% ,Day = %day% , Month = %month% , Year = %year%, Birthday Age = %diff_dob%, Anniversary Age = %diff_hiredate% 
                        </td>
                    </tr>
                    <tr>
                        <td>Notification Type :</td>
                        <td>
                            @foreach($notification_types->where('id','<>',4) as $notification_type)
                            <input type="hidden" value="{{$notification_type->notificationmessage->message}}" id="notification{{$notification_type->id}}">
                            @endforeach
                            <select name="notification_type" class="form-control notification_type">
                                <option value="">-Select Notification Type-</option>
                                @foreach($notification_types->where('id','<>',4) as $notification_type)
                                        <option value="{{$notification_type->id}}">{{$notification_type->name}} </option>
                                    @endforeach
                            </select>
                        </td>

                    </tr>
                    <tr class="hide holiday_tr">
                        <td>Holidays Type :</td>
                        <td>
                            @foreach($holidays as $holiday)
                                <input type="hidden" value="{{$holiday->holidaymessage->message}}" id="holiday{{$holiday->id}}">
                            @endforeach
                            <select name="holiday" class="form-control holiday">
                                <option value="">-Select Holiday-</option>
                                @foreach($holidays as $holiday)
                                    <option value="{{$holiday->id}}">{{$holiday->title}}</option>
                                @endforeach
                            </select>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2">

                        <textarea class="form-control notification_message" >

                        </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button class="pull-right btn btn-info" onclick="saveMessages()">Save</button></td>
                    </tr>
                </table>

                </div>
            </div>
        </div>
    </div>
<script>
    $(function () {
        $('.reminderBefore').select2();
        $('.not_status').bootstrapToggle({  on: 'on',    off: 'off',   onstyle:'info',   offstyle:'default'     });
        $('.notification_message').summernote({height:'300'});

        $('#saveNotification').click(function(){

            statuses= $('.not_status').map(function(){
                return resolveStatus($(this).is(':checked'));
            }).get();
            notification_type_id=$('.not_status').map(function(){
                return this.value;
            }).get();
            reminderBefore=$('.reminderBefore').map(function(){
                return $(this).val().join(',');
            }).get();

            let formData = {
                notification_type_id: notification_type_id,
                status: statuses,
                reminder_before:reminderBefore,
                type:'toggleNotification',
                _token:'{{csrf_token()}}'
            }

            return postData(formData,'{{url('notifications')}}');
        });



        $('.notification_type').change(function () {
            let     id=$(this).val();

            if(id==3){
                $('.holiday_tr').removeClass('hide');
                $('.notification_message').summernote('code','');

            }
            else{
                // alert($(`#notification${id}`).val());
                notification_id=$(`#notification${id}`).val();
                $('.notification_message').summernote('code',notification_id);
                $('.#notification'+id).val(notification_id);
                $('.holiday_tr').addClass('hide');
            }
        })

        $('.holiday').change(function () {
            id=$(this).val();

            $('.notification_message').summernote('code',$(`#holiday${id}`).val());

        })
    })

   function resolveStatus(checkedStatus){
       if(checkedStatus){
           return 'active';
       }
       else{
           return 'in-active';
       }
    }

    function saveMessages() {
        let notification_type=$('.notification_type').val();
        let message=$('.notification_message').summernote('code');


        if(notification_type=='' || message.trim()==='' ){
            return toastr.error('Some Field Empty');

        }
        //condition for others
        model_name='NotificationMessage';

        model_id=notification_type;

        $('#notification'+model_id).val(message);

        //condition for holiday
        if(notification_type==3){
            holiday_type=$('.holiday').val();
            if(holiday_type=='' || message.trim()==='' ){
                return toastr.error('Some Field Empty');
            }
            model_name='HolidayMessage';
            model_id=$('.holiday').val();
            $('#holiday'+model_id).val(message);
        }
        let formData={
            type:'saveMessage',
            _token:'{{csrf_token()}}',
            message:message.trim(),
            model_name:model_name,
            model_id:model_id
        }
        return postData(formData,'{{url('notifications')}}');
    }


</script>
