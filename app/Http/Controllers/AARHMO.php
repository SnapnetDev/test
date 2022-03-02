<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Excel;

class AARHMO extends Controller
{
    //
	public function __construct(){
		$this->middleware(['auth']);
	}

	public function HMO(Request $request){
		$allHMO = \App\AARHMO::get();
		return view('hmo.hmo-setup', compact('allHMO'));
	}

	public function NewHMO(Request $request){
		if($request->hasFile('template')){
			$nameOfHMO = trim($request->name);
			$path = $request->file('template')->getRealPath();
			$data = Excel::load($path, function($reader) {
			})->get();

			$hmoReturnId = \App\AARHMO::create(['hmo' => $nameOfHMO])->id;
			if($hmoReturnId){
				foreach ($data as $key => $value) {
					$nameofSheetasBand = $value->getTitle();
					foreach ($value as $key => $value) {
						$hospital = $value['hospital'];
						$category = $value['category'];
						$coverage = $value['coverage'];
						$address = $value['address'];
						$contact = $value['contact'];

						\App\AARHMOHospitals::create([
							'hmo' => $hmoReturnId,
							'hospital' => $hospital,
							'band' => $nameofSheetasBand,
							'category' => $category,
							'coverage' => $coverage,
							'address' => $address,
							'contact' => $contact
						]);
					}
				}
			}
		}
		return redirect('/hmo-setup');
	}

	public function HMOHospitalsPreview(Request $request){
		$HMOHospitalsPreview = \App\AARHMOHospitals::where('hmo',$request->hmoId)->get(); //->paginate(20);
		$bandsCategory = \App\AARHMOHospitals::select('band')->distinct()->where('hmo',$request->hmoId)->get();
		return view('hmo.hmo-setup', compact('HMOHospitalsPreview', 'bandsCategory'));
	}

	public function getHMOHospital(Request $request){
		return \App\AARHMOHospitals::where('id',$request->id)->first();
	}

	public function patchHMOHospital(Request $request){
		$patchHospital = \App\AARHMOHospitals::find($request->hospitalId);
		$patchHospital->hospital = $request->hospital;
		$patchHospital->band = $request->band;
		$patchHospital->category = $request->category;
		$patchHospital->address = $request->address;
		$patchHospital->save();
		return redirect("/$request->hmoName/$request->hmoId");
	}

	public function HMOSelfService(Request $request){
		$request->userId ? $userId = base64_decode($request->userId) : $userId = Auth::user()->id;
		$findUser = \App\AARHMOSelfService::where('userId', $userId)->get()->count();
		if($findUser == 0){
			\App\AARHMOSelfService::create(['userId' => $userId]);
		}
		$HMOSelfService = \App\AARHMOSelfService::where('userId', $userId)->first();
		$AllHMO = \App\AARHMO::get();
		$genotype = ['(A+)', '(A-)', '(B+)', '(B-)', '(O+)', '(O-)', '(AB+)', '(AB-)' ];
		$bloodgroup = ['AA', 'AS', 'SS', 'AC' ];
		return view('hmo.selfservice', compact('AllHMO', 'userId', 'HMOSelfService', 'genotype', 'bloodgroup'));
	}

	public function PostHMOSelfService(Request $request){
		$request->userId ? $userId = base64_decode($request->userId) : $userId = Auth::user()->id;
		$post = [
			'userId' => $userId,
			'hmo' => $request->hmoId,
			'primary_hospital' => $request->hospital1,
			'secondary_hospital' => $request->hospital2,
			'genotype' => $request->genotype,
			'health_plan_type' => $request->healthplantype,
			'bloodgroup' => $request->bloodgroup,
			'health_plan_type' => $request->healthplantype,
			'precondition' => $request->precondition,
			'status' => 1
		];

		\App\User::where('id', $userId)->update(['religion' => $request->religion, 'phone' => $request->userPhone, 'address' => $request->address]);
		$checker = \App\AARHMOSelfService::where('userId', $userId)->first();
		if($checker){
			$hmoRecord = \App\AARHMOSelfService::where('userId', $userId)->update($post);
		}else{
			$hmoRecord = \App\AARHMOSelfService::create($post);
		}

		//////////////Save Dependants////////////////////////////
		foreach ($request->dependant as $key => $value) {
			$dependantName = trim($request->dependant[$key]);
			$type = $request->type[$key];
			$post = [
				'userId' => $userId,
				'type' => $type,
				'fullname' => $dependantName,
				'date_of_birth' => $request->dob[$key],
				'gender' => $request->gender[$key],
				'primary_hospital' => $request->hospitalLoop1[$key],
				'secondary_hospital' => $request->hospitalLoop2[$key],
				'health_plan_type' =>  $request->dependanthealthplantype[$key],
				'pre_condition' => $request->preCondition[$key],
				'occupation' => $request->occupation[$key],
				'phone' => $request->phone[$key]
			];
			$checker = \App\AARHMOSelfServiceDependents::where('userId', $userId)->where('type', $type)->first();
			if($checker && $dependantName){
				$checker->update($post);
			}elseif(!$checker && $dependantName){
				$dependant = \App\AARHMOSelfServiceDependents::create($post);
			}
			////////////Dependants Passport Processor/////////////
			if(@$request->dependantPassport[$key]){
				$passport = $request->dependantPassport[$key];
				$PassportfileName = md5($type.$userId).'.'.$passport->getClientOriginalExtension();
				$destinationPath = 'assets/hmo/';
				$passport->move($destinationPath, $PassportfileName);
				$passport = ['passport' => $PassportfileName];
				\App\AARHMOSelfServiceDependents::where('userId', $userId)->where('type', $type)->update($passport);
			}
		}

		if(!empty($request->userId))
			$path = "?userId={$request->userId}&path=6534";

		return redirect("/selfservice-hmo".$path);
	}

	public function HMODirectory(Request $request){
		$HMODirectory = \App\AARHMOSelfService::where('hmo', '<>', NULL)->get();//paginate(20);
		return view('hmo.hmo-directory', compact('HMODirectory'));
	}

	public function getHMOHospitalsList(Request $request){
		return \App\AARHMOHospitals::where('hmo',$request->id)->get();
	}

	public function getHMOHospitalsBand(Request $request){
		return \App\AARHMOHospitals::select('band')->distinct()->where('hmo',$request->id)->where('band','<>','')->get();
	}

	public function deleteUserHMO(Request $request){
		$delete = \App\AARHMOSelfService::where('userId', $request->userId)->delete();
		$delete2 = \App\AARHMOSelfServiceDependents::where('userId', $request->userId)->delete();
		if($delete){
			return 'success';
		}
	}









}
