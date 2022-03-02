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
        <h3 class="white m-t-0">{{$activity->causer?$activity->causer->name:''}} made changes to his Next of Kin Information</h3>

        <div class="m-t-30">
            <i class="icon icon md-search-replace font-size-40"></i>

        </div>
    </div>
</div>

<section class="slidePanel-inner-section">
    <div class="card card-shadow card-inverse ">
        <div class="card-block p-20 h-full">
            <h3 class="grey-600">Changes</h3>

            @if($activity->changes!='')
                @php
                    //$change_count= count($activity->changes['old']);
                    $hide=['password','id',];
                $classes=['name'=>'name','address'=>'address','relationship'=>'relationship','phone'=>'phone'];
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
                        @if(($activity->changes['old'][$key]!=$activity->changes['attributes'][$key])&&$key!='updated_at'&&array_key_exists($key, $classes))
                            <ul class="list-group ">
                                <li class=" list-group-item bg-grey-300 ">
                                    @if(array_key_exists($key, $classes))

                                        <label class="chkcontainer pull-left">
                                            <input type="checkbox" checked="" id="{{$classes[$key]}}" class=" chk">
                                            <span class="checkmark"></span>
                                        </label>
                                    @endif
                                    {{ucfirst($item_name)}}</li>

                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]}}</li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>


                            </ul>


                        @endif

                    @else
                        @if($key!='updated_at'&&array_key_exists($key, $classes))
                        <ul class="list-group ">
                            <li class=" list-group-item bg-grey-300 ">
                                @if(array_key_exists($key, $classes))

                                    <label class="chkcontainer pull-left">
                                        <input type="checkbox" checked="" id="{{$classes[$key]}}" class=" chk">
                                        <span class="checkmark"></span>
                                    </label>
                                @endif
                                {{ucfirst($item_name)}}</li>


                            <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>


                        </ul>
                            @endif
                    @endif
                @endforeach

                <form action="" id="approveChangeForm" method="POST">
                    <input type="hidden" name="user_id" value="{{$nok->user->id}}">
                    @php
                        $exemptions=['user_id','last_change_approved_on','last_change_approved','last_change_approved_by'];
                    @endphp

                    @foreach($activity->changes['attributes'] as  $key=>$change)
                        @if(!in_array($key, $exemptions))
                            <input type="hidden" name="{{$key}}" class="{{array_key_exists($key, $classes)?$classes[$key]:''}}" value="{{$activity->changes['attributes'][$key]}}">
                        @endif
                            @endforeach
                    @csrf
                    <input type="hidden" name="type" value="approve_nok_changes">
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
