
<div class="col-md-12">
    <h4>
       <u>Courses</u>
    </h4>
</div>


<div class="col-md-12" style="padding: 0;">

    <div class="nav-tabs-vertical" data-plugin="tabs">


        <ul class="nav nav-tabs nav-tabs-line mr-25" role="tablist">
            @foreach ($courses['data'] as $k=>$v)
                @php
                    $cls = '';
                    if ($k == 0){
                       $cls = 'active';
                    }
                @endphp
                <li class="nav-item" role="presentation"><a id="tr{{ $v['id'] }}head" class="nav-link {{ $cls }}" data-toggle="tab" href="#tr{{ $v['id'] }}" aria-controls="exampleTabsLineLeftOne" role="tab" aria-selected="true">{{ $v['fullname'] }}</a></li>
            @endforeach
        </ul>
        <div class="tab-content py-15">

            @foreach ($courses['data'] as $k=>$v)
                @php
                    $cls = '';
                    if ($k == 0){
                       $cls = 'active';
                    }
                @endphp
                <div class="tab-pane {{ $cls }} col-md-12" id="tr{{ $v['id'] }}" role="tabpanel" style="padding-top: 14px;padding-bottom: 29px;border: 1px solid #eee;">

                    <div class="col-md-12">
                        <b><u>{{ $v['fullname'] }}</u></b>
                    </div>

                    <div class="col-md-12">
                        <b>Progress Status</b>
                    </div>

                    <div class="col-md-12" style="text-align: right;">
                        <b>0%</b>
                    </div>

                    <div class="col-md-12">
                        <b>Enrollement Status</b> (<i>already - enrolled</i>)
                    </div>

                    <div class="col-md-12" style="text-align: right;">
                        <form action="{{ route('process.action.command',['enroll-user']) }}" method="post">
                            @csrf
                            <input type="hidden" name="userId" value="{{ $userId }}" />
                            <input type="hidden" name="js-trigger-click-id[]" value="p-training-header" />
                            <input type="hidden" name="js-trigger-click-id[]" value="tr{{ $v['id'] }}head" />
                            {{--&js-trigger-click-id[]=p-training-header&js-trigger-click-id[]=tr4head--}}
                            <button type="submit" class="btn btn-sm btn-success">Enroll</button>
                        </form>
                    </div>

                </div>


            @endforeach


        </div>
    </div>

</div>


