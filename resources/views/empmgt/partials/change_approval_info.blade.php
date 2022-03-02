<style>
    /* The container */
    .chkcontainer {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .chkcontainer input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .chkcontainer:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .chkcontainer input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .chkcontainer input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .chkcontainer .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>

<div class="card card-shadow card-inverse white bg-facebook">
    <div class="card-block p-20 h-full">
        <h3 class="white m-t-0">{{$activity->causer?$activity->causer->name:''}} made changes to {{$activity->subject?$activity->subject->name:''}}</h3>

        <div class="m-t-30">
            <i class="icon icon md-search-replace font-size-40"></i>

        </div>
    </div>
</div>

<section class="slidePanel-inner-section" id="panel">
    <div class="card card-shadow card-inverse ">
        <div class="card-block p-20 h-full">
            <h3 class="grey-600">Changes</h3>


            @if($activity->changes!='')
                @php
                    //$change_count= count($activity->changes['old']);
                    $hide=['password','id',];

                 $classes=['name'=>'name','first_name'=>'first_name','middle_name'=>'middle_name','last_name'=>'last_name', 'email'=>'email', 'emp_num'=>'emp_num','sex'=>'sex','dob'=>'dob','phone'=>'phone','marital_status'=>'marital_status',
                 'branch.name'=>'branch_id','branch_id'=>'branch_id','payroll_type'=>'payroll_type','project_salary_category.name'=>'project_salary_category_id','project_salary_category_id'=>'project_salary_category_id','confirmation_date'=>'confirmation_date',
                'hiredate'=>'hiredate','image'=>'image','address'=>'address','lga_id'=>'lga_id','lga.name'=>'lga_id','bank_id'=>'bank_id','bank.bank_name'=>'bank_id','bank_account_no'=>'bank_account_no','state_id'=>'state_id','state.name'=>'state_id','country_id'=>'country_id','country.name'=>'country_id','expat'=>'expat'];
                @endphp
                <label class="chkcontainer">Select All
                    <input type="checkbox" checked="checked" id="selectAll">
                    <span class="checkmark"></span>
                </label>

                @foreach($activity->changes['attributes'] as  $key=>$change)
                    @php
                        $item_name=$key;
                           $item_name= str_replace(".", " ", $item_name);
                        $item_name= str_replace("_", " ", $item_name);
                    @endphp
                    @if($activity->description=='updated')
                        @if(($activity->changes['old'][$key]!=$activity->changes['attributes'][$key])&&$key!='updated_at'&&$key!='role_id'&&$key!='lga_id'&&$key!='state_id'&&$key!='bank_id'&&$key!='country_id'&&$key!='grade_id'&&$key!='line_manager_id'&&$key!='project_salary_category_id'&&$key!='union_id'&&$key!='section_id'&&$key!='user_id'&&$key!='last_change_approved')
                            <ul class="list-group ">
                                <li class=" list-group-item bg-grey-300 ">
                                    @if(array_key_exists($key, $classes))

                                        <label class="chkcontainer pull-left">
                                            <input type="checkbox" checked="" id="{{$classes[$key]}}" class=" chk">
                                            <span class="checkmark"></span>
                                        </label>
                                    @endif
                                    {{ucfirst($item_name)}}</li>
                                @if($key=='employment_status')
                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]==1?"Confirmed":($activity->changes['old'][$key]==0?"Probation":($activity->changes['old'][$key]==2?"Disengaged":""))}}</li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]==1?"Confirmed":($activity->changes['attributes'][$key]==0?"Probation":($activity->changes['attributes'][$key]==2?"Disengaged":""))}}</li>
                                @elseif($key=='expat')
                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]==1?"Expatriate":($activity->changes['old'][$key]==0?"National":"")}}</li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]==1?"Expatriate":($activity->changes['attributes'][$key]==0?"National":"")}}</li>
                                @elseif($key=='image')
                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;<img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150" src="{{ file_exists(public_path('uploads/avatar'.$activity->changes['old'][$key]))?asset('uploads/avatar'.$activity->changes['old'][$key]):($user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="..." id='img-upload'></li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;<img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150" src="{{ file_exists(public_path('uploads/avatar'.$activity->changes['attributes'][$key]))?asset('uploads/avatar'.$activity->changes['attributes'][$key]):($user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="..." id='img-upload'></li>

                                @else
                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]}}</li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>


                                @endif
                            </ul>


                        @endif
{{--                        @if($activity->changes['attributes'][$key]=='image' )--}}
{{--                            <table>--}}
{{--                                <tr>--}}
{{--                                    <th>Old</th>--}}
{{--                                    <th>New</th>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    @if(isset($activity->changes['old']))--}}
{{--                                        <td><img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150" src="{{ file_exists(public_path('uploads/avatar'.$activity->changes['attributes'][$key]))?asset('uploads/avatar'.$activity->changes['attributes'][$key]):($user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="..." id='img-upload'></td>--}}
{{--                                        @else--}}
{{--                                        <td><img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150" src="{{ $user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png')}}" alt="..." id='img-upload'></td>--}}
{{--                                        @endif--}}

{{--                                    <td><img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150" src="{{ file_exists(public_path('uploads/avatar'.$activity->changes['attributes'][$key]))?asset('uploads/avatar'.$activity->changes['attributes'][$key]):($user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="..." id='img-upload'></td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}

{{--                        @endif--}}
                    @else
                    @endif
                @endforeach

                <form action="" id="approveChangeForm" method="POST">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    @php
                        $exemptions=['company.name','branch.name','job.title','role.name','lga.name','bank.bank_name','state.name','country.name','grade.level','plmanager.name','project_salary_category.name','union.name','section.name','updated_at','last_change_approved','last_change_approved_on','last_change_approved','last_change_approved_by'];
                    @endphp

                    @foreach($activity->changes['attributes'] as  $key=>$change)
                        @if(!in_array($key, $exemptions))
                            <input type="hidden" name="{{$key}}" class="{{array_key_exists($key, $classes)?$classes[$key]:''}}" value="{{$activity->changes['attributes'][$key]}}">
                        @endif
                    @endforeach
                    @csrf
                    <input type="hidden" name="type" value="approve_profile_changes">
                    <p>Please ensure to contact employee before approving or rejecting change</p>
                    <button type="submit" class="btn btn-success">Approve</button>
                    <button type="button" class="btn btn-danger" onclick="rejectChange({{$activity->subject_id}})">Reject</button>
                </form>

            @else
                <p>change was not captured</p>
            @endif
        </div>
    </div>
    <div >
    </div>


</section>
