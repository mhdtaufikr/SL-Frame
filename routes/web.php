<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\SLFrameController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Login Controller
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/auth/login', [AuthController::class, 'postLogin']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    //Home Controller
    Route::get('/checksheet', [HomeController::class, 'index'])->name('checksheet');




    //SLFrame Controller
    Route::post('/slframe', [SLFrameController::class, 'index']);
    Route::get('/slframe/{noframe}', [SLFrameController::class, 'show'])->name('show');
    Route::post('/submit', [SLFrameController::class, 'submit'])->name('submit');
    Route::post('/submit/pdi', [SLFrameController::class, 'submitPDI'])->name('submitPDI');
    Route::post('/submit/main', [SLFrameController::class, 'submitMain'])->name('submitMain');
    Route::delete('/slframe/delete/{id}', [SLFrameController::class, 'delete']);
    Route::delete('/slframe/delete/pending/{id}', [SLFrameController::class, 'deletePending']);
    Route::get('/record', [SLFrameController::class, 'slFrameRecords'])->name('record');
    Route::get('/home', [SLFrameController::class, 'chartSlFrame'])->name('home');
    Route::get('/detail/{id}', [SLFrameController::class, 'detailSLFrame']);
    Route::get('/export', [SLFrameController::class, 'export'])->name('export');
    Route::get('/detail/{role}/{date}', [SLFrameController::class, 'detailPDI']);
    Route::post('/frame/search', [SLFrameController::class, 'slFrameRecords']);



    //Dropdown Controller
    Route::get('/dropdown', [DropdownController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/dropdown/store', [DropdownController::class, 'store'])->middleware(['checkRole:IT']);
    Route::patch('/dropdown/update/{id}', [DropdownController::class, 'update'])->middleware(['checkRole:IT']);
    Route::delete('/dropdown/delete/{id}', [DropdownController::class, 'delete'])->middleware(['checkRole:IT']);

    //Rules Controller
    Route::get('/rule', [RulesController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/rule/store', [RulesController::class, 'store'])->middleware(['checkRole:IT']);
    Route::patch('/rule/update/{id}', [RulesController::class, 'update'])->middleware(['checkRole:IT']);
    Route::delete('/rule/delete/{id}', [RulesController::class, 'delete'])->middleware(['checkRole:IT']);

    //User Controller
    Route::get('/user', [UserController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/user/store', [UserController::class, 'store'])->middleware(['checkRole:IT']);
    Route::post('/user/store-partner', [UserController::class, 'storePartner'])->middleware(['checkRole:IT']);
    Route::patch('/user/update/{user}', [UserController::class, 'update'])->middleware(['checkRole:IT']);
    Route::get('/user/revoke/{user}', [UserController::class, 'revoke'])->middleware(['checkRole:IT']);
    Route::get('/user/access/{user}', [UserController::class, 'access'])->middleware(['checkRole:IT']);

});
