<div class="card card-shadow card-inverse white bg-facebook">
    <div class="card-block p-20 h-full">
        <h3 class="white m-t-0">{{$activity->causer?$activity->causer->name:''}} made changes to {{$activity->subject?$activity->subject->name:''}}</h3>

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
                    $hide=['password','id',]
                @endphp

                @foreach($activity->changes['attributes'] as  $key=>$change)
                    @php
                        $item_name=$key;
                           $item_name= str_replace(".", " ", $item_name);
                        $item_name= str_replace("_", " ", $item_name);
                    @endphp
                @if($activity->description=='updated')
                    @if(($activity->changes['old'][$key]!=$activity->changes['attributes'][$key])&&$key!='updated_at'&&$key!='role_id'&&$key!='lga_id'&&$key!='state_id'&&$key!='bank_id'&&$key!='country_id'&&$key!='grade_id'&&$key!='line_manager_id'&&$key!='project_salary_category_id'&&$key!='union_id'&&$key!='section_id')
                        <ul class="list-group ">
                            <h4 class="list-group-item-heading list-group-item bg-grey-300 ">{{ucfirst($item_name)}}</h4>
                            @if($key=='employment_status')
                                <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]==1?"Confirmed":($activity->changes['old'][$key]==0?"Probation":($activity->changes['old'][$key]==2?"Disengaged":""))}}</li>
                                <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]==1?"Confirmed":($activity->changes['attributes'][$key]==0?"Probation":($activity->changes['attributes'][$key]==2?"Disengaged":""))}}</li>
                                @elseif($key=='expat')
                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]==1?"Expatriate":($activity->changes['old'][$key]==0?"National":"")}}</li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]==1?"Expatriate":($activity->changes['attributes'][$key]==0?"National":"")}}</li>
                            @else
                                <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]}}</li>
                                <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>

                            @endif
                        </ul>


                    @endif
                    @else
                    @endif
                @endforeach

            @else
                <p>change was not captured</p>
            @endif
        </div>
    </div>
    <div >
    </div>


</section>
