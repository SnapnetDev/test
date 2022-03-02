<?php

namespace App\Filters;

use App\Job;
use App\JobListing;
use App\JobListingExternal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EJobListingFilter
{
    public static function apply(Request $filters)
    {
        $listing = (new JobListingExternal)->newQuery();

        // Search for a user based on their email.
        if ($filters->filled('name_contains')) {
          $jt=$filters->input('name_contains');
          $listing->where('title','like','%' . $jt . '%');

            // $listing->whereHas('title','like' ,'%' . $filters->input('name_contains') . '%');
        }

          // Search for a user based on their role.

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
