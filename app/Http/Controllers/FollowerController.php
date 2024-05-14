<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $following = Follow::where('following_id', Auth::user()->id)->with('userFollower')->get()->pluck('userFollower');

        if (!$following) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['followers' => $following]);
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
            return response()->json(['message' => 'User not found'], 404);
        }

        $follow = Follow::where([
            ['follower_id', $user->id],
            ['following_id', Auth::user()->id],
        ])->first();

        if (!$follow) {
            return response()->json(['message' => 'The user is not following you']);
        }

        if ($follow->is_accepted == 1) {
            return response()->json(['message' => 'Follow request is already accepted'], 422);
        }

        $follow->update(['is_accepted' => 1]);

        return response()->json(['message' => 'Follow request accepted'], 200);
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
    public function destroy(Follow $follow)
    {
        //
    }
}
