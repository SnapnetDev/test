<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/09/2020
 * Time: 22:16
 */

namespace App\Http\Controllers;


use App\LoanApproval;
use App\Services\LoanApprovalService;
use App\User;

class LoanApprovalController
{
    private $loanApprovalService = null;

    function __construct(LoanApprovalService $loanApprovalService)
    {
        $this->loanApprovalService = $loanApprovalService;
    }

    function index(){
      return $this->loanApprovalService->index();
    }

    function approve(){
//      $user = User::find(request('userId'));
      $objApproval = LoanApproval::find(request('loan_approval_id'));
      $user = $objApproval->loan_request->user;

      $this->loanApprovalService->approveStage($objApproval,$user);
      return [
          'message'=>'Loan approved and passed on to the next stage.',
          'error'=>false
      ];

    }

    function reject(){
//        $user = User::find(request('userId'));
        $objApproval = LoanApproval::find(request('loan_approval_id'));
        $user = $objApproval->loan_request->user;
        $this->loanApprovalService->rejectStage($objApproval,$user);

        return [
            'message'=>'Loan rejected stage.',
            'error'=>false
        ];

    }


}