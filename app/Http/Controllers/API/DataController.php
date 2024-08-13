<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingService;
use App\Models\SignatureType;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataController extends BaseController
{
    public function index()
    {
        $countries = Country::orderBy('name', 'asc')->get();
        $states = State::where('country_id', 226)->orderBy('name', 'asc')->get();
        $signature_types = SignatureType::orderBy('id', 'asc')->get();

        $data = [
            'countries' => $countries,
            'states' => $states,
            'signature_types' => $signature_types,
        ];

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data,
        ]);
    }

    public function index2()
    {
        $countries = Country::select('id', 'iso', 'name')->orderBy('name', 'asc')->get();
        $states = State::select('id', 'name', 'country_id')->where('country_id', 226)->orderBy('name', 'asc')->get();
        $signature_types = SignatureType::select('id', 'name')->orderBy('id', 'asc')->get();
        $shipping_services = ShippingService::select('id', 'service_name', 'service_code', 'project_id')->where('project_id', 2)->orderBy('id', 'asc')->get();

        $data = [
            'countries' => $countries,
            'states' => $states,
            'signature_types' => $signature_types,
            'shipping_services' => $shipping_services,
        ];

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data,
        ]);
    }

    public function fetchAddressList(Request $request)
    {
        $user = Auth::user();

        $search = NULL;
        $type = NULL;

        if ($request->address_type == "ship_from") {
            $type = $request->address_type;
            $search = $request->search_ship_from;
        }

        if ($request->address_type == "ship_to") {
            $type = $request->address_type;
            $search = $request->search_ship_to;
        }

        $addresses = [];

        $addresses = Address::query()
            ->when($search, function ($qry) use ($search) {
                $qry->where(function ($query) use ($search) {
                    $query->where('address', 'like', '%' . $search . '%')
                        ->orWhere('address_2', 'like', '%' . $search . '%')
                        ->orWhere('address_3', 'like', '%' . $search . '%')
                        ->orWhere('fullname', 'like', '%' . $search . '%')
                        ->orWhere('state', 'like', '%' . $search . '%')
                        ->orWhere('city', 'like', '%' . $search . '%');
                });
            })
            ->where('user_id', $user->id)
            ->when($type, function ($qry) use ($type) {
                $qry->where('type', $type);
            })
            ->orderBy('id', 'desc')
            ->get();

        $data['addresses'] = $addresses;

        return $this->sendResponse($data, 'success');
    }

    public function fetchAddress(Request $request)
    {
        $address = Address::where('id', $request->address_id)->first();

        $data = [
            'address' => $address
        ];

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data,
        ]);
    }

    public function profile()
    {
        $user = User::select('id', 'name', 'email')->where('id', Auth::user()->id)->first();

        $data = [
            'user' => $user
        ];

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data,
        ]);
    }
}
