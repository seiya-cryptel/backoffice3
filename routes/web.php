<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Localization;

/**
 * Redirect to the default locale
*/
Route::get('/', function(){
    return redirect('/' . app()->getLocale());
 });

/**
 * Set the locale
 */
Route::get('/setlocale/{lang}', function($lang) {
    app()->setLocale($lang);
    session(['localization' => $lang]);
    // return redirect()->back();
    return redirect("/" . $lang);
})->name('localization');

/**
 * Routes that require localization
 */
Route::group(['prefix' => '{locale}', 'middleware' => Localization::class], 
function () {
        // トップ
        Route::view('/', 'dashboard')
            ->middleware(['auth', 'verified'])
            ->name('/');

        Route::view('dashboard', 'dashboard')
            ->middleware(['auth', 'verified'])
            ->name('dashboard');
    
        /**
         * ユーザ プロファイル
         */
        Route::view('profile', 'profile')
            ->middleware(['auth'])
            ->name('profile');
});

require __DIR__.'/auth.php';
