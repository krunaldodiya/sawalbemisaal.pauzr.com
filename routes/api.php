<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/users/password/change', "UserController@changePassword");
Route::middleware('auth:sanctum')->get('/users/profile/edit', "UserController@editProfile");
Route::middleware('auth:sanctum')->get('/users/wallet', "UserController@getWallet");
Route::middleware('auth:sanctum')->get('/users/me', "UserController@me");
Route::middleware('auth:sanctum')->get('/users/info', "UserController@getUserById");
Route::middleware('auth:sanctum')->post('/users/token/set', "UserController@setToken");

Route::middleware('auth:sanctum')->get('/rankings', "RankingController@getRankings");

Route::middleware('auth:sanctum')->post('/orders/create', "OrderController@createOrder");

Route::middleware('auth:sanctum')->post('/plans', "PlanController@getPlans");

Route::middleware('auth:sanctum')->get('/payments/withdraw/history', "PaymentController@getWithdrawHistory");
Route::middleware('auth:sanctum')->post('/payments/withdraw', "PaymentController@withdrawAmount");

Route::middleware('auth:sanctum')->post('/quiz/host', "QuizController@host");
Route::middleware('auth:sanctum')->post('/quiz/generate', "QuizController@generate");
Route::middleware('auth:sanctum')->post('/quiz/join', "QuizController@join");
Route::middleware('auth:sanctum')->post('/quiz/submit', "QuizController@submit");
Route::middleware('auth:sanctum')->get('/quiz/active', "QuizController@getActiveQuizzes");
Route::middleware('auth:sanctum')->get('/quiz/user', "QuizController@getUserQuizzes");
Route::middleware('auth:sanctum')->get('/quiz/questions/all', "QuizController@getAllQuestions");
Route::middleware('auth:sanctum')->get('/quiz/questions/answerable', "QuizController@getAnswerableQuestions");
Route::middleware('auth:sanctum')->get('/quiz/info', "QuizController@getQuizById");
Route::middleware('auth:sanctum')->get('/quiz/winners', "QuizController@getQuizWinners");
Route::middleware('auth:sanctum')->get('/quiz/answers', "QuizController@getQuizAnswers");

Route::middleware('guest:api')->post('/auth/login', "AuthController@login");
Route::middleware('guest:api')->post('/auth/register', "AuthController@register");

Route::middleware('guest:api')->post('/otp/request', "OtpController@requestOtp");
Route::middleware('guest:api')->post('/otp/verify', "OtpController@verifyOtp");

Route::get('/countries', "HomeController@getCountries");
Route::get('/languages', "HomeController@getLanguages");
Route::get('/categories', "HomeController@getCategories");
Route::get('/questions', "HomeController@getQuestions");
