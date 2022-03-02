<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 3/29/2020
 * Time: 10:40 PM
 */

namespace App\Traits;


use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

trait RestTrait
{


    function doGet($url)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

//die($url);
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

//dd($response);

        if ($err) {
            return [];
        } else {
//	die($response);
            return json_decode($response, true);
        }

    }

    function doPost($url, callable $callback)
    {
//		$client = new Client(['header' => [
//			'Accept' => 'application/json',
//			'Content-Type' => 'application/x-www-form-urlencoded'
//		],
//    'verify' => false]);
////		dd($callback([]));
//
//		return $client->post($url,[
//			 RequestOptions::JSON => $callback([])
//		]);

        $postData = $callback([]);
        $fields_string = '';
        foreach ($postData as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
//       dd($fields_string);
        $fields_string = rtrim($fields_string, '&');
//open connection
        $ch = curl_init();

//set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//execute post
        $result = curl_exec($ch);

//		echo $result;

//close connection
        curl_close($ch);

        return json_decode($result, true);
    }


}
