<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\GiftCard;
use App\Models\GiftCardComment;
use App\Models\GiftCardFile;
use App\Notifications\GiftCardApprovalNotification;
use App\Notifications\GiftCardNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class GiftCardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = GiftCard::with('user')->orderBy('id', 'desc');

        if ($user->type == 'customer') {
            $query->where('user_id', $user->id);
        }

        $gift_cards = $query->get();

        return Inertia::render('GiftCard/Index', [
            'gift_cards' => $gift_cards
        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if ($request->isMethod('POST')) {

            $validated_data = $request->validate([
                'title' => 'required|string|max:150',
                'type' => 'required|string|in:PHYSICAL,ELECTRONIC',
                'amount' => 'required|numeric',
                'qty' => 'required|integer|between:1,10',
                'notes' => 'required|max:1500',
            ]);

            $gift_card = GiftCard::create([
                'user_id' => $user->id,
                'title' => $validated_data['title'],
                'type' => $validated_data['type'],
                'amount' => $validated_data['amount'],
                'qty' => $validated_data['qty'],
                'notes' => $validated_data['notes'],
            ]);

            $this->gift_card_final_amount($gift_card->id);


            $url = URL::route('gift-card.edit', $gift_card->id);
            $customer = $gift_card->user;
            $customerDetailURL = '<a href="' . route('customers.show', $customer->id) . '">' . $customer->name_with_suite_no . '</a>';

            $data = [
                'url' => URL::route('gift-card.edit', $gift_card->id),
                'message' => $customerDetailURL . ' has created an gift card request. <a href="' . $url . '">Click Here</a>',
            ];

            $admins = User::where('type', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new GiftCardNotification($data));
            }

            return redirect()->route('gift-card.index')->with('success', 'Successfully Requested');
        }

        return Inertia::render('GiftCard/Create');
    }

    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $gift_card = GiftCard::with('files', 'user')->findOrFail($id);

        if ($request->isMethod('POST')) {
            $request->validate([
                'title' => 'required|string|max:150',
                'type' => 'required|string|in:PHYSICAL,ELECTRONIC',
                'amount' => 'required|numeric',
                'qty' => 'required|integer|between:1,10',
                'notes' => 'required|max:1500',
                'status' => [Rule::requiredIf($user->type == 'admin')],
            ]);

            $gift_card->update([
                'title' => $request->title,
                'type' => $request->type,
                'amount' => $request->amount,
                'qty' => $request->qty,
                'notes' => $request->notes,
                'status' => $request->status,
            ]);

            $this->gift_card_final_amount($gift_card->id);

            $files = $request->file();
            if (isset($files['images'])) {
                foreach ($files['images'] as $key => $file) {
                    $image_object = $file['image'];
                    $file_name = time() . '_' . $image_object->getClientOriginalName();
                    $image_object->storeAs('uploads', $file_name);
                    if ($_SERVER['HTTP_HOST'] == 'localhost:8000') {
                        File::move(storage_path('app/uploads/' . $file_name), public_path('/public/uploads/' . $file_name));
                    } else {
                        File::move(storage_path('app/uploads/' . $file_name), public_path('../public/uploads/' . $file_name));
                    }

                    $gift_card_file = new GiftCardFile();
                    $gift_card_file->file_name = $file_name;
                    $gift_card_file->gift_card_id = $gift_card->id;

                    if ($key == 0) {
                        $gift_card_file->display = 1;
                    } else {
                        $gift_card_file->display = 0;
                    }

                    $gift_card_file->save();
                }
            }

            $url = URL::route('gift-card.edit', $gift_card->id);
            $customer = $gift_card->user;
            $customerDetailURL = 'Customer <strong><a href="' . route('customers.show', $customer->id) . '">' . $customer->name_with_suite_no . '</strong></a>';

            $auser = $user->type == 'admin' ? 'Admin' : $customerDetailURL;
            $data = [
                'url' => URL::route('gift-card.edit', $gift_card->id),
                'message' => $auser . ' has modified an gift card request. <a href="' . $url . '">Click Here</a>',
            ];

            if ($user->type == 'admin') {
                $customer = User::find($gift_card->user_id);
                $customer->notify(new GiftCardNotification($data));
            } else {
                $admins = User::where('type', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new GiftCardNotification($data));
                }
            }


            if (isset($gift_card->getChanges()['status'])) {
                $gift_card->update(['admin_updated_at' => Carbon::now()]);
                $gift_card->status == 'Accepted' ? $gift_card->update(['admin_approved_at' => Carbon::now()]) : $gift_card->update(['admin_approved_at' => NULL]);

                $customer = User::find($gift_card->user_id);
                $customer->notify(new GiftCardApprovalNotification($gift_card));
            }


            if ($request->has('approve') && $request->approve == 1) {
                $amount = $gift_card->amount * $gift_card->qty;
                if ($amount <= 0) {
                    return redirect()->back()->with('error', 'OPERRATION FAILED TO PERFORM, AMOUNT MUST BE GREATER THAN 0');
                }

                if ($amount > 5) {
                    $percentage_amount =  $amount * 5 / 100;
                    $final_amount = $amount + $percentage_amount;
                } else {
                    $final_amount =  $amount + 5;
                }

                if ($gift_card->type == 'PHYSICAL') {
                    $final_amount = $final_amount + 25;
                }

                return redirect()->route('payment.index', [
                    'payment_module' => 'gift_card',
                    'payment_module_id' => $gift_card->id,
                ]);
            }

            return redirect()->route('gift-card.index')->with('success', 'Successfully Modified');
        }

        $comments = GiftCardComment::where('gift_card_id', $gift_card->id)->with('user')->orderBy('id', 'desc')->get();

        return Inertia::render('GiftCard/Edit', [
            'gift_card' => $gift_card,
            'comments' => $comments,
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $user = Auth::user();
        $gift_card = GiftCard::find($id);
        $validated_data = $request->validate([
            'message' => 'required',
        ]);

        GiftCardComment::create([
            'gift_card_id' => $id,
            'user_id' => $user->id,
            'message' => $validated_data['message'],
        ]);

        $url = URL::route('gift-card.edit', $gift_card->id);
        $customer = $gift_card->user;
        $customerDetailURL = 'Customer <strong><a href="' . route('customers.show', $customer->id) . '">' . $customer->name_with_suite_no . '</strong></a>';

        $auser = $user->type == 'admin' ? 'Admin' : $customerDetailURL;
        $data = [
            'url' => URL::route('gift-card.edit', $gift_card->id),
            'message' => $auser . ' has commented on an gift card request. <a href="' . $url . '">Click Here</a>',
        ];
        if ($user->type == 'admin') {
            $customer = User::find($gift_card->user_id);
            $customer->notify(new GiftCardNotification($data));
        } else {
            $admins = User::where('type', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new GiftCardNotification($data));
            }
        }

        return redirect()->back();
    }

    public function deleteImage(Request $request)
    {
        GiftCardFile::find($request->id)->delete();
        return redirect()->back();
    }

    private function gift_card_final_amount($id)
    {
        $gift_card = GiftCard::find($id);
        $sub_total = $gift_card->amount * $gift_card->qty;

        if ($sub_total > 5) {
            $percentage_amount =  $sub_total * 5 / 100;
            $final_amount = $sub_total + $percentage_amount;
        } else {
            $final_amount =  $sub_total + 5;
        }

        if ($gift_card->type == 'PHYSICAL') {
            $final_amount = $final_amount + 25;
        }

        $gift_card->update(['final_amount' => $final_amount]);
    }
}
