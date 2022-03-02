<?php


namespace App\Traits;



use App\User;
use Illuminate\Http\Request;
use App\CompanyDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait CompanyDocumentTrait

{
    public function processGet($route,Request $request)
    {
        switch ($route) {
            case 'download':
                # code...
                return $this->download($request);
                break;
            case 'index':
                # code...
                return $this->company_documents($request);
                break;
            case 'my_company_documents':
                # code...
                return $this->my_company_documents($request);
                break;
            case 'document':
                # code...
                return $this->document($request);
                break;
            case 'delete_document':
                # code...
                return $this->delete_document($request);
                break;
            default:
                # code...
                break;

        }
    }

    public function processPost(Request $request){
        // try{
        switch ($request->type) {
            case 'save_company_document':
                # code...
                return $this->save_document($request);
                break;
            case 'update_company_document':
                # code...
                return $this->update_document($request);
                break;
            default:
                # code...
                break;
        }

    }

    public function company_documents(Request $request){
        $documents=CompanyDocument::all();

        return view('company_documents.admin',compact('documents'));

    }
    public function my_company_documents(Request $request){
        $documents=CompanyDocument::all();

        return view('company_documents.user',compact('documents'));

    }
    public function document(Request $request){
        return $document=CompanyDocument::find($request->document_id);

    }
    public function save_document(Request $request){
        if ($request->file('document')) {
        $document=CompanyDocument::Create(['title'=>$request->title,'created_by'=>Auth::user()->id,'company_id'=>companyId(),'description'=>$request->description]);



            $path = $request->file('document')->store('company_documents');
            if (Str::contains($path, 'company_documents')) {
                $filepath = Str::replaceFirst('company_documents', '', $path);
            } else {
                $filepath = $path;
            }

            $document->file = $filepath;
            $document->save();


        }
        return 'success';
    }
    public function update_document(Request $request){
        $document=CompanyDocument::find($request->document_id);
            $document->update(['title'=>$request->title,'user_id'=>Auth::user()->id,'company_id'=>companyId(),'description'=>$request->description]);

        if ($request->file('document')) {

            $path = $request->file('document')->store('company_documents');
            if (Str::contains($path, 'company_documents')) {
                $filepath = Str::replaceFirst('company_documents', '', $path);
            } else {
                $filepath = $path;
            }

            $document->file = $filepath;
            $document->save();


        }
        return 'success';
    }






public function delete_document(Request $request){
    $document=CompanyDocument::find($request->document_id);
    if ($document){
        $document->delete();
        return 'success';
    }
}

    private function download(Request $request)
    {
        $document = CompanyDocument::find($request->document_id);
        if ($document->file != '') {
            $path = $document->file;
//            response()->header('Content-Type', $document->mime);
            return response()->download(public_path('uploads/company_documents' . $path));
        } else {
            redirect()->back();
        }
    }




}
