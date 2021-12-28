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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $websites = Website::all();

        return sendResponse(WebsiteResource::collection($websites), 'Websites retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
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
     * Remove the specified resource from storage.
     *
     * @param Website $website
     * @return \Illuminate\Http\JsonResponse
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
