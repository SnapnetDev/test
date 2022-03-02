<?php

namespace App\Traits;

use Illuminate\Support\Facades\Request;
use Ixudra\Curl\Facades\Curl;


trait FaceMatchTrait
{
    private $Ocp_Apim_Subscription_Key, $names, $endpoint, $faceId, $urll, $endpointUrl,$facelistid;

    public function __construct(){
        $this->setDefault();
    }

    public function setDefault()
    {

        $this->Ocp_Apim_Subscription_Key = env('AZURE_CONITIVE_KEY', '0c5ddc7201a74406b258034e02f52dc9');
        $this->endpoint = env('AZURE_CONITIVE_ENDPOINT', 'https://westeurope.api.cognitive.microsoft.com');
        $this->names = env('AZURE_TEST_NAME', 'HCMatrixStaff');
        $this->facelistid = env('AZURE_FACE_LIST_ID', '3');
    }

    private function baseUrl()
    {
        $this->setDefault();
        return Curl::to($this->endpointUrl)
            ->withHeaders(["Ocp-Apim-Subscription-Key:{$this->Ocp_Apim_Subscription_Key}"])
            ->withData($this->urll)
            ->asJson();
    }

    //    Call this method first
    public function createFaceList($id)
    {
        $this->setDefault();
        $this->endpointUrl = "{$this->endpoint}/face/v1.0/facelists/{$id}";
        $this->urll = ['name' => $this->names];
        $response = $this->baseUrl()->put();
        return is_null($response) ? 'Face ID Created Successfully' : response()->json($this->decodeCrazyJson($response->error));
    }


    // Call this method second and get the perssistedFaceId , pass an array of images
    public function addFacetoList(array $urls = ['https://i.ibb.co/jRc1w7y/harishan-kobalasingam-Uflm-CP-Nk-Ig-unsplash.jpg'])
    {
        $this->setDefault();
        $persistentFaceId = [];

        foreach ($urls as $url) {
            $this->endpointUrl = "{$this->endpoint}/face/v1.0/facelists/{$this->facelistid}/persistedFaces";
            $this->urll = ['url' => $url];
            $response = $this->baseUrl()->post();
            $persistentFaceId[$url] = $response->persistedFaceId;
        }
        //return response()->json(['status' => 'success', 'data' => $persistentFaceId]);
        return $persistentFaceId;
    }


    private function faceSimilar()
    {

        $this->endpointUrl = "{$this->endpoint}/face/v1.0/findsimilars";
        $this->urll = [

            "faceId" => $this->faceId,
            "faceListId" =>$this->facelistid,
            "maxNumOfCandidatesReturned" => 10,
            "mode" => "matchPerson"
        ];

        $response = $this->baseUrl()->post();

        return $response;
    }

    // call this function last
    public function faceDetectandMatch($imageUrl)
    {
        $this->setDefault();
        $this->endpointUrl = "{$this->endpoint}/face/v1.0/detect?returnFaceId=true&returnFaceLandmarks=false&recognitionModel=recognition_01&returnRecognitionModel=false";
        $this->urll = ['url' => $imageUrl];
        $response = $this->baseUrl()->post();
        if(isset($response->error->message)) { throw new \Exception($response->error->message); }
        $this->faceId = isset($response[0]->faceId) ? $response[0]->faceId : '';
        return $this->faceSimilar();

    }

    private function decodeCrazyJson($crazyJson)
    {
        try {
            return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $crazyJson), true);
        } catch (\Exception $ex) {
            return $crazyJson;
        }
    }

}