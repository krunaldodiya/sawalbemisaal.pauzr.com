<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToggleFollow;
use App\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(ToggleFollow $request)
    {
        $user = User::find(auth()->id());

        try {
            $user->followings()->toggle($request->following_id);
            return response(['success' => true], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
