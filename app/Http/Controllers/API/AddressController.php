<?php

namespace App\Http\Controllers\API;

use App\Models\Address;
use App\Models\Country;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\API\BaseController as BaseController;

class AddressController extends BaseController
{
    public function store(Request $request)
    {
        try {

            $user = Auth::user();

            $address_count = Address::where('user_id', $user->id)
                ->where('type', $request->type)
                ->count();

            if ($address_count > 10) {
                return $this->sendError('The address count is not more than 10.');
            }

            $rules = [
                'company_name' => 'nullable|max:100',
                'fullname' => 'required|regex:/^[A-Za-z0-9\s]+$/',
                'is_residential' => 'required|boolean',
                'country_id' => 'required',
                'city' => 'required|regex:/^[A-Za-z0-9\s]+$/|',
                'zip_code' => 'required|regex:/^[A-Za-z0-9\s]+$/|',
                'phone' => 'required|string|numeric|digits:10',
                'email' => 'required|email|string',
                'address' => 'required|string|max:35|regex:/^[A-Za-z0-9\s]+$/',
                'address_2' => 'nullable|string|max:35',
                'address_3' => 'nullable|string|max:35',
                'tax_no' => 'nullable|max:100',
                'type' => 'required|in:ship_from,ship_to',
            ];

            if (in_array($request->country_id, [226])) { // 226, 138, 38
                $rules += [
                    // 'state' => ['required'],
                    'state' => ['required', 'min:2', 'max:2'],
                ];
            } else {
                $rules += [
                    'state' => ['nullable', 'min:2', 'max:2'],
                ];
            }

            $messages = [
                'regex' => 'The :attribute must only contain letters (english) and numbers.'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return $this->sendError('Validation Failed!', $validator->errors());
            }

            $country = Country::find($request->country_id);
            $country_code = $country->iso;

            if (in_array($request->country_id, [226, 138, 38])) {

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
                        return $this->sendError($message);
                    }

                    if ($address_type == 'RESIDENTIAL' && $request->is_residential == 0) {
                        $message = 'The address you entered is residential but you select business.';
                        return $this->sendError($message);
                    }

                    // if ($address_type == 'UNKNOWN') {
                    //     $message = 'The address you have entered is not valid.';
                    //     return $this->sendError($message);
                    // }
                } catch (\Throwable $th) {
                    return $this->sendError($th->getMessage());
                }
            }

            $data = [
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'fullname' => $request->fullname,
                'country_id' => $request->country_id,
                'country_code' => $country_code,
                'state' => $request->state,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'address_2' => $request->address_2,
                'address_3' =>  $request->address_3,
                'is_residential' => $request->is_residential,
                'tax_no' => $request->tax_no,
                'type' => $request->type,
            ];


            if ($request->update_flag == true) {
                $address = Address::find($request->address_id);
                $address->update($data);
            } else {
                // $address = Address::where('user_id', $user->id)
                //     ->where('address', $request->address)
                //     ->where('type', $request->type)
                //     ->first();

                // if ($address) {
                //     return $this->sendError('This address is already added.');
                // } else {
                    $address = Address::create($data);
                // }
            }

            $message = "The address have been created successfully.";
            $data['address_id'] = $address->id;
            $data['address_type'] = $address->type;

            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return $th;
            return $this->sendError($th->getMessage());
        }
    }
}
