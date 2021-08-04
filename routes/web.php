<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::get('/', function () {
    return view('welcome');
});
// Route::group(['prefix' => 'admin'], function () {
//     Route::group(['prefix' => 'categories'], function () {
//         Route::get('listCate', [CategoryController::class, 'getListCate'])->name('category.list');
//         Route::post('search', [CategoryController::class, 'search'])->name('category.search');
//         Route::get('addCate', [CategoryController::class, 'addCate'])->name('category.add');
//         Route::post('saveCate', [CategoryController::class, 'saveAdd'])->name('category.saveAdd');
//         Route::get('editCate/{id}', [CategoryController::class, 'editCate']);
//         Route::post('editCate/{id}', [CategoryController::class, 'saveEdit'])->name('category.saveEdit');
//         Route::get('delete/{id}', [CategoryController::class, 'deleteCate']);
//         Route::get('listCategory', [CategoryController::class, 'getList'])->name('category.listCate');
//         Route::get('datatable', [CategoryController::class, 'getData'])->name('category.filter');
//     });

//     Route::group(['prefix' => 'products'], function () {
//         Route::get('listProduct', [ProductController::class, 'listProduct'])->name('product.list');
//         Route::get('addPro', [ProductController::class, 'addPro'])->name('product.add');
//         Route::post('addPro', [ProductController::class, 'saveAdd'])->name('product.saveAdd');
//         Route::get('detailPro', [ProductController::class, 'detailPro'])->name('product.detail');
//         Route::get('editPro/{id}', [ProductController::class, 'editPro'])->name('product.edit');
//         Route::post('editPro/{id}', [ProductController::class, 'saveEdit'])->name('product.saveEdit');
//         Route::get('deletePro/{id}', [ProductController::class, 'deletePro'])->name('product.delete');
//         Route::get('datatable', [ProductController::class, 'getData'])->name('product.filter');
//         route::post('upload_image', [ProductController::class, 'upload'])->name('product.upload');
//     });
// });