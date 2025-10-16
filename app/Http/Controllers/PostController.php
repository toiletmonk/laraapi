<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\PostIndexRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(PostIndexRequest $request)
    {
        $post = $this->postService->getFilteredPosts($request->validated());

        return PostResource::collection($post)->response()->setStatusCode(200)->getData(true);
    }

    public function store(CreatePostRequest $request)
    {
        $data = $request->validated();
        $post = Post::create($data);

        return response()->json([
            'post' => $post
        ], 201);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return response()->json(['message'=>'Updated succesfully'], 200);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }

    public function show(Post $post)
    {
        return response()->json(['post'=>$post]);
    }
}
