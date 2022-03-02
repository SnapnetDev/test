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

trait Union{
public function processGet($route,Request $request)
{
	switch ($route) {
			
			case 'unions':
			return $this->unions($request);
			break;
			case 'union':
			return $this->getUnion($request);
			break;
			case 'delete_union':
			return $this->deleteUnion($request);
			break;
			case 'remove_union_member':
			return $this->remove_union_member($request);
			break;
			case 'search':
			return $this->search($request);
			break;
			case 'download_union_members_template':
			return $this->downloadUnionMembersUploadTemplate($request);
			break;
		
		default:
			return $this->index($request);
			break;
	}
}
public function processPost(Request $request)
{
	switch ($request->type) {
		
			case 'save_union':
			return $this->save_union($request);
			break;
			case 'save_union_member':
			return $this->save_union_member($request);
			break;
			case 'import_union_users':
			return $this->importUnionUsers($request);
			break;

			
		
		default:
			# code...
			break;
	}
}



public function unions(Request $request)
{
	 $unions=\App\UserUnion::where(['company_id'=>companyId()])->get();
	 return view('unions.index',compact('unions'));
	
	
}

public function getUnion(Request $request)
{
	return $union=\App\UserUnion::where('id',$request->union_id)->with('users')->first();
	
}
public function deleteUnion(Request $request)
{
	$union=\App\UserUnion::find($request->union_id);
	if ($union) {
		if (count($union->users)<1) {
			$union->delete();
		}
		
	}
	
	
	return 'success';
}
public function remove_union_member(Request $request)
{
	$user=\App\User::find($request->user_id);
	if ($user) {
		$user->union()->dissociate();
	$user->save();
	return 'success';
	}
	
}

public function save_union(Request $request)
{
	try{
		
		 
		$union=\App\UserUnion::updateOrCreate(['id'=>$request->union_id],['name'=>$request->name,'company_id'=>companyId(),'dues_formula'=>$request->formula]);
		if ($request->filled('user_id')) {
			$users_count=count($request->user_id);
			for ($i=0; $i <$users_count ; $i++) { 
			$user=\App\User::find($request->user_id[$i]);
               
               if($user){
                 
                        $union->users()->save($user);
                 
                 }
		}
		}
		
		
		return 'success';

	}catch(\Exception $ex){
		return $ex->getMessage();
	}
}
public function save_union_member(Request $request)
{
	try{
		$users_count=count($request->$user_id);
		for ($i=0; $i <$userscount ; $i++) { 
			$user=\App\User::find($request->user_id[$i]);
               
               if($user){
                 $union=UserUnion::find($request->union_id);
                   if ($union) {
                        $union->users()->save($user);
                   }
                 }
		}
		
		return 'success';

	}catch(\Exception $ex){
		return $ex->getMessage();
	}
}
public function downloadUnionMembersUploadTemplate(Request $request)
    {
       $template=\App\User::select('name as Employee Name','emp_num as Staff Id')->where(['company_id'=>companyId()])->get()->toArray();
                           
       $unions=\App\UserUnion::select('name as Union Name', 'dues_formula as Union Dues Formula')->where(['company_id'=>companyId()])->get()->toArray();

                           return $this->exportunionexcel("Union Members Upload Template",['template'=>$template,'unions'=>$unions]);
    }
  private function exportunionexcel($worksheetname,$data)
  {
    return \Excel::create($worksheetname, function($excel) use ($data)
    {
      foreach($data as $sheetname=>$realdata)
      {
        $excel->sheet($sheetname, function($sheet) use ($realdata,$sheetname)
        {
                  $sheet->fromArray($realdata);
                  if ($sheetname=='template') {
                  	$sheet->cell('c1', function($cell) {
                                  $cell->setValue('Union Name');
            
                              });
                  }
                  if($sheetname=='unions'){
            
                    $sheet->_parent->addNamedRange(
                      new \PHPExcel_NamedRange(
                        'sdx', $sheet->_parent->getSheet( 1 ), "A2:A" . $sheet->_parent->getSheet( 1 )->getHighestRow()
                      )
                    );
                
                    for($j=2; $j<=100; $j++){ 
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

public function importUnionUsers(Request $request)
    {
       if($request->hasFile('union_template')){
       

      $datas=\Excel::load($request->file('union_template')->getrealPath(), function($reader) { 
                                         $reader->noHeading();
        // $reader->formatDates(true, 'Y-m-d');
                           })->get();

          
          $user;
           foreach ($datas as $data) {
            
            if ($data) {
              foreach ($data as  $dt) {
              	
              	$user=\App\User::where(['emp_num'=>$dt[1]])->first();
               
               if($user){
                  $union=\App\UserUnion::where(['name'=>$dt[2]])->first();
                   if ($union) {
                        $union->users()->save($user);
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
     
                
        if($request->q==""){
            return "";
        }
       else{
        $name=\App\User::doesntHave('union')
        ->where('name','LIKE','%'.$request->q.'%')
        ->where(['company_id'=>companyId()])

                        ->select('id as id','name as text')
                        ->get();
            }
        
        
        return $name;
    
     
    }

}