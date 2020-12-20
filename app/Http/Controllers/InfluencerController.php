<?php

namespace App\Http\Controllers;

use App\Http\Requests\Influencer as InfluencerRequest;

use App\Influencer;

use Throwable;

class InfluencerController extends Controller
{
    public function join(InfluencerRequest $request)
    {
        try {
            Influencer::create($request->all());
            return response(['success' => true], 200);
        } catch (Throwable $error) {
            return response(['success' => false], 500);
        }
    }
}
