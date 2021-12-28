<?php

namespace App\Http\Controllers\Api;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Website;
use Exception;
use Illuminate\Support\Facades\Event;
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
        * @OA\Post(
        * path="/api/v1/subscribtions",
        * operationId="Subscribe to a Website",
        * tags={"Register"},
        * summary="Post Register",
        * description=" Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"user_id", "website_id"},
        *               @OA\Property(property="user_id", type="integer"),
        *               @OA\Property(property="website_id", type="integer")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Register Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
    */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required|integer',
            'website_id' => 'required|integer',
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



    public function sendMails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website_id' => 'required|integer'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);


        $website = Website::find($request->website_id);


        // $posts = $website->posts();

        $subscriptions = $website->subscriptions();
        foreach($subscriptions as $subscription){
            Event::fire(new SendMail($subscription->id));
        }

        return response()->json(['message', 'Mails have been sent']);

    }

}
