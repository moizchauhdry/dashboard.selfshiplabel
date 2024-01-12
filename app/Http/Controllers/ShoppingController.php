<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Inertia\Inertia;
use GuzzleHttp\Client;
use App\Models\City;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

  public function pricing()
  {
    $cities_from = City::where('country_code_2', 'US')->get()->toArray();
    $cities_to = City::where('country_code_2', '!=', 'US')->get()->toArray();

    return Inertia::render('Pricing', [
      'cities_from' => $cities_from,
      'cities_to' => $cities_to
    ]);
  }


  public function shopping()
  {
    $user = Auth::user();

    $profile_complete = TRUE;
    //check if profile is complete      
    if (
      empty($user->first_name) ||
      empty($user->last_name) ||
      empty($user->address1) ||
      empty($user->country) ||
      empty($user->state) ||
      empty($user->city) ||
      empty($user->postal_code) ||
      empty($user->phone_no) ||
      empty($user->email)
    ) {
      $profile_complete = FALSE;
    }

    $cities_from = City::where('country_code_2', 'US')->get()->toArray();
    $cities_to = City::where('country_code_2', '!=', 'US')->get()->toArray();


    return Inertia::render('Shopping', [
      'cities_from' => $cities_from,
      'cities_to' => $cities_to,
      'profile_complete' => $profile_complete
    ]);
  }


  // public function getListings()
  // {

  //   $curl = curl_init();
  //   curl_setopt_array($curl, array(
  //     CURLOPT_URL => 'https://xpsshipper.com/restapi/v1/customers/12339140/services',
  //     CURLOPT_RETURNTRANSFER => true,
  //     CURLOPT_ENCODING => '',
  //     CURLOPT_MAXREDIRS => 10,
  //     CURLOPT_TIMEOUT => 0,
  //     CURLOPT_FOLLOWLOCATION => true,
  //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //     CURLOPT_CUSTOMREQUEST => 'GET',
  //     CURLOPT_HTTPHEADER => array(
  //       'Authorization: Bearer m99PyQIqoq5GmAKjs1wOTAbhQ0ozkc0s',
  //       'Cookie: PHPSESSID=3rf796ooctiic30gq0e54bpg45'
  //     ),
  //   ));

  //   $response = curl_exec($curl);

  //   curl_close($curl);
  //   $response = json_decode($response);
  // }

  /*
    This will get quote for all selected packages and items.

    */
  // public function getQuotes(Request $request)
  // {

  //   $markup = SiteSetting::getByName('markup');

  //   $ship_from_zip = $request->input('ship_from');
  //   $ship_to_zip = $request->input('ship_to');
  //   $weight = $request->input('weight');
  //   $units = $request->input('weight_unit', 'lb_in');
  //   $length = $request->input('length');
  //   $width = $request->input('width');
  //   $height = $request->input('height');
  //   $declared_value = $request->input('declared_value');
  //   $ship_from_city = City::where('zip', $ship_from_zip)->first();
  //   $ship_to_city = City::where('zip', $ship_to_zip)->first();

  //   $units = explode('_', $units);

  //   $weight_unit = isset($units[0]) ? $units[0] : 'lb';
  //   $dimention_unit = isset($units[1]) ? $units[1] : 'in';

  //   $headers = [
  //     'cache-control' => 'no-cache',
  //     'Content-Type' => 'application/json',
  //     'Authorization' => 'Bearer ' . $this->token
  //   ];

  //   $client = new Client();

  //   $request = $client->get('https://xpsshipper.com/restapi/v1/customers/12339140/services', [
  //     'headers' => $headers,
  //     'http_errors' => true,
  //   ]);

  //   $response = $request ? $request->getBody()->getContents() : null;

  //   $response = json_decode($response);

  //   $service_rates = [];

  //   try {

  //     foreach ($response->services as $service) {

  //       if (!empty($service->carrierCode) && strpos($service->serviceCode, 'international') !== false) {

  //         //echo "<br>Getting Rates for ".$service->serviceCode."<br>";

  //         $sender = [
  //           "country" =>  $ship_from_city->country_code_2,
  //           "zip" =>  $ship_from_zip
  //         ];

  //         $receiver = [
  //           "country" => $ship_to_city->country_code_2,
  //           "zip" => $ship_to_zip
  //         ];

  //         //$declared_value = "10";

  //         $pieces = [
  //           0 => [
  //             "weight" => $weight,
  //             "length" => $length,
  //             "width" => $width,
  //             "height" => $height,
  //             "insuranceAmount" => null,
  //             "declaredValue" => $declared_value
  //           ]
  //         ];

  //         $post_params = [
  //           "carrierCode" => $service->carrierCode,
  //           "serviceCode" =>  $service->serviceCode,
  //           "packageTypeCode" => $this->getPackageTypeCode($service),
  //           "sender" => $sender,
  //           "receiver" => $receiver,
  //           "residential" => true,
  //           "signatureOptionCode" => "DIRECT",
  //           "contentDescription" => "stuff and things",
  //           "weightUnit" => $weight_unit,
  //           "dimUnit" => $dimention_unit,
  //           "currency" => "USD",
  //           "customsCurrency" => "USD",
  //           "pieces"  => $pieces,
  //           "billing" => [
  //             "party" => "receiver"
  //           ]
  //         ];

  //         $request = $client->post('https://xpsshipper.com/restapi/v1/customers/' . $this->customer_id . '/quote', [
  //           'headers' => $headers,
  //           'body' => json_encode($post_params),
  //           'http_errors' => true,
  //         ]);

  //         $response = $request ? $request->getBody()->getContents() : null;

  //         $response = json_decode($response);

  //         $markup_amount = $response->totalAmount * ((int)$markup / 100);

  //         $total = $response->totalAmount + $markup_amount;

  //         $service_rates[] = [
  //           'label' => $service->serviceLabel,
  //           'serviceCode' => $service->serviceCode,
  //           'currency' => $response->currency,
  //           //'totalAmount' => $response->totalAmount,
  //           'totalAmount' => $total,
  //           'baseAmount' => $response->baseAmount,
  //         ];
  //       }
  //     }

  //     return response()->json(['status' => TRUE, 'services' => $service_rates]);
  //   } catch (\Exception $ex) {

  //     $ex_message = $ex->getMessage();

  //     $pos = strpos($ex_message, '{"error":"');

  //     $pos1 = strpos($ex_message, '"errorCategory"');
  //     $length = $pos1 - ($pos + 12);

  //     $message = substr($ex_message, $pos + 10, $length);

  //     return response()->json([
  //       'status' => FALSE,
  //       'message' => $message,
  //     ]);
  //   }
  // }

  // private function getPackageTypeCode($service)
  // {

  //   $type = "";

  //   switch ($service->carrierCode) {
  //     case "dhl":
  //       $type =  "dhl_custom_package";
  //       break;
  //     case "fedex":
  //       $type = "fedex_custom_package";
  //       break;
  //     case "usps":
  //       $type = "usps_custom_package";
  //       break;
  //   }

  //   return $type;
  // }
}
