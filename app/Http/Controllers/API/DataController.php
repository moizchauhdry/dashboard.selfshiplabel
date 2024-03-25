<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Address;
use App\Models\Country;
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

    public function addresses(Request $request)
    {
        $user = Auth::user();

        if ($request->type == 1) {
            $type = 'ship_from';
            $search = $request->search_ship_from;
        }

        if ($request->type == 2) {
            $type = 'ship_to';
            $search = $request->search_ship_to;
        }

        $addresses = [];

        if ($search) {
            $addresses = Address::query()
                ->where(function ($query) use ($search) {
                    $query->where('address', 'like', '%' . $search . '%')
                        ->orWhere('address_2', 'like', '%' . $search . '%')
                        ->orWhere('address_3', 'like', '%' . $search . '%')
                        ->orWhere('fullname', 'like', '%' . $search . '%')
                        ->orWhere('state', 'like', '%' . $search . '%')
                        ->orWhere('city', 'like', '%' . $search . '%');
                })
                ->where('user_id', $user->id)
                ->where('type', $type)
                ->orderBy('id', 'desc')
                ->get();
        }

        $data['addresses'] = $addresses;

        return $this->sendResponse($data, 'success');
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

    public function getAddress(Request $request)
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
}
