<?php

    namespace App\Http\Controllers\indians;

    use App\Mail\monthlyPayrollPrepared;
    use App\Mail\monthlyPayrollDecision;
    use App\Mail\monthlyPayrollOverview;

    use Session;
    use DateTime;
    use Illuminate\Http\Request;
    use DB;
    use Auth;
    use Config;
    use App\Http\Requests;
    use Response;
    use Input;
    use App\Item;
    use Excel;
    use PHPExcel;
    use PHPExcel_IOFactory;
    use Carbon\Carbon;
    use PDF;
    use APP;
    use MAIL;
    // use App\Http\Controllers\Controller;

    class PayrollController extends  \App\Http\Controllers\Controller {
        
        public function __construct() {

            $this->middleware('auth');

        }
            
        // Week End Functions Start
        // fill in the week_end updation form
        public function fill_weekend_form() {

            $week_end_details = DB::table(Config::get('constants.tables.WEEKEND_DAYS'))
                ->select('*')           
                ->get()->toArray();

            return view('payroll/weekend_days')->with('week_end_details', $week_end_details);

        //    return json_encode(array('Success'=>1, 'week_end_details'=>$html));
        }

        // update the weekends
        public function update_weekend_form(Request $request){

            $weekend_day = $request->input('weekend_day');

            //Set all the selection to 0
            DB::table(Config::get('constants.tables.WEEKEND_DAYS'))->update(['weekend_status' =>0]);

            if (count($weekend_day) > 0) {
                foreach ($weekend_day as $key => $dayname) {
                    DB::table(Config::get('constants.tables.WEEKEND_DAYS'))
                        ->where('weekend_day', $dayname)
                        ->update(['weekend_status' => 1]);
                }
            }
            
            $request->session()->flash('success', 'Weekend days updated successfully!');
            
            return redirect('edit-weekend_days');  

               
            //return json_encode(array("Success" => 1, "session" => $request->session()->get('success')));   
        }

        /***************************Week End Functions end*******************************/


        /***************************Casual Leave Functions Start*******************************/

        //fill in the casual leave updation form
        public function fill_casual_leaves() {

            $casual_leaves_details = DB::table(Config::get('constants.tables.CL_DETAILS'))
                ->select('*')           
                ->get()->toArray();

            return view('payroll/edit-casual_leaves')->with('casual_leaves_details', $casual_leaves_details);

        }

        public function enablepay(Request $request){
            try{
                \App\User::where('id',$request->id)->update(['locked'=>$request->type]);
                return 'Success';
            }
            catch(\Exception $ex){
                return 'Failure';
            }
        }
        //update the casual leave
        public function update_casual_leaves(Request $request){

            $num_of_leaves = $request->input('num_of_leaves');

            $arr = array();
            $rule = array();
            $cnt=0;

            foreach($request->input('job_role') as $val) {
                $cnt++;
                $arr['num_of_leaves'.$val] = 'required|integer';
                $rule['num_of_leaves'.$val.'.required'] = 'The casual leaves / month field is required.';  
                $rule['num_of_leaves'.$val.'.integer'] = 'The casual leaves / month must be an integer.';  
            }

            $this->validate($request, $arr,$rule);

            $casual_leaves = DB::table(Config::get('constants.tables.CL_DETAILS'))
                ->select('*')           
                ->get()->toArray();

            foreach ($casual_leaves as $casual_leave) {
                DB::table(Config::get('constants.tables.CL_DETAILS'))
                    ->where('job_role', $casual_leave->job_role)
                    ->update(['num_of_leaves' => $request->input('num_of_leaves'.$casual_leave->job_role)]);
            }

            Session::flash('success', 'Casual Leaves / Month updated successfully!');

            return redirect('edit-casual_leaves');  
               
            return json_encode(array("Success" => 1, "session" => $request->session()->get('success')));   
        }

        /***************************Casual Leave Functions end*******************************/

        /***************** Leave calendar start ****************/
       
        public function holiday_calendar() {
            return view('payroll/leave_calendar');
        }

 


        // Add Holiday Form
        public function add_holiday_form() {
            return view('holiday/add_holiday');
        }

      
        // Update holiday
    
 

        // Fill the edit holiday form
    

      

        public function validateDate($date) {   
            $d = Carbon::createFromFormat('Y-m-d', $date);
            return $d && $d->format('Y-m-d') === $date;
        }

        /***************** Leave calendar end ****************/

        // Get all the employees for basicpay in list view
        public function basicpay_list() {

            $tbl_basicpay = Config::get('constants.tables.BASICPAY');
            $tbl_user = Config::get('constants.tables.USER');
            
            $employees = DB::table($tbl_user)
                            ->select(DB::raw("DISTINCT $tbl_user.grade, $tbl_basicpay.basicpay"))
                            ->leftJoin($tbl_basicpay, "$tbl_user.grade", '=', "$tbl_basicpay.emp_grade")
                            ->where("$tbl_user.grade",'!=','0')
                            ->whereRaw("($tbl_user.locked=0 or $tbl_user.locked=2)")
                            // ->where("$tbl_user.locked",0)
                            ->get();

            $tax = $this->apply_tax();

            return view('payroll/employeelist', ['employees'=>$employees, 'tax'=>$tax]);
            
        }
        
        // Get selective employee to add / update the basicpay
        public function basicpay_update(Request $request) {

            $employee_grade = $request->input('grade');
            $action_button = $request->input('action');
            $basicpay = $request->input('basicpay');
        
            $this->validate($request, [
                'basicpay'=>'required|numeric',
                ], ['basicpay.required' => 'The Basic pay field is Required.']);
                
            if ($action_button == "add") {

                DB::table(Config::get('constants.tables.BASICPAY'))->insert(
                    ['emp_grade' => $employee_grade, 'basicpay' => $basicpay]
                );

                $request->session()->flash('success', 'Basicpay added successfully!');
                
            } elseif ($action_button == "update") {
                
                DB::table(Config::get('constants.tables.BASICPAY'))
                    ->where('emp_grade', $employee_grade)
                    ->update(['basicpay' => $basicpay]);

                $request->session()->flash('success', 'Basicpay updated successfully!');

            }

            return json_encode(array("Success" => 1));
            
        }
        //enable / disable payroll form employee
        public function endis(Request $request){
            $check=\DB::table('payroll')->where('id',$request->pid)->value('paid');
            $cont=0;
            if($check!=1){
            $update=\DB::table('payroll')->where('id',$request->pid);
                if($check==0){
                $update=$update->update(['paid'=>2]);
                $cont=1;
                }
                else{
                $update=$update->update(['paid'=>0]);
                $cont=2;
                }
            }
            return ['msg'=>'success','content'=>$cont];
        }

        public function clearpayroll($monthyear){
            //cond 1
            $check=\DB::table('payroll')->where('month_year',$monthyear)->where('paid',1)->value('paid');
            if(is_null($check) && $monthyear == date('M-Y')){
            //rolball 0
               $getpayroll=\DB::table('payroll')->where('month_year',$monthyear)->select('id')->pluck('id');
               $rollback0=\DB::table('payroll_details')->whereIn('payroll_id',$getpayroll)->delete(); 
                //rollback 1
            $rollback=$this->rollback('payroll',$monthyear);
            //rollback 2
           
            $rollback2=$this->rollback('payroll_pending',$monthyear);
    
            //rollback 3
            $rollback3=\DB::table('specific_salary_components')
                            ->whereMonth('updated_at',date('m'))
                            ->whereYear('updated_at',date('Y'))
                            ->get();
            foreach($rollback3 as $roll){
                $grants= $roll->grants == 0 ? 1 : $roll->grants;
                $status= $roll->status == 0 ? 1 : $roll->status;
                $indrollback=\DB::table('specific_salary_components')->where('id',$roll->id)->update(['grants'=>($grants-1),'status'=>($status-1)]);
            }
            return 1;
            }
            return 0;

        }
        public function rollback($table,$monthyear){
         $rollback=\DB::table($table)->where('month_year',$monthyear)->delete();  
          return 1; 
        }
        // Get all the employees for payroll in list view
        public function payroll_list($month_year=null) {

            $month_year = $month_year ? date('M-Y',strtotime('1-'.$month_year)) : $this->get_payroll_run_date();
            session(['month_year'=>$month_year]);

            $employees =

                // select user
                DB::table('users as u')

                    ->select('u.*', 'b.basicpay', 'p.id as payroll_id', 'p.ps_issued', 'p.ps_file','p.paid')

                    ->join('basicpay_details as b', 'u.grade', '=', 'b.emp_grade')

                    ->leftJoin('payroll as p', function ($join) {
                        $join->on('u.id', '=', 'p.emp_id')->where('p.month_year', session('month_year'));
                    })

                    ->where('u.role', '>', 0)
                
                    ->where([['u.role', '>', 0],['u.locked',0]])
                    ->orwhere([['u.role', '>', 0],['u.locked',2]])
                    ->orderBy('b.id', 'desc')
                    ->orderBy('u.role', 'desc')
                    ->get();


            $pending = DB::table('payroll_pending')
                        ->select('*')
                        ->where('month_year', $month_year)
                        ->first();

            $wallet = DB::table('wallets')
                        ->select('id')
                        ->first();
            try{
            $balance = app('App\Http\Controllers\MoneywaveController')->getbal()['balance'];
                }
                catch(\Exception $ex){
                    $balance=0;
                }
            $net = $this->generate_chart($month_year);
            $this_month = $month_year == $this->get_payroll_run_date();

            return view('payroll/payroll_list', compact('employees', 'pending', 'net', 'month_year', 'this_month', 'wallet', 'balance'));
            
        }

        public function generate_chart($month_year=null) {

            if ($month_year == null) {
                $month_year = $this->get_payroll_run_date();
            }

            $net = ['net_salary'=>0, 'allowances'=>0, 'deductions'=>0];

            $payrolls = $this->get_payroll_by_month($month_year);
                // return $payrolls;
            if ($payrolls) {

                foreach ($payrolls as $key => $payroll) {

                    $net['net_salary'] += $payroll->netsalary;

                    $charges = explode(',', $payroll->component_charges);
                    $types = explode(',', $payroll->component_types);

                    foreach ($types as $key => $type) {
                        $net[$type == 1 ? 'allowances' : 'deductions'] += $charges[$key];
                    }

                }

            }

            foreach ($net as $key => $value) {
                $net[$key] = round($value, 2);
            }

            $net['month_year'] = $month_year;
            return $net;

        }

        private function calculate_salary_component($constant, $formula, &$net) {

            foreach ($net as $key => $value) {
                if (substr_count($formula, $key)) {
                    $formula = str_ireplace($key, $net[$key], $formula);
                }
            }

            eval('$result = (' . $formula .');');

            return $result;

        }
        
        // Payslip list view of previous months for admin
        public function view_previous_payslip(Request $request) {

            $employees = [];

            if (!empty($request->selectmonth)) {

                $tbl_basicpay = Config::get('constants.tables.BASICPAY');
                $tbl_user = Config::get('constants.tables.USER') ;
                $tbl_payroll = Config::get('constants.tables.PAYROLL');

                $employees = DB::table($tbl_user)
                    ->select("$tbl_user.*", "$tbl_basicpay.basicpay", "$tbl_payroll.id as payroll_id", "$tbl_payroll.ps_issued", "$tbl_payroll.ps_file", "$tbl_payroll.month_year")
                    ->join($tbl_basicpay, "$tbl_user.grade", '=', "$tbl_basicpay.emp_grade")
                    ->leftJoin($tbl_payroll, "$tbl_user.id", '=', "$tbl_payroll.emp_id")

                    ->whereRaw("($tbl_user.locked=0 or $tbl_user.locked=2)")
                    ->where("$tbl_payroll.month_year",  $request->selectmonth)
                    ->whereIn("$tbl_user.role", [Config::get('constants.roles.People_Manager'), Config::get('constants.roles.Employee'), Config::get('constants.roles.Factory_Employee')])
                    ->orderBy("$tbl_user.role", 'desc')
                    ->get();

            }

            return view('payroll/payroll_selection_list', ['employees'=>$employees]);
            
        }
        
        // Get the employee payroll details in list view
        public function emp_payroll_list() {

            $tbl_basicpay = Config::get('constants.tables.BASICPAY');
            $tbl_user = Config::get('constants.tables.USER');
            $tbl_payroll = Config::get('constants.tables.PAYROLL');

            $trainings = DB::table(Config::get('constants.tables.TRAINING'))
                ->select('*')
                ->orderBy('id', 'DESC')
                ->get();

            $employees = DB::table($tbl_basicpay)
                ->select($tbl_user.'.*', $tbl_basicpay.'.basicpay', $tbl_payroll.'.id as payroll_id', $tbl_payroll.'.ps_issued', $tbl_payroll.'.month_year', $tbl_payroll.'.ps_file')
                ->join($tbl_user, $tbl_user.'.grade', '=', $tbl_basicpay.'.emp_grade')
                ->leftJoin($tbl_payroll, $tbl_user.'.id', '=', $tbl_payroll.'.emp_id')
                //->where("$tbl_payroll.month_year",  $month_year)
                ->where($tbl_user.'.id', Auth::id())
                // ->where($tbl_user.'.locked',0)
                ->whereRaw("($tbl_user.locked=0 or $tbl_user.locked=2)")
                ->orderBy($tbl_payroll.'.id', 'DESC')
                ->get();

            return view('payroll/emp_payroll_list',['employees'=>$employees]);
            
        }
      
        // Function to calculate the total number of working days
        public function getWorkingDays($startDate, $endDate, $holidays, $leavedays) {

            $endDate = strtotime($endDate);
            $startDate = strtotime($startDate);
            $days = ($endDate - $startDate) / 86400 + 1;

            $no_full_weeks = floor($days / 7);
            //$no_remaining_days = fmod($days, 7);
            $no_remaining_days = $days;

            //It will return 1 if it's Monday,.. ,7 for Sunday
            $the_first_day_of_week = date("N", $startDate);
            $the_last_day_of_week = date("N", $endDate);

            for ( $i = $startDate; $i <= $endDate; $i = $i + 86400 ) {
                if (in_array(date( 'N', $i ), $leavedays)) {
                    $no_remaining_days--;
                }
            }

            $workingDays = $no_remaining_days;

            // We subtract the holidays
            foreach($holidays as $holiday) {
                $time_stamp = strtotime($holiday);
                // If the holiday doesn't fall in weekend
                if ($startDate <= $time_stamp && $time_stamp <= $endDate && !(in_array(date("N",$time_stamp),$leavedays))) {
                    $workingDays--;
                }
            }

            return $workingDays;

        }

    //get the dates in mutiple days leave
    public function createDateRangeArray($strDateFrom,$strDateTo)
    {    
        $aryRange = [];
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom,5,2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo,5,2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo>=$iDateFrom) {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }

        public function fnConvertFormula($str, $basic_pay = 0, $housing_allowance = 0, $transport_allowance = 0, $gross_salary = 0)
        {
            $str_temp = str_replace("[[", '$', $str);
            $formula = str_replace("]]", "", $str_temp);
            eval( '$result = (' . $formula. ');' );
            //echo $result; exit();
            return $result;
            
        }

        private function get_monthly_work_days($weekends) {

            $startDate = date("Y-m-01", strtotime(' -1 month'));
            $endDate = date("Y-m-t", strtotime(' -1 month'));

            return $this->getWorkingDays($startDate, $endDate, $this->holidays(), $weekends);

        }

        private function get_employee_details($emp_id) {

            // GET employee details
            return DB::table(Config::get('constants.tables.BASICPAY') . ' as b')
                    ->select('u.*', 'b.basicpay')
                    ->join(Config::get('constants.tables.USER') .' as u', 'u.grade', '=', 'b.emp_grade')
                    ->where('u.id', $emp_id)
                    ->first();

        }

        private function get_all_employee_details() {

            // GET employee details
            return DB::table(Config::get('constants.tables.BASICPAY') . ' as b')
                    ->select('u.*', 'b.basicpay')
                    ->join(Config::get('constants.tables.USER') .' as u', 'u.grade', '=', 'b.emp_grade')
                
                    ->where([['u.role', '>', 0],['u.locked',0]])
                    ->orwhere([['u.role', '>', 0],['u.locked',2]])
                    ->get()->toArray();

        }

        public function get_employee_casual_leaves($role) {

            // Getting the total no. of avaialble leaves per month
            return DB::table('absencesettings')
                    ->select('day_num as num_of_leaves')    
                    ->where('role', $role)       
                    ->get()->first();

        }

        private function get_employee_attendance($emp_id) {

            return DB::table(Config::get('constants.tables.DAILY_ATTENDANCE'))
                    ->selectRaw('COUNT(*) as count')
                    ->whereRaw('Month(date) = ' . date('m', strtotime(' -1 month')))
                    ->where('emp_id', $emp_id)
                    ->whereNotNull('clock_out')
                    ->value('count');

        }

        private function holidays() {

               $holidays=$this->get_holiday();
            
             return $holidays;

        }

        private function weekends() {

            $weekend_details = DB::table(Config::get('constants.tables.WEEKEND_DAYS'))   
                                ->select('*') 
                                ->where('weekend_status', 1)
                                ->get()->toArray();

            $weekends = [];
            foreach($weekend_details as $weekend) {
                array_push($weekends, date('N', strtotime($weekend->weekend_day)));
            }

            return $weekends;
        }

  

        private function leaves($weekends, $emp_id) {

            // Get leave details
           $leaves_query =  DB::table('absencerequests')
                            ->select('*')
                            ->where(function ($query) {
                                $query->whereRaw('Month(startdate) = '. date('m', strtotime(' -1 month')))
                                      ->orWhereRaw('Month(enddate) = ' . date('m', strtotime(' -1 month')));
                            })
                            ->where('admin_approve', 1)
                            ->where('emp_id', $emp_id)
                            ->get();

            $leaves = [];
            foreach ($leaves_query as $leave) {

                $from = date("Y-m-01", strtotime(' -1 month'));

                $from_date = $leave->startdate < $from ? $from : $leave->enddate;

                $to = date("Y-m-t", strtotime(' -1 month'));

                $to_date = $leave->enddate > $to ? $to : $leave->enddate;

                $leaves = array_merge($leaves, $this->createDateRangeArray($from_date, $to_date));

            }

            foreach ($leaves as $key => $leave) {

                if (in_array(date("N", strtotime($leave)), $weekends)) {
                    unset($leaves[$key]);            
                }

            }

            return count($leaves);
       }
        

        private function get_basic_pay_percentage() {
            return DB::table('payroll_month_settings')->select('basic_pay_percentage')->value('basic_pay_percentage');
        }

        private function allowances_and_deductions(&$payroll,$id=0) {

            // determine gross salary and basic pay
             

            $percentage =(float) $this->get_basic_pay_percentage();
            $Udetail=\App\User::where('id',$id)->first();
           
            $payroll['gross_salary'] = $this->porateSalary($payroll['employee']->basicpay,$id,$payroll['working_days']);
            $payroll['real_gross_salary']=$Udetail->resolveBasic($Udetail['grade']);
           //  $payroll['gross_salary2'] = ($payroll['employee']->basicpay/2);
            $payroll['total_allowances'] = $payroll['total_deductions'] = 0;
             $payroll['basic_pay'] = $payroll['gross_salary'] * $percentage; 

            if($this->check_late_charge()){

            $payroll['basic_pay'] = ($payroll['basic_pay'] / $payroll['working_days']) * $payroll['emp_working_days'];
            }
             
            

            $payroll['basic_pay'] = number_format((float) $payroll['basic_pay'], 2, '.', '');

            // calsulate allowances and deductions

            $net = ['basic_pay' => $payroll['basic_pay'], 'gross_salary' => $payroll['gross_salary']];

            $payroll['allowances'] = $payroll['deductions'] = [];

            $components = $this->get_salary_components('ASC',$id);

            foreach ($components as $component) {

                if ($component->status == 1) {
               
                    $payroll['component_names'][$component->constant] = $component->name;
    
                    $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
    
                    $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$component->constant] = 
                        number_format($value, 2, '.', '');
    
                    $component->type == 1 ? $payroll['total_allowances'] += $value : $payroll['total_deductions'] += $value;
                    
                }
            }
             
            $payroll['consolidated_allowance'] = $this->consolidated_allowance($payroll['gross_salary']);

        }

        private function consolidated_allowance($gross_salary) {

            $annual_gross = $gross_salary * 12;

            $consolidated = ($annual_gross * (1 / 100)) > 200000 ?
                (($annual_gross * (1 / 100)) + ($annual_gross * (20 / 100))):
                (200000 + ($annual_gross * (20 / 100)));

            return abs(number_format($consolidated, 2, '.', '') / 12);

        }

        private function expenses(&$payroll) {

            // Get the details of the approved expenses which are not claimed
            $expenses = DB::table(Config::get('constants.tables.EXPENSES'))   
                ->select('*')
                ->where('emp_id', $payroll['employee']->id)
                ->where('is_approved', 1)
                ->where('is_claimed', 0)
                ->get()->toArray();

            $payroll['total_expenses'] = 0;
            $payroll['expenses'] = [];

            foreach ($expenses as $expense) {
                $payroll['expenses'][$expense->expense_details] = $expense->expense_charge;           
                $payroll['total_expenses'] += $expense->expense_charge;
            }

        }

        private function late_deductions(&$payroll) {

            $payroll['net_salary'] = $payroll['basic_pay'];
           //  + $payroll['total_allowances'] + $payroll['total_expenses'];

            // Get the month and year for the payroll
            $payroll['month_year'] = $this->get_payroll_run_date();

            $payroll['late_coming_deduction'] = 0;

            if ($this->check_late_charge()) {

                // get percentage late from table
                $payroll['total_late_deduction'] =
                    (float) DB::table(Config::get('constants.tables.DAILY_ATTENDANCE'))
                    ->selectRaw('sum(daily_deduction_percentage) as tot_late_deduction')
                    ->where('emp_id', $payroll['employee']->id)
                    ->where('date', '>=', date("Y-m-01", strtotime(' -1 month')))
                    ->where('date', '<=', date("Y-m-t", strtotime(' -1 month')))
                    ->value('tot_late_deduction');
                
                // Late coming deduction
          

                if ($payroll['total_late_deduction'] > 0) {
                    $payroll['late_coming_deduction'] = 
                        ($employee->basicpay / $payroll['working_days']) * ($payroll['total_late_deduction'] / 100);
                }

                $payroll['net_salary'] -= $payroll['late_coming_deduction'];

            }

            if ($this->apply_tax()) {
                $payroll['net_salary'] -= $payroll['tax_payable'];
            }

            // $payroll['net_salary'] += $payroll['specifics']['allowances'];
            // $payroll['net_salary'] -= $payroll['specifics']['deductions'];

        }
// adjustedSalary($id,$basicpay)
        private function taxes(&$payroll,$id=0) { 

            // 'nh_fund', 'pension_fuund', 'nsitf'

            if ($this->apply_tax()) {

                $payroll['total_reliefs'] = $payroll['total_deductions'] + $payroll['consolidated_allowance'];

                $payroll['taxable_income'] = abs($payroll['gross_salary'] - $payroll['total_reliefs']);
                $last_change=\App\User::where('id',$id)->value('last_grade_change_date');
                if(!is_null($last_change) && date('Y')==date('Y',strtotime($last_change))){

                $adjustedSal=$this->adjustedSalary($id,$payroll['gross_salary']);
                $payroll['real_gross_salary']=$adjustedSal;
// \Log::debug($adjustedSal.'erty');
                $adjustedconsolidate=$this->consolidated_allowance($adjustedSal);
                $total_reliefs=$payroll['total_deductions'] + $adjustedconsolidate;
                $taxable_income=$adjustedSal - $total_reliefs;
             }
             else{
                $adjustedSal=$payroll['gross_salary'];
                $taxable_income= $payroll['taxable_income'];
             }
                $payroll['minimum_tax_payable'] = (1 / 100) * $adjustedSal;

                $payroll['tax_payable'] = $payroll['cal_tax_pay'] = ( $this->tax_payable($taxable_income * 12) ) / 12;

            }

        }

       //come back here
 private function tax_payable($taxable_income) {
            $val=[];
    
            $check=0;
            $taxarray=[ 
                        300000=>0.07,
                        300001=>0.11,
                        500000=>0.15,
                        500001=>0.19,
                        1600000=>0.21,
                        3200000=>0.24,
                        3200001=>0.24,
                        3200002=>0.24,
                        3200003=>0.24
                    ];
                    
            $taxable=$taxable_income;
            $prev=0;
            foreach($taxarray as $key=>$value){

               if($key==300001 || $key==500001 || $key==3200001){
                $key=$key-1;
            }
            if($key==3200002){
                $key=$key-2;
            }
            if($key==3200003){
                $key=$key-3;
            }

            $taxable -=$key;
        
            if($taxable_income <300000){
                $check=1;
            }
            else{
                if($prev < $key && $prev >0){
                    $val[]=$prev*$value;
                }
                elseif($taxable<0){
                    $val[]=0;
                }
                else{
                    $val[]=$key*$value;
                }
            }
            $prev=$taxable;

        } 

        if($check==1){
            return abs($taxable_income * 0.07);
        }
        else{
            return abs(array_sum($val));
        }
    }

        private function attendance(&$payroll, $emp_id) {

            $weekends = $this->weekends();

            $payroll['working_days'] = $this->get_monthly_work_days($weekends);

            $payroll['employee'] = $this->get_employee_details($emp_id);

            $casual_leaves = $this->get_employee_casual_leaves($payroll['employee']->role);

            $payroll['emp_working_days'] = $payroll['daily_attendance_days'] = $this->get_employee_attendance($emp_id);

            $leaves_taken = $this->leaves($weekends, $emp_id);

            if ($payroll['daily_attendance_days'] != ($payroll['working_days'] - $leaves_taken)) {
                $leaves_taken = $payroll['working_days'] - $payroll['daily_attendance_days'];
            }

            $payroll['num_of_leaves'] = $leaves_taken < $casual_leaves->num_of_leaves ? $leaves_taken : $casual_leaves->num_of_leaves;

            $payroll['num_of_lop_leaves'] = 0;
        
            if ($leaves_taken > 0) {
                if ($leaves_taken >= $payroll['num_of_leaves']) {
                    $payroll['emp_working_days'] = $payroll['daily_attendance_days'] + $payroll['num_of_leaves'];
                    $payroll['num_of_lop_leaves'] = $leaves_taken - $payroll['num_of_leaves'];
                } else {
                    $payroll['emp_working_days'] = $payroll['daily_attendance_days'];
                    $payroll['num_of_leaves'] = $leaves_taken;
                }            
            }


        }

        private function calculate_specific_salary_components(&$payroll) {

            $components = $this->get_employee_specific_components($payroll['employee']->id);

            $payroll['specifics']['allowances'] = $payroll['specifics']['deductions'] = 0;

            if ($components) {
                foreach ($components as $key => $component) {
                    $payroll['component_names'][$key] = $component->name;
                    $payroll['specifics'][$component->type == 1 ? 'allowances' : 'deductions'] += $component->amount;
                    $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$key] = number_format($component->amount, 2, '.', '');
                }
            }
            
        }

        private function get_employee_specific_components($id) {
            return DB::table('specific_salary_components')   
                ->select('*')
                ->where('status', 0)
                ->where('emp_id', $id)
                ->get()->toArray();
        }

        private function get_payroll_by_month($month_year) {
            return DB::table('payroll as p')   
                ->selectRaw("p.netsalary, GROUP_CONCAT(d.charge SEPARATOR ', ') AS component_charges, GROUP_CONCAT(d.type SEPARATOR ', ') AS component_types")
                ->join('payroll_details as d', 'p.id', '=', 'd.payroll_id')
                ->where('month_year', $month_year)
                ->groupBy('p.id')
                ->get()->toArray();
        }

        // Fill the payroll form to save by admin
        public function fill_payroll_form($id) {

            $payroll = [];

            $this->porateSalary($payroll,$id);
            $this->attendance($payroll, $id);
            $this->allowances_and_deductions($payroll);
            $this->calculate_specific_salary_components($payroll);
            $this->expenses($payroll);
            $this->taxes($payroll,$id);
            $this->late_deductions($payroll);

            $payroll['serialize']['allowances'] = $payroll['allowances'];
            $payroll['serialize']['deductions'] = $payroll['deductions'];
            $payroll['serialize']['component_names'] = $payroll['component_names'];
            $payroll['serialize'] = serialize($payroll['serialize']);

            return response()->json($payroll);
            
        }

        private function get_allowance_or_deductions_formula($is_allowance) {
            return DB::table(Config::get('constants.tables.ALLOWANCE_DEDUCTION'))   
                ->select('*') 
                // ->where('job_role', $emp_role)
                ->where('is_allowance', $is_allowance)
                ->where('status', 1)
                ->get()->toArray();
        }

        private function get_payroll($id) {

            return DB::table(Config::get('constants.tables.PAYROLL') . ' as p')
                    ->select("p.*", "u.name","u.emp_num as emp_num")
                    ->join(Config::get('constants.tables.USER') . ' as u', "u.id", '=', "p.emp_id")
                        
                    ->where([['u.role', '>', 0],['u.locked',0],['p.id',$id]])
                    ->orwhere([['u.role', '>', 0],['u.locked',2],['p.id',$id]])
                   
                    ->first();

        }
        public function update_comp_member(Request $request){
            try{
            $clearexisting=\DB::table('components_applies')->where('comp_id',$request->compid)->delete();
           
               
            
             if($request->has('empids')){
            for($i=0; $i<count($request->empids); $i++){

                $resolveid=DB::table('users')->where('emp_num',$request->empids[$i])->value('id');
                $updatenew=\DB::table('components_applies')->insert(['comp_id'=>$request->compid,'appid'=>$resolveid]);
            }
        }
        else{
             $updatenew=\DB::table('components_applies')->insert(['comp_id'=>$request->compid,'appid'=>0]); 
              
        }
            return 'success';
         }
            catch(\Exception $ex){
                return $ex;
            }
        } 
        private function get_payroll_details($id) {

            return DB::table(Config::get('constants.tables.PAYROLL_DETAILS'))
                    ->select("*")
                    ->where("payroll_id", $id)
                    ->get()
                    ->toArray();

        }

        private function get_allowances() {

            return DB::table('salary_components')   
                    ->select('*')
                    ->where("type", 1)
                    ->where("status", 1)
                    ->get()
                    ->toArray();

        }

        private function get_expenses($emp_id, $created_date) {

            return DB::table(Config::get('constants.tables.EXPENSES'))   
                    ->select('*') 
                    ->where('emp_id', $emp_id)
                    ->where("is_approved", 1)
                    ->where("is_claimed", 1)
                    ->where("claimed_date", $created_date)
                    ->get()
                    ->toArray();

        }

        public function issue_payslip_for_all_employees() {

            try {


                DB::table('payroll_pending')
                    ->where('pending', '2')
                    ->where('payslips_issued', '0')
                    ->update(['payslips_issued'=>'1']);
                    //send mail
                session()->flash('success', 'Payslips have been issued!');

            } catch (Exception $e) {
                session()->flash('error', 'Something went wrong, please try again!');
            }

        }

        public function pay_all_employees() {

            try {

            } catch (Exception $e) {

            }
            
        }

        // salary poration function
        private function porateSalary($basicpay,$id,$workdays){
            $hireDate=\App\User::where('id',$id)->value('hireDate');
            if(date('Y-m') == date('Y-m',strtotime($hireDate))){
                $dailyPayment= $basicpay/$workdays;
                $newworkday=$this->getRealWorkingDays($hireDate,date('Y-m-t'));
                    if($newworkday==0){
                        return $basicpay;
                    }
                    $new_basic=$dailyPayment*$newworkday; 
                    return  $new_basic;
                }
                return $basicpay;
        }

        private function adjustedSalary($id,$basicpay){
               $Udetail=\App\User::where('id',$id)->select('prev_grade','grade','last_grade_change_date')->first();
                $oldcalc=date('n',strtotime("{$Udetail['last_grade_change_date']} -1 month"));
                $newmth=12-$oldcalc;
                $oldsal=$Udetail->resolveBasic($Udetail['prev_grade']) * $oldcalc;
                $newsal=$basicpay * $newmth;
              
                return ($oldsal+$newsal)/12 ;

        }

        private function getRealWorkingDays($startDate, $endDate)
                {
                    $begin = strtotime($startDate);
                    $end   = strtotime($endDate);
                    if ($begin > $end) {
                        // echo "startdate is in the future! <br />";

                        return 0;
                    } else {
                        $no_days  = 0;
                        $weekends = 0;
                        while ($begin <= $end) {
                    $no_days++; // no of days in the given interval
                    $what_day = date("N", $begin);
                    if ($what_day > 5) { // 6 and 7 are weekend days
                        $weekends++;
                    };
                    $begin += 86400; // +1 day
                };
                $working_days = $no_days - $weekends;

                return $working_days;
            }
        }
        public function add_payroll_for_all_employees() {

            $ids = DB::table(Config::get('constants.tables.USER') . ' as u')
                    ->join(Config::get('constants.tables.BASICPAY') . ' as b', 'u.grade', '=', 'b.emp_grade')
                    ->where('u.role', '>', 0)
                    ->orderBy('b.id', 'desc')
                
                    ->where([['u.role', '>', 0],['u.locked',0]])
                    ->orwhere([['u.role', '>', 0],['u.locked',2]])
                    ->orderBy('u.role', 'desc')
                    ->pluck('u.id');

            $percentage =(float) $this->get_basic_pay_percentage();

            foreach ($ids as $id) {

                $payroll = [];

                // $payroll['gross_salary']=40000;
                // $this->porateSalary($payroll,$id);
                $this->attendance($payroll, $id);
                $this->allowances_and_deductions($payroll,$id);
                

//dd here
                $this->calculate_specific_salary_components($payroll);
                $this->expenses($payroll);
                $this->taxes($payroll,$id);
                $this->late_deductions($payroll);

                // return response()->json($payroll,404);
                $payroll['basicpay'] = $payroll['employee']->basicpay * $percentage;
                $payroll['num'] = $payroll['employee']->emp_num;
                $payroll['id'] = $payroll['employee']->id;
                $payroll['attendance_days'] = $payroll['daily_attendance_days'];
                $payroll['leave_days'] = $payroll['num_of_leaves'];
                $payroll['lop_leave_days'] = $payroll['num_of_lop_leaves'];
                if(count($payroll['deductions'])>0){
                    $totaldeduction=array_sum(array_values($payroll['deductions']));
                }
                else{
                   $totaldeduction=0;
               }
               if(count($payroll['allowances'])>0){
                $totalallowance=array_sum(array_values($payroll['allowances']));
                }
              else{
                  $totalallowance=0;
               }
                 $payroll['salary'] = ($payroll['net_salary']+$totalallowance)-$totaldeduction;

                $now = new DateTime();
                $serialize = [
                    'allowances'=>$payroll['allowances'],
                    'deductions'=>$payroll['deductions'],
                    'component_names'=>$payroll['component_names']
                ];

                $this->save_payroll_salary_components($serialize, $this->save_payroll($payroll, $now));
                $this->save_payroll_expenses($id, $now);
                $this->update_payroll_specific_salary_components($payroll['id'], $now);

            }
            

            session()->flash('success', 'Payrolls added successfully!');

            $month_year = $this->get_payroll_run_date();

            DB::table('payroll_pending')->insert(['pending'=>'1', 'month_year'=>$month_year]);

            \Mail::to(Auth::user()->email)->send(new monthlyPayrollPrepared(Auth::user()->name, $month_year));

            // mail the first approval
            $emp = DB::table('payroll_approval as p')
                ->select('u.name', 'u.email')
                ->leftJoin('users as u', 'p.first', '=', 'u.emp_num')
                ->first();

            if ($emp) {
                \Mail::to($emp->email)->send(new monthlyPayrollOverview($emp->name, $month_year));
            } else {
                DB::table('payroll_pending')->update(['pending'=>'2']);
            }       
           
            return redirect('payroll-list');
    }

        //Fill the saved payroll form to view by admin
        public function fill_payroll_view($id) {

            $payroll = $this->get_payroll($id);

            $payroll_details = $this->get_payroll_details($payroll->id);

            $employee = $this->get_employee_details($payroll->emp_id);

            $allowance_query = $this->get_allowances();

            $expenses_query = $this->get_expenses($payroll->emp_id, $payroll->created_date);

            $expenses_name = "";
            $expenses_total = 0;

            foreach($expenses_query as $expense) {

                $payroll_expense = DB::table($Config::get('constants.tables.PAYROLL_DETAILS'))
                                    ->select("*")
                                    ->where("payroll_id", $payroll->id)
                                    ->where("allowance_id", $expense->id)
                                    ->get()
                                    ->first();
                
                $expenses_name .= $payroll_expense->name. " - ".$payroll_expense->charge.", ";
                $expenses_total += $payroll_expense->charge;

            }

            $expenses_name = empty($expenses_name)? "": substr($expenses_name, 0, -2); // to remove last two characters
            $expenses_value = (float)($expenses_total);

            $payroll_display = "";        
            $payroll_display .= '<p><b>Employee Name &nbsp; : &nbsp; </b>'.$payroll->name.'</p>';
            $payroll_display .= '<p><b>Employee Number &nbsp; : &nbsp; </b>'.$payroll->emp_num.'</p>';
            $payroll_display .= '<p><b>Monthly Basic Pay &nbsp; : &nbsp; </b>'.number_format(($payroll->basic_pay),2).'</p>';
            $payroll_display .= '<table data-toggle="table" class="table table-striped" data-mobile-responsive="true" data-pagination="true" data-search="true">';
            $payroll_display .= '<tr>';
            $payroll_display .= '<th><b>Basic Pay</b></th>';
            $payroll_display .= '<td style="text-align:right">'.number_format(($payroll->basic_pay),2).'</td>';
            $payroll_display .= '</tr>';

            $payroll_display .= '<th colspan="2"><b>Allowances</b></th>';        
            $payroll_display .= '</tr>';
            foreach($payroll_details as $details) {
                if($details->type==1) {
                    $payroll_display .= '<tr>';
                    $payroll_display .= '<td>'.$details->name.'</td>';
                    $payroll_display .= '<td style="text-align:right">(+) '.number_format($details->charge,2) .'</td>';       
                    $payroll_display .= '</tr>';
                }
            }
            if(count($expenses_query)>0)
            {
                $payroll_display .= '<tr>';
                $payroll_display .= '<th colspan="2"><b>Expenses</b></th>';        
                $payroll_display .= '</tr>';
                foreach($payroll_details as $details) {
                    if($details->type==2) {
                        $payroll_display .= '<tr>';
                        $payroll_display .= '<td>'.$details->name.'</td>';
                        $payroll_display .= '<td style="text-align:right">(+) '.number_format($details->charge,2) .'</td>';       
                        $payroll_display .= '</tr>';
                    }
                }
            }
        $payroll_display .= '<tr>';
            $payroll_display .= '<th><b>Gross Pay</b></th>';
            $payroll_display .= '<td style="text-align:right">'.number_format($payroll->grosssalary,2).'</td>';
            $payroll_display .= '</tr>';
        $payroll_display .= '<tr>';
            $payroll_display .= '<th colspan="2"><b>Deductions Details</b></th>';        
            $payroll_display .= '</tr>';

            foreach($payroll_details as $details) {
                if($details->type==0) {
                    $payroll_display .= '<tr>';
                    $payroll_display .= '<td>'.$details->name.'</td>';
                    $payroll_display .= '<td style="text-align:right">(-) '.number_format($details->charge,2) .'</td>';
                    $payroll_display .= '</tr>';
                }
            }
            $payroll_display .= '<tr>';
            $payroll_display .= '<td>Tax Payable</td>';
            $payroll_display .= '<td style="text-align:right">(-)'.number_format($payroll->tax_payable,2).'</td>';        
            $payroll_display .= '</tr>';
            $payroll_display .= '<tr>';
            $payroll_display .= '<td>Late Coming Deduction</td>';
            $payroll_display .= '<td style="text-align:right">(-) '.number_format($payroll->late_coming_deduction,2).'</td>';        
            $payroll_display .= '</tr>';
            $payroll_display .= '<tr>';
            $payroll_display .= '<th colspan="2"><h3 ><b class="pull-right">';
            $payroll_display .= 'NET PAY : '.number_format($payroll->netsalary,2);
            $payroll_display .= '</b></h3></th>';
            $payroll_display .= '</tr>';
            $payroll_display .= '';
            $payroll_display .= '</table>';
            
            return json_encode(array('payroll'=>$payroll_display));
            
        }

        private function apply_tax() {
            return DB::table('tax_settings')->select('apply')->value('apply');
        }

        private function save_payroll($data, $now) {

            $input = [
                'emp_id' => $data['id'],
                'emp_num' => $data['num'],
                'basicpay' => $data['basicpay'],
                'attendance_days' => $data['attendance_days'],
                'working_days' => $data['working_days'],
                'leave_days' => $data['leave_days'],
                'lop_leave_days' => $data['lop_leave_days'],
                'late_coming_deduction' => $data['late_coming_deduction'],
                'month_year' => $data['month_year'],
                'basic_pay' => $data['basic_pay'],
                'netsalary' => $data['salary'],
                'grosssalary' =>$data['gross_salary'], 
                'created_by' => Auth::id(),
                'created_date' => $now,
                'consolidated_allowance' => $data['consolidated_allowance']
            ];

            if ($this->apply_tax()) {

                $input['total_reliefs'] = $data['total_reliefs'];
                $input['taxable_income'] = $data['taxable_income'];
                $input['cal_tax_pay'] = $data['cal_tax_pay'];
                $input['minimum_tax_payable'] = $data['minimum_tax_payable'];
                $input['tax_payable'] = $data['tax_payable'];

            }
            
            DB::table(Config::get('constants.tables.PAYROLL'))->insert($input);

            return DB::getPdo()->lastInsertId();

        }

        private function save_payroll_details($id, $type, $name, $charge, $allowance_id=0) {

            DB::table(Config::get('constants.tables.PAYROLL_DETAILS'))
            ->insert([
                'payroll_id' => $id,
                'type' => $type,
                'allowance_id' => $allowance_id, 
                'name' => $name,
                'charge' => $charge
            ]);

        }

        private function save_payroll_expenses($id, $now) {

            //Adding the expenses claimed in the payroll
            $expenses = DB::table(Config::get('constants.tables.EXPENSES'))   
                ->select('*') 
                ->where('emp_id', $id)
                ->where("is_approved", 1)
                ->where("is_claimed", 0)
                ->get()->toArray();

            foreach($expenses as $expense) {

                $this->save_payroll_details($payroll_id, 2, $expense->expense_details, $expense->expense_charge, $expenses->id);

                // Updating the claim status of the expenses claimed in the payroll
                DB::table(Config::get('constants.tables.EXPENSES'))
                    ->where('id', $expense->id)
                    ->update(['is_claimed' => 1, 'claimed_date' => $now]);

            }

        }

        private function save_payroll_salary_components($components, $payroll_id) {

            foreach ($components as $key => $component) {

                if ($key != 'component_names') {

                    $type = $key == 'allowances' ? 1 : 0;
                    foreach ($component as $key => $value) {
                        $this->save_payroll_details($payroll_id, $type, $components['component_names'][$key], $value);
                    }

                }
                
            }
        }

        private function update_payroll_specific_salary_components($emp_id, $now) {

            $components = $this->get_employee_specific_components($emp_id);

            foreach ($components as $key => $component) {

                $update['grants'] = $component->grants+1;
                $update['updated_at'] = $now;

                if ($update['grants'] == $component->duration) {
                    $update['status'] = 1;
                }

                DB::table('specific_salary_components')
                    ->where('id', $component->id)
                    ->update($update);
                
            }

        }
        
        //Get selective employee to add / update the basicpay
        public function payroll_update(Request $request) {

            $now = new DateTime();

            $this->save_payroll_salary_components(unserialize($request->get('serialize')), $this->save_payroll($request->all(), $now));
            $this->save_payroll_expenses($request->get('id'), $now);
            $this->update_payroll_specific_salary_components($request->get('id'), $now);

            $request->session()->flash('success', 'Payroll added successfully!');
           
            return redirect('payroll-list');
            
        }

        private function get_certificate($id) {
            return DB::table(Config::get('constants.tables.PAYROLL') . ' as p')
                ->join(Config::get('constants.tables.USER') . ' as e', 'e.id', '=', 'p.emp_id')
                ->leftJoin(Config::get('constants.tables.USER') . ' as a', 'a.id', '=', 'p.created_by')
                ->select('p.*','e.emp_num as emp_num', 'e.name as employee_name', 'e.role', 'a.name as admin_name')
                ->where('p.id', '=', $id)
                ->first();
        }
        
        // Generating payslip by admin
        public function create_payslip($id) {

            set_time_limit(0);

            $now = new DateTime();
            $ps_file = strtotime('now') . '.pdf';

            $certificate = $this->get_certificate($id);

            $settings = DB::table(Config::get('constants.tables.SETTINGS'))
                ->select('*')
                ->where('id', 1)
                ->first();

            $certificate_details = [
                'certificate' => $certificate,
                'payroll_details' => $this->get_payroll_details($id),
                'settings' => $settings
            ];

            $view = \View::make('payslip_certificate', compact('certificate_details'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->setWatermarkText($settings->watermark_text, '100px', '0.4', '30deg', '45%');
            $pdf->setPaper('A3');

            $mc_file_uploaded = $pdf->loadHTML($view)->save('psc/'.$ps_file);

            if ($json = $this->delete_old_payslip($mc_file_uploaded, $id, $ps_file, $now)) {
                return $json;
            }

            $view = \View::make('payslip_certificate', compact('certificate_details'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            return \Response::download(public_path('psc/'.$ps_file), \Auth::user()->name.date('M-Y').'payroll.pdf');


        }

        private function delete_old_payslip($mc_file_uploaded, $id, $ps_file, $now) {

            if ($mc_file_uploaded) {

                // Deleting the existing payslip file if present

                $old_ps_file = DB::table(Config::get('constants.tables.PAYROLL'))
                                ->select('ps_file')
                                ->where('id', '=', $id)
                                ->value('ps_file');

                if ($old_ps_file != '' && file_exists('public/psc/' . $old_ps_file)) {
                    unlink('public/psc/' . $old_ps_file);
                }

                DB::table(Config::get('constants.tables.PAYROLL') )
                    ->where('id', $id)
                    ->update([
                        'ps_issued' => 1,
                        'ps_file' => $ps_file,
                        'ps_issued_by' => Auth::id(),
                        'ps_issued_on' => $now
                    ]);

                Session::flash('success', 'Payslip issued successfully!');
                return request()->json(['Success' => 1]);

            }

        }

        public function get_payroll_policy() {

            $policy = DB::table('payroll_month_settings')->select('*')->first();

            if (!$policy) {
                $input['end_of_current_month'] = 1;
                $input['beginning_of_next_month'] = 0;
                DB::table('payroll_month_settings')->insert($input);
                $policy = DB::table('payroll_month_settings')->select('*')->first();
            }

            return response()->json(
                ['current'=>$policy->end_of_current_month, 'next'=>$policy->beginning_of_next_month, 'basic_pay'=>$policy->basic_pay_percentage]
            );
        }

        public function payroll_month_settings($when, $percentage) {

            if ($when == 1) {
                $input['end_of_current_month'] = 1;
                $input['beginning_of_next_month'] = 0;
            } else {
                $input['end_of_current_month'] = 0;
                $input['beginning_of_next_month'] = 1;
            }

            $input['basic_pay_percentage'] = $percentage;

            DB::table('payroll_month_settings')->update($input);
               
            return response()->json(['Success' => 1]);

        }

        public function run_payroll_by_month_end() {

            $set = DB::table('payroll_month_settings')->select('*')->first();
            return $set->end_of_current_month == 1 ? true : false;

        }

        private function get_payroll_run_date() {
            return $this->run_payroll_by_month_end() ? date('M-Y') : date('M-Y', strtotime(' -1 month'));
        }

        private function get_payroll_ids($date = null) {

            if ($date == null) {
                $date = $this->get_payroll_run_date();
            }

            return DB::table(Config::get('constants.tables.PAYROLL'))
                    ->where('month_year', $date)
                    ->where('ps_issued', NULL)
                    ->pluck('id');
        }

        public function generate_payroll_excel($month_year) {

            $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

            $objPHPExcel = new PHPExcel();

            $objPHPExcel->setActiveSheetIndex(0);

            $ids = $this->get_payroll_ids($month_year);

            if ($ids) {
                $i = 1;
                foreach ($ids as $key => $id) {

                    $certificate = $this->get_certificate($id);

                    $payroll_details = $this->get_payroll_details($id);

                    $excel = [];

                    $excel['name'] = $certificate->employee_name;
                    $excel['emp_num'] = $certificate->emp_num;
                    $excel['basic_pay'] = $certificate->basicpay;
                    $excel['net_salary'] = $certificate->netsalary;
                    $otherheader=[];
                    if ($this->apply_tax()) {
                        $excel['tax_payable'] = $certificate->tax_payable;
                        $otherheader[]='tax_payable';
                    }

                    if ($this->check_late_charge()) {
                        $excel['late_coming_deduction'] = $certificate->late_coming_deduction;
                       $otherheader[]='late_coming_deduction';
                    }

                    foreach ($payroll_details as $detail) {
                        $excel[$detail->name] = $detail->charge;

                    }
                    $count=session(['count'=>count(array_keys($excel))]);
                    if (session()->has('count') && count(array_keys($excel))>$count || $key == 0) {
                        foreach (array_keys($excel) as $key => $column_name) {

                            $objPHPExcel->getActiveSheet()->SetCellValue("{$alphabet[$key]}1", strtoupper(str_replace('_', ' ', $column_name)));
                        }
                    }

                    $i++; $j = 0; 

                        $header=array_merge($this->getHeader(),$otherheader);

                    foreach ($excel as $key => $value) {
                        if (is_numeric($value)) {
                            $value = number_format($value, 2);
                        }

                         if(in_array($key, $header)){  
                        $objPHPExcel->getActiveSheet()->SetCellValue($alphabet[$j].$i, $value);
                         }
                         else{
                        $objPHPExcel->getActiveSheet()->SetCellValue($alphabet[$j].$i, 0);
                            $objPHPExcel->getActiveSheet()->SetCellValue($alphabet[($j+1)].$i, $value);
                          
                        }
                        $j++;
                    }


                }
                    session()->forget('count');
                $file_name = $month_year. '_PAYROLL.xlsx';
                $file = public_path('sample_files') . '/' . $file_name;

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save($file);

                return Response::download($file, $file_name, ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
                    
            } else {
                return back();
            }
 

        }
        
        private function getHeader(){
           $header= \DB::table('salary_components')->where('status',1)->pluck('name')->toArray();
           $mergedheader=['name','emp_num','basic_pay','net_salary'];
           return array_merge($header,$mergedheader);
        }
        /***************** Allowances / Deductions start **************************/

        private function set_job_titles(&$array, $role) {

            foreach ($array as $key => $ob) {
                $array[$key]->job_title = self::get_job_title($ob->{$role});
            }

        }

        public function get_salary_components($order = 'DESC',$appid=0) {

            $salcomp=DB::table('salary_components as sc')
                            ->leftJoin('components_applies as ca', 'sc.id','=','ca.comp_id')
                            ->select('*')
                            ->orderBy('sc.id', $order)
                            ->whereNotIn('ca.appid',[$appid])
                            ->orWhere('ca.appid',0)
                            ->get()
                            ->toArray();
            return $salcomp;

        }

        public function get_salary_components_hack($order = 'DESC') {

            $salcomp=DB::table('salary_components')
                         ->select('*')
                         ->orderBy('id', $order)
                         ->get()
                         ->toArray();
            return $salcomp;

        }

        private function get_specific_salary_components($order = 'DESC') {

            return DB::table('specific_salary_components as s')
                ->select('s.*', 'u.name as emp_name')
                ->leftJoin('users as u', 'u.id', '=', 's.emp_id')
                ->orderBy('s.id', $order)
                ->get()
                ->toArray();

        }

        public function allowances() {

            return view('payroll/allowances', ['components'=>$this->get_salary_components_hack()]);
        }

        public function specific_components() {
            return view('payroll/specific_components', ['components'=>$this->get_specific_salary_components()]);
        }

        public static function get_job_title($job_role) {

            switch ($job_role) {

                case Config::get('constants.roles.Admin_User'):
                    return 'Admin';

                case Config::get('constants.roles.People_Manager'):
                    return 'People Manager';

                case Config::get('constants.roles.Employee'):
                    return 'Employee';

                case Config::get('constants.roles.Doctor'):
                    return 'Doctor';

                case Config::get('constants.roles.Factory_Employee'):
                    return 'Factory Employee';

            }

        }

        private function get_allowance_post_data($request) {

            $input['job_role'] = $request->input('job_role');
            $input['is_allowance'] = $request->input('is_allowance');
            $input['is_formula'] = $request->input('is_formula');
            $input['name'] = $request->input('name');
            $input['charge_percentage'] = $request->input('charge_percentage');
            $input['charge_formula'] = $request->input('charge_formula');

            $input['is_formula'] == 1 ? $input['charge_percentage'] = '' : $input['charge_formula'] = '';

            return $input;

        }

        private function get_allowance_required_fields() {

            $arr['type'] = ['required'];
            $arr['name'] = ['required'];
            $arr['formula'] = ['required'];
            $arr['constant'] = ['required'];

            return $arr;

        }

        // Add Allowance Form

        public function add_allowance_form() {

            $roles = DB::table(Config::get('constants.tables.USER'))
                ->select('role')->distinct('role')
                ->get();

            return view('allowance/add_allowance')->with('roles', $roles);

        }

        public function add_component(Request $request) {

            $rules = [
                'name' => 'required|min:5|unique:salary_components,name',
                'type' => 'required',
                'constant' => 'required|unique:salary_components,constant',
                'formula' => 'required',
                'appllyid'=>'required'
            ];

            $this->validate($request, $rules);

            $input = $request->all();
            unset($input['_token']);
            unset($input['appllyid']);

            $success = DB::table('salary_components')->insertGetId($input);
            // /set applied to
            if(!$request->has('appllyid') || $request->appllyid[0]==0){
                  $insert=DB::table('components_applies')->insert(['appid'=>0,'comp_id'=>$success]);
           
            }
            else{
            for($i=0; $i<count($request->appllyid); $i++){

                $resolveid=DB::table('users')->where('emp_num',$request->appllyid[$i])->value('id');
                $insert=DB::table('components_applies')->insert(['appid'=>$resolveid,'comp_id'=>$success]);
            }
              }
            if ($success) {
                return back();
            }

        }

        public function edit_component(Request $request) {

            $rules = [
                'name' => 'required|min:5',
                'type' => 'required',
                'formula' => 'required'
            ];

            $input = $request->all();
            $id = $input['id'];

            unset($input['_token'], $input['id']);

            $this->validate($request, $rules);
            
            $successor = DB::table('salary_components')
            ->select('*')
            ->where('id', $id)->first();

            if ($successor) {

                DB::table('salary_components')->where('id', $id)->update($input);

                $message = 'Salary component updated successfully!';
                $request->session()->flash('success', $message);

            } else {
                $request->session()->flash('error', 'No record Found. Please try again!');
            }

            return back();

        }


       private function read_excel($path, $request) {
            try{
            $rows = Excel::load($path)->get();

            if ($rows) {

                foreach ($rows as $key => $row) {
                   $emp_id= \App\User::where('emp_num',$row->emp_num)->value('id');

                    $inputs[] = [
                        'name' => $row->name,
                        'emp_num' => $row->emp_num,
                        'emp_id' => $emp_id,
                        'type' => $row->type,
                        'amount' => $row->amount,
                        'duration' => $row->duration,
                        'comment' => $row->comment
                    ];

                    if (!$this->check_emp_id($row->emp_num)) {
                        $c = $key + 2;
                        $request->session()->flash('error', "Invalid employee num - $row->emp_num at column $c");
                        return;
                    }

                }

                foreach ($inputs as $input) {
                    DB::table('specific_salary_components')->insert($input);
                }

                $request->session()->flash('success', 'Component added succesfully!');

            }
        }
            catch(\Exception $ex){
                  $request->session()->flash('Error', 'Please Check Your Excel file!');

            

            }

        }

        public function delete_specific_component($id) {

            DB::table('specific_salary_components')->where('id', $id)->delete();
                
            session()->flash('success', 'Component deleted successfully!');
            
            return back();

        }

        public function add_specific_components(Request $request) {

            $rules = [
                'name' => 'required|min:5|unique:specific_salary_components,name',
                'emp_num' => 'required|integer',
                'type' => 'required',
                'amount' => 'required',
                'duration' => 'required'
            ];

            $errors = [
                'name' => 'Type in the name for this salary component.',
                'emp_num' => 'Please type in the employee num.',
                'type' => 'Please iindicate if this is an allowance or deduction.',
                'amount' => 'Please enter the amount to charge the employee(s)',
                'duration' => 'Indicate the number of times (in months) this component will be processed.'
            ];

            if ($request->hasFile('excel') && $request->file('excel')->isValid()) {

                $this->read_excel($request->excel->path(), $request);

            } else {

                $this->validate($request, $rules, $errors);

                $input = $request->all();
                $input['emp_id']=\App\User::where('emp_num',$input['emp_num'])->value('id');
                unset($input['_token']);
                $saveinp=DB::table('specific_salary_components')->insert($input);
                if ($this->check_emp_id($input['emp_id']) || $saveinp) {
                    $request->session()->flash('success', 'Component added succesfully!');
                } else {
                    $request->session()->flash('error', "Invalid employee num - {$input['emp_num']}");
                }

            }

            return back();

        }

        private function check_emp_id($id) {
            return DB::table(Config::get('constants.tables.USER'))
                ->select('id')
                ->where('emp_num', $id)
                ->value('id');
        }

        // Adding Allowance
        public function add_allowance(Request $request) {

            $input = $this->get_allowance_post_data($request);
            $arr = $this->get_allowance_required_fields($input['is_formula']);
            $this->validate($request, $arr);

            $input['created_by'] = Auth::id();
            $input['created_date'] = new DateTime();

            DB::table(Config::get('constants.tables.ALLOWANCE_DEDUCTION'))->insert($input);

            $message = $this->is_allowance($input['is_allowance']) . ' added successfully!';
            $request->session()->flash('success', $message);

            // Adding the new allowance / deduction to the existing payrolls
            $allowance_id = DB::getPdo()->lastInsertId();

            $payrolls = DB::table(Config::get('constants.tables.PAYROLL'))   
                ->select('id') 
                ->orderBy('id')
                ->get()->toArray();

            foreach ($payrolls as $payroll) {
                 DB::table(Config::get('constants.tables.PAYROLL_DETAILS'))
                    ->insert([
                        'payroll_id' => $payroll->id,
                        'type' => $input['is_allowance'],
                        'allowance_id' => $input['allowance_id'],
                        'name' => $input['name'],
                        'percentage' => 0
                    ]);            
            }

            return json_encode(array("Success" => 1));
        }

        //Update allowance
        public function update_allowance_form(Request $request) {

            $id = $request->input('id');

            $input = $this->get_allowance_post_data($request);
            $arr = $this->get_allowance_required_fields();
            $this->validate($request, $arr);
            
            $successor = DB::select("SELECT * FROM ".Config::get('constants.tables.ALLOWANCE_DEDUCTION')." WHERE id='". $id . "'");

            if (count($successor) == 1) {

                DB::table(Config::get('constants.tables.ALLOWANCE_DEDUCTION'))
                    ->where('id', $id)
                    ->update($input);

                $message = $this->is_allowance($input['is_allowance']) . ' updated successfully!';
                $request->session()->flash('success', $message);

            } else {
                $request->session()->flash('error', 'No record Found. Please try again!');
            }

            return json_encode(array("Success" => 1));       
            //return redirect('allowance-list');
        }

        // List of all the allowances - Current Admin User
        public function allowances_list()
        {
            $allowances = DB::table(Config::get('constants.tables.ALLOWANCE_DEDUCTION'))
                ->select('*')
                ->get();
            
            return view('allowance/allowancelist',['allowances'=>$allowances]);        
        }

        // Fill the edit allowance form
        public function fill_allowance_form($id)
        {

            $allowance_details = DB::table('salary_components')
                ->select('*')
                ->where('id', '=', $id)            
                ->first();        
            
            return json_encode(array('allowance_details'=>$allowance_details, 'id' => $id));
        }

        // Status Change
        public function allowance_status_change($id) {
            
            $component = DB::table('salary_components')
                ->select('*')
                ->where('id', '=', $id)            
                ->first();

            if ($component) {

                $new_status = $component->status == 1 ? 0 : 1;
                
                $icon = $component->status == 1 ? '-slash' : '';
                $btn_clr = $component->status == 1 ? 'btn-warning' : 'btn-success';
                $btn_title = $component->status == 1 ? 'Make Active' : 'Make Inactive';

                DB::table('salary_components')
                    ->where('id', $component->id)
                    ->update(['status' => $new_status]);

                $status_div = 
                    '<a onclick="fnStatusChange('.$id.','.$new_status.')">
                        <i class="btn btn-sm '.$btn_clr.' waves-effect icon fa-eye'.$icon.'" aria-hidden="true" title="'.$btn_title.'"></i>
                    </a>';

                return json_encode(array("Success" => "1", "status_div" => $status_div));
            }
        }

        private function is_allowance($allowance) {
            return $is_allowance == 1 ? 'Allowance' : 'Deduction';
        }

        // Delete allowance
        public function delete_allowance($arg) {

            $successor = DB::table(Config::get('constants.tables.ALLOWANCE_DEDUCTION'))
                ->select('*')
                ->where('id', '=' , $arg)            
                ->first();
            
            DB::table(Config::get('constants.tables.ALLOWANCE_DEDUCTION'))->where('id', '=', $arg)->delete();
                
            session()->flash('success', $this->is_allowance($successor->is_allowance).' deleted successfully!');
            
            return redirect('allowance-list');

        }

        /***************** Allowances / Deductions end ****************************/

        /***************** Daily Attendance start ****************************/
        
        
        // Daily attendance by employee view page
        public function daily_attendance() {

            $now = new DateTime();
            $condition = "";
            $daily_records = array();
            
            $tbl_user = Config::get('constants.tables.USER');
            $employee = DB::table($tbl_user)
                ->select("*")
                ->where("id", Auth::id())
                ->get()->first();
            
            //to check current day is a weekend or not
            $current_day = date("N");
            $weekend_details = DB::table(Config::get('constants.tables.WEEKEND_DAYS'))   
                ->select('*') 
                ->where('weekend_status',1)
                ->get()->toArray();

                $weekends = array();
                foreach($weekend_details as $weekend) {
                    array_push($weekends,date('N', strtotime($weekend->weekend_day)));
                }

                if(in_array($current_day, $weekends)) {
                    $condition="weekend";
                }
                
            
            //to check current date is a holiday or not
            if (empty($condition)) {
                $current_day = date("Y-m-d");
                $holiday_details = DB::select("SELECT * FROM ".Config::get('constants.tables.HOLIDAYS')." WHERE (Month(single_day) = ".date('m')." || (Month(from_date) = ".date('m')." || Month(to_date) = ".date('m').")) and status=1");

                $holidays = array();
                foreach ($holiday_details as $holiday) {
                    if ($holiday->multiple_days==1) {
                        $monthenddate = $holiday->to_date;
                        if($holiday->to_date > date("Y-m-t")) {
                            $monthenddate = date("Y-m-t");
                        }
                        $holidays = array_merge($holidays,$this->createDateRangeArray($holiday->from_date,$monthenddate));
                    } else {
                        array_push($holidays,$holiday->single_day);
                    }
                }
                if(in_array($current_day, $holidays)) {
                    $condition="holiday";
                }
                //print_r($holidays); exit();
            }
            
            //to check current date leave approved or not
            $leaves_query = DB::select("SELECT * FROM ".Config::get('constants.tables.EMPLOYEE_LEAVES')." WHERE (Month(from_date) = ".date('m')." || Month(to_date) = ".date('m').") AND leave_status=2 AND emp_id=".Auth::id());
            
            foreach ($leaves_query as $leave) {
                if($leave->from_date < date("Y-m-d") && $leave->to_date > date("Y-m-d")){
                    $condition="leaveapproved";
                    break;
                }
            }
            //echo '<pre>'; print_r($leaves_query); exit();
            
            //to check current date is already punched or not
            if(empty($condition)) {
                $tbl_daily_attendance = Config::get('constants.tables.DAILY_ATTENDANCE');
                $daily_records = DB::table($tbl_daily_attendance)
                    ->select("*")
                    ->where("emp_id", Auth::id())
                    ->where("date", date("Y-m-d"))
                    ->get();


                foreach($daily_records as $daily_record) {
                    if(!empty($daily_record->clock_out)) {
                        $condition="fullrecordexists";
                    } elseif (!empty($daily_record->clock_in)){
                        $condition="halfrecordexists";
                        //echo 'inside if'; exit();
                    }
                    //echo $daily_record->clock_out; exit();
                }
            }
            return view('payroll/daily_attendance',['employee'=>$employee,'condition'=>$condition])->with('daily_records', $daily_records);
            
        }
        
        // Get selective employee to add / update the basicpay
        public function daily_attendance_update(Request $request) {

            $employee_id = Auth::id();
            $employee_num = $request->input('num');
            $now = new DateTime();

            // Default Start Time
            $default_start_time = date("Y-m-d 09:00");
            // return $default_start_time;
            $record_id = $request->input('record_id');
            if (empty($record_id)) {

                $daily_deduction_percentage = 0;
                $late_time = '';

                if (strtotime($default_start_time) < strtotime(date("Y-m-d H:i:s"))) {

                    $late = strtotime(date("Y-m-d H:i:s")) - strtotime($default_start_time);
                    $late_min = date("i", $late);
                    $late_time = date("i:s", $late);

                    $tbl_late_coming = Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS');

                    $sql = "select * from ".$tbl_late_coming." where late_minute >= ".$late_min." and status=1 and role = ".Auth::user()->role." order by late_minute limit 1";
                    
                    $late_deduct = DB::select($sql);
                    if(count($late_deduct)<=0)
                    {
                        $sql = "select * from ".$tbl_late_coming." where late_minute = (select max(late_minute) from ".$tbl_late_coming." where status = 1 and role = ".Auth::user()->role.") order by late_minute limit 1";
                        $late_deduct = DB::select($sql);
                    }
                    if(count($late_deduct)>0)
                        $daily_deduction_percentage = $late_deduct[0]->late_percentage;
                    
                }

                DB::table(Config::get('constants.tables.DAILY_ATTENDANCE'))->insert(
                    ['emp_id' => $employee_id, 'emp_num' => $employee_num, 'date' => $now, 'clock_in' => $now, 'late_time' => $late_time, 'daily_deduction_percentage' => $daily_deduction_percentage]
                );
                $request->session()->flash('success', 'Attendance In-Time added successfully!');
            } else {
                DB::table(Config::get('constants.tables.DAILY_ATTENDANCE'))
                    ->where('id', $record_id)
                    ->update(['clock_out' => $now]);
                $request->session()->flash('success', 'Attendance Out-Time added successfully!');
            }
            return redirect('daily-attendance');
            
        }
      
     //Updating daily attendance in the database by people manager of employee
        public function daily_attendance_emp_update(Request $request){ 
            $att_id = $request->input('id');
            $new_intime = $request->input('intime');
            $existing_intime = date("g:i A",strtotime($request->input('ex_intime')));
            $existing_date = date("Y-m-d",strtotime($request->input('ex_intime')));
            $now = new DateTime();
            $diff = round(abs(strtotime($existing_intime) - strtotime($new_intime))/60,2);
            
            $str_fulltime = strtotime("-$diff minutes",strtotime($request->input('ex_intime')));
            $new_fulltime = date("Y-m-d H:i:s",$str_fulltime);
            
            /*echo 'new intime - '.$new_intime;
            echo '<br> existing intime - '. $existing_intime;        
            echo '<br> existing date - '. $existing_date;
            echo '<br> in table - '.$request->input('ex_intime');
            echo '<br> diff - '. $diff;        
            echo '<br> existing strtotime - '. strtotime($request->input('ex_intime'));
            echo '<br> new strtotime      - '. $str_fulltime;
            echo '<br> for db - '. $new_fulltime;
            exit();*/

            //Default Start Time
            $default_start_time = date("Y-m-d 09:00",strtotime($new_fulltime));
            //return $default_start_time;
            

                $daily_deduction_percentage = 0;
                $late_time = '';
                //return strtotime($default_start_time).' - '.strtotime(date("Y-m-d H:i:s",strtotime($new_fulltime))).' = '.$default_start_time.' - '.date("Y-m-d H:i:s");
                //If Late Coming
                if(strtotime($default_start_time) < strtotime(date("Y-m-d H:i:s",strtotime($new_fulltime))))
                {
                    $late = strtotime(date("Y-m-d H:i:s",strtotime($new_fulltime))) - strtotime($default_start_time);
                    $late_min = date("i", $late);
                    $late_time = date("i:s", $late);
                    //return $late_min;

                    $tbl_late_coming = Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS');

                    $sql = "select * from ".$tbl_late_coming." where late_minute >= ".$late_min." and status=1 and role = ".Auth::user()->role." order by late_minute limit 1";
                    
                    $late_deduct = DB::select($sql);
                    if(count($late_deduct)<=0)
                    {
                        $sql = "select * from ".$tbl_late_coming." where late_minute = (select max(late_minute) from ".$tbl_late_coming." where status = 1 and role = ".Auth::user()->role.") order by late_minute limit 1";
                        $late_deduct = DB::select($sql);
                    }
                    if(count($late_deduct)>0)
                        $daily_deduction_percentage = $late_deduct[0]->late_percentage;
                    
                }
                //return '<br><br> deduction - '.$daily_deduction_percentage;
               
                DB::table(Config::get('constants.tables.DAILY_ATTENDANCE'))
                    ->where('id', $att_id)
                    ->update(['clock_in' => $new_fulltime, 'daily_deduction_percentage' => $daily_deduction_percentage]);
                $request->session()->flash('success', 'Employee Attendance Updated successfully!');
            return json_encode(array("Success" => 1));
            //return redirect('day-att-emp-list');
            
        }
        
         ///Updating daily attendance in the database by people manager of employee
        public function daily_attendance_emp_save(Request $request){ 
            $employee_id = $request->input('save_emp_id');
            $employee_num = $request->input('save_emp_num');
            $att_date = $request->input('save_att_date');

            $getstartclose=\App\workinghours::first();
            $intime = date("Y-m-d ".$getstartclose['sob'],strtotime($att_date));
            $outtime = date("Y-m-d ".$getstartclose['cob'],strtotime($att_date));
            //return $default_start_time;
            $daily_deduction_percentage = 0;
            $late_time = '';
            DB::table(Config::get('constants.tables.DAILY_ATTENDANCE'))->insert(
                    ['emp_id' => $employee_id, 'emp_num' => $employee_num, 'date' => $att_date, 'clock_in' => $intime, 'clock_out' => $outtime, 'late_time' => $late_time, 'daily_deduction_percentage' => $daily_deduction_percentage]
                );
        $request->session()->flash('success', 'Employee Attendance Saved successfully!');            
                
            return json_encode(array("Success" => 1));
            
        }
        
        //Get Daily attendance individual view for admin
        public function view_daily_attendance($id='') {
            if($id!='')
                $sql = "SELECT * FROM ".Config::get('constants.tables.DAILY_ATTENDANCE')." WHERE (Month(date) = ".date('m', strtotime(' -1 month'))." OR Month(date) = ".date('m').")  AND emp_id=".$id. " ORDER BY id DESC";
            else
                $sql = "SELECT * FROM ".Config::get('constants.tables.DAILY_ATTENDANCE')." WHERE (Month(date) = ".date('m', strtotime(' -1 month'))." OR Month(date) = ".date('m').")  AND emp_id=".Auth::id()." ORDER BY id DESC";
            $daily_attendances = DB::select($sql);         
          
            return view('payroll/view_daily_attendance',['daily_attendances'=>$daily_attendances]);
            
        }
        
        //Daily attendance calendar view of employee for admin and poeple manager
     


      function view_emp_daily_attendance(Request $request)
        {
            $id = $request->input('id');

             //echo '<pre>'; print_r($id); exit();
            session(['employeeid'=>$id]);
            return view('payroll/daily_attendance_emp_calendar',['emp_id'=>$id]);
        }
        
        //calendar function to Get Daily attendance individual view of employee for admin and poeple manager
        public function view_emp_daily_attendance_calendar(Request $request)
        {
            $id = $request->input('emp_id');
           return  self::daily_attendance_all($id,$request->start,$request->end,$request->red_clr,$request->green_clr,$request->cyan_clr);
           
        }
        
        //Daily attendance calendar view for employee
        function view_daily_attendance_calendar(Request $request)
        {
            $id = $request->id;
        if(\Auth::user()->id==$id){
          
        }
        else{
          $id=\Auth::user()->id;
        }
             //echo '<pre>'; print_r($id); exit();
            return view('payroll/daily_attendance_calendar',['emp_id'=>$id]);
        }
      
        function daily_attendance_calendar(Request $request){

             return   self::daily_attendance_all($request->emp_id,$request->start,$request->end,$request->red_clr,$request->green_clr,$request->cyan_clr); 
        }
        
        //i am here olaoluwa
        //breath attendance
        function breath_attendance($date,$id){

             $daily_attendances =DB::table('daily_attendance')
              ->where('emp_id',$id)
              ->whereDate('date',$date)
              ->select('*')
              ->first();
            return $daily_attendances;
        }

        //get public holiday
        public function get_holiday(){

              $holiday_details =DB::table('public_holidays')
                                    ->select('start_date','end_date')
                                    ->whereRaw('(Month(start_date)) = ' . date('m', strtotime(' -1 month')))
                                    ->orWhereRaw('Month(end_date) = ' . date('m', strtotime(' -1 month')))      
                                    ->get();
 
            
            $holidays=[];
          foreach ($holiday_details as $holiday) {
             
            $begin = new DateTime(  $holiday->start_date );
            $end   = new DateTime( $holiday->end_date );

              for($i = $begin; $i < $end; $i->modify('+1 day')){
 
                $holidays[]=$i->format(date('Y')."-m-d");

            }

          }
    
          
            return $holidays;
        }

        //get weekends
        public function get_weekend(){

             $weekend_details = DB::table(Config::get('constants.tables.WEEKEND_DAYS'))   
                ->select('*') 
                ->where('weekend_status',1)
                ->get()->toArray();

                $weekends = [];
                foreach($weekend_details as $weekend){
                    array_push($weekends,date('N', strtotime($weekend->weekend_day)));
                }
                return $weekends;
        }

        //calendar function for Daily attendance calendar view of employee
        function daily_attendance_all($id,$start,$end,$red_clr,$green_clr,$cyan_clr) 
        {
          
            $begin = new DateTime( $start );
            $end   = new DateTime( $end );
            $holidays=$this->get_holiday();
            $weekends=$this->get_weekend();
            
          
        for($i = $begin; $i < $end; $i->modify('+1 day')){

                if($i->format("Y-m-d")==date('Y-m-d')){
                    break;
                }
                $dayattendance=self::breath_attendance($i->format("Y-m-d"),$id);


          if(in_array($i->format("Y-m-d"), $holidays)) {  

                           $res_arr[]=[
                           'attendance_id'=>'',
                           'clock_in'=>'',
                           'clock_out'=>'',
                           'date'=>$i->format("Y-m-d"),
                           'title'=>'Holiday',
                           'backgroundColor'=>$cyan_clr,
                           'borderColor'=>$cyan_clr
                           ];           
                      
                  }
             else if(in_array(date( 'N', strtotime($i->format("Y-m-d")) ), $weekends)) {

                           $res_arr[]=[
                           'attendance_id'=>'',
                           'clock_in'=>'',
                           'clock_out'=>'',
                           'date'=>$i->format("Y-m-d"),
                           'title'=>'Weekend',
                           'backgroundColor'=>$cyan_clr,
                           'borderColor'=>$cyan_clr
                           ];  

                      
                  } elseif(isset($dayattendance)) {
                      

                           $res_arr[]=[
                           'attendance_id'=>$dayattendance->id,
                           'clock_in'=>$dayattendance->clock_in,
                           'clock_out'=>$dayattendance->clock_out,
                           'date'=>$i->format("Y-m-d"),
                           'title'=>'Present',
                           'backgroundColor'=>$green_clr,
                           'borderColor'=>$green_clr
                           ];   
                   }


                   else{

                            $res_arr[]=[
                            'attendance_id'=>'',
                            'clock_in'=>'',
                            'clock_out'=>'',
                            'date'=>$i->format("Y-m-d"),
                            'title'=>'Absent',
                            'backgroundColor'=>$red_clr,
                            'borderColor'=>$red_clr
                            ];  

                   }
                    

                   }
                 
                  
            return response()->json($res_arr);
       
        }   
        //Get Daily attendance list view for admin
        public function daily_attendance_list()
        {
            $tbl_user = Config::get('constants.tables.USER');
            $employees = DB::table($tbl_user)
                ->select("*")
          ->where("$tbl_user.role", '!=',  Config::get('constants.roles.Admin_User'))
                //->whereIn("$tbl_user.role", [Config::get('constants.roles.People_Manager'), Config::get('constants.roles.Employee')])
                ->orderBy("$tbl_user.id", 'desc')
                // ->where("$tbl_user.locked",0)
                ->whereRaw("($tbl_user.locked=0 or $tbl_user.locked=2)")
                ->get();
            
            //echo '<pre>'; print_r($employees); exit();
            return view('payroll/daily_attendance_list',['employees'=>$employees]);
            
            
        }
        
        //Get Daily attendance list view for people manager
        public function day_att_emp_list()
        {
            $tbl_user = Config::get('constants.tables.USER');
            
            $user_id = DB::select("SELECT * FROM $tbl_user WHERE id='".Auth::id()."'");
            //echo '<pre>'; print_r($user_id[0]->id); exit();
            $employees = DB::table($tbl_user)
                ->select("*")
                ->where("$tbl_user.linemanager_id", '=', $user_id[0]->id)
                //->whereIn("$tbl_user.role", [Config::get('constants.roles.People_Manager'), Config::get('constants.roles.Employee')])
                
                ->whereRaw("($tbl_user.locked=0 or $tbl_user.locked=2)")
                ->orderBy("$tbl_user.id", 'desc')
                ->get();
            
            //echo '<pre>'; print_r($employees); exit();
            return view('payroll/daily_attendance_list',['employees'=>$employees]);
            
            
        }

        private function check_late_charge() {
            return (bool) DB::table('apply_attendance')->select('apply')->value('apply');
        }

        public function disable_late_charge() {

            if (DB::table('apply_attendance')->select('apply')->value('apply') == null) {
                DB::table('apply_attendance')->insert(['apply'=>'0']);
            } else {
                DB::table('apply_attendance')->update(['apply'=>'0']);
            }
            
            return back();
        }

        public function enable_late_charge() {

            if (DB::table('apply_attendance')->select('apply')->value('apply') == null) {
                DB::table('apply_attendance')->insert(['apply'=>'1']);
            } else {
                DB::table('apply_attendance')->update(['apply'=>'1']);
            }

            return back();
        }

        public function disable_tax() {

            if (DB::table('tax_settings')->select('apply')->value('apply') == null) {
                DB::table('tax_settings')->insert(['apply'=>'0']);
            } else {
                DB::table('tax_settings')->update(['apply'=>'0']);
            }
            
            return back();
        }

        public function enable_tax() {

            if (DB::table('tax_settings')->select('apply')->value('apply') == null) {
                DB::table('tax_settings')->insert(['apply'=>'1']);
            } else {
                DB::table('tax_settings')->update(['apply'=>'1']);
            }

            return back();
        }
        
        //Get Daily attendance settings by admin
        public function daily_attendance_settings()
        {
            $tbl_daily_settings = Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS');
            $settings = DB::table($tbl_daily_settings)
                ->select("*")
                ->get();

            $apply = $this->check_late_charge();
          
        $roles = DB::table(Config::get('constants.tables.USER'))
                ->select('role')->distinct('role')
                ->get();

             //   return $roles;
            
            //echo '<pre>'; print_r($settings); exit();
            return view('payroll/daily_attendance_settings',['settings'=>$settings, 'apply'=>$apply])->with('roles', $roles);
       
        }
        
        //update Daily attendance settings by admin
        public function daily_attendance_settings_update(Request $request)
        {
            
            $this->validate($request,[
                'job_role' => 'required',
                'late_min' =>'required|integer',
                'late_percent' =>'required|numeric',
            ],[
                'late_min.required' => 'The late by minutes field is Required.',
                'late_min.integer' => 'The late by minutes field must be integer.',
                'late_percent.required' => 'The late charge percentage field is Required.',
                'late_percent.numeric' => 'The late charge percentage must be numeric.'
            ]);
            
            $late_minute = $request->input('late_min');
            $job_role = $request->input('job_role');
            $id = $request->input('id');
            $late_percentage = $request->input('late_percent');
            $now = new DateTime();
                DB::table(Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS'))
                        ->where('id', $id)
                        ->update(['late_minute' => $late_minute, 'late_percentage' => $late_percentage, 'role' => $job_role, 'modify_date' => $now, 'modify_by' => Auth::id()]);
            
            $request->session()->flash('success', 'Daily Attendance Settings Updated successfully!');
            //return redirect('daily-attendance-settings');
            return json_encode(array("Success" => 1));
        }

        //Status Change
        public function daily_attendance_settings_status_change(Request $request)
        {
            $id = $request->id;
            $old_status = $request->status;
            
            $successor = DB::select("SELECT * FROM ".Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS')." WHERE id='". $id . "'");

            if(count($successor)==1) {
                $new_status=1;
                $icon = "";
                $btn_clr = "btn-success";
                $btn_title = "Make Inactive";

                if($old_status==1)
                {
                    $new_status = 0;
                    $icon = "-slash";
                    $btn_clr = "btn-warning";
                    $btn_title = "Make Active";
                }
                DB::table(Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS'))
                    ->where('id', $id)
                    ->update(['status' => $new_status]);
                $status_div = '<a onclick="fnStatusChange('.$id.','.$new_status.')"><i class="btn btn-sm '.$btn_clr.' waves-effect icon fa-eye'.$icon.'" aria-hidden="true" title="'.$btn_title.'"></i></a>';
                return json_encode(array("Success" => "1", "status_div" => $status_div));
            }
        }

        //Delete Daily attendance settings
        public function delete_daily_attendance_settings($arg)
        {
            //delete Daily attendance settings
            DB::table(Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS'))
            ->where('id', '=', $arg)
            ->delete();
            
            session()->flash('success', 'Daily attendance settings deleted successfully!');
            
            return redirect('daily-attendance-settings');

        }
        
        //add new Daily attendance settings by admin
        public function daily_attendance_settings_add(Request $request)
        {
            
            $this->validate($request,[
                'job_role' => 'required',
                'late_min' =>'required|integer',
                'late_percent' =>'required|numeric',
            ],[
                'late_min.required' => 'The late by minutes field is Required.',
                'late_min.integer' => 'The late by minutes field must be integer.',
                'late_percent.required' => 'The late charge percentage field is Required.',
                'late_percent.numeric' => 'The late charge percentage must be numeric.'
            ]);
            
            $late_minute = $request->input('late_min');
            $job_role = $request->input('job_role');        
            $late_percentage = $request->input('late_percent');
            $now = new DateTime();
            
                DB::table(Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS'))->insert([
                    'role' => $job_role,
                    'late_minute' => $late_minute,
                    'late_percentage' => $late_percentage,
                    'status' => 1,
                    'created_date' => $now,
                    'created_by' => Auth::id()
                ]);
            
            $request->session()->flash('success', 'Daily Attendance Settings Added successfully!');
            //return redirect('daily-attendance-settings');
            return json_encode(array("Success" => 1));
        }
        
        //Daily attendance settings individual for edit by admin
        public function daily_attendance_settings_edit($id)
        {
            $tbl_daily_settings = Config::get('constants.tables.DAILY_ATTENDANCE_SETTINGS');
            $settings_details = DB::table($tbl_daily_settings)
                ->select("*")
                ->where('id', '=' , $id) 
                ->first();
            
            $roles = DB::table(Config::get('constants.tables.USER'))
                ->select('role')->distinct('role')
                ->get();         
            return json_encode(array('settings_details'=>$settings_details, 'id' => $id, 'roles'=> $roles));
            //return view('payroll/daily_attendance_settings',['settings'=>$settings])->with('roles', $roles);
       
        }
        
        /***************** Daily Attendance end ****************************/

        /***************************Payslip logo / watermark Functions Start*******************************/
        //fill in the payslip logo / watermark form

        public function fill_payslip_details()
        {
            $payslip_details = DB::table(Config::get('constants.tables.SETTINGS'))
                ->select('*')
                ->first();

            return view('payroll/payslip_details')->with('payslip_details', $payslip_details);;
        }

        public function get_payroll_approval_settings()
        {
            $settings = DB::table('payroll_approval')
                ->select('first', 'second')
                ->get()
                ->first();

            $employees = DB::table(Config::get('constants.tables.USER') . ' as u')
                            ->select('u.emp_num', 'u.name')
                            ->join(Config::get('constants.tables.BASICPAY') . ' as b', 'u.grade', '=', 'b.emp_grade')
                            ->where('u.role', '>', 0)
                        
                             ->where([['u.role', '>', 0],['u.locked',0]])
                             ->orwhere([['u.role', '>', 0],['u.locked',2]])
                            ->orderBy('u.name', 'asc')
                            ->get()
                            ->toArray();

            return view('payroll/payroll_approval_settings', compact('settings', 'employees'));;
        }

        public function update_payroll_approval_settings(Request $request)
        {   

            $this->validate($request, ['first'=>'required'], ['first'=>'The first level approval must be set.']);

            $input = $request->all();
            unset($input['_token']);

            $input['second'] = $input['second'] == '' ? NULL : $input['second'];

            if ($input['first'] == $input['second']) {

                session()->flash('error', 'You cannot assign the same personnel for both approval levels!');

            } elseif (!DB::table('payroll_approval')->select('*')->first()) {

                DB::table('payroll_approval')->insert($input);
                session()->flash('success', 'Payroll approval settings updated!');

            } elseif (DB::table('payroll_approval')->update($input)) {

                session()->flash('success', 'Payroll approval settings updated!');

            } else {

                session()->flash('error', 'Payroll approval settings update failed!');

            }

            return redirect('/payroll-approval-settings');
        }

        public function payroll_approval_overview()
        {   
            if ($this->check_pending_payroll()) {

                $current = $this->generate_chart();

                $month = $this->run_payroll_by_month_end() ? date('M-Y', strtotime('- 1 month')) : date('M-Y', strtotime('- 2 month'));

                $previous = $this->generate_chart($month);
                return view('payroll/payroll_approval', compact('current', 'previous'));
            } else {
                return back();
            }                
        }

        public function check_pending_payroll()
        {

            $pending = DB::table('payroll_pending')
                ->select('*')
                ->where('pending', '1')
                ->orderBy('id', 'desc')
                ->first();

            if ($pending ) {
            
                $settings = DB::table('payroll_approval')
                    ->select('first', 'second')
                    ->where('first', Auth::user()->emp_num)
                    ->orWhere('second', Auth::user()->emp_num)
                    ->first();
                  if(isset($settings->first)){  
                if ($settings->first == Auth::user()->emp_num && !$pending->first_decision ) {
                    return true;
                } else if ($settings->second == Auth::user()->emp_num && $pending->first_decision == '1' && !$pending->second_decision) {
                    return true;
                }
            }
            return false;
            
            }

            return false;
        }

        public function approve_or_reject_payroll(Request $request)
        {       

            $input = $request->all();
             
            if (empty($input['comment'])) {
                session()->flash('error', 'Please write a comment for your decision');
                return back();
            } 
           if (isset($request->approve)) {
                $decision = 1;
               
            } else if (isset($request->reject)) {
                $decision = 0;
                
            }
            // dd($decision);

            $pending = DB::table('payroll_pending')
                ->select('*')
                ->where('pending', '1')
                ->orderBy('id', 'desc')
                ->first();
                    
            if ($pending) {

                $month_year = $this->get_payroll_run_date();
            
                $settings = DB::table('payroll_approval')
                    ->select('first', 'second')
                    ->where('first', Auth::user()->emp_num)
                    ->orWhere('second', Auth::user()->emp_num)
                    ->first();

                if ($settings->first == Auth::user()->emp_num && !$pending->first_decision) {

                    DB::table('payroll_pending')->update(['first_decision'=>$decision]);

                    // notify this guy that he just approved/rejected the payroll
                    \Mail::to(Auth::user()->email)->send(new monthlyPayrollDecision(Auth::user()->name, $month_year, $decision == 1 ? 'Approved' : 'Rejected'));

                    if ($decision == 1) {

                        DB::table('payroll_pending')->where('month_year',$month_year)->update(['first_comment'=>$input['comment']]);

                        if (!$settings->second) {
                            DB::table('payroll_pending')->update(['pending'=>'2']);
                        } else {
                            $emp = DB::table('users as p')->select('u.name', 'u.email')->first();
                            \Mail::to($emp->email)->send(new monthlyPayrollPrepared($emp->name, $month_year));
                        }

                    } else {
                        //f here
                        // $this->clearpayroll($month_year);
                        //notify the payroll
                         DB::table('payroll_pending')->where('month_year',$month_year)->update(['first_comment'=>$input['comment']]);

                        if (!$settings->second) {
                            DB::table('payroll_pending')->update(['pending'=>'2']);
                        }
                    }

                } else if ($settings->second == Auth::user()->emp_num && $pending->first_decision == '1' && !$settings->second_decision) {

                    DB::table('payroll_pending')->where('month_year',$month_year)->update(['second_decision'=>$decision]);

                    // notify this guy that he just approved/rejected the payroll
                    \Mail::to(Auth::user()->email)->send(new monthlyPayrollDecision(Auth::user()->name, $month_year, $decision == 1 ? 'Approved' : 'Rejected'));

                    if ($decision == 1) {
                        DB::table('payroll_pending')->where('month_year',$month_year)->update(['pending'=>'2','second_comment'=>$input['comment']]);
                    } else {

                        DB::table('payroll_pending')->where('month_year',$month_year)->update(['pending'=>'2','second_comment'=>$input['comment']]);
                        // $this->clearpayroll($month_year);
                    }
                }
            }

            return redirect('home')->with('success','Operation Successful');
        }

        private function reset_payroll($pending_id, $month_year) 
        {
            // reset the payroll
            foreach(DB::table('payroll')
                ->where('month_year', $month_year)
                ->pluck('id') as $id) {
                DB::table('payroll_details')->where('payroll_id', $id)->delete();
            }

            DB::table('payroll')->where('month_year', $month_year)->delete();
            DB::table('payroll_pending')->where('id', $pending_id)->delete();

            // notify the payroll guy that the payroll was rejected

        }

        //update the payslip logo / watermark
        public function update_payslip_details(Request $request){

            $this->validate($request,[
            'watermark_text'=>'required',
            'payslip_logo'=>'mimes:jpg,jpeg,png,gif|max:2048' //2048 = 2 MB
            ]);

            $watermark_text = $request->input('watermark_text');


            $successor = $training_materials = DB::table(Config::get('constants.tables.SETTINGS'))
                ->select('*')
                ->where('id', '1')
                ->first();
                $payslip_logo = $successor->payslip_logo;

            if($request -> file('payslip_logo'))
            {
                $file = $request -> file('payslip_logo');
                $original_file_name = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $payslip_logo = strtotime("now").".".$extension;

                if($successor->payslip_logo!='' && file_exists("public/payslip_logo/".$successor->payslip_logo))
                    unlink("public/payslip_logo/".$successor->payslip_logo);
                //move uploaded file
                $file->move(public_path('payslip_logo'), $payslip_logo);
            }

            if(count($successor)==1) {
                DB::table(Config::get('constants.tables.SETTINGS'))
                    ->where('id', 1)
                    ->update(['watermark_text' => $watermark_text, 'payslip_logo' => $payslip_logo]);
                $request->session()->flash('success', 'Payslip logo / watermark updated successfully!');
            } else {
                $request->session()->flash('error', 'No record Found. Please try again!');
            }  

            
            return redirect('edit-payslip-details');  

            //return json_encode(array("Success" => 1));  
        }

        /***************************Payslip logo / watermark Functions end*******************************/

        /***************************Employee Expenses Functions start*******************************/
        //Employee Expenses List
        public function my_expenses_list()
        {
             $expense_list = DB::table(Config::get('constants.tables.EXPENSES'))   
                ->select('*')
                    ->where('emp_id', Auth::id())
                ->orderBy('id', 'DESC')
                ->get()->toArray();

            return view('payroll/employee_expenses_list')->with('expense_list', $expense_list);    
        }

        //Adding expense
        public function add_expense(Request $request){       
            $now = new DateTime();

            $expense_details = $request->input('expense_details');
            $expense_charge = $request->input('expense_charge');
            $expense_date = date("Y-m-d", strtotime($request->input('expense_date')));

            $this->validate($request,[
            'expense_details'=>array('required'),
            'expense_charge'=>array('required', 'regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'),
            'expense_date'=>array('required', 'date')
            ]);

            //To save the track of status change we can save the created date and status
            $status_tracks = Config::get('constants.expense_status.APPLIED');
            $status_track_dates = $now;   
            $status_track_by  = Auth::id();


            DB::table(Config::get('constants.tables.EXPENSES'))->insert(['expense_details' => $expense_details,'expense_charge' => $expense_charge,'expense_date' => $expense_date,'created_date' => $now, 'emp_id' => Auth::id(), 'status_tracks' => $status_tracks, 'status_track_dates' => $status_track_dates, 'status_track_by' => $status_track_by]);

            $request->session()->flash('success', 'Expense added successfully!');
            
            return json_encode(array("Success" => 1));
        } 


        //Delete expense
        public function delete_expense($arg)
        {
            $tbl_holiday = Config::get('constants.tables.EXPENSES');
           
                //delete holiday
                DB::table($tbl_holiday)
                ->where('id', '=', $arg)
                ->delete();
                
                session()->flash('success', 'Expense deleted successfully!');
            
            return redirect('my-expenses');

        } 

        //Fill the edit expense form
        public function fill_expense_form($id)
        {
            $expense_details = DB::table(Config::get('constants.tables.EXPENSES'))
                ->select('*')
                ->where('id', '=' , $id)            
                ->first();

            $expense_details->expense_date = date("M d, Y", strtotime($expense_details->expense_date));

            
            return json_encode(array('expense_det'=>$expense_details, 'id' => $id));
        }


        //Update expense
        public function update_expense(Request $request) {
            $id = $request->input('id');
            $now = new DateTime();
            $expense_details = $request->input('expense_details');
            $expense_charge = $request->input('expense_charge');
            $expense_date = date("Y-m-d", strtotime($request->input('expense_date')));
            $revise_expense = $request->input('revise_expense');

            $this->validate($request, [
                'expense_details'=>array('required'),
                'expense_charge'=>array('required', 'regex:/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/'),
                'expense_date'=>array('required', 'date')
            ]);

            
            
            $successor = DB::table(Config::get('constants.tables.EXPENSES'))
                ->where('id', $id)
                ->first();

            if (count($successor)==1) {

                $status_tracks = $successor->status_tracks;  
                $status_track_dates = $successor->status_track_dates;  
                $status_track_by = $successor->status_track_by;  
                $expense_status = $successor->expense_status;
                $status_updated_by = $successor->status_updated_by;
                $status_updated_on = $successor->status_updated_on;

                //If Status changed from revise to revised
                if($revise_expense==1)
                {
                    $expense_status = Config::get('constants.expense_status.REVISED');
                    $status_tracks = $successor->status_tracks.'||||'.$expense_status;
                    $status_track_dates = $successor->status_track_dates.'||||'.date("Y-m-d H:i:s");
                    $status_track_by = $successor->status_track_by.'||||'.Auth::id();

                    $status_updated_by = Auth::id();
                    $status_updated_on = $now; 
                }

                DB::table(Config::get('constants.tables.EXPENSES'))
                    ->where('id', $id)
                    ->update(['expense_details' => $expense_details, 'expense_charge' => $expense_charge, 'expense_date' => $expense_date, 'expense_status' => $expense_status, 'status_updated_by' => $status_updated_by, 'status_updated_on' => $status_updated_on, 'status_tracks' => $status_tracks, 'status_track_dates' => $status_track_dates, 'status_track_by' => $status_track_by]);
                $request->session()->flash('success', 'Expense updated successfully!');
            } else {
                $request->session()->flash('error', 'No record Found. Please try again!');
            }        
            return json_encode(array("Success" => 1));  
        }

        //Admin all employee Expenses List
        public function employee_expenses_list()
        {
            $tbl_expenses = Config::get('constants.tables.EXPENSES');
            $tbl_user = Config::get('constants.tables.USER');   

            $expense_list = DB::table($tbl_expenses)   
                ->select($tbl_expenses.'.*', $tbl_user.'.name as user_name')
                ->join($tbl_user, $tbl_user.'.id', '=', $tbl_expenses.'.emp_id')
                // ->where($tbl_user.'.locked',0)
                ->whereRaw("($tbl_user.locked=0 or $tbl_user.locked=2)")
                ->orderBy($tbl_expenses.'.id', 'DESC')
                ->get()->toArray();

            
            $sql = "SELECT ".$tbl_expenses.".*, ".$tbl_user.".name as user_name FROM ".$tbl_expenses." join ".$tbl_user." on (".$tbl_user.".id = ".$tbl_expenses.".emp_id)";
            //If the logged in user is admin
            if(Auth::user()->role==Config::get('constants.roles.Admin_User'))
                $sql.=" where (".$tbl_user.".linemanager_id = ".Auth::id()." || ".$tbl_user.".linemanager_id = 0)";
            else
                $sql.=" where ".$tbl_user.".linemanager_id = ".Auth::id();
            $sql.= " order by ".$tbl_expenses.".id desc";

            $expense_list = DB::select($sql);

            return view('payroll/all_employee_expenses_list')->with('expense_list', $expense_list);    
        }

        //Expense Status Change
        public function expense_status_change(Request $request)
        {
            $id = $request->input('expense_id');
            $now = new DateTime();
            $today = Date('Y-m-d');
            $expense_status = $request->input('expense_status');  

            $successor = DB::table(Config::get('constants.tables.EXPENSES'))
                ->where('id', $id)
                ->first();  

            if(count($successor)==1)
            {
                //If admin added any internal diagnosis
                if(Auth::user()->role==Config::get('constants.roles.Admin_User') || Auth::user()->role==Config::get('constants.roles.People_Manager'))
                {
                    $this->validate($request,[
                        'expense_status'=>'required'
                    ]);

                    $status_updated_by = $successor->status_updated_by;
                    $status_updated_on = $successor->status_updated_on;  
                    $status_tracks = $successor->status_tracks;  
                    $status_track_dates = $successor->status_track_dates;     
                    $status_track_by =  $successor->status_track_by;       

                    //If expense status updated
                    if($expense_status!=$successor->expense_status)
                    {
                        $status_updated_by = Auth::id();
                        $status_updated_on = $now;
                        $status_tracks = $successor->status_tracks.'||||'.$expense_status;
                        $status_track_dates = $successor->status_track_dates.'||||'.date("Y-m-d H:i:s");
                        $status_track_by = $successor->status_track_by.'||||'.Auth::id();
                    }   

                    $is_approved = 0;
                    $approved_by = '';
                    $approved_on = '';
                    
                    //Approved
                    if($expense_status==Config::get('constants.expense_status.APPROVED'))
                    {
                        $is_approved = 1;
                        $approved_by = Auth::id();
                        $approved_on = $now;                    
                    }
                
                    DB::table(Config::get('constants.tables.EXPENSES'))
                        ->where('id', $id)
                        ->update(['expense_status' => $expense_status, 'status_updated_by' => $status_updated_by, 'status_updated_on' => $status_updated_on, 'is_approved' => $is_approved, 'approved_by' => $approved_by, 'approved_on' => $approved_on, 'status_tracks' => $status_tracks, 'status_track_dates' => $status_track_dates, 'status_track_by' => $status_track_by]);
                    $request->session()->flash('success', 'Expense status updated successfully!');
                }  
            }
            else
            {
                $request->session()->flash('error', 'No record Found. Please try again!');
            }      
           
                if($expense_status==Config::get('constants.expense_status.APPLIED'))
                {
                    $icon = "fa-exclamation-circle";
                    $clr= "warning";
                    $title="Applied";
                }
                else if($expense_status==Config::get('constants.expense_status.APPROVED'))
                {
                    $icon = "fa-check";
                    $clr= "success";
                    $title="Approved";
                }
                else if($expense_status==Config::get('constants.expense_status.REVISE'))
                {
                    $icon = "fa-file-text-o";
                    $clr= "warning";
                    $title="Revise";
                }
                else if($expense_status==Config::get('constants.expense_status.REVISED'))
                {
                    $icon = "fa-copy";
                    $clr= "success";
                    $title="Revised";
                }
                else if($expense_status==Config::get('constants.expense_status.REJECTED'))
                {
                    $icon = "fa-close";
                    $clr= "danger";
                    $title="Rejected";
                }

                     $status_div = '<a onclick="fnStatusChange('.$id.','.$expense_status.')"><i class="btn btn-sm btn-'.$clr.' waves-effect icon '.$icon.'" aria-hidden="true" title="'.$title.'"></i></a>';

                return json_encode(array("Success" => "1", "status_div" => $status_div));
        }


        /***************************Employee Expenses Functions end*******************************/
    }