<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('cors')->group(function() {
Route::prefix('operator')->group(function() {
	Route::prefix('auth')->group(function() {	
		Route::post('login', 'Auth\OperatorController@login');
		Route::prefix('forgot-password')->group(function() {
			Route::post('/', 'Auth\OperatorController@forgot_password');
			Route::post('verify-otp', 'Auth\OperatorController@verify_otp');
			Route::post('reset', 'Auth\OperatorController@reset_password');
		});

		Route::get('check', 'Auth\OperatorController@check')
			->middleware('auth:operator');
	});

	Route::middleware('auth:operator')->group(function() {
		Route::prefix('fish')->group(function() {
			Route::get('/', 'Operator\FishController@all');
			Route::get('/{id}', 'Operator\FishController@detail');
			Route::post('/add', 'Operator\FishController@add');
			Route::put('/edit/{id}', 'Operator\FishController@edit');
			Route::delete('/delete/{id}', 'Operator\FishController@delete');
		});
		Route::prefix('fish-image')->group(function() {
			Route::get('/', 'Operator\FishImageController@all');
			Route::get('/{id}', 'Operator\FishImageController@detail');
			Route::post('/add', 'Operator\FishImageController@add');
			Route::put('/edit/{id}', 'Operator\FishImageController@edit');
			Route::delete('/delete/{id}', 'Operator\FishImageController@delete');
		});
	});
});

Route::prefix('merchant-operator')->group(function() {
	Route::prefix('auth')->group(function() {
		Route::post('login', 'Auth\MerchantOperatorController@login');
		Route::prefix('forgot-password')->group(function() {
			Route::post('/', 'Auth\MerchantOperatorController@forgot_password');
			Route::post('verify-otp', 'Auth\MerchantOperatorController@verify_otp');
			Route::post('reset', 'Auth\MerchantOperatorController@reset_password');
		});
		
		Route::post('reset-default-password', 'Auth\MerchantOperatorController@reset_default_password')
			->middleware('auth:merchant_operator');
		Route::middleware(['auth:merchant_operator', 'default.password'])->group(function () {
			Route::get('check', 'Auth\MerchantOperatorController@check');
			Route::get('fish', 'Merchant\ProductController@fish_data');
			
			Route::prefix('products')->group(function () {
				Route::get('/', 'Merchant\ProductController@all');
				Route::get('/{id}', 'Merchant\ProductController@detail');
				Route::post('/add', 'Merchant\ProductController@add');
				Route::put('/edit/{id}', 'Merchant\ProductController@edit');
				Route::delete('/delete/{id}', 'Merchant\ProductController@delete');
			});
			Route::prefix('transaction')->group(function () {
				Route::get('/','TransactionController@dataMerchant');
				Route::get('/detail/{id}','TransactionController@detailMerchant');
				Route::put('/update/{id}','TransactionController@changeStatus');
				Route::put('/cancel/{id}','TransactionController@cancelMerchant');
			});
		});
	});
});


Route::prefix('client')->group(function() {
	Route::prefix('auth')->group(function() {
		Route::post('register', 'Auth\ClientController@register');
		Route::post('login', 'Auth\ClientController@login');
		Route::prefix('forgot-password')->group(function() {
			Route::post('/', 'Auth\ClientController@forgot_password');
			Route::post('verify-otp', 'Auth\ClientController@verify_otp');
			Route::post('reset', 'Auth\ClientController@reset_password');
		});

		Route::middleware('auth:client')->group(function () {
		Route::post('send-email', 'Auth\ClientController@send_email_verification');
		Route::post('verify-email', 'Auth\ClientController@verify_email');
		
			Route::middleware('email.verified')->group(function () {
				Route::get('check', 'Auth\ClientController@check');
				Route::prefix('cart')->group(function () {
					Route::get('/','Client\CartController@all');
					Route::post('/add','Client\CartController@add');
					Route::put('/edit/{id}','Client\CartController@edit');
					Route::delete('/delete','Client\CartController@delete');
				});
				Route::prefix('review')->group(function () {
					Route::get('/{id}','Client\ReviewController@detail');
					Route::post('/add/{id}','Client\ReviewController@add');
					Route::put('/edit/{id}','Client\ReviewController@edit');
					Route::delete('/delete/{id}','Client\ReviewController@delete');
				});
				Route::prefix('transaction')->group(function () {
					Route::get('/','TransactionController@data');
					Route::post('/checkout','TransactionController@checkout');
					Route::get('/detail/{id}','TransactionController@detail');
					Route::post('/upload/{id}','TransactionController@uploadImage');
					Route::put('/cancel/{id}','TransactionController@cancel');
				});
			});
		});
	});
	Route::prefix('fish')->group(function () {
		Route::get('/','Client\FishController@all');
		Route::get('/{id}','Client\FishController@detail');
		Route::get('/merchant/{id}','Client\FishController@get_by_merchant');
	});
});
});