<?php

namespace App\Http\Controllers;

use App\Story;

use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function getStories(Request $request)
    {
        $stories = Story::query()
            ->has('items', '>', 0)
            ->with(['items' => function ($query) {
                return $query->orderBy("order", "asc");
            }])
            ->orderBy('order', 'asc')
            ->get();

        return response(['stories' => $stories], 200);
    }
}
