<?php

namespace App\Http\Controllers;

use App\Story;

use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function getStories(Request $request)
    {
        $stories = Story::with('items')
            ->orderBy('order', 'asc')
            ->get();

        return response(['stories' => $stories], 200);
    }
}
