<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\PostAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'caption' => ['required'],
            'attachments' => ['required', 'array'],
            'attachments.*' => ['mimes:jpg,jpeg,webp,png,gif'],
        ]);

        if ($validation->fails()) {
            return Response::json([
                'message' => 'Invalid field',
                'errors' => $validation->errors()
            ], 401);
        }

        if (Auth::check()) {
            $post = Post::create([
                'caption' => $request->caption,
                'user_id' => Auth::user()->id
            ]);

            foreach ($request->attachments as $key => $attachment) {
                $attachment->move(storage_path('app/public/storage/imgs'), 'img-' . Carbon::now()->format('Y-m-d-His-') . $key . '.' . $attachment->getClientOriginalExtension());
                PostAttachment::create([
                    'storage_path' => 'img-' . Carbon::now()->format('Y-m-d-His-') . $key . '.' . $attachment->getClientOriginalExtension(),
                    'post_id' => $post->id
                ]);
            }

            return Response::json(['message' => 'Create post success'], 201);
        }

        return Response::json(['message' => 'Unauthenticated'], 401);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'page' => ['min:0'],
            'size' => ['min:1'],
        ]);

        $page = $request->page ?? 0;
        $size = $request->size ?? 0;

        if ($validation->fails()) {
            return Response::json([
                'message' => 'Invalid field',
                'errors' => $validation->errors()
            ], 422);
        }

        $post = Post::with(['user', 'attachment'])->where('user_id', Auth::user()->id)->get();

        if (Auth::check()) {
            return Response::json([
                'page' => $page,
                'size' => $size,
                'posts' => $post,
            ], 200);
        }

        return Response::json(['message' => 'Unaunthenticated']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::check()) {
            if ($post->user->id == Auth()->user()->id) {
                $post->delete();

                return Response::json([], 204);
            } else if (!$post) {
                return Response::json(['message' => 'Post not found'], 404);
            } else if($post->user_id != Auth::user()->id) {
                return Response::json(['message' => 'Forbidden access'], 403);
            }
        }

        return Response::json(['message' => 'Unauthenticated'], 401);
    }
}
