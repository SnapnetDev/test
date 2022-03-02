@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <style media="screen">
    .form-cont{
      border: 1px solid #cccccc;
      padding: 10px;
      border-radius: 5px;
    }
    #compcont {
      list-style: none;
    }
    #compcont li{
      margin-bottom: 10px;
    }
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Off Payroll Items</h1>
      <div class="page-header-actions">
    <div class="row no-space w-250 hidden-sm-down">

      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

        </div>
      </div>
    </div>
  </div>
    </div>
    <div class="page-content container-fluid">
          @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                        {{ session('success') }}
                    </div>
                @endif
            <form class="form-horizontal" method="POST" id="itemForm">
              {{ csrf_field() }}
              <div class="panel panel-info panel-line" >
                <div class="panel-heading main-color-bg">
                  <h3 class="panel-title">Item Details</h3>
                </div>

                <div class="panel-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="">
                           
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Off Payroll Item Type</label>
                           <select class="form-control" name="type" id="type">
                               @foreach($types as $type)
                               <option value="{{$type->id}}">{{$type->name}}</option>
                               @endforeach
                           </select>
                           
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Source</label>
                           <select class="form-control"  name="source" id="source">
                              
                               <option value="payroll_constant">Payroll Constant</option>
                               <option value="salary_component_constant">Salary Component Constant</option>
                               <option value="amount">Amount</option>
                           </select>
                           
                        </div>
                    </div>
                    <div class="col-md-3" >
                      <div class="form-group" id="payroll_constant_div">
                          <label for="">Payroll Component</label>
                         <select name="payroll_constant" class="form-control" id="payroll_constant">
                            
                             <option value="basic_salary">Basic_salary</option>
                             
                         </select>
                         
                      </div>
                  
                    <div class="form-group" id="salary_component_constant_div">
                        <label for="">Salary Component</label>
                       <select name="salary_component_constant" id="salary_component_constant" class="form-control">
                          @foreach ($project_salary_components as $key=>$project_salary_component)
                           <option value="{{$key}}">{{$project_salary_component}}</option>
                           @endforeach
                           
                       </select>
                       
                    </div>
               
                  <div class="form-group" id="amount_div">
                      <label for="">Amount</label>
                      <input type="text" class="form-control" id="amount" name="amount" placeholder="">
                     
                  </div>
              </div>
              <div class="col-md-3" >
                <div class="form-group" >
                    <label for="">Is Prorated</label>
                   <select class="form-control" name="is_prorated" id="is_prorated">
                      
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                       
                   </select>
                   
                </div>
              </div>
              <div class="col-md-3" >
                <div class="form-group">
                  <label for="">Percentage</label>
                  <input type="text" class="form-control" id="percentage" name="percentage" placeholder="">
                 
              </div>
              </div>
                </div>

                </div>

                </div>

                <div class="panel  panel-info panel-line">
                  <div class="panel-heading ">
                    <h3 class="panel-title">Component Details</h3>
                  </div>

                  <div class="panel-body">
                    <ul id="compcont">

                    </ul>
                    <button type="button" id="addComp" name="button" class="btn btn-primary">New Component</button>
                  </div>
                  </div>
                  <button type="submit" class="btn btn-primary">
                      Save Off Payroll Item
                  </button>
                </form>

  </div>
</div>

@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.users').select2();
  $('#compcont').sortable();
  $('#salary_component_constant').attr("disabled",true);
              $('#salary_component_constant_div').hide();
              $('#amount').attr("disabled",true);
              $('#amount_div').hide();
              

  var compcont = $('#compcont');
        var i = $('#compcont li').length + 1;

        $('#addComp').on('click', function() {
          //console.log('working');
                $(' <li><div class="form-cont" > <div class="form-group"> <label for="">Percentage</label> <input type="text" class="form-control" name="comp_percentage[]" id="" placeholder="" required> </div><div class="form-group type"> <label for="">Source</label> <select class="form-control select-source " name="comp_source[]" >  <option value="payroll_constant">Payroll Component</option> <option value="salary_component">Salary Component </option> <option value="amount">Amount</option></select> </div> <div class="form-group payroll_constant-div"> <label for="">Payroll Component</label> <select class="form-control payroll_constants" name="comp_payroll_constant[]" ><option value="basic_salary">Basic Salary</option>  </select> </div> <div class="form-group salary_component_constant-div"> <label for="">Salary Component</label> <select class="form-control salary_component_constants" name="comp_salary_component[]" >   @foreach ($project_salary_components as $key=>$component) <option value="{{$key}}">{{$component}}</option> @endforeach </select> </div><div class="form-group amount-div">  <label for="">Amount</label><input type="number" step="0.01" name="comp_amount[]" class="form-control amount_components"> </div> <div class="form-group"> <button type="button" class="btn btn-primary " id="remComp">Remove Component</button> </div> </div> </li>').appendTo(compcont);
                //console.log('working'+i);
               $('#compcont li').last().find('.salary_component_constant-div').hide();
               $('#compcont li').last().find('.salary_component_constant-div').find('.salary_component_constants').attr("disabled",true);
                $('#compcont li').last().find('.amount-div').hide();
               $('#compcont li').last().find('.amount-div').find('.amount_components').attr("disabled",true);
                i++;
                return false;
        });

        $(document).on('click',"#remComp",function() {
          //console.log('working'+i);
                if( i > 1 ) {
                   console.log('working'+i);
                        $(this).parents('li').remove();
                        i--;
                }
                return false;
    });
        $(document).on('change',"#source",function() {


         if (this.value=='payroll_constant')
            {
              $('#salary_component_constant').attr("disabled",true);
              $('#salary_component_constant_div').hide();
              $('#amount').attr("disabled",true);
              $('#amount_div').hide();
              $('#payroll_constant').removeAttr("disabled");
              $('#payroll_constant_div').show();


            }

          if (this.value=='salary_component_constant')
            {
              $('#amount').attr("disabled",true);
              $('#amount_div').hide();
              $('#payroll_constant').attr("disabled",true);
              $('#payroll_constant_div').hide();
              $('#salary_component_constant').removeAttr("disabled");
              $('#salary_component_constant_div').show();


            }
            if (this.value=='amount')
            {
              $('#payroll_constant').attr("disabled",true);
              $('#payroll_constant_div').hide();
              $('#salary_component_constant').attr("disabled",true);

              $('#amount').removeAttr("disabled");
              $('#amount_div').show();
              $('#salary_component_constant_div').hide();


            }


          });
          $(document).on('change',".select-source",function() {


if (this.value=="payroll_constant")
   {
     $(this).parents('li').find('.payroll_constant-div').find('.payroll_constants').attr("disabled",true);
     $(this).parents('li').find('.payroll_constant-div').hide();
     $(this).parents('li').find('.salary_component_constant-div').find('.salary_component_constants').attr("disabled",true);
     $(this).parents('li').find('.salary_component_constant-div').hide();
      $(this).parents('li').find('.amount-div').find('.amount_components').removeAttr("disabled");
    $(this).parents('li').find('.amount-div').show();


   }

 if (this.value=="salary_component")
   {
     $(this).parents('li').find('.payroll_constant-div').find('.payroll_constants').attr("disabled",true);
     $(this).parents('li').find('.payroll_constant-div').hide();
     $(this).parents('li').find('.amount-div').find('.amount_components').attr("disabled",true);
     $(this).parents('li').find('.amount-div').hide();
      $(this).parents('li').find('.salary_component_constant-div').find('.salary_component_constants').removeAttr("disabled");
    $(this).parents('li').find('.salary_component_constant-div').show();


   }
   if (this.value=="amount")
   {
     $(this).parents('li').find('.payroll_constant-div').find('.payroll_constants').attr("disabled",true);
     $(this).parents('li').find('.payroll_constant-div').hide();
     $(this).parents('li').find('.salary_component_constant-div').find('.salary_component_constants').attr("disabled",true);

       $(this).parents('li').find('.amount-div').find('.amount_components').removeAttr("disabled");
       $(this).parents('li').find('.amount-div').show();
    $(this).parents('li').find('.salary_component_constant-div').hide();


   }


 });
});
</script>
<script>
  itemForm.onsubmit = async (e) => {
e.preventDefault();

let response = await fetch('{{url('/items')}}', {
  method: 'POST',
  body: new FormData(itemForm)
});

let result = await response.json();
if(result.success){
        console.log('success');
        location.assign('{{url('items')}}')
    }else{
        console.log('error');
    }
};
 
</script>

@endsection
