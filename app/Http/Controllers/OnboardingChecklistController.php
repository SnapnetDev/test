<?php

namespace App\Http\Controllers;

use App\OnboardingChecklist;
use Illuminate\Http\Request;

class OnboardingChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $parent_id = 0;
//        $parent = null;
        if (request()->filled('parent_id')){
            $parent_id = request('parent_id');
        }

        $list = OnboardingChecklist::fetch()->where('parent_id',$parent_id)->get();

        $personnels = OnboardingChecklist::getPersonnels();

        $sideLabel = OnboardingChecklist::getCheckListTitle($parent_id);

        return  view('onboard.settings.index',compact(['list','personnels','sideLabel','parent_id']));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        return redirect()->back()->with([
//            'message'=>'Test',
//            'error'=>false
//        ]);


        try {
            $obj = new OnboardingChecklist;
            $obj->useParentId(request('parent_id'));
            $obj->getLastStep();
            $obj->validateAssignedPersonnel();
            $obj->uploadTemplate();
            $obj->saveNewCheckList();

            return redirect()->back()->with([
                'message'=>$obj->message,
                'error'=>$obj->error
            ]);


        }catch (\Exception $exception){
            return redirect()->back()->with([
                'message'=>$exception->getMessage(),
                'error'=>true
            ]);

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OnboardingChecklist  $onboardingChecklist
     * @return \Illuminate\Http\Response
     */
    public function show(OnboardingChecklist $onboardingChecklist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OnboardingChecklist  $onboardingChecklist
     * @return \Illuminate\Http\Response
     */
    public function edit(OnboardingChecklist $onboardingChecklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OnboardingChecklist  $onboardingChecklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$checklistSettings)
    {
        $checklistSettings = OnboardingChecklist::fetch()->where('id',$checklistSettings)->first();
        //
//        dd($checklistSettings);
        $response = $checklistSettings->saveCheckList();

        return redirect()->back()->with([
            'message'=>'Check list updated',
            'error'=>false,
            'data'=>$response
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OnboardingChecklist  $onboardingChecklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnboardingChecklist $checklistSetting)
    {
        //
//        dd(OnboardingChecklist::all(),$checklistSetting);
        $checklistSetting->garbage();

        return redirect()->back()->with([
            'message'=>'Checklist removed.',
            'error'=>false
        ]);
    }
}
