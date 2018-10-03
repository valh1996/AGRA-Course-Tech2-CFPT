<?php

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

Route::get('/', 'PostController@index')->name('post.index');
Route::get('/post/{id}/edit', 'PostController@edit')->where(['id' => '[0-9]+'])->name('post.edit');

Route::post('/post/add', 'PostController@store')->name('post.add');
Route::delete('/post/{id}/del', 'PostController@delete')->where(['id' => '[0-9]+'])->name('post.del');
Route::put('/post/{id}/update', 'PostController@update')->where(['id' => '[0-9]+'])->name('post.update');