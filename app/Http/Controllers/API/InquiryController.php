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
            $data['inquiries'] = Inquiry::where('user_id', $user->id)->orderBy('id', 'desc')->get();

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
                'title' => 'required|min:10|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendError('validation failed', $validator->errors());
            }

            $data = [
                'title' => $request->title,
                'user_id' => $user->id
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
            $response['inquiry_messages'] = InquiryMessage::where('user_id', $user->id)->orderBy('id', 'desc')->get();

            return $this->sendResponse($response, 'success');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
