
<div class="card card-shadow card-inverse white bg-facebook">
    <div class="card-block p-20 h-full">
        <h3 class="white m-t-0">{{$activity->causer?$activity->causer->name:''}} made changes to their Dependant Information</h3>

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

                @endphp

                @foreach($activity->changes['attributes'] as  $key=>$change)
                    @php
                        $item_name=$key;
                           $item_name= str_replace(".", " ", $item_name);
                        $item_name= str_replace("_", " ", $item_name);
                    @endphp
                    @if($activity->description=='updated')
                        @if(($activity->changes['old'][$key]!=$activity->changes['attributes'][$key])&&$key!='user_id'&&$key!='emp_id'&&$key!='company_id')
                            <ul class="list-group ">
                                <li class=" list-group-item bg-grey-300 ">

                                    {{ucfirst($item_name)}}</li>

                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]}}</li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>


                            </ul>


                        @endif

                    @else
                        @if($key!='user_id'&&$key!='company_id')
                        <ul class="list-group ">
                            <li class=" list-group-item bg-grey-300 ">


                                {{ucfirst($item_name)}}</li>


                            <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>


                        </ul>
                            @endif
                    @endif
                @endforeach

                <form action="" id="approveChangeForm" method="POST">
                    <input type="hidden" name="eh_id" value="{{$activity->subject_id}}">
                    @php
                        $exemptions=['user_id','last_change_approved_on','last_change_approved','last_change_approved_by','company_id'];
                    @endphp

                    @foreach($activity->changes['attributes'] as  $key=>$change)
                        @if(!in_array($key, $exemptions))
                            <input type="hidden" name="{{$key}}" value="{{$activity->changes['attributes'][$key]}}">
                        @endif
                            @endforeach
                    @csrf
                    <input type="hidden" name="type" value="approve_dependant_changes">
                    <button type="submit" class="btn btn-success">Approve</button>
                    <button type="button" class="btn btn-danger" onclick="rejectChange({{$activity->subject_id}},{{$activity->causer_id}})">Reject</button>
                </form>

            @else
                <p>change was not captured</p>
            @endif
        </div>
    </div>
    <div >
    </div>


</section>
