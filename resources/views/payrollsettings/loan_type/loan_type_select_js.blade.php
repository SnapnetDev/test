@include('payrollsettings.loan_type.loan_base_js')
<script>

  var LoanTypeSelect =  (function(mixin){



     return function(){

         var $root = null;
         var list = [];

         return {


             mount($el){

                 $root = $(`<select name="loan_type_id" class="form-control"></select>`);

                 $el.html('');
                 $el.append($root);

                 this.loadResolvedLoanTypes();
                 this.initBindings();

             },

             attachInterestRateCalc($payment,$amount,obj){

                 var ir = obj.interest_rate/12 * $amount.val()/100;
                 $payment.val(ir.toFixed(2));

             },

             attachAmountCalc($payment,$amount,obj){


                 // $amount.off('keyup');
                 // $amount.on('keyup',(evt)=>{
                 //
                 //     this.attachInterestRateCalc($payment,$amount,obj);
                 //
                 // });
                 $amount.trigger('keyup');

             },



             initBindings(){
                 $root.on('change',()=>{
                     // alert('changed...');

                     var $amount = $('#amount');
                     var $payment = $('#payment');

                     var obj = this.searchLoanTypeById($root.val());

                     $('input[name="rate"]').val(obj.interest_rate);
                     $('input[name="rate"]').attr('readonly','readonly');

                     $('input[name="period"]').val(obj.repayment_period);
                     $('input[name="period"]').attr('readonly','readonly');

                     // this.attachInterestRateCalc($payment,$amount,obj);
                     this.attachAmountCalc($payment,$amount,obj);

                     if (obj.pace_salary_component){
                         $('input[name="amount"]').val(obj.pace_salary_component.amount * obj.multiplier_index);
                         $('input[name="amount"]').attr('readonly','readonly');

                         return;
                     }


                     $('input[name="amount"]').val(0);
                     $('input[name="amount"]').removeAttr('readonly');

                 });
             },

             searchLoanTypeById(id){
                var found = {};
                found = list.find((v)=>{
                    return v.id == id;
                });
                return found;
             },

             loadResolvedLoanTypes(){

                 mixin.doFetch('{{ route('get.resolved.loan.types') }}').then((response)=>{

                     $root.html(`<option value="">--Select Loan Type--</option>`);

                     list = response.list;

                     response.list.forEach((item)=>{

                         var $each = `<option value="${item.id}">${item.name}</option>`;
                         $root.append($each);

                     });


                     this.initBindings();

                 });

             }


         };

     };


    })(mixinCrud);


</script>