<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ActionController::class)->group(function () {
    Route::post('action', 'store');
    Route::get('action/center', 'center');
    Route::get('action/all','showAll');
    Route::get('action/{action}', 'show');
    Route::put('action/{action}/update', 'update');
    Route::delete('action/{action}/destroy', 'destroy');
    Route::post('action/reducirPlazas', 'reducirPlazas');
    Route::post('action/aumentarPlazas', 'aumentarPlazas');
});

Route::controller(UserController::class)->group(function () {
    Route::post("login", "login");
    Route::post('user', 'store');
    Route::get('/user/joined', 'isEnroled');
    Route::get('user/all','showAll');
    Route::get('user/{user}', 'show');
    Route::post('user/{user}/update', 'update');
    Route::delete('user/{user}/destroy', 'destroy');
    Route::post('user/join', 'join');
    Route::post('user/joinDelete', 'joinDelete');
    Route::get('user/{user}/actions','showOne');
});

Route::controller(CenterController::class)->group(function () {
    Route::post('center', 'store');
    Route::get('center/all','showAll');
    Route::get('center/{center}', 'show');
    Route::put('center/{center}/update', 'update');
    Route::delete('center/{center}/destroy', 'destroy');
});
