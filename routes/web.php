<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\TranslationController;

/*
|--------------------------------------------------------------------------
| Language Switch Route
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function ($locale) {

    if (in_array($locale, ['en', 'fr', 'es'])) {
        Session::put('locale', $locale);
    }

    return redirect()->back();

})->name('lang.switch');


/*
|--------------------------------------------------------------------------
| Translations Dashboard Route
|--------------------------------------------------------------------------
*/



Route::get('/translations', [TranslationController::class,'index']);
Route::post('/translations/save', [TranslationController::class,'store']);
Route::post('/translations/add', [TranslationController::class,'addKey']);

/*
|--------------------------------------------------------------------------
| Home Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});