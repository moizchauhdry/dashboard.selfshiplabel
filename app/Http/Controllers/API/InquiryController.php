<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Inquiry;
use App\Models\InquiryMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InquiryController extends BaseController
{
    public function list()
    {
        try {
            $user = Auth::user();
            $data['inquiries'] = Inquiry::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);

            return $this->sendResponse($data, 'success');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function fetch(Request $request)
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

    public function create(Request $request)
    {
        try {
            $user = Auth::user();

            $rules = [
                'subject' => 'required|min:3|max:100',
                'name' => 'required|min:3|max:50',
                'email' => 'required|max:50|email',
                'department' => 'required|max:50',
                'message' => 'required|min:3|max:500',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendError('validation failed', $validator->errors());
            }

            $data = [
                'user_id' => $user->id,
                'subject' => $request->subject,
                'name' => $request->name,
                'email' => $request->email,
                'department' => $request->department,
            ];

            $inquiry = Inquiry::create($data);

            return $this->sendResponse($inquiry, 'success');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function messageSend(Request $request)
    {
        try {

            $response = [];

            $user = Auth::user();

            $rules = [
                'inquiry_id' => 'required',
                'message' => 'required|min:3',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendError('validation failed', $validator->errors());
            }

            $inquiry = Inquiry::where('id', $request->inquiry_id)->where('user_id', $user->id)->first();

            if ($inquiry) {
                $data = [
                    'user_id' => $user->id,
                    'user_type' => $user->type,
                    'inquiry_id' => $request->inquiry_id,
                    'message' => $request->message,
                ];

                $response = InquiryMessage::create($data);
            } else {
                return $this->error('Invalid Inquiry');
            }

            return $this->sendResponse($response, 'success');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function messageList(Request $request)
    {
        try {

            $user = Auth::user();
            $response['inquiry_messages'] = InquiryMessage::where('user_id', $user->id)->where('inquiry_id', $request->inquiry_id)->orderBy('id', 'asc')->paginate(1000);

            return $this->sendResponse($response, 'success');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
