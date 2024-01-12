<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Address;
use App\Models\Country;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::orderBy('id', 'desc')->with('country')->where('user_id', Auth::user()->id)->get();

        return Inertia::render('Address/AddressList', [
            'addresses' => $addresses
        ]);
    }

    public function create()
    {
        $countries = Country::all(['id', 'nicename as name', 'iso'])->toArray();

        return Inertia::render('Address/CreateAddress', [
            'countries' => $countries,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $user = Auth::user();

        $rules = [
            'fullname' => 'regex:/^[A-Za-z0-9\s]+$/|required',
            'is_residential' => 'required|boolean',
            'country_id' => 'required',
            'city' => 'regex:/^[A-Za-z0-9\s]+$/|required|string',
            'zip_code' => 'regex:/^[A-Za-z0-9\s]+$/|required|string',
            'phone' => 'required|string',
            'email' => 'email|required|string',
            'address' => 'required|string|max:35|regex:/^[A-Za-z0-9\s]+$/',
            'address_2' => 'nullable|string|max:35',
            'address_3' => 'nullable|string|max:35',
            'tax_no' => 'nullable|max:100',
            'state' => ['min:2', 'max:2', Rule::requiredIf($request->state_required == true)],
        ];

        $messages = [
            'regex' => 'The :attribute must only contain letters (english) and numbers.'
        ];

        if ($request->api == true) {
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status' => true,
                    'message' => 'errors',
                    'errors' => $validator->errors(),
                ]);
            }
        } else {
            $request->validate($rules, $messages);
        }


        $country = Country::find($request->country_id);
        $country_code = $country->iso;

        if ($request->state_required == true) {

            try {

                $client = new Client();

                $result = $client->post('https://apis.fedex.com/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => 'l7ef7275cc94544aaabf802ef4308bb66a',
                        'client_secret' => '48b51793-fd0d-426d-8bf0-3ecc62d9c876',
                    ]
                ]);

                $authorization = $result->getBody()->getContents();
                $authorization = json_decode($authorization);

                $headers = [
                    'X-locale' => 'en_US',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authorization->access_token
                ];

                $body = [
                    "inEffectAsOfTimestamp" => "2019-09-06",
                    "validateAddressControlParameters" => [
                        "includeResolutionTokens" => true
                    ],
                    "addressesToValidate" => [
                        [
                            "address" => [
                                "streetLines" => [
                                    $request->address
                                ],
                                "city" =>  $request->city,
                                "stateOrProvinceCode" =>  $request->state,
                                "postalCode" =>  $request->zip_code,
                                "countryCode" => $country_code,
                                "urbanizationCode" => "string",
                                "addressVerificationId" => "string"
                            ]
                        ]
                    ]
                ];

                $api_request = $client->post('https://apis.fedex.com/address/v1/addresses/resolve', [
                    'headers' => $headers,
                    'body' => json_encode($body)
                ]);

                $response = $api_request->getBody()->getContents();
                $response = json_decode($response);

                $address_type = $response->output->resolvedAddresses[0]->classification;

                if ($address_type == 'BUSINESS' && $request->is_residential == 1) {
                    $message = 'The address you have entered is business but you select residential.';
                    if ($request->api == true) {
                        return response()->json([
                            'status' => true,
                            'message' => $message,
                        ]);
                    } else {
                        return redirect()->back()->with('error', $message);
                    }
                }

                if ($address_type == 'RESIDENTIAL' && $request->is_residential == 0) {
                    $message = 'The address you entered is residential but you select business.';
                    if ($request->api == true) {
                        return response()->json([
                            'status' => true,
                            'message' => $message,
                        ]);
                    } else {
                        return redirect()->back()->with('error', $message);
                    }
                }

                // if ($address_type == 'UNKNOWN') {
                //     return redirect()->back()->with('error', 'The address you have entered is not valid.');
                // }
            } catch (\Throwable $th) {
                if ($request->api == true) {
                    return response()->json([
                        'status' => true,
                        'message' => $th->getMessage(),
                    ]);
                } else {
                    return redirect()->back()->with('error', $th->getMessage());
                }
            }
        }

        $address = new Address();
        $address->user_id = $user->id;
        $address->fullname = $request->fullname;
        $address->country_id = $request->country_id;
        $address->country_code = $country_code;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->zip_code = $request->zip_code;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->address = $request->address;
        $address->address_2 = $request->address_2;
        $address->address_3 =  $request->address_3;
        $address->is_residential = $request->is_residential;
        $address->tax_no = $request->tax_no;
        $address->save();

        $success_message = "The address have been created successfully.";
        if ($request->packages_address == true) {
            return redirect()->back()->with('success', $success_message);
        } else {
            if ($request->api == true) {
                return response()->json([
                    'status' => true,
                    'message' => $success_message,
                ]);
            } else {
                return redirect()->route('addresses')->with('success', $success_message);
            }
        }
    }

    public function edit($id)
    {
        $address = Address::find($id);

        $countries = Country::all(['id', 'nicename as name', 'iso'])->toArray();

        return Inertia::render('Address/EditAddress', [
            'address' => $address,
            'countries' => $countries
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $address = Address::find($id);

        $validated = $request->validate([
            'fullname' => 'regex:/^[A-Za-z0-9\s]+$/|required',
            'is_residential' => 'required|boolean',
            'country_id' => 'required',
            'city' => 'regex:/^[A-Za-z0-9\s]+$/|required|string',
            'zip_code' => 'regex:/^[A-Za-z0-9\s]+$/|required|string',
            'phone' => 'required|string',
            'email' => 'email|required|string',
            'address' => 'regex:/^[A-Za-z0-9\s]+$/|required|string|max:35',
            'address_2' => 'nullable|string|max:35',
            'address_3' => 'nullable|string|max:35',
            'tax_no' => 'nullable|max:100',
        ], [
            'regex' => 'The :attribute must only contain letters (english) and numbers.'
        ]);


        $country = Country::find($request->country_id);
        $country_code = $country->iso;

        if ($request->state_required == true) {
            $validated += $request->validate([
                'state' => 'required|min:2|max:2',
            ]);

            try {
                $client = new Client();
                $result = $client->post('https://apis.fedex.com/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                        'client_id' => 'l7ef7275cc94544aaabf802ef4308bb66a',
                        'client_secret' => '48b51793-fd0d-426d-8bf0-3ecc62d9c876',
                    ]
                ]);

                $authorization = $result->getBody()->getContents();
                $authorization = json_decode($authorization);

                $headers = [
                    'X-locale' => 'en_US',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authorization->access_token
                ];

                $body = [
                    "inEffectAsOfTimestamp" => "2019-09-06",
                    "validateAddressControlParameters" => [
                        "includeResolutionTokens" => true
                    ],
                    "addressesToValidate" => [
                        [
                            "address" => [
                                "streetLines" => [
                                    $validated['address']
                                ],
                                "city" =>  $validated['city'],
                                "stateOrProvinceCode" =>  $validated['state'],
                                "postalCode" =>  $validated['zip_code'],
                                "countryCode" => $country_code,
                                "urbanizationCode" => "string",
                                "addressVerificationId" => "string"
                            ]
                        ]
                    ]
                ];

                $api_request = $client->post('https://apis.fedex.com/address/v1/addresses/resolve', [
                    'headers' => $headers,
                    'body' => json_encode($body)
                ]);

                $response = $api_request->getBody()->getContents();
                $response = json_decode($response);

                $address_type = $response->output->resolvedAddresses[0]->classification;

                if ($address_type == 'BUSINESS' && $request->is_residential == 1) {
                    return redirect()->back()->with('error', 'The address you have entered is business but you select residential.');
                }

                if ($address_type == 'RESIDENTIAL' && $request->is_residential == 0) {
                    return redirect()->back()->with('error', 'The address you entered is residential but you select business.');
                }
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'The address you have entered is not valid.');
            }
        }

        $address->user_id = Auth::user()->id;
        $address->fullname = $validated['fullname'];
        $address->country_id = $validated['country_id'];
        $address->country_code = $country_code;
        $address->state = $request->state;
        $address->city = $validated['city'];
        $address->zip_code = $validated['zip_code'];
        $address->phone = $validated['phone'];
        $address->email = $validated['email'];
        $address->address = $validated['address'];
        $address->address_2 = $request->address_2;
        $address->address_3 =  $request->address_3;
        $address->is_residential = $validated['is_residential'];
        $address->tax_no = $request->tax_no;

        $address->update();

        if ($request->packages_address == true) {
            return redirect()->back()->with('success', 'The address have been created successfully.');
        } else {
            return redirect()->route('addresses')->with('success', 'The address have been created successfully.');
        }
    }

    public function destroy($id)
    {
        Address::find($id)->delete();
        return back()->with('success', 'Address deleted!');
    }

    public function suite()
    {


        $suite_no = Auth::user()->suite_no;

        $name = Auth::user()->name;
        $response['warehouses'] = Warehouse::all();

        $addresses = [
            0 => [
                'name' => 'New Jersey Address',
                'address' =>  $name . ' <br> 4 TAFT RD,<br> SUITE #' . $suite_no . '<br> TOTOWA <br> NEW JERSEY 07512 <br> 302 265 0777'
            ],
            1 => [
                'name' => 'Tax Free Delaware Address',
                'address' =>  $name . '<br> 1217 OLD COOCHS BRIDGE RD <br> SUITE #' . $suite_no . '<br> NEWARK <br> DELAWARE 19713 <br>301 265 0777',
            ]
        ];

        $addresses = $response['warehouses'];

        /*
        $addresses = [
            'New Jersey Address' =>  [
                'fullname' => $name,
                'address1' => '4 TAFT RD',
                'address2' => 'SUITE #'.$suite_no,
                'city' => 'TOTOWA',
                'state' => 'NEW JERSEY',
                'zip' => '07512',
                'phone' => '302 265 0777'
            ],
            'Tax Free Delaware Address' =>  [
                'fullname' => $name,
                'address1' => '1217 OLD COOCHS BRIDGE RD',
                'address2' => 'SUITE #'.$suite_no,
                'city' => 'NEWARK',
                'state' => 'DELAWARE',
                'zip' => '19713',
                'phone' => '302 265 0777'
            ]
        ];
        */

        return Inertia::render('Address/SuiteAddress', [
            'addresses' => $addresses
        ]);
    }

    public function getShippingAddress($id)
    {
        $address = Address::find($id);

        if ($address != NULL) {
            return response()->json(['status' => true, 'address' => $address]);
        } else {
            return response()->json(['status' => false, 'address' => NULL]);
        }
    }
}
