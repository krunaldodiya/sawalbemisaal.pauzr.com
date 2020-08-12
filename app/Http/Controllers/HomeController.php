<?php

namespace App\Http\Controllers;

use App\Category;
use App\Country;
use App\Language;
use App\QuestionTranslation;

use App\Http\Requests\GetQuestions;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionTranslationModel;

class HomeController extends Controller
{
    public function getCountries(Request $request)
    {
        $countries = Country::get();

        return response(['countries' => $countries], 200);
    }

    public function getLanguages(Request $request)
    {
        $languages = Language::get();

        return response(['languages' => $languages], 200);
    }

    public function getCategories(Request $request)
    {
        $categories = Category::get();

        return response(['categories' => $categories], 200);
    }

    public function getQuestions(GetQuestions $request)
    {
        $language = Language::where('shortname', "en")->first();
        $language_id = $request->language_id ? $request->language_id : $language->id;

        $questions = QuestionTranslation::where('language_id', $language_id)
            ->whereHas('question', function ($query) use ($request) {
                if ($request->category_id) {
                    return $query->where('category_id', $request->category_id);
                }
            })
            ->paginate(20);

        return response(['questions' => $questions], 200);
    }

    public function excel(Request $request)
    {
        $data = Excel::import(new QuestionTranslationModel, $request->file);

        dd($data);
    }
}
