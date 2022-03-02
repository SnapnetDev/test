@php
 if (isset($mode) && $mode == 'hr'){
   $cmd = 'respond-to-training-plan'; // 'approve-training-plan';
   $text = 'Training Approval Form';
   $js = 'onsubmit="return confirm(\'Do you want to confirm this action?\')"';
 }else{
   $cmd = 'update-training-plan';
   $text = 'Save Training Plan';
   $js = '';
 }




     $list  = [];
     if ($item->training_groups()->exists()){
        foreach ($item->training_groups as $obj){
           $list[] = [
              'text'=>$obj->group->name,
              'val'=>$obj->group->id
           ];
        }
     }


 $enableEditAttr = '';

 if ($item->status == 1){
  $enableEditAttr = ' disabled readonly ';
 }
//approveTrainingPlan_
@endphp


{{--<script>--}}
    {{--function GetContext{{ $item->id  }}(){--}}
        {{--return {--}}
                  {{--status:{{ $item->status }},--}}
                  {{--cost_per_head:{{ $item->cost_per_head }},--}}
                  {{--number_of_enrollees:{{ $item->number_of_enrollees }},--}}
                  {{--groups:{!! json_encode($list) !!},--}}
                  {{--groupObj:{val:0},--}}
                  {{--dep_id:{val: {{ $item->dep_id? $item->dep_id:0 }}},--}}
                  {{--role_id:{val: {{ $item->role_id? $item->role_id:0 }}}--}}
              {{--};--}}
    {{--}--}}
{{--</script>--}}

<form {!! $js !!} action="{{ route('process.action.command',[$cmd]) }}" method="post">

    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}" />

    <div id="update-training-plan{{ $item->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md" >



            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ $text }}</h4>
                </div>

                <div class="modal-body">


                    <div class="col-md-12">

                        <div>
                            {{--{{ $item->train_start }}--}}
                        </div>

                        <training-plan-form
                                training_groups="{{ json_encode($item->training_groups)  }}"
                                groups="{{ json_encode($vars['groups']) }}"
                                train_start="{{ $item->train_start }}"
                                train_stop="{{ $item->train_stop }}"
                                year_of_training="{{ $item->year_of_training }}"
                                departments='{{ json_encode($vars['departments']) }}'
                                dep_id="{{ $item->dep_id }}"
                                role_id="{{ $item->role_id }}"
                                roles='{{ json_encode($vars['roles']) }}'
                                locked="{{ ($item->status == 1)? 1 : 0 }}"
                                name="{{ $item->name }}"
                                cost_per_head = "{{ $item->cost_per_head }}"
                                number_of_enrollees = "{{ $item->number_of_enrollees }}"
                                status="{{ $item->status * 1 }}"
                                reason="{{ $item->reason }}"

                                @if (isset($hr))
                                    mode="hr"
                                @endif

                        ></training-plan-form>
                        {{--mode="hr"--}}




                    </div>



                </div>
                <div class="modal-footer">


                    @if ($item->status == 1)

                    <span style="
    float: left;
    padding-left: 15px;
    display: inline-block;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    color: green;
">Approved</span>
                    @endif


                        @if ($item->status == 2)

                            <span style="
    float: left;
    padding-left: 15px;
    display: inline-block;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    color: red;
">Rejected</span>
                        @endif

                   @if ( (isset($mode) && $mode == 'hr') && ($item->status == 0 ||  $item->status == 2) )
                    <button type="submit" class="btn btn-success btn-sm">Confirm Action</button>
                   @elseif ($item->status == 0 || $item->status == 2)
                     <button type="submit" class="btn btn-success btn-sm">Update</button>
                   @endif
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</form>
