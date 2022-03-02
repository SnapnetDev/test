@extends('ui.basic_crud')

@section('page-header')
    Upload Training Budget
@endsection

@section('create-target')
    #create-training-budget
@endsection

@section('create-label')
    Add Training Budget
@endsection

@section('crud-table')


    <style>
        .approval1{

            background-color: rgba(0,255,0,0.1);
            color: #000;

        }

        .approval2{
            background-color: rgba(255,0,0,0.1);
            color: #000;
        }
    </style>


    {{--@include('training_new.searchbar')--}}


    <form action="" method="get" style="display: block;margin-bottom: 11px;">
    <div class="row">

        <div class="col-md-2" style="padding: 0;">
            <select class="form-control" name="year">
                <option value="">--Select Year--</option>
                <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
            </select>
        </div>
        <div class="col-md-2" style="padding: 0;">
            <button type="submit" style="padding: 7px;" class="btn btn-sm btn-success">Filter</button>
        </div>
        <div class="col-md-6" style="color: #2c7cc1;">
            <label for="" style="
    font-size: 17px;
    font-weight: bold;
    /*color: #777;*/
    text-transform: uppercase;">{{ $prevYear['label'] }} N {{ number_format($prevYear['data']['sum']) }}</label>
            <span style="font-size: 21px;">|</span>

            <label for="" style="
    font-size: 17px;
    font-weight: bold;
    /*color: #777;*/
    text-transform: uppercase;">{{ $currentYear['label'] }} N {{ number_format($currentYear['data']['sum']) }}</label>
        </div>

    </div>
    </form>


    <table class="table">
        <tr>
            <th>
                Grade
            </th>
            <th>
                Name Of Training
            </th>
            <th>
                Allocation Total
            </th>
            <th>
                Year Of Allocation
            </th>
            <th>
                Date Created
            </th>
            <th>
                Actions
            </th>
        </tr>

        @foreach ($list as $k=>$v)


            <tr class="approval{{ $v->status }}">
                <td>
                    {{ $v->grade->level }}
                </td>
                <td>
                    {{ $v->training_budget_name }}
                </td>
                <td>
                    {{ $v->allocation_total }}
                </td>
                <td>
                    {{ $v->year_of_allocation }}
                </td>
                <th>
                    {{ $v->created_at }}
                </th>
                <th>
                    <a data-toggle="modal" data-target="#update-training-budget{{ $v->id }}" style="color: #fff;" class="btn btn-sm btn-info">Edit</a>

                    @if ($v->status == 0)
                        <form onsubmit="return confirm('Do you want to remove this training budget?')" method="post" action="{{ route('process.action.command',['delete-training-budget']) }}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $v->id }}" />
                            <button type="submit" style="color: #fff;" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    @endif
                </th>
            </tr>


        @endforeach


    </table>


    {{--<div b-context="root()">--}}
        {{--<div b-text="version">--}}
        {{--</div>--}}
        {{--<div b-loop="list">--}}
            {{--<script type="text/html">--}}

              {{--<span b-context="{}">--}}
                {{--<div b-text="name"></div>--}}
                  {{--<input type="text" b-sync="name" />--}}

              {{--</span>--}}

            {{--</script>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div b-context="{a:23,init:function(){ var $this = this; this.listen('foo',function(v){ $this.a = v; }); }}">--}}
        {{--<div b-text="a"></div>--}}
        {{--<input type="text" b-sync="a" />--}}
        {{--<button b-on="['click',function(){ this.publish('goo',this.a); }]">Send</button>--}}
    {{--</div>--}}

    {{--<div b-context="{a:43,init:function(){ var $this = this; this.listen('goo',function(v){ $this.a = v; }); }}">--}}
        {{--<div b-text="a"></div>--}}
        {{--<input type="text" b-sync="a" />--}}
        {{--<button b-on="['click',function(){ this.publish('foo',this.a); }]">Send</button>--}}
    {{--</div>--}}


    {{--<div b-context="root()">--}}
        {{--<input type="text" b-sync="b" />--}}
        {{--<input type="text" b-sync="clr" />--}}
        {{--<input type="text" b-sync="bg" b-show="b >= a" />--}}
        {{--<input type="checkbox" b-checked="chk" />--}}
        {{--<input type="radio" b-checked="(b >= a)" />--}}
        {{--<input b-css="{color:clr,backgroundColor:bg}" type="text" b-value="a" b-readonly="(b >= a)" b-disabled="(b >= a)"/>--}}

        {{--<input type="checkbox" b-checked="chk" />--}}

        {{--<div b-loop="listr">--}}
            {{--<script type="text/html">--}}
                {{--<span>--}}
                    {{--<div b-text="a"></div>--}}
                    {{--<input type="text" b-sync="a" />--}}
                {{--</span>--}}
            {{--</script>--}}
        {{--</div>--}}

    {{--</div>--}}

    <script>

        function root(){

            return {

                list:[],

                version:'1.0.0',

                init:function(){
                    this.fetchNames();
                },
                fetchNames:function(){
                    this.ajax({
                        url:'{{ route('app.get',['getAjax']) }}',
                        type:'get',
                        data:{},
                        success:function(response){
                            this.list = response;
                        }
                    });
                }


            };

        }

        function handleKeyUp(){
            this.b = this.$el.val();
            console.log(this,this.$el);
        }
        // function(){ this.$self.b = this.$el.val(); }
    </script>


@endsection

@section('edit-modals')

    @foreach ($list as $item)
        @include('training_new.modals.budget_edit')
    @endforeach

@endsection

@section('create-modal')
    @include('training_new.modals.budget_create')
@endsection

@section('crud-script')

    {{--@include('training_new.js_framework.binder')--}}
    @include('training_new.js_framework.binder_v2')

@endsection