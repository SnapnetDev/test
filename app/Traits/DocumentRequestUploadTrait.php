<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait DocumentRequestUploadTrait
{
    /*
     * File upload trait used in DocumentRequestTrait
     *
     * @param $file
    */
    public function uploadFile($file): string
    {
        $destinationPath = '/uploads/requisition';
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($_SERVER['DOCUMENT_ROOT'] . $destinationPath, $fileName);

        return $fileName;

    }
}