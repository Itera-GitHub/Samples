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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('candidate')->group(function () {
    Route::post('single-cv-upload','CandidatesController@uploadSingleCV')->name('candidate-single-cv-upload');
    Route::get('bulk-cv-upload','CandidatesController@uploadBulkCV')->name('candidate-bulk-cv-upload');
    Route::post('bulk-cv-upload','CandidatesController@uploadBulkCV')->name('candidate-bulk-cv-upload_');
    Route::post('create','CandidatesController@store')->name('candidate-store');
    Route::get('list','CandidatesController@index')->name('candidate-list');
    Route::get('{id}','CandidatesController@show')->name('candidate-show');
    Route::post('assign-offer','CandidatesController@assignOffer')->name('candidate-store');
    Route::post('send-group-mail','CandidatesController@sendGroupMail')->name('candidate-send-group-mail');
    Route::get('get-form/{formName}','CandidatesController@getForm')->name('candidate-get-form');
});
Route::prefix('forms')->group(function () {
    Route::get('{form_name}','GenericFormController@getFormTemplate')->name('forms-get');
});

Route::prefix('offer-history')->group(function () {
    Route::post('create','CandidateOfferHistoriesController@store')->name('offer-history-store');
});

Route::prefix('tag')->group(function () {
    Route::get('list','TagsController@index')->name('tag-list');
    Route::get('{id}','TagsController@show')->name('tag-show');
    Route::post('create','TagsController@store')->name('tag-store');
});

Route::prefix('candidate-tag')->group(function () {
    Route::post('create','CandidateTagsController@store')->name('candidate-tag-store');
    Route::post('bulk-create','CandidateTagsController@bulkStore')->name('candidate-tag-bulk-store');
});

Route::prefix('offer-tag')->group(function () {
    Route::post('create','OfferTagsController@store')->name('candidate-tag-store');
    Route::post('bulk-create','OfferTagsController@bulkStore')->name('candidate-tag-bulk-store');
});

Route::prefix('email-template')->group(function () {
    Route::get('list','EmailTemplatesController@index')->name('email-template-list');
});

Route::prefix('offer')->group(function () {
    Route::get('list','OffersController@index')->name('offer-list');
});


