<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomForm;
use App\Models\CustomFormItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomFormController extends Controller
{
    public function index()
    {
        $customs = CustomForm::orderBy('id', 'desc')->paginate(10);
        return Inertia::render('CustomForm/Index', [
            'customs' => $customs,
        ]);
    }

    public function create()
    {
        $countries = Country::orderBy('name', 'asc')->get();

        return Inertia::render('CustomForm/Create', [
            'countries' => $countries
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ship_from_company' => 'nullable',
            'ship_from_person' => 'required',
            'ship_from_contact' => 'required',
            'ship_from_email' => 'required|email',
            'ship_from_address' => 'required',
            'ship_from_country' => 'required',
            'ship_from_incoterms' => 'required',

            'ship_to_company' => 'nullable',
            'ship_to_person' => 'required',
            'ship_to_contact' => 'required',
            'ship_to_email' => 'required|email',
            'ship_to_address1' => 'required',
            'ship_to_address2' => 'nullable',
            'ship_to_address3' => 'nullable',
            'ship_to_tax_id' => 'nullable',
            'ship_to_city' => 'required',
            'ship_to_state' => 'nullable',
            'ship_to_zipcode' => 'nullable',
            'ship_to_country' => 'required',

            'tracking_number' => 'nullable',
            'package_date' => 'required|date',
            'package_no' => 'nullable',
            'package_type' => 'nullable',
            'package_weight' => 'required|numeric',
            'sold_to' => 'required',
            'special_instructions' => 'nullable',
            'shipping_total' => 'required|numeric',

            'package_items.*.desc' => 'required',
            'package_items.*.qty' => 'required',
            'package_items.*.price' => 'required|gt:0|numeric',
            'package_items.*.origin' => 'required',
            'package_items.*.battery' => 'nullable',
            'package_items.*.code' => 'nullable',
        ], [
            'package_items.*.description.required' => 'The package items description field is required.',
            'package_items.*.quantity.required' => 'The package items quantity field is required.',
            'package_items.*.price.required' => 'The package items price field is required.',
            'package_items.*.price.gt' => 'The package items price must be greater than 0.',
            'package_items.*.origin_country.required' => 'The package items origin country field is required.',
        ]);


        $data = [
            'ship_from_company' => $request->ship_from_company,
            'ship_from_person' => $request->ship_from_person,
            'ship_from_contact' => $request->ship_from_contact,
            'ship_from_email' => $request->ship_from_email,
            'ship_from_address' => $request->ship_from_address,
            'ship_from_city' => $request->ship_from_city,
            'ship_from_state' => $request->ship_from_state,
            'ship_from_zipcode' => $request->ship_from_zipcode,
            'ship_from_country' => $request->ship_from_country,
            'ship_from_incoterms' => $request->ship_from_incoterms,

            'ship_to_company' => $request->ship_to_company,
            'ship_to_person' => $request->ship_to_person,
            'ship_to_contact' => $request->ship_to_contact,
            'ship_to_email' => $request->ship_to_email,
            'ship_to_address1' => $request->ship_to_address1,
            'ship_to_address2' => $request->ship_to_address2,
            'ship_to_address3' => $request->ship_to_address3,
            'ship_to_tax_id' => $request->ship_to_tax_id,
            'ship_to_city' => $request->ship_to_city,
            'ship_to_state' => $request->ship_to_state,
            'ship_to_zipcode' => $request->ship_to_zipcode,
            'ship_to_country' => $request->ship_to_country,

            // 'tracking_number' => $request->tracking_number,
            'package_date' => $request->package_date,
            // 'package_no' => $request->package_no,
            'package_type' => $request->package_type,
            'package_weight' => $request->package_weight,
            'sold_to' => $request->sold_to,
            'special_instructions' => $request->special_instructions,
            'shipping_total' => $request->shipping_total,
        ];

        $custom = CustomForm::create($data);

        foreach ($request->package_items as $key => $item) {
            CustomFormItem::create([
                'custom_id' => $custom->id,
                'desc' => $item['desc'],
                'code' => $item['code'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'origin' => $item['origin'],
                'battery' => $item['battery'],
            ]);
        }

        return redirect()->route('custom-form.index')->with('success', 'The customs form create successfully.');
    }

    public function print($id)
    {
        $record = CustomForm::find($id);
        $custom_items = CustomFormItem::where('custom_id', $record->id)->get();

        $html = view('pdfs.custom-form', [
            'record' => $record,
            'custom_items' => $custom_items,
        ])->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
