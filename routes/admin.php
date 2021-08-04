<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

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


Route::group(['prefix' => 'categories'], function () {
    Route::get('addCate', [CategoryController::class, 'addCate'])->name('category.add');
    Route::post('saveCate', [CategoryController::class, 'saveAdd'])->name('category.saveAdd');
    Route::get('editCate/{id}', [CategoryController::class, 'editCate'])->name('category.edit');
    Route::post('editCate/{id}', [CategoryController::class, 'saveEdit'])->name('category.saveEdit');
    Route::get('delete/{id}', [CategoryController::class, 'deleteCate'])->name('category.delete');
    Route::get('listCategory', [CategoryController::class, 'getList'])->name('category.listCate');
    Route::get('datatable', [CategoryController::class, 'getData'])->name('category.filter');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('listProduct', [ProductController::class, 'listProduct'])->name('product.list');
    Route::get('addPro', [ProductController::class, 'addPro'])->name('product.add');
    Route::post('addPro', [ProductController::class, 'saveAdd'])->name('product.saveAdd');
    Route::get('detailPro', [ProductController::class, 'detailPro'])->name('product.detail');
    Route::get('editPro/{id}', [ProductController::class, 'editPro'])->name('product.edit');
    Route::post('editPro/{id}', [ProductController::class, 'saveEdit'])->name('product.saveEdit');
    Route::get('deletePro/{id}', [ProductController::class, 'deletePro'])->name('product.delete');
    Route::get('datatable', [ProductController::class, 'getData'])->name('product.filter');
    route::post('upload_image', [ProductController::class, 'upload'])->name('product.upload');
});

Route::group(['prefix' => 'tags'], function () {
    Route::post('search', [TagController::class, 'search'])->name('tag.search');
    Route::get('addTag', [TagController::class, 'addTag'])->name('tag.add');
    Route::post('saveTag', [TagController::class, 'saveAdd'])->name('tag.saveAdd');
    Route::get('editTag/{id}', [TagController::class, 'editTag'])->name('tag.edit');
    Route::post('editTag/{id}', [TagController::class, 'saveEdit'])->name('tag.saveEdit');
    Route::get('delete/{id}', [TagController::class, 'deleteTag'])->name('tag.delete');
    Route::get('listTag', [TagController::class, 'getList'])->name('tag.listTag');
    Route::get('datatable', [TagController::class, 'getData'])->name('tag.filter');
});