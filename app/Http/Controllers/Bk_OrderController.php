<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Inertia\Inertia;
use GuzzleHttp\Client;
use App\Models\Order;
use App\Models\City;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;


class Bk_OrderController extends Controller
{

    public $token = 'm99PyQIqoq5GmAKjs1wOTAbhQ0ozkc0s';   
    public $cookie = 'PHPSESSID=3rf796ooctiic30gq0e54bpg45';   
    public $customer_id = '12339140';
    public $integration_id = '59690';
    
    
    public function index(){

        $orders = Order::all()->toArray();

        return Inertia::render('Orders/OrdersList',[
          'orders' => $orders
        ]);        
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $order = Order::findOrFail($id);

        //$this->searchOrder($order);
        
        $headers = [
          'cache-control' => 'no-cache',
          'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];

        $client = new Client();

        $book_no = '42113406';
        
        $request = $client->get('https://xpsshipper.com/restapi/v1/customers/'.$this->customer_id.'/shipments/'.$book_no, [
            'headers' => $headers,
            'http_errors' => true,
        ]);

        $response = $request ? $request->getBody()->getContents() : null;


        // echo '<pre>';
        // print_r($response);
        // exit; 

        $order_details = json_decode($response);

        return Inertia::render('Orders/OrderDetail',[
          'order' => $order,
          'order_details' => $order_details
        ]);    
    }

    private function searchOrder($order){

            /*
      POST /restapi/v1/customers/TEST00002/searchShipments
      {
        "keyword": "2317948"
      }
      */

      $paylod = [
        //'keyword' => $order->order_id,
        'keyword' => '5',
      ];

      $headers = [
        'cache-control' => 'no-cache',
        'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $this->token
      ];

      $client = new Client();

      $book_no = '42113406';
      
      $request = $client->post('https://xpsshipper.com/restapi/v1/customers/'.$this->customer_id.'/searchShipments', [
          'headers' => $headers,
          'body' => json_encode($paylod),
          'http_errors' => true,
      ]);

      $response = $request ? $request->getBody()->getContents() : null;

      $order_details = json_decode($response);

      $shipments = $order_details->shipments;

      // echo '<pre>';
      // print_r($shipments);
      // exit; 

      echo "Herere"; exit;


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $order_id = (string) time();
      $user = Auth::user();
    
      $shipping_total = '20';
    
      $ship_from_zip = $request->input('ship_from');
      $ship_to_zip = $request->input('ship_to');
      $weight = $request->input('weight');
    
      $units = $request->input('weight_unit','lb_in');
      $length = $request->input('length');
      $width = $request->input('width');
      $height = $request->input('height');
    
      $declared_value = $request->input('declared_value');
      
      $ship_from_city = City::where('zip',$ship_from_zip)->first();
      $ship_to_city = City::where('zip',$ship_to_zip)->first();
    
      $units = explode('_',$units);
    
      $weight_unit = isset($units[0]) ? $units[0] : 'lb';
      $dimention_unit = isset($units[1]) ? $units[1] : 'in';
    
    
      //local order info
      $order = new Order();
      $order->user_id = $user->id;
      $order->order_id = $order_id;  
      $order->sender = $user->first_name." ".$user->last_name;
      $order->receiver = $user->last_name." ".$user->first_name;
      $order->from_address = $ship_from_city->country_code_2;
      $order->to_address = $ship_to_city->country_code_2;
      $order->save();
    
      $order_data = [
            'orderId' => $order_id,
            'orderDate' => date('Y-m-d',time()),
            'orderNumber' => (string) $order->id,
            'fulfillmentStatus' => 'pending',
            'shippingService' => 'Standard',
            'shippingTotal' => $shipping_total,
            'weightUnit' => $weight_unit,
            'dimUnit' => $dimention_unit,
            'dueByDate' => date('Y-m-d',time()+(86400*5)),
            'orderGroup' => 'Workstation 1',
            'contentDescription' => 'Testing orders',
            'sender' => [
              'name' => $user->name,
              'company' => 'Jones Co.',
              'address1' => $user->address1,
              'address2' => '#54',
              'city' => $user->city,
              'state' => $user->state,
              'zip' => $user->postal_code,
              'country' => $user->country,
              'phone' => $user->phone,
              'email' => $user->email,
            ],
            'returnTo' => [
              'name' => $user->name,
              'company' => 'Jones Co.',
              'address1' => $user->address1,
              'address2' => '#54',
              'city' => $user->city,
              'state' => $user->state,
              'zip' => $user->postal_code,
              'country' => $user->country,
              'phone' => $user->phone,
              'email' => $user->email,
            ],
            'receiver' => [
              'name' => $user->first_name.' '.$user->last_name,
              'company' => '',
              'address1' => '54 Green St.',
              'address2' => '',
              'city' => 'Salt Lake City',
              'state' => 'UT',
              'zip' => '84106',
              'country' => 'US',
              'phone' => '8013920046',
              'email' => 'alice@jensen.egg',
            ],
            'items' => [
              0 => [
                'productId' => '856673',
                'sku' => 'ade3-fe21-bb9a',
                'title' => 'Socks',
                'price' => '3.99',
                'quantity' => 2,
                'weight' => '0.5',
                'imgUrl' => 'http://sockstore.egg/img/856673',
                'htsNumber' => '555555',
                'countryOfOrigin' => 'US',
                'lineId' => '1',
              ],
            ],
            'packages' => [
              0 => [
                'weight' => $weight,
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'insuranceAmount' => NULL,
                'declaredValue' => $declared_value,
              ],
            ],
          ];
    
          $headers = [
            'cache-control' => 'no-cache',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
          ];
    
          $client = new Client();
    
          //  /restapi/v1/customers/TEST00002/integrations/3214/orders/123456
          $request = $client->put('https://xpsshipper.com/restapi/v1/customers/'.$this->customer_id.'/integrations/'.$this->integration_id.'/orders/'.$order_id, [
              'headers' => $headers,
              'body' => json_encode($order_data),
              'http_errors' => true,
          ]);
    
          $response = $request ? $request->getBody()->getContents() : null;
    
          $response = json_decode($response);
    
          if(isset($response->ok)){
              return response()->json(['status' => TRUE]); 
          }else{
              return response()->json(['status' => FALSE]); 
          }    
    }

    public function quote(Request $request){
      
      $markup = SiteSetting::getByName('markup');

      $ship_from_zip = $request->input('ship_from');
      $ship_to_zip = $request->input('ship_to');
      $weight = $request->input('weight');
      $units = $request->input('weight_unit','lb_in');
      $length = $request->input('length');
      $width = $request->input('width');
      $height = $request->input('height');
      $declared_value = $request->input('declared_value');
      $ship_from_city = City::where('zip',$ship_from_zip)->first();
      $ship_to_city = City::where('zip',$ship_to_zip)->first();

      $sender = [
        "country" =>  $ship_from_city->country_code_2,
        "zip" =>  $ship_from_zip
      ];

      $receiver = [
          "country" => $ship_to_city->country_code_2,
          "zip" => $ship_to_zip
      ];

      //$declared_value = "10";

      $pieces = [];

      $packages = $request->input('packages');

      foreach($packages as $package_data){

        $package = json_decode($package_data);

        $units = explode('_',$package->unit);

        $weight_unit = isset($units[0]) ? $units[0] : 'lb';
        $dimention_unit = isset($units[1]) ? $units[1] : 'in';

        $pieces[] = [
          "weight"=> $package->weight,
          "length"=> $package->length,
          "width"=> $package->width,
          "height"=> $package->height,
          "insuranceAmount"=> null,
          "declaredValue"=> $package->declared_value
        ];
      }

      $headers = [
          'cache-control' => 'no-cache',
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $this->token
      ];

      $client = new Client();
      
      $ghuzzle_request = $client->get('https://xpsshipper.com/restapi/v1/customers/12339140/services', [
          'headers' => $headers,
          'http_errors' => true,
      ]);

      $response = $ghuzzle_request ? $ghuzzle_request->getBody()->getContents() : null;

      $response = json_decode($response);

      $service_rates = [];

      try {

        foreach($response->services as $service){

          if (!empty($service->carrierCode) && strpos($service->serviceCode, 'international') !== false) {
                      

            $post_params = [          
                "carrierCode" => $service->carrierCode,
                "serviceCode" =>  $service->serviceCode,
                "packageTypeCode" => $this->getPackageTypeCode($service),
                "sender" => $sender,
                "receiver" => $receiver,
                "residential" => true,
                "signatureOptionCode" => "DIRECT",
                "contentDescription" => "stuff and things",
                "weightUnit" => $weight_unit,
                "dimUnit" => $dimention_unit,
                "currency"=> "USD",
                "customsCurrency"=> "USD",
                "pieces"  => $pieces,
                "billing" => [
                  "party" => "receiver"
                ]
            ];

            $ghuzzle_request = $client->post('https://xpsshipper.com/restapi/v1/customers/'.$this->customer_id.'/quote', [
                'headers' => $headers,
                'body' => json_encode($post_params),
                'http_errors' => true,
            ]);
      
            $response = $ghuzzle_request ? $ghuzzle_request->getBody()->getContents() : null;

            $response = json_decode($response);

            $markup_amount = $response->totalAmount*((int)$markup/100); 

            $total = $response->totalAmount+$markup_amount;

            $service_rates[] = [
              'label' => $service->serviceLabel,
              'serviceCode' => $service->serviceCode,
              'currency' => $response->currency,
              //'totalAmount' => $response->totalAmount,
              'totalAmount' => $total,
              'baseAmount' => $response->baseAmount,
            ];

          }

        }

        return response()->json(['status' => TRUE,'services' => $service_rates]);

      }catch(\Exception $ex){
        

        
        $ex_message = $ex->getMessage();

        $pos = strpos($ex_message,'{"error":"');
                
        $pos1 = strpos($ex_message,'"errorCategory"');
        $length = $pos1-($pos+12);

        $message = substr($ex_message,$pos+10,$length);

        return response()->json([
          'status' => FALSE,
          'message' => $message,
        ]);

      }                                                                                                                                                                                                                                                                                                                                                              

    }


    private function getPackageTypeCode($service){
      
      $type = "";

      switch($service->carrierCode){
        case "dhl" : 
          $type =  "dhl_custom_package";
          break;
        case "fedex":
          $type = "fedex_custom_package";
          break;
        case "usps":
          $type = "usps_custom_package";
          break;
      }

      return $type;
    }

}