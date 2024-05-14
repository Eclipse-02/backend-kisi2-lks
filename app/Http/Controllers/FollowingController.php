<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use \Response;
use Illuminate\Support\Facades\Auth;

class FollowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $following = Follow::where('follower_id', Auth::user()->id)->with('userFollowing')->get()->pluck('userFollowing');

        if (!$following) {
            return Response::json(['message' => 'User not found'], 404);
        }
        return Response::json(['following' => $following], 200);
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
    public function store($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return Response::json(['message' => 'User not found'], 404);
        }

        if (Auth::user()->username == $username) {
            return Response::json(['message' => 'You are not allowed to follow yourself'], 422);
        }

        $follow = Follow::where([
            ['follower_id', '=', Auth::user()->id],
            ['following_id', '=', $user->id],
        ])->first();

        if ($follow) {
            return Response::json([
                'message' => "You are already followed",
                'status' => $follow->is_accepted == 1 ? "following" : "requested"
            ], 422);
        }

        if (!$follow) {
            Follow::create([
                'follower_id' => Auth::user()->id,
                'following_id' => $user->id,
                'is_accepted' => $user->is_private == 1 ? 0 : 1
            ]);

            return Response::json([
                'message' => 'Follow success',
                'status' => $user->is_private == 1 ? 'requested' : 'following'
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Follow $follow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Follow $follow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return Response::json(['message' => 'User not found'], 404);
        }

        $follow = Follow::where([
            ['follower_id', '=', Auth::user()->id],
            ['following_id', '=', $user->id],
        ])->first();

        if (!$follow) {
            return Response::json(['message' => "You are not following the user"], 422);
        }

        $follow->delete();

        return Response::json([], 204);
    }
}
