<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
//Rutas para las actividades
Route::controller(ActionController::class)->group(function () {
    Route::post('action', 'store'); //Crear
    Route::get('action/center', 'center'); //Mostar centros
    Route::get('action/all','showAll'); //Mostrar todas
    Route::get('action/{action}', 'show'); //Detalles de una actividad
    Route::put('action/{action}/update', 'update'); //Actualizar una actividad
    Route::delete('action/{action}/destroy', 'destroy'); //Eliminar una actividad
    Route::post('action/reducirPlazas', 'reducirPlazas'); //Reducir las plazas de una actividad
    Route::post('action/aumentarPlazas', 'aumentarPlazas'); //Aumentar las plazas de una actividad
});
//Rutas para los usuarios
Route::controller(UserController::class)->group(function () {
    Route::post("login", "login"); //Iniciar sesion
    Route::post('user', 'store'); //Crear usuario
    Route::get('/user/joined', 'isEnroled'); //A que actividades esta asociado
    Route::get('user/all','showAll'); //Mostrar todos los usuarios
    Route::get('user/{action}/action','actions'); //Mostrar actividades de un usuario
    Route::get('user/{user}', 'show'); //Detalles de un usuario
    Route::post('user/{user}/update', 'update'); //Actualizar un usuario
    Route::delete('user/{user}/destroy', 'destroy'); //Eliminar
    Route::post('user/join', 'join'); //Asociar usuario a actividad
    Route::post('user/joinDelete', 'joinDelete'); //Desasociar
    Route::get('user/{user}/actions','showOne'); //Mostrar actividad de usuario
});
//Rutas para los centros
Route::controller(CenterController::class)->group(function () {
    Route::post('center', 'store'); //Crear
    Route::get('center/all','showAll'); //Mostrar todos
    Route::get('center/{center}', 'show'); //Mostrar uno
    Route::put('center/{center}/update', 'update'); //Actualizar
    Route::delete('center/{center}/destroy', 'destroy'); //Eliminar
});
