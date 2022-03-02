<?php

namespace App\Console\Commands;

use App\Payroll;
use App\PayrollDetail;
use function GuzzleHttp\Psr7\str;
use Illuminate\Console\Command;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;


class PayrollFundTransferCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:transfer {payroll_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info($this->argument('payroll_id'));
        $this->disbursePayment($this->argument('payroll_id'));
    }
    public function disbursePayment($payroll){
        $payroll=Payroll::find($payroll);

        $narration="Salary {$payroll->month} {$payroll->year}";
        $this->info($narration);
        $users_payroll=PayrollDetail::where('payment_status',0)->where('payroll_id',$payroll->id)->whereHas('user',function ($query){
            $query->where('bank_account_no','!=','');
        })->with('user')->get();
        foreach ($users_payroll as $user_payroll){
            $user=$user_payroll->user;
            $user_details=$this->nameEnquiry($user);
            $user_payroll->update(['name_enquiry_response'=>$user_details]);
            // if ($user_details['accountname']==$user->first_name){//do a better check
            $enquiry_ref=$user_details['requestid'];
           
            $account_no=str_replace('-N','',$user->bank_account_no);
            $credit=$this->creditAccount($account_no,$user_details['accountname'],$enquiry_ref,$user_payroll->netpay,$narration);
            $user_payroll->update(['payment_response'=>$credit,'payment_status'=>1]);
            //  }
        }
    }

    public function nameEnquiry($user){
        $account_no=str_replace('-N','',$user->bank_account_no);
        $request=[
            'accountno'=>$account_no,
            'requestid'=>$this->generateUniqueId(),
            'sourcebankcode'=>'561',
            'productcode'=>'HRM',
        ];
        $endpoint='https://digital.novambl.com/coreapi/transferservice/core/nameenquiry';
        $response=$this->makeAPICall($request,$endpoint);
        $this->info('request to name enquiry '.$user->name);
        return $response;
    }

    public function creditAccount($account_no,$account_name,$enquiry_ref,$amount,$narration){
        $request=[
            'requestid'=>$this->generateUniqueId(),
            'transactionref'=>$this->generateUniqueId(),
            'accountno'=>$account_no,
            'accountname'=>$account_name,
            'enquiryref'=>$enquiry_ref,
            'amount'=>$amount,
            'narration'=>$narration,
            'sourcebankaccount'=>'G-20151034',
            'sourcecustomername'=>'STAFF GL ACCOUNT',
            'sourcebankcode'=>'561',
            'productcode'=>'HRM',
        ];
        
        $endpoint='https://digital.novambl.com/coreapi/transferservice/core/credit';
        $response=$this->makeAPICall($request,$endpoint);
        $this->info('request to credit account '.$account_no);
        return $response;
    }

    public function generateUniqueId(){
        $start=(int)100000000000;
        $end=(int)mt_getrandmax();
        return mt_rand($start, $end);
    }

    public function makeAPICall($request,$endpoint){
        $encrypted_request=$this->encrypt($request);
       $encrypted_request=trim($encrypted_request);
            $client = new Client();
            $res=  $client->post($endpoint,
                [
                    'headers' => [
                        'Content-Type'  => 'text/plain',
                        'Module'        => 'HRM',
                    ],
                    'body' => $encrypted_request,
                    'verify' => false
                ]);
            $response= (string) $res->getBody();
            return $this->decrypt($response);
         try { }
        catch (\Exception $e) {
            return 'error in making request';
        }
    }
    public function makeRawAPICall($request,$endpoint){
        $request=['payload'=>$request];
        
            $client = new Client();
            $res=  $client->post($endpoint,
                [
                    'headers' => [
                        'Content-Type'  => 'application/x-www-form-urlencoded',
                        //'Module'        => 'HRM',
                    ],
                    'form_params' => $request
                ]);
            $response= $res->getBody();
            return $response;
            try {
         }
        catch (\Exception $e) {
            return 'error in making request';
        }
    }

    public function encrypt($data){
        $data=json_encode($data);
        $encrypted= $this->makeRawAPICall($data,'https://encdec.azurewebsites.net?type=enc');
        return $encrypted;
    }

    public function decrypt($data){
        $decrypted= $this->makeRawAPICall($data,'https://encdec.azurewebsites.net?type=dec');
        $decrypted=json_decode($decrypted,true);
        // $this->info($decrypted);
        return $decrypted;
    }
}
