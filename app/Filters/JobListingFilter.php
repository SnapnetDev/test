<?php

namespace App\Filters;

use App\Job;
use App\JobListing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JobListingFilter
{
    public static function apply(Request $filters)
    {
        $listing = (new JobListing)->newQuery();

        // Search for a user based on their email.
        if ($filters->filled('name_contains')) {
          $jt=$filters->input('name_contains');
          $listing->whereHas('job',function($query) use($jt){
              $query->where('jobroles.title','like','%' . $jt . '%');
          });
            // $listing->whereHas('title','like' ,'%' . $filters->input('name_contains') . '%');
        }

          // Search for a user based on their role.
          if ($filters->filled('department')&& $filters->input('deptftype')=='or') {
            $department=$filters->input('department');
            $listing->whereHas('job.department', function ($query) use ($department) {
                $query->where('departments.id', $department[0]);
                if (count($department)>1){
                for ($i=1; $i <count($department) ; $i++) {
                  $query->orWhere('departments.id', $department[$i]);
                }
                }
            });
          }
          if ($filters->filled('department')&& $filters->input('deptftype')=='and') {
            $department=$filters->input('department');
            $listing->whereHas('job.department', function ($query) use ($department) {
                $query->where('departments.id', $department[0]);
                if (count($department)>1) {
                for ($i=1; $i <count($department) ; $i++) {
                  $query->where('departments.id', $department[$i]);
                }
                }
            });
          }
        // Search for a user based on their creation date.
        if ($filters->filled('created_to')) {
          $dt_from=Carbon::parse($filters->input('created_from'));
          $dt_to=Carbon::parse($filters->input('created_to'));
            $listing->whereBetween('joblistings.created_at', [$dt_from,$dt_to]);
        }
        if ($filters->filled('updated_to')) {
          $dt_from=Carbon::parse($filters->input('updated_from'));
          $dt_to=Carbon::parse($filters->input('updated_to'));
            $listing->whereBetween('joblistings.created_at', [$dt_from,$dt_to]);
        }
        // $company_id=companyId();
          // $listing->where('company_id',$company_id);

        // Get the results and return them.
        return $listing->paginate(5);

        }


    }
