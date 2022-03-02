<?php
namespace App\Traits;
use Illuminate\Http\Request;
use App\JobListing;
use App\JobListingExternal;
use App\Applicant;
use App\User;
use App\JobApplication;
use App\Company;
use App\Department;
use App\Filters\JobListingFilter;
use App\Filters\EJobListingFilter;
use Auth;

trait RecruitTrait
{
	public function processGet($route,Request $request){
		switch ($route) {
			
				case 'joblistings':
				# code...
				return $this->jobListings($request);
				break;
				case 'view_job_listing':
				# code...
				return $this->viewJobListing($request);
				break;
				case 'delete_job_listing':
				# code...
				return $this->deleteJobListing($request);
				break;
				case 'change_listing_status':
				# code...
				return $this->changeJobListingStatus($request);
				break;
				case 'get_job_listing_info':
				# code...
				return $this->getJobListingInfo($request);
				break;
				case 'myjobs':
				# code...
				return $this->empJobListings($request);
				break;
				case 'jobsapplied':
				# code...
				return $this->empAppJobListings($request);
				break;
				case 'favjobs':
				# code...
				return $this->empFavJobListings($request);
				break;
				case 'viewjob':
				# code...
				return $this->empViewJobListing($request);
				break;
				case 'emp_job_fav':
				# code...
				return $this->empJobListingFavorite($request);
				break;
				case 'emp_job_apply':
				# code...
				return $this->empJobListingApplication($request);
				break;
				case 'applicant_summary':
				# code...
				return $this->applicantSummary($request);
				break;
				case 'external':
				# code...
				return $this->externalJobListings($request);
				break;
				case 'delete_ejob_listing':
				# code...
				return $this->deleteEJobListing($request);
				break;
				case 'change_elisting_status':
				# code...
				return $this->changeEJobListingStatus($request);
				break;
				case 'get_ejob_listing_info':
				# code...
				return $this->getEJobListingInfo($request);
				break;
			default:
				return $this->index($request);
				break;
		}
		 
	}


	public function processPost(Request $request){
		try{
		switch ($request->type) {
			case 'save_listing':
				# code...
			     return $this->saveJobListing($request);
				break;
			
			default:
				# code...
				break;
		}
	}
	catch(\Exception $ex){

		return response()->json(['status'=>'error','message'=>$ex->getMessage()]);
	}

	}

	public function saveJobListing(Request $request)
	{
		if ($request->target==1) {
			$listing=JobListing::updateOrCreate(['id'=>$request->job_listing_id],['job_id'=>$request->job_id,'salary_from'=>$request->salary_from,'salary_to'=>$request->salary_to,'expires'=>date('Y-m-d',strtotime($request->expires)),'level'=>$request->level,'experience_from'=>$request->experience_from,'experience_to'=>$request->experience_to,'requirements'=>$request->requirements,'type'=>$request->jtype]);
		return 'success';
		}elseif($request->target==2){
			$listing=JobListingExternal::updateOrCreate(['id'=>$request->job_listing_id],['job_ref'=>$request->job_ref,'title'=>$request->title,'level'=>$request->level,'state_id'=>$request->state,'country_id'=>$request->country,'salary_from'=>$request->salary_from,'salary_to'=>$request->salary_to,'experience_from'=>$request->experience_from,'experience_to'=>$request->experience_to,'description'=>$request->description,'experience'=>$request->experience,'skills'=>$request->skills,'expires'=>date('Y-m-d',strtotime($request->expires)),'user_id'=>Auth::user()->id,'status'=>0]);
			return 'success';
		}
		 

	}

	// public function jobListings(Request $request)
	// {
	// 	$listings=JobListing::all();

	// }

	public function viewJobListing(Request $request)
	{
		$joblisting=JobListing::find($request->listing_id);
		$company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
		
				return view('recruit.view',compact('joblisting','company','departments','jobs'));

	}
	public function empViewJobListing(Request $request)
	{
		$joblisting=JobListing::find($request->listing_id);
		if ($joblisting->status==1) {
			$company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
		
				return view('recruit.user_view',compact('joblisting','company','departments','jobs'));
		}else{
			return redirect()->back()->with('This job cannot be viewed as it has been unlisted');
		}
		

	}
	 public function empJobListings(Request $request)
    {
    	if (count($request->all())==0) {
        $company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
         $joblistings=JobListing::where(['status'=>1])->paginate(5);

        return view('recruit.user_listing',compact('company','departments','jobs','joblistings'));
    }else{

    	
        $company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
         $joblistings=JobListingFilter::apply($request);

        return view('recruit.user_listing',compact('company','departments','jobs','joblistings'));
    }
    }
     public function empFavJobListings(Request $request)
    {
        $company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
          $joblistings=JobListing::where(['status'=>1])->paginate(5);

        return view('recruit.user_fav_listing',compact('company','departments','jobs','joblistings'));
    }
     public function applicantSummary(Request $request)
    {
        $joblisting=JobListing::find($request->listing_id);
        $user=User::find($request->user_id);

       $qualification=$user->educationHistories()->whereHas('qualification', function ($query) use ($joblisting) {
        	$query->where('qualifications.id', $joblisting->job->qualification_id);

        })->count();
         

        return view('recruit.partials.applicantsummary',compact('joblisting','user','qualification'));
    }
     public function empAppJobListings(Request $request)
    {
        $company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
         $joblistings=JobListing::where(['status'=>1])->paginate(5);

        return view('recruit.user_application_listing',compact('company','departments','jobs','joblistings'));
    }
	
	public function getJobListingInfo(Request $request)
	{
		return $joblisting=JobListing::find($request->listing_id);


	}
	public function getEJobListingInfo(Request $request)
	{
		return $joblisting=JobListingExternal::find($request->listing_id);


	}
	public function deleteJobListing(Request $request)
	{
		$listing=JobListing::find($request->listing_id);
	   if ($listing) {
	     $listing->delete();
	      return 'success';
	   }
	}
	public function deleteEJobListing(Request $request)
	{
		$listing=JobListingExternal::find($request->listing_id);
	   if ($listing) {
	     $listing->delete();
	      return 'success';
	   }
	}
	public function changeEJobListingStatus(Request $request)
	  {
	   $listing=JobListingExternal::find($request->listing_id);
	   if ($listing->status==1) {
	     $listing->update(['status'=>0]);
	      return 2;
	   }elseif($listing->status==0){
	    $listing->update(['status'=>1]);
	    return 1;
	   }
	 }
	public function changeJobListingStatus(Request $request)
	  {
	   $listing=JobListing::find($request->listing_id);
	   if ($listing->status==1) {
	     $listing->update(['status'=>0]);
	      return 2;
	   }elseif($listing->status==0){
	    $listing->update(['status'=>1]);
	    return 1;
	   }
	 }
	 public function externalJobListings(Request $request)
	 {
	 	if (count($request->all())==0) {
        $company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
         $joblistings=JobListingExternal::paginate(5);


        return view('recruit.elisting',compact('company','departments','jobs','joblistings'));
        }else{
                 
        $company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
        
                $joblistings=EJobListingFilter::apply($request);
                 return view('recruit.elisting',compact('company','departments','jobs','joblistings'));
             
         }
	 }

	 public function empJobListingApplication(Request $request)
	  {
	    $listing=JobListing::find($request->listing_id);
	   $user=Auth::user();
	   if ($user->applications->contains('job_listing_id', $listing->id)) {
	   	$user->applications()->where('job_listing_id', $listing->id)->first()->delete();
	   	return 2;
	   }else{
	   	$user->applications()->create(['job_listing_id'=>$listing->id]);
	   return 1;
	   }
	  
	 }
	 public function empJobListingFavorite(Request $request)
	  {
	   $listing=JobListing::find($request->listing_id);
	   $user=Auth::user();
	   if ($user->favorites->contains('job_listing_id', $listing->id)) {
	   	$user->favorites()->where('job_listing_id', $listing->id)->first()->delete();
	   	return 2;
	   }else{
	   	$user->favorites()->create(['job_listing_id'=>$listing->id]);
	   return 1;
	   }
	   
	 }
}