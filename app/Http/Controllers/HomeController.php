<?php

namespace App\Http\Controllers;

use App\Category;
use App\Country;
use App\Faq;
use App\Language;
use App\QuestionTranslation;

use App\Http\Requests\GetQuestions;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionTranslationModel;
use App\Locale;
use Illuminate\Support\Facades\Storage;

use Error;

class HomeController extends Controller
{
    public function downloadApp(Request $request)
    {
        $file_name = "sawal-bemisaal.apk";

        $path = storage_path("app/public/apps/$file_name");

        $headers = [
            "Content-Type" => "application/vnd.android.package-archive",
            "Content-Disposition" => "attachment; filename='$file_name'",
        ];

        return response()->download($path, "$file_name", $headers);
    }

    public function sendAppLink(Request $request)
    {
        $mobile = $request->mobile;

        if (!$mobile) {
            throw new Error("No mobile provided");
        }

        $url = "https://api.msg91.com/api/v2/sendsms";

        $data = [
            'sender' => "SOCIAL",
            'route' => "4",
            'country' => "91",
            "sms" => [
                [
                    "message" => "Get the Sawal Bemisaal app and enjoy Quizzing on the go! \n Learn & Earn ! Click https://bit.ly/SawalBemisaalApp to download now!",
                    "to" => [$mobile]
                ]
            ]
        ];

        $client = new \GuzzleHttp\Client();

        $request = $client->post($url, [
            'json' => $data,
            'headers' => [
                "authkey" => env("MSG91_KEY"),
                'content-type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        return $request->getBody();
    }

    public function getFaqs(Request $request)
    {
        $faqs = Faq::orderBy('order', 'asc')->get();

        return response(['faqs' => $faqs], 200);
    }

    public function getCountries(Request $request)
    {
        $countries = Country::get();

        return response(['countries' => $countries], 200);
    }

    public function getLocales(Request $request)
    {
        $locales = Locale::where('language_id', $request->language_id)->get();

        return response(['locales' => $locales], 200);
    }

    public function getLanguages(Request $request)
    {
        $languages = Language::with('locales')->get();

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

    public function getMediaFile(Request $request)
    {
        $media = $request->media;

        $file = $request->file;

        if (Storage::disk('public')->exists($file)) {
            return response()->file("storage/{$file}");
        }

        throw new \Error("File Does not exists");
    }
}
