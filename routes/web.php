<?php

use App\Http\Controllers\AllPacketController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PacketFullController;
use App\Http\Controllers\PacketMiniController;
use App\Http\Controllers\StoryQuestionController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/', 'login')->name('loginPost');
});

Route::middleware('authenticated')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::controller(AllPacketController::class)->group(function () {
        Route::get('/get-all-paket/full-test', 'index')->name('allpacket.index');
    });

    Route::controller(PacketFullController::class)->group(function () {
        Route::get('/get-all-paket/full-test', 'getFullPaket')->name('packetfull.first');
        Route::get('/data-packet-full/{id}', 'index')->name('packetfull.index');
        Route::get('/entry-question-full/{id}', 'getEntryQuestionFull')->name('packetfull.entryQuestion');
        Route::patch('/edit-question/{id}', 'editQuestion')->name('packetfull.editQuestion');
        Route::post('/entry-question-full', 'postEntryQuestionFull')->name('packetfull.postEntryQuestion');
        Route::post('/entry-data-multiple/{id}', 'entryMultiple')->name('packetfull.entryMultiple');
        Route::post('/edit-answer/{id}/test', 'editAnswer')->name('packetfull.editAnswer');

        // entry nested question 
        Route::get('/entry/nested-question/{id}', 'entryNestedFullQuestion')->name('packetfull.entryNested');
        Route::post('/entry/nested-question/{id}', 'addNestedFullQuestion')->name('packetfull.addNested');
        Route::get('/entrydata/nested-question/{idNested}/{idPacket}/get-all', 'getAllNested')->name('packetfull.getAllNested');
        Route::post('/entry/nested-question/{id}/add-data', 'storeDataNested')->name('packetfull.storeDataNested');
        Route::get('/entry/nested-question/{id}/deleted', 'deleteNestedQuestion')->name('packetfull.deleteNestedQuestion');
        Route::patch('/edit-nested/{id}', 'editNested')->name('packetfull.editNested');
    });

    Route::controller(PacketMiniController::class)->group(function () {
        Route::get('/get-all-paket/mini-test', 'getMiniPaket')->name('packetmini.first');
        Route::get('/data-packet-mini/{id}', 'index')->name('packetmini.index');
        Route::get('/entry-question-mini/{id}', 'getEntryQuestionMini')->name('packetmini.entryQuestion');
        Route::patch('/edit-packet/{id}', 'editPacket')->name('packetmini.edit');
        Route::get('/delete-packet/{id}', 'deletePacket')->name('packetmini.delete');
        Route::post('/add-packet', 'addPacket')->name('packetmini.store');
    });

    Route::controller(StoryQuestionController::class)->group(function () {
        Route::get('/entry/nested-question', 'index')->name('entrynested.index');
    });
});
