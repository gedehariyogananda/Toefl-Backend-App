<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\PacketController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\ForYouController;
use App\Http\Controllers\Api\GameAnswerController;
use App\Http\Controllers\Api\GameClaimController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\PairingClaimController;
use App\Http\Controllers\Api\QuizAnswerController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\QuizEnrollController;
use App\Http\Controllers\Api\QuizGameScoreController;
use App\Http\Controllers\Api\QuizResultController;
use App\Http\Controllers\Api\QuizTypeController;
use App\Http\Controllers\Api\RandomWordController;
use App\Http\Controllers\Api\ScrambledWordController;
use App\Http\Controllers\Api\ValueHomeController;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/forgot', 'forgot');
    Route::post('/users/verify-otp-forgot',  'verifyOtpForgot');
    Route::post('/reset', 'reset');
    Route::post('/users/verify-otp',  'verifyOtpRegister');
    Route::post('/users/new-otp',  'newOtp');
    Route::get('/users/profile',  'profile');
    Route::post('/logout',  'logout');
    Route::post('check/password', 'checkPassword');
    Route::post('change/password', 'changePassword');
});
Route::get('/get-onboarding-target',[ValueHomeController::class, 'getTargetOnBoarding']);


Route::middleware('auth:api')->group(function () {
    Route::controller(PacketController::class)->group(function () {
        Route::get('/get-all-paket/full-test', 'getAllPacketFullTest');
        Route::get('/get-all-paket/mini-test', 'getAllPacketMiniTest');
        Route::get('/get-pakets/{idPacket}', 'getQuestionPacket');
    });
    Route::controller(AnswerController::class)->group(function () {
        Route::post('/submit-paket/{idPacket}', 'submitPacket');
        Route::get('/get-score/{idPacket}', 'getScoreSubmit');
        Route::patch('/retake-test/{idPacket}', 'retakeTest');
        Route::get('/answer/users/{idPacket}', 'answerUsers');
    });
    Route::controller(BookmarkController::class)->group(function () {
        Route::get('/get-all-bookmark', 'getAllBookmark');
        Route::get('/get-bookmark/{idBookmark}', 'getSpesificBookmark');
        Route::patch('/add-bookmark/{idSoal}', 'addBookmark');
        Route::patch('/update-bookmark/{idBookmark}', 'updateBookmark');
    });

    Route::controller(ValueHomeController::class)->group(function () {
        Route::get('/get-all/targets', 'getAllTargetValue');
        Route::patch('/add-and-patch-target', 'addTarget');
        Route::get('/get-score-toefl', 'getLevelUser');
    });

    Route::resource('/randomword', RandomWordController::class);
    Route::resource('/quizs',QuizController::class);
    Route::resource('/quiztypes',QuizTypeController::class);
    Route::resource('/games',GameController::class);
    Route::resource('/gameclaims',GameClaimController::class);
    Route::resource('/quizclaims',QuizEnrollController::class);
    Route::resource('/leaderboard',QuizGameScoreController::class);
    Route::resource('/gameanswer',GameAnswerController::class);
    Route::resource('/quizanswer',QuizAnswerController::class);
    Route::resource('/quizgameresult',QuizResultController::class);
    Route::resource('/randomword',RandomWordController::class);
    Route::resource('/scrambledword',ScrambledWordController::class);
    Route::resource('/pairingclaims',PairingClaimController::class);
    Route::resource('/foryou', ForYouController::class);
});
