<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\InquiryMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::orderBy('id','desc')->paginate(10);

        return Inertia::render('Inquiry/Index', [
            'inquiries' => $inquiries,
        ]);
    }


    public function fetch(Request $request,$user_id,$track_id)
    {

        $user = Auth::user();
        $inquiry = Inquiry::where('user_id', $user_id)->where('id', $track_id)->orderBy('id', 'desc')->first();
        $inquiry_messages = InquiryMessage::where('inquiry_id', $track_id)->orderBy('id', 'asc')->get();

        return Inertia::render('Inquiry/InquryChat', [
             'inquiry' => $inquiry,
            // 'inquiry_messages' => $inquiry_messages,
            // 'inquiry_id' => $inquiry->id,
            // 'user_id' => $inquiry->user_id,

        ]);
    }

    public function messageSend(Request $request)
    {
         try {
            
            $response = [];
                        
            $rules = [
                'inquiry_id' => 'required',
                'message' => 'required|min:3',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            // if ($validator->fails()) {
            //     return $this->sendError('validation failed', $validator->errors());
            // }

            $inquiry = Inquiry::where('id', $request->inquiry_id)->where('user_id', $request->user_id)->first();
            if ($inquiry) {
                $data = [
                    'user_id' => $request->user_id,
                    'user_type' => 'admin',
                    'inquiry_id' => $request->inquiry_id,
                    'message' => $request->message,
                ];

                $response = InquiryMessage::create($data);
            } else {
                return $this->error('Invalid Inquiry');
                
            }
            //    event(new SendMessage($inquiry,$response));
            //  broadcast(new SendMessage($inquiry,$response))->toOthers();
            return $this->sendResponse($response, 'success');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

    }

    public function messageList(Request $request)
    {
        try {

            $response['inquiry_messages'] = InquiryMessage::where('user_id', $request->user_id)->where('inquiry_id', $request->inquiry_id)->orderBy('id', 'asc')->paginate(1000);
            return $this->sendResponse($response, 'success');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }


    public function listfetch(Request $request)
    {
        try {
            $user = Auth::user();
            $data['inquiry'] = Inquiry::where('user_id', $user->id)->where('id', $request->inquiry_id)->orderBy('id', 'desc')->first();
            $data['inquiry_messages'] = InquiryMessage::where('inquiry_id', $request->inquiry_id)->orderBy('id', 'asc')->paginate(500);

            return $this->sendResponse($data, 'success');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
