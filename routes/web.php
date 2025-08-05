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
         * Route for the マスタ／設定
         */
        Route::view('appSettings', 'appSettings')
            ->middleware(['auth', 'verified'])
            ->name('appSettings');
        Route::view('appSettingCreate', 'appSettingCreate')
            ->middleware(['auth', 'verified'])
            ->name('appSettingCreate');
        Route::view('appSettingUpdate/{id}', 'appSettingUpdate')
            ->middleware(['auth', 'verified'])
            ->name('appSettingUpdate');

        /**
         * Route for the マスタ／担当者役割
         */
        Route::view('personRoles', 'personRoles')
            ->middleware(['auth', 'verified'])
            ->name('personRoles');
        Route::view('personRoleCreate', 'personRoleCreate')
            ->middleware(['auth', 'verified'])
            ->name('personRoleCreate');
        Route::view('personRoleUpdate/{id}', 'personRoleUpdate')
            ->middleware(['auth', 'verified'])
            ->name('personRoleUpdate');

        /**
         * Route for the マスタ／顧客グループ
         */
        Route::view('clientGroups', 'clientGroups')
            ->middleware(['auth', 'verified'])
            ->name('clientGroups');
        Route::view('clientGroupCreate', 'clientGroupCreate')
            ->middleware(['auth', 'verified'])
            ->name('clientGroupCreate');
        Route::view('clientGroupUpdate/{id}', 'clientGroupUpdate')
            ->middleware(['auth', 'verified'])
            ->name('clientGroupUpdate');

        /**
         * Route for the マスタ／サービス
         */
        Route::view('services', 'services')
            ->middleware(['auth', 'verified'])
            ->name('services');
        Route::view('serviceCreate', 'serviceCreate')
            ->middleware(['auth', 'verified'])
            ->name('serviceCreate');
        Route::view('serviceUpdate/{id}', 'serviceUpdate')
            ->middleware(['auth', 'verified'])
            ->name('serviceUpdate');

        /**
         * Route for the マスタ／税率
         */
        Route::view('taxRates', 'taxRates')
            ->middleware(['auth', 'verified'])
            ->name('taxRates');
        Route::view('taxRateCreate', 'taxRateCreate')
            ->middleware(['auth', 'verified'])
            ->name('taxRateCreate');
        Route::view('taxRateUpdate/{id}', 'taxRateUpdate')
            ->middleware(['auth', 'verified'])
            ->name('taxRateUpdate');

        /**
         * Route for the マスタ／顧客
         */
        Route::view('clients', 'clients')
            ->middleware(['auth', 'verified'])
            ->name('clients');
        Route::view('clientEditCreate', 'clientEditCreate')
            ->middleware(['auth', 'verified'])
            ->name('clientEditCreate');
        Route::view('clientEditUpdate/{id}', 'clientEditUpdate')
            ->middleware(['auth', 'verified'])
            ->name('clientEditUpdate');

        /**
         * Route for the マスタ／顧客／担当者
         */
        Route::view('clientPersons/{client_id}', 'clientPersons')
            ->middleware(['auth', 'verified'])
            ->name('clientPersons');
        Route::view('clientPersonEditCreate/{client_id}', 'clientPersonEditCreate')
            ->middleware(['auth', 'verified'])
            ->name('clientPersonEditCreate');
        Route::view('clientPersonEditUpdate/{client_id}/{id}', 'clientPersonEditUpdate')
            ->middleware(['auth', 'verified'])
            ->name('clientPersonEditUpdate');

        /**
         * Route for the マスタ／顧客／契約
         */
        Route::view('contracts/{client_id}', 'contracts')
            ->middleware(['auth', 'verified'])
            ->name('contracts');
        Route::view('contractEditCreate/{client_id}', 'contractEditCreate')
            ->middleware(['auth', 'verified'])
            ->name('contractEditCreate');
        Route::view('contractEditUpdate/{client_id}/{id}', 'contractEditUpdate')
            ->middleware(['auth', 'verified'])
            ->name('contractEditUpdate');

        /**
         * Route for the 見積
         */
        Route::view('estimates', 'estimates')
            ->middleware(['auth', 'verified'])
            ->name('estimates');
        Route::view('estimateEditCreate', 'estimateEditCreate')
            ->middleware(['auth', 'verified'])
            ->name('estimateEditCreate');
        Route::view('estimateEditUpdate/{id}', 'estimateEditUpdate')
            ->middleware(['auth', 'verified'])
            ->name('estimateEditUpdate');

        /**
         * Route for the 請求
         */
        Route::view('bills', 'bills')
            ->middleware(['auth', 'verified'])
            ->name('bills');
        Route::view('billEditCreate', 'billEditCreate')
            ->middleware(['auth', 'verified'])
            ->name('billEditCreate');
        Route::view('billEditUpdate/{id}', 'billEditUpdate')
            ->middleware(['auth', 'verified'])
            ->name('billEditUpdate');
    
        /**
         * ユーザ プロファイル
         */
        Route::view('profile', 'profile')
            ->middleware(['auth'])
            ->name('profile');
});

require __DIR__.'/auth.php';
