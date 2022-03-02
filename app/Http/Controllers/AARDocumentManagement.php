<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AARDocumentManagement extends Controller
{
    //
	public function __construct(){
		$this->middleware(['auth']);
	}

    public function DocumentDirectory (Request $request){
    	$allDocument = \App\AARHMO::get();
    	$allUser = \App\User::get();
    	return view('document_management.document-directory', compact('allDocument', 'allUser'));
    }


}
