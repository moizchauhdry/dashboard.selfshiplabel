<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'desc')->get();
        return Inertia::render('Coupons/Index', [
            'coupons' => $coupons,
        ]);
    }

    public function create()
    {
        return Inertia::render('Coupons/Create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|min:3',
            'code' => 'required|unique:coupons|min:3',
            'discount' => 'required|numeric',
            // 'expiry_date' => 'required|array',
        ]);

        $coupon = new Coupon();
        $coupon->name = $request->input('name');
        $coupon->discount = $request->input('discount');
        $coupon->code = $request->input('code');
        // $coupon->starts_at = Carbon::parse($request->expiry_date[0])->format('Y-m-d');
        // $coupon->expires_at = Carbon::parse($request->expiry_date[1])->format('Y-m-d');
        $coupon->save();

        return redirect()->route('coupon.index')->with('success', 'Coupon Added!');
    }

    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $status = $request->status;

        $coupon = Coupon::find($id);
        $coupon->status = $status;
        $coupon->save();

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $coupon = Coupon::find($id);
        $coupon->delete();
        return response()->json(['status' => '1']);
    }
}
