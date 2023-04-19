<?php

use Illuminate\Support\Facades\Route;
use Modules\Qna\Http\Controllers\QnaAdminController;
use Modules\Qna\Http\Controllers\QnaCategoryController;
use Modules\Qna\Http\Controllers\QnaConfigController;
use Modules\Qna\Http\Controllers\QnaController;

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

// Q&A
Route::prefix('/qna')
    ->controller(QnaController::class)->group(function () {
        Route::get('/', 'index')->name('qna.index');
        Route::get('/create', 'create')->name('qna.create');
        Route::post('/', 'store')->name('qna.store');
        Route::get('/{qna}', 'show')->name('qna.show');
        Route::get('/{qna}/edit', 'edit')->name('qna.edit');
        Route::put('/{qna}', 'update')->name('qna.update');
        Route::delete('/{qna}', 'destroy')->name('qna.destroy');
    });

// 관리자
Route::prefix('/adm')
    ->middleware('admin.menu.permission')->group(function () {
        // Q&A 관리
        Route::prefix('/qna')->controller(QnaAdminController::class)->group(function () {
            Route::get('/', 'index')->name('admin.qna.index');
            Route::get('/{qna}', 'edit')->name('admin.qna.edit');
            Route::put('/{qna}', 'update')->name('admin.qna.update');
            Route::put('/list', 'updateList')->name('admin.qna.list.update');
            Route::delete('/{qna}', 'destroy')->name('admin.qna.destroy');
            Route::delete('/list/delete', 'destroyList')->name('admin.qna.list.destroy');
        });
        // 설정 관리
        Route::prefix('/qna-config')->controller(QnaConfigController::class)->group(function () {
            Route::get('/', 'index')->name('admin.qna-config.index');
            Route::put('/', 'update')->name('admin.qna-config.update');
        });
        // 분류 관리
        Route::prefix('/qna-category')->controller(QnaCategoryController::class)->group(function () {
            Route::get('/', 'index')->name('admin.qna-category.index');
            Route::get('/create/{qnaCategory?}', 'create')->name('admin.qna-category.create');
            Route::post('/', 'store')->name('admin.qna-category.store');
            Route::put('/list', 'updateList')->name('admin.qna-category.list.update');
            Route::delete('/{qnaCategory}', 'destroy')->name('admin.qna-category.destroy');
        });
    });
