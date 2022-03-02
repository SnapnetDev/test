<?php

namespace App\Traits;

use App\User;
use App\Qualification;
use App\EducationHistory;
use App\EmploymentHistory;
use App\Skill;
use App\Dependant;
use App\Department;
use App\Company;
use App\Job;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

trait Section
{
    public function processGet($route, Request $request)
    {
        switch ($route) {

            case 'sections':
                return $this->sections($request);
                break;
            case 'section':
                return $this->getSection($request);
                break;
            case 'delete_section':
                return $this->deleteSection($request);
                break;
            case 'remove_section_member':
                return $this->remove_section_member($request);
                break;
            case 'search':
                return $this->search($request);
                break;
            case 'download_section_members_template':
                return $this->downloadSectionMembersUploadTemplate($request);
                break;
            case 'download_section_non_members_template':
                return $this->downloadSectionNonMembersUploadTemplate($request);
                break;

            default:
                return $this->index($request);
                break;
        }
    }
    public function processPost(Request $request)
    {
        switch ($request->type) {

            case 'save_section':
                return $this->save_section($request);
                break;
            case 'save_section_member':
                return $this->save_section_member($request);
                break;
            case 'import_section_users':
                return $this->importSectionUsers($request);
                break;



            default:
                # code...
                break;
        }
    }



    public function sections(Request $request)
    {
        
        $sections = \App\UserSection::where(['company_id' => companyId()])->get();
        $non_section_users=\App\User::doesntHave('section')->where('status','!=',2)->get();
        return view('sections.index', compact('sections','non_section_users'));
    }

    public function getSection(Request $request)
    {
        return $section = \App\UserSection::where('id', $request->section_id)->with('users')->first();
    }
    public function deleteSection(Request $request)
    {
        $section = \App\UserSection::find($request->section_id);
        if ($section) {
            if (count($section->users) < 1) {
                $section->delete();
            }
        }


        return 'success';
    }
    public function remove_section_member(Request $request)
    {
        $user = \App\User::find($request->user_id);
        if ($user) {
            $user->section()->dissociate();
            $user->save();
            return 'success';
        }
    }

    public function save_section(Request $request)
    {
        try {


            $section = \App\UserSection::updateOrCreate(['id' => $request->section_id], ['name' => $request->name, 'company_id' => companyId(), 'other_name' => $request->other_name, 'salary_project_code' => $request->salary_project_code, 'charge_project_code' => $request->charge_project_code]);
            if ($request->filled('user_id')) {
                $users_count = count($request->user_id);
                for ($i = 0; $i < $users_count; $i++) {
                    $user = \App\User::find($request->user_id[$i]);

                    if ($user) {

                        $section->users()->save($user);
                    }
                }
            }


            return 'success';
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function save_section_member(Request $request)
    {
        try {
            $users_count = count($request->user_id);
            for ($i = 0; $i < $users_count; $i++) {
                $user = \App\User::find($request->user_id[$i]);

                if ($user) {
                    $section = UserSection::find($request->section_id);
                    if ($section) {
                        $section->users()->save($user);
                    }
                }
            }

            return 'success';
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function downloadSectionMembersUploadTemplate(Request $request)
    {
        $template = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where(['company_id' => companyId()])->where('status','!=',2)->get()->toArray();

        $sections = \App\UserSection::select('name as Section Name', 'other_name as Section Other Name')->where(['company_id' => companyId()])->get()->toArray();

        return $this->exportsectionexcel("Section Members Upload Template", ['template' => $template, 'sections' => $sections]);
    }
    public function downloadSectionNonMembersUploadTemplate(Request $request)
    {
        $template = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where(['company_id' => companyId(),'section_id'=>0])->where('status','!=',2)->get()->toArray();

        $sections = \App\UserSection::select('name as Section Name', 'other_name as Section Other Name')->where(['company_id' => companyId()])->get()->toArray();

        return $this->exportsectionexcel("Section Members Upload Template", ['template' => $template, 'sections' => $sections]);
    }
    private function exportsectionexcel($worksheetname, $data)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname) {
                    $sheet->fromArray($realdata);
                    if ($sheetname == 'template') {
                        $sheet->cell('c1', function ($cell) {
                            $cell->setValue('Section Name');
                        });
                    }
                    if ($sheetname == 'sections') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdx',
                                $sheet->_parent->getSheet(1),
                                "A2:A" . $sheet->_parent->getSheet(1)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("C$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdx');
                        }
                    }
                });
            }
        })->download('xlsx');
    }

    public function importSectionUsers(Request $request)
    {
        if ($request->hasFile('section_template')) {


            $datas = \Excel::load($request->file('section_template')->getrealPath(), function ($reader) {
                $reader->noHeading();
                // $reader->formatDates(true, 'Y-m-d');
            })->get();


            $user = new \App\User;
            foreach ($datas as $data) {

                if ($data) {
                    foreach ($data as  $dt) {

                        $user = \App\User::where(['emp_num' => $dt[1]])->first();

                        if ($user) {
                            $section = \App\UserSection::where(['name' => $dt[2]])->first();
                            if ($section) {
                                $section->users()->save($user);
                            }
                        }
                    }
                }
            }
        }
        return 'success';
    }

    public function search(Request $request)
    {

        if ($request->q == "") {
            return "";
        } else {
            $name = \App\User::where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->q . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->q . '%')
                    ->orWhere('last_name', 'like', '%' . $request->q . '%');
            })
                // ->select('id as id', 'first_name as text', 'company_id')
                ->select(\DB::raw("CONCAT(first_name, ' ',middle_name,' ', last_name) AS text"),'id' )
                ->get();
        }


        return $name;
    }
}
