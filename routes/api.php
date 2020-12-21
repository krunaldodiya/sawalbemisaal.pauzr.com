<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->post('/users/password/change', "UserController@changePassword");
Route::middleware('auth:sanctum')->post('/users/profile/edit', "UserController@editProfile");
Route::middleware('auth:sanctum')->post('/users/token/set', "UserController@setToken");
Route::middleware('auth:sanctum')->post('/users/avatar/upload', "UserController@uploadAvatar");
Route::middleware('auth:sanctum')->get('/users/wallet', "UserController@getWallet");
Route::middleware('auth:sanctum')->get('/users/me', "UserController@me");
Route::middleware('auth:sanctum')->get('/users/info', "UserController@getUserById");
Route::middleware('guest:api')->get('/users/invite/{sender_id}/{mobile}', 'UserController@checkInvitation');

Route::middleware('auth:sanctum')->post('/follows/toggle', "FollowController@toggle");

Route::middleware('auth:sanctum')->get('/rankings', "RankingController@getRankings");
Route::middleware('auth:sanctum')->get('/plans', "PlanController@getPlans");
Route::middleware('auth:sanctum')->post('/plans/purchase', "OrderController@createOrder");

Route::middleware('auth:sanctum')->get('/payments/withdraw/history', "PaymentController@getWithdrawHistory");
Route::middleware('auth:sanctum')->post('/payments/withdraw', "PaymentController@withdrawAmount");

Route::middleware('auth:sanctum')->get('/stories', "StoryController@getStories");

Route::middleware('auth:sanctum')->post('/quiz/host', "QuizController@host");
Route::middleware('auth:sanctum')->post('/quiz/generate', "QuizController@generate");
Route::middleware('auth:sanctum')->post('/quiz/join', "QuizController@join");
Route::middleware('auth:sanctum')->post('/quiz/join/bulk', "QuizController@joinBulk");
Route::middleware('auth:sanctum')->post('/quiz/submit', "QuizController@submitQuiz");
Route::middleware('auth:sanctum')->post('/quiz/start', "QuizController@startQuiz");
Route::middleware('auth:sanctum')->get('/quiz/active', "QuizController@getActiveQuizzes");
Route::middleware('auth:sanctum')->get('/quiz/user', "QuizController@getUserQuizzes");
Route::middleware('auth:sanctum')->get('/quiz/questions', "QuizController@getQuizQuestions");
Route::middleware('auth:sanctum')->get('/quiz/info', "QuizController@getQuizById");
Route::middleware('auth:sanctum')->get('/quiz/winners', "QuizController@getQuizWinners");
Route::middleware('auth:sanctum')->get('/quiz/answers', "QuizController@getQuizAnswers");
Route::middleware('auth:sanctum')->get('/quiz/search', "QuizController@searchQuizByTitle");

Route::middleware('guest:api')->post('/auth/login', "AuthController@login");
Route::middleware('guest:api')->post('/auth/register', "AuthController@register");

Route::middleware('guest:api')->post('/otp/request', "OtpController@requestOtp");
Route::middleware('guest:api')->post('/otp/verify', "OtpController@verifyOtp");

Route::middleware('auth:sanctum')->post('/faqs', "HomeController@getFaqs");
Route::middleware('guest:api')->post('/upload/excel', "HomeController@excel");

Route::get('/payments/proofs', "PaymentController@proofs");
Route::post('/influencer/join', "InfluencerController@join");
Route::post('/app/send-link', "HomeController@sendAppLink");

Route::get('/countries', "HomeController@getCountries");
Route::get('/languages', "HomeController@getLanguages");
Route::get('/categories', "HomeController@getCategories");
Route::get('/questions', "HomeController@getQuestions");
Route::get('/locales', "HomeController@getLocales");
