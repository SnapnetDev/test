<?php

namespace App\Traits;

use Illuminate\Http\Request;


trait Document {
	public $allowed=['JPG','PNG','jpeg','png','gif','jpg','pdf'];
	public function processGet($route,Request $request){
		switch ($route) {
			case 'mydocument':
				# code...
				return $this->myDocument($request);
				break;
			case 'files':
				return $this->files($request);
				# code...
				break;
			case 'download':
				# code...
				return $this->download($request);
				break;
			case 'movefile':
				# code...
				return $this->moveFile($request);
				break;
			case 'listEmp':
				return $this->listEmp($request);
				# code...
				break;
			case 'deleteFolder':
				# code...
				return $this->manageFolder($request,'delete_folder');
				break;
		
			default:
				# code...
				break;
		}
		 
	}


	public function processPost(Request $request){
		try{
		switch ($request->type) {
			case 'delete':
				# code...
				return $this->delete($request);
				break;
			case 'upload':
				return $this->upload($request);
				# code...
				break;
			case 'renameFolder':
			# code...
				return $this->manageFolder($request,'rename_folder');
				break;
			case 'addFolder':
				# code...
				return $this->manageFolder($request,'add_folder');
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

	private function download(Request $request){

		if($request->user()->role->permissions->contains('constant', 'download_document')){

		$path = \App\Document::where('id',$request->id)->value('document');
		$path2 = \App\Document::where('id',$request->id)->update(['last_mod_id'=>$request->user()->id]);
		//Put Condition for permission here
		return response()->download(storage_path("app/$path"));
		}
		else{
			return 'You do not have permission to perform this action, Press the back butoon on your browser to continue';	 
	
		}

	}

	private function listEmp(Request $request){
		$name=\App\User::where('name','LIKE','%'.$request->q.'%')
						->select('id','name as text')
						->skip(0)->take(30)->get();
		return $name;
	}

	private function upload(Request $request){
		$data=$request->all();
 		 
 		$mime=$request->document->getClientOriginalextension();
 		if(!(in_array($mime, $this->allowed))): throw new \Exception("Invalid File Type"); endif;
 		// app/public
		$image=$request->file('document')->storeAs("public/document",$request->document->getClientOriginalName(),'local');
		$data['document']=$image; 
		$datacompany=['company_id'=>session('company_id')];
		$data=array_merge($data,$datacompany);
		if(!$request->has('user_id')){
			throw new \Exception("You do not have Permission to perform this action");
			
		}
		$saveDocument=\App\Document::create($data);
		return response()->json(['status'=>'success','message'=>'Document Successfully Uploaded']);		 
 	}


	private function delete(Request $request){

		if($request->user()->role->permissions->contains('constant', 'delete_document')){
			$path = \App\Document::where('id',$request->id)->delete();
		}
		else{
			throw new \Exception('You do not have Permission to perform this action');	 
		}

		if($request->ajax()){
			return response()->json(['status'=>'success','message'=>'Operation Successfull']);
		}
		return redirect()->back()->with('message','Operation Successfull');

	}

	private function myDocument(Request $request){

		$pageType='media';
		$folders=\App\DocumentType::get();
		return view('document.document',compact('pageType','folders'));
	}

	private function files(Request $request){
		$documents=\App\Document::where('id','<>',0);
		if($request->has('folder_id')){
			 $documents=$documents->where('type_id',$request->folder_id);
		}
		if($request->has('q')){
		  $documents=$documents->where(function($query) use($request) {
		  					$query->where('document','like',"%$request->q%")
		  					   ->orwhereHas('user',function($query) use ($request){
		  					   		$query->where('name','like',"%$request->q%");
		  					   });
		  				});
		}

		if($request->user()->role->permissions->contains('constant', 'view_all_document')){
			
		}
		else{
			$documents=$documents->where('user_id',$request->user()->id);
		}
		$documents=$documents->paginate(20);
		$folders=\App\DocumentType::get();
		 return view('document.listfile',compact('documents','folders'));
	}

	private function manageFolder(Request $request,$type){
		if($request->user()->role->permissions->contains('constant', $type)){
			$check=\App\DocumentType::where('docname',$request->docname)
									 ->orWhere('id',$request->id);
			if($type=='delete_folder'){
				$check=$check->delete();
				 return response()->json(['status'=>'success','message'=>'Folder Successfully Delete']);
			}

			$check=$check->value('id');


			if(!is_null($check) && $type=='add_folder'){
				throw new \Exception("Folder Name Already Exist");				
			}

			$rename=\App\DocumentType::updateOrCreate(['id'=>$request->id],$request->all());
			$message= $type=='add_folder' ? 'Folder Successfully Added'  : 'Folder Successfully Renamed';
			return response()->json(['status'=>'success','message'=>'Folder Successfully Renamed']);
		}
		else{
			return response()->json(['status'=>'error','message'=>'You do not have permission to perform this action']);	
		}
	}

	private function moveFile(Request $request){
		try{

		if($request->user()->role->permissions->contains('constant', 'delete_document')){
		$movefile=\App\Document::where('id',$request->id)
								->update(['type_id'=>$request->destination]);
		}
		else{

 		return response()->json(['status'=>'error','message'=>'You do not have permission to perform this action']);	
		}
								
 		return response()->json(['status'=>'success','message'=>'Operation Successfull']);
		}
		catch(\Exception $ex){

 		return response()->json(['status'=>'error','message'=>$ex->getMessage()]);
		}
	}
 


}