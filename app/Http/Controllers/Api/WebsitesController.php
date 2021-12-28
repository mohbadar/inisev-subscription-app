<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteResource;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Cache;

class WebsitesController extends Controller
{
      /**
         * @OA\Get(
         * path="/api/v1/websites",
         * summary="get-all-websites",
         * operationId="get-all-websites",
         * tags={"get-all-websites"},
         * @OA\RequestBody(
         *    required=true,
         *    description="",
         *    @OA\JsonContent(
         *
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, Please try again")
         *        )
         *     )
         * )
    */
    public function index()
    {
        $websites = Website::all();

        return sendResponse(WebsiteResource::collection($websites), 'Websites retrieved successfully.');
    }

     /**
        * @OA\Post(
        * path="/api/v1/websites",
        * operationId="Register New website",
        * tags={"Register"},
        * summary="Post Register",
        * description=" Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","description", "url"},
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="description", type="text"),
        *               @OA\Property(property="url", type="text")
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|min:10',
            'description' => 'required|min:40',
            "url" => 'required'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $website    = Website::create([
                'name'       => $request->name,
                'url'       => $request->url,
                'description' => $request->description
            ]);

            Cache::store('file')->put($website->name, $website->url, 600); // 10 Minutes Cache

            $success = new WebsiteResource($website);
            $message = 'Yay! A website has been successfully created.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops! Unable to create a new website.';
        }

        return sendResponse($success, $message);
    }

      /**
         * @OA\Get(
         * path="/api/v1/websites/{id}",
         * summary="get-website",
         * operationId="get-website",
         * tags={"get-websites"},
         * @OA\RequestBody(
         *    required=true,
         *    description="",
         *    @OA\JsonContent(
         *
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, Please try again")
         *        )
         *     )
         * )
    */
    public function show($id)
    {
        $website = Website::find($id);

        if (is_null($website)) return sendError('Website not found.');

        return sendResponse(new WebsiteResource($website), 'Website retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Website    $website
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Website $website)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|min:10',
            'description' => 'required|min:40'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $website->title       = $request->title;
            $website->description = $request->description;
            $website->save();

            $success = new WebsiteResource($website);
            $message = 'Yay! Website has been successfully updated.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the website.';
        }

        return sendResponse($success, $message);
    }

     /**
         * @OA\Delete(
         * path="/api/v1/websites/{id)",
         * summary="delete-website",
         * operationId="delete-website",
         * tags={"delete-websites"},
         * @OA\RequestBody(
         *    required=true,
         *    description="",
         *    @OA\JsonContent(
         *
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, Please try again")
         *        )
         *     )
         * )
    */
    public function destroy(Website $website)
    {
        try {
            $website->delete();
            return sendResponse([], 'The website has been successfully deleted.');
        } catch (Exception $e) {
            return sendError('Oops! Unable to delete website.');
        }
    }
}
