

@usercan(upload_training_plan)
<li class="site-menu-item ">
    <a class="animsition-link" href="{{ route('offline_training.index') }}?{{ uniqid() }}">
        <span class="site-menu-title">Manage Training Plans</span>
    </a>
</li>
@endusercan

{{--@usercan(approve_training_plan)--}}
{{--<li class="site-menu-item ">--}}
    {{--<a class="animsition-link" href="{{ route('app.get',['fetch-training-plan-for-approval']) }}">--}}
        {{--<span class="site-menu-title">Approve Training Plan</span>--}}
    {{--</a>--}}
{{--</li>--}}
{{--@endusercan--}}


{{--@usercan(upload_training_budget)--}}
{{--<li class="site-menu-item ">--}}
    {{--<a class="animsition-link" href="{{ route('app.get',['fetch-my-training-budget']) }}">--}}
        {{--<span class="site-menu-title">Upload Training Budget</span>--}}
    {{--</a>--}}
{{--</li>--}}
{{--@endusercan--}}
