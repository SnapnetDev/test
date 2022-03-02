<div class="card card-shadow card-inverse white bg-facebook">
    <div class="card-block p-20 h-full">
        <h3 class="white m-t-0">Payroll was {{$activity->description}}</h3>

        <div class="m-t-30">
            <i class="icon icon md-search-replace font-size-40"></i>

        </div>
    </div>
</div>

<section class="slidePanel-inner-section">
    <div class="card card-shadow card-inverse ">
        <div class="card-block p-20 h-full">
            <h3 class="grey-600">Changes</h3>


            @if(isset($activity->changes['attributes']))
            @php
                $hide=['updated_at','company_id','workflow_id'];
            @endphp

                @foreach($activity->changes['attributes'] as  $key=>$change)
                    @php
                        $item_name=$key;
                           $item_name= str_replace(".", " ", $item_name);
                        $item_name= str_replace("_", " ", $item_name);
                    @endphp
                    @if($activity->description=='updated')
                        @if(($activity->changes['old'][$key]!=$activity->changes['attributes'][$key])&&$key!='updated_at'&&$key!='company_id'&&$key!='workflow_id'&&$key!='user_id')
                            <ul class="list-group ">
                                <h4 class="list-group-item-heading list-group-item bg-grey-300 ">{{ucfirst($item_name)}}</h4>
                                @if($key=='payslip_issued'||$key=='approved')
                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]==1?"Yes":($activity->changes['old'][$key]==0?"No":"")}}</li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]==1?"Yes":($activity->changes['attributes'][$key]==0?"No":"")}}</li>

                                @else
                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['old'][$key]}}</li>
                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>

                                @endif
                            </ul>
                        @endif
                    @elseif($activity->description=='created')
                        @if($key!='updated_at'&&$key!='company_id'&&$key!='workflow_id'&&$key!='user_id')
                            <ul class="list-group ">
                                <h4 class="list-group-item-heading list-group-item bg-grey-300 ">{{ucfirst($item_name)}}</h4>
                                @if($key=='payslip_issued'||$key=='approved')

                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]==1?"Yes":($activity->changes['attributes'][$key]==0?"No":"")}}</li>

                                @else

                                    <li class="list-group-item bg-green-100 grey-600"><strong><i class="icon md-plus green-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>

                                @endif
                            </ul>
                        @endif
                    @elseif($activity->description=='deleted')
                        @if($key!='updated_at'&&$key!='company_id'&&$key!='workflow_id'&&$key!='user_id')
                            <ul class="list-group ">
                                <h4 class="list-group-item-heading list-group-item bg-grey-300 ">{{ucfirst($item_name)}}</h4>
                                @if($key=='payslip_issued'||$key=='approved')

                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]==1?"Yes":($activity->changes['attributes'][$key]==0?"No":"")}}</li>

                                @else

                                    <li class="list-group-item bg-red-100 grey-600"><strong><i class="icon md-minus red-900"></i></strong>&nbsp;{{$activity->changes['attributes'][$key]}}</li>

                                @endif
                            </ul>
                        @endif
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
