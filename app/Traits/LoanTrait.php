<?php
namespace App\Traits;
use App\Grade;
use App\LoanPolicy;
use App\Payroll;
use App\Bank;
use App\Company;
use App\CompanyAccountDetail;
use App\PayslipDetail;
use App\PayrollDetail;
use App\PayrollPolicy;
use App\SalaryComponent;
use App\Services\LoanApprovalService;
use App\Services\SpecificSalaryWriter;
use App\SpecificSalaryComponent;
use App\Workflow;
use App\LatenessPolicy;
use App\Setting;
use App\Loan;
use App\User;
use App\Holiday;
use App\LoanRequest;
use App\LoanApproval;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;
use Excel;

trait LoanTrait{

	public function processGetLoan($route,Request $request)
	{
        // return $request->all();
		switch ($route) {
			case 'loan_requests':

				return $this->getLoanList($request);
				break;
			case 'my_loan_requests':

				return $this->myLoanList($request);
				break;
			case 'approve_loan_request':

				return $this->approveLoan($request);
				break;
			case 'loan_excel':

				return $this->loanExcel($request);
				break;

			default:
				# code...
				break;
		}
	}
	public function processPostLoan(Request $request)
	{
		switch ($request->type) {
			case 'loan_request':
				return $this->saveLoanRequest($request);
				break;

			default:
				# code...
				break;
		}
	}

	public function myLoanList(Request $request)
	{
		$loan_requests=Auth::user()->loan_requests()->paginate(5);

		return view('loan.mylist',compact('loan_requests'));
	}

	public function getLoanList(Request $request)
	{
		$loan_requests=LoanRequest::paginate(5);

		return view('loan.list',compact('loan_requests'));
	}

	public function saveLoanRequest(Request $request)
	{
        $userId = \Auth::user()->id;
        $guarantor_id = request('guarantor_id');

        if (Grade::userHasHigherGrade($guarantor_id,$userId)){
            return 'Guarantor"s band is higher than yours!';
        }

//        return '101';
//        dd(101);


		$netpay=$request->netpay;
		$maximum_allowed=$netpay*(intval(Setting::where('name','loan_allowed')->first()->value)/100);
		$annual_interest=intval(Setting::where('name','loan_interest')->first()->value);
		$monthly_rate=$annual_interest/12/100;
		$start=1;
		$monthly_payment=0;
		$length=1+$monthly_rate;
		for ($i=0; $i <$request->period ; $i++) {
			$start=$start*$length;
		}
		$repayment_starts=date('Y-m-d',strtotime($request->starts.'-01'));
		$payment=$monthly_payment=$request->amount*$monthly_rate/(1-(1/$start));
		$total_amount_repayable=$payment*$i;
		$total_interest=$total_amount_repayable-$request->amount;
		$loan_policy=LoanPolicy::where(['company_id'=>companyId()])->first();
		if ($loan_policy){
            $loan_request=LoanRequest::create([
                'user_id'=>Auth::user()->id,
                'netpay'=>$netpay,
                'amount'=>$request->amount,
                'monthly_deduction'=>$payment,
                'period'=>$request->period,
                'current_rate'=>$request->get('rate'), //$annual_interest,
                'repayment_starts'=>$repayment_starts,
                'status'=>0,
                'completed'=>0,
                'workflow_id'=>$loan_policy->workflow_id,
                'company_id'=>companyId(),
                'loan_type_id'=>$request->loan_type_id
            ]);


//            $stage = Workflow::find($loan_policy->workflow_id)->stages->first();
            (new LoanApprovalService)->initStage($loan_policy->workflow_id,$loan_request->id,$loan_request->user);
//			$stage=Workflow::find($loan_policy->workflow_id)->stages->first();
//        $loan_approval=LoanApproval::create(['stage_id'=>$stage->id,'status'=>0,'loan_request_id'=>$loan_request->id]);
        }

//		return $loan_request;

//		dd($loan_request);

			if(isset($loan_request)){

				return 'success';
			}else{
				return 'failed';
			}


	}
	public function approveLoan(Request $request)
	{

		$loan_request=loanRequest::find($request->loan_request_id);
		$user = $loan_request->user;

		$obj = new SpecificSalaryWriter;

		$obj->writeConfig([

			'duration'=>$loan_request->period,
			'amount'=>$loan_request->amount,
			'specific_salary_component_type_id'=>$loan_request->specific_salary_component_type_id

		],$user);

	   if ($loan_request){
	     $loan_request->update(['status'=>1,'approved_by'=>Auth::user()->id]);
	   }

	   return 'success';

	}

	public function loanExcel(Request $request)
	{
		$loan_request_id=$request->loan_request_id;
		$loan_request=LoanRequest::find($loan_request_id);
		$netpay=333245;
		$view='loan.loan_schedule';
		$this->exportToExcel($loan_request,$netpay,$view);

	}
	private function exportToExcel($datas,$netpay,$view){

        return     \Excel::create("$view", function($excel) use ($datas,$view,$netpay) {

            $excel->sheet("$view", function($sheet) use ($datas,$view,$netpay) {
                $sheet->loadView("$view",compact("datas","netpay"))
                ->setOrientation('landscape');
            });

        })->export('xlsx');
    }
}

