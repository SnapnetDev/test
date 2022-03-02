<div align="right" style="margin-bottom: 11px;">
    <button class="btn btn-sm btn-primary" onclick="$('#create-loan-type-form').modal();" href="#"> + Loan Type</button>
</div>

<div id="loan-type-list" style="min-height: 300px;">
    <b>
       <i> ... Loading ... </i>
    </b>
</div>

{{--<script src="https://unpkg.com/vue@3.0.0/dist/vue.global.js" defer></script>--}}
@include('payrollsettings.loan_type.loan_base_js')
@include('payrollsettings.loan_type.loan_type_grade_js')
@include('payrollsettings.loan_type.loan_js')
