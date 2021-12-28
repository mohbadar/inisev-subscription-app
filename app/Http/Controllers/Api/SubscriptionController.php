<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Exception;
use Illuminate\Support\Facades\Validator;


class SubscriptionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $subs = Subscription::all();

        return sendResponse(SubscriptionResource::collection($subs), 'Websites retrieved successfully.');
    }


    /**
     * Subscribe to a Website
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
            'website_id' => 'required',
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $website    = Subscription::create([
                'user_id'       => $request->user_id,
                'website_id'       => $request->website_id
            ]);
            $success = new SubscriptionResource($website);
            $message = 'Yay! A website has been successfully created.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops! Unable to create a new website.';
        }

        return sendResponse($success, $message);
    }

}
