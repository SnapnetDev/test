<?php

namespace App\Filters;

use App\Stage;
use App\Workflow;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkflowFilter
{
    public static function apply(Request $filters)
    {
        $wf = (new WorkFlow)->newQuery();

        // Search for a user based on their email.
        if ($filters->filled('name_contains')) {
            $wf->where('name','like' ,'%' . $filters->input('name_contains') . '%');
        }

          // Search for a user based on their role.
          if ($filters->filled('stage_id')) {
            $stage_id=$filters->input('stage_id');
            $wf->whereHas('stages', function ($query) use ($stage_id) {
                $query->where('stages.id', $stage_id);

            });
          }


        // Get the results and return them.
        return $wf->paginate(5);

        }


    }
