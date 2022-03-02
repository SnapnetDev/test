<div class="card card-shadow card-inverse white bg-facebook">
    <div class="card-block p-20 h-full">
        @if($activity->subject_type === 'App\LeaveRequest' && $activity->description == 'created')
            <h3 class="white m-t-0">{{$activity->subject?$activity->subject->name:''}} Leave Request
                was {{$activity->description}} {{$activity->causer?'by '.$activity->causer->name:''}}</h3>
        @elseif($activity->subject_type === 'App\LeaveRequest' && $activity->description == 'updated')
            <h3 class="white m-t-0">{{$activity->causer?$activity->causer->name:''}} made changes
                to {{$activity->subject?$activity->subject->user->name:''}}</h3>

            {{--   Capture Leave Approvals         --}}
        @elseif($activity->subject_type === 'App\LeaveApproval')
            <h3 class="white m-t-0">{{$activity->causer?$activity->causer->name:''}} has approved
                {{$activity->subject?$activity->subject->leave_request->user->name.' Leave
                Request': ''}}</h3>

        @endif
        <div class="m-t-30">
            <i class="icon icon md-search-replace font-size-40"></i>

        </div>
    </div>
</div>

<section class="slidePanel-inner-section">
    <div class="card card-shadow card-inverse ">
        <div class="card-block p-20 h-full">
            <h3 class="grey-600">Changes</h3>

            @if($activity->description == 'created' || $activity->description == 'deleted')

                <h4 class="list-group-item-heading list-group-item bg-grey-300 ">Created At</h4>
                <ul class="list-group ">
                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus
                                    green-900"></i></strong>&nbsp; {{$activity->created_at->toDayDateTimeString()}}
                    </li>
                </ul>
            @elseif($activity->changes == '')
                <p>change was not captured</p>
            @endif

            {{-- capture changes if event occurred in LeaveRequest Model --}}
            @if($activity->description == 'updated' && $activity->changes != '' && $activity->subject_type == 'App\LeaveRequest')
                <ul class="list-group ">
                    @foreach($activity->changes['attributes'] as $attrKey => $attrValue)
                        {{!!! $attrItem = str_replace(['_', '.'], ' ', $attrKey) }}
                        <h4 class="list-group-item-heading list-group-item bg-grey-300 "> {{ ucfirst($attrItem) }} </h4>
                        <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong> {{
                        $activity->changes['old'][$attrKey] === 0 ? 'Pending' : $activity->changes['old'][$attrKey]
                         }} </li>
                        <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus
                                    green-900"></i></strong>&nbsp; {{ $attrValue === 1 ? 'Approved' : $attrValue}}
                        </li>
                    @endforeach
                </ul>
            @elseif($activity->changes == '' )
                <p>change was not captured</p>
            @endif

            {{-- capture changes if event occurred in LeaveApproval Model --}}
            @if($activity->description == 'updated' && $activity->changes != '' && $activity->subject_type == 'App\LeaveApproval')
                <ul class="list-group ">
                    @foreach($activity->changes['attributes'] as $attrKey => $attrValue)
                        {{!!! $attrItem = str_replace(['_', '.'], ' ', $attrKey) }}
                        <h4 class="list-group-item-heading list-group-item bg-grey-300 "> {{ ucfirst($attrItem) }} </h4>

                        <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong> {{
                        $activity->changes['old'][$attrKey] === 0 ? 'Pending' : $activity->changes['old'][$attrKey]
                         }} </li>
                        <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus
                                    green-900"></i></strong>&nbsp; {{ $attrValue === 1 ? 'Approved' : $attrValue}}
                        </li>
                    @endforeach
                </ul>
            @elseif($activity->changes == '' )
                <p>change was not captured</p>
            @endif


        </div>
    </div>
    <div>
    </div>


</section>