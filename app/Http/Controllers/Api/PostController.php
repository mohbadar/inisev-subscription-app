<?php

namespace App\Http\Controllers\Api;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Website;
use Illuminate\Support\Facades\Event;


class PostController extends Controller
{
        /**
         * @OA\Get(
         * path="/api/v1/posts",
         * summary="get-all-posts",
         * operationId="get-all-posts",
         * tags={"get-all-posts"},
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
        $posts = Post::all();

        return sendResponse(PostResource::collection($posts), 'Posts retrieved successfully.');
    }

     /**
        * @OA\Post(
        * path="/api/v1/posts",
        * operationId="Register New Post",
        * tags={"Register"},
        * summary="Post Register",
        * description=" Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"title","description", "website_id"},
        *               @OA\Property(property="title", type="text"),
        *               @OA\Property(property="description", type="text"),
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
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title'       => 'required',
            'description' => 'required',
            'website_id' => 'required'
        ]);


        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        // try {

            $post    = Post::create([
                'title'       => $request->title,
                'description' => $request->description,
                'website_id'  => $request->website_id
            ]);

            $website = Website::find($request->website_id);


            // $posts = $website->posts();

            $subscriptions = $website->subscriptions();
            foreach($subscriptions as $subscription){
                Event::fire(new SendMail($subscription->id));
            }



            $success = new PostResource($post);
            $message = 'Yay! A post has been successfully created.';
        // } catch (Exception $e) {
        //     $success = [];
        //     $message = 'Oops! Unable to create a new post.';
        // }

        return sendResponse($success, $message);
    }
     /**
         * @OA\Get(
         * path="/api/v1/websites/{id)",
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
        $post = Post::find($id);

        if (is_null($post)) return sendError('Post not found.');

        return sendResponse(new PostResource($post), 'Post retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post    $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|min:10',
            'description' => 'required|min:40'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $post->title       = $request->title;
            $post->description = $request->description;
            $post->save();

            $success = new PostResource($post);
            $message = 'Yay! Post has been successfully updated.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the post.';
        }

        return sendResponse($success, $message);
    }

     /**
         * @OA\Delete(
         * path="/api/v1/posts/{id)",
         * summary="delete-post",
         * operationId="delete-post",
         * tags={"delete-post"},
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
    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return sendResponse([], 'The post has been successfully deleted.');
        } catch (Exception $e) {
            return sendError('Oops! Unable to delete post.');
        }
    }
}
