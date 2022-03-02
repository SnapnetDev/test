<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MailRecieved;

class AARMailMangement extends Controller
{
       //
	public function __construct(){
		$this->middleware(['auth']);
	}

		public function MailDirectory(Request $request){
			if(Auth::user()->role->permissions->contains('constant','mail_management')){
				$allMail = \App\AARMailMangement::orderBy('created_at', 'DESC')->get();
			}else{
			$allMail = \App\AARMailMangement::where('receiver',Auth::user()->id)->orWhere('userId',Auth::user()->id)->orderBy('created_at', 'DESC')->get();
			}		
		$allUser = \App\User::get();
		return view('mail_management.mail-directory', compact('allMail', 'allUser'));
		}



		public function MailAcknowledgement(Request $request,$id){
			$mail = \App\AARMailMangement::find($id);
			$mail->update(['status'=>'1']);
		return redirect('/mail-directory');
		}



		public function NewMail(Request $request){
			$newMail = \App\AARMailMangement::create([
				'userId' => Auth::user()->id,
				'sender' => $request->sender,
				'receiver' => $request->receiver,
				'subject' => $request->subject,
				'email' => $request->email,
				'phone' => $request->phone,
				'direction' => $request->direction,
				'status' => '0',
				'comments' => '',
			]);
			$newMail->resolveName->notify(new MailRecieved($newMail));
			return redirect('/mail-directory/');
		}

		public function EditMail(Request $request){
			$editMail = \App\AARMailMangement::find($request->id);
			$editMail->update([
				'sender' => $request->sender,
				'subject' => $request->subject,
				'email' => $request->email,
				'phone' => $request->phone,
				'direction' => $request->direction,
				'status' => '0',
				'comments' => '',
			]);
			$editMail->resolveName->notify(new MailRecieved($editMail));
			return redirect('/mail-directory/');
		}


		public function MailDelete(Request $request, $id){
			$deleteMail = \App\AARMailMangement::find($id)->delete();
					if($deleteMail){
						return 'success';
					}
		//return redirect('/mail-directory');
		}

		public function MailGet(Request $request, $id){
			return \App\AARMailMangement::where('id', $id)->first();
		}



}
