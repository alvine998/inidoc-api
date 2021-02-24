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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// INIDOC

// GET
Route::get('/users/{id_user}', 'ApiController@getUsersById');
Route::get('/apotek/{id_apotek}', 'ApiController@getApotekById');
Route::get('/getproduct', 'ApiController@getProduct');
Route::get('/getproductimage/{id_product}', 'ApiController@getProductImage');
Route::get('/getcart/{id_user}', 'ApiController@getMyCart');
Route::get('/getaddress/{id_user}', 'ApiController@getMyAddress');
Route::get('/getprovinsi', 'ApiController@getProvinsi');
Route::get('/gettransaction/{id_buyer}/{status_transaction}', 'ApiController@getTransaction');
Route::get('/gettransactionseller/{id_seller}/{status_transaction}', 'ApiController@getTransactionSeller');
Route::get('/gettransactionapotek/{id_apotek}/{status_transaction}', 'ApiController@getTransactionApotek');
Route::get('/getalldoctor', 'ApiController@getAllDoctor');
Route::get('/getalldoctor/{spesialis}', 'ApiController@getDoctorSpesialis');
Route::get('/getrecomenddoctor', 'ApiController@getRecommendDoctor');
Route::get('/getallpasien', 'ApiController@getAllPasien');
Route::get('/getallhospital', 'ApiController@getAllHospital');
Route::get('/getchat/{id_user}/{id_doctor}', 'ApiController@getChat');
Route::get('/getchatuser/{id_user}', 'ApiController@getChatByUser');
Route::get('/getchatdoctor/{id_doctor}', 'ApiController@getChatByDoctor');
Route::get('/getambulance', 'ApiController@getAmbulance');
Route::get('/getambulance/{id_hospital}', 'ApiController@getMyAmbulance');
Route::get('/gethospital/{id_hospital}', 'ApiController@getMyHospital');
Route::get('/gethospitalpromise/{id_hospital}', 'ApiController@getHospitalPromise');
Route::get('/gethospitalpromisebyuser/{id_user}', 'ApiController@getHospitalPromiseByUser');
Route::get('/getalltestlab', 'ApiController@getAllTestLab');
Route::get('/gettestlab/{id_test_lab}', 'ApiController@getTestLab');
Route::get('/getnotification/{id_receiver}', 'ApiController@getNotification');
Route::get('/getsaldouser/{id_user}', 'ApiController@getSaldoUser');
Route::get('/getsaldodoctor/{id_doctor}', 'ApiController@getSaldoDoctor');
Route::get('/getaddress/{id_user}', 'ApiController@getMyAddress');
Route::get('/getproductbyuser/{id_user}', 'ApiController@getProductByUser');
Route::get('/getproductbyapotek/{id_apotek}', 'ApiController@getProductByApotek');
Route::get('/getresep/{id_user}', 'ApiController@getResep');
Route::get('/getresepbyapotek/{id_apotek}', 'ApiController@getResepByApotek');
Route::get('/gettesttrabsactionlab/{id_user}', 'ApiController@getTestLabUser');
Route::get('/gettesttrabsactionlab', 'ApiController@getTestLabTransaction');
Route::get('/getallapotek', 'ApiController@getAllApotek');
Route::get('/getdoctorbyid/{id_doctor}', 'ApiController@getDoctorById');

// POST
Route::post('/loginbyphone', 'ApiController@loginByPhone');
Route::post('/logindoctorbyphone', 'ApiController@loginDoctorByPhone');
Route::post('/loginrsbyphone', 'ApiController@loginRSByPhone');
Route::post('/loginapotekbyphone', 'ApiController@loginApotekByPhone');
Route::post('/registeruser', 'ApiController@registerUser');
Route::post('/registerdoctor', 'ApiController@registerDoctor');
Route::post('/registerhospital', 'ApiController@registerHospital');
Route::post('/registerapotek', 'ApiController@registerApotek');
Route::post('/postproduct', 'ApiController@postProduct');
Route::post('/postproductimage', 'ApiController@postProductImage');
Route::post('/postcart', 'ApiController@postCart');
Route::post('/postaddress', 'ApiController@postAddress');
Route::post('/posttransaction', 'ApiController@postTransaction');
Route::post('/postpromisehospital', 'ApiController@postPromiseHospital');
Route::post('/postchat/{id_user}/{id_doctor}', 'ApiController@postChat');
Route::post('/postambulance', 'ApiController@postAmbulance');
Route::post('/posttestlab', 'ApiController@postTestLab');
Route::post('/posttestlabtransaction', 'ApiController@postTestLabTransaction');
Route::post('/postnotification', 'ApiController@postNotification');
Route::post('/postsaldouser', 'ApiController@postSaldoUser');
Route::post('/postsaldodoctor', 'ApiController@postSaldoDoctor');
Route::post('/postsaldoapotek', 'ApiController@postSaldoApotek');
Route::post('/postsaldoprodia', 'ApiController@postSaldoProdia');
Route::post('/postsaldotestlab', 'ApiController@postSaldoTestLab');
Route::post('/postaddress', 'ApiController@postAddress');
Route::post('/postresep', 'ApiController@postResep');

// Put
Route::put('/updateprofil/{id}', 'ApiController@updateProfil');
Route::put('/updateproduct/{id_product}', 'ApiController@updateProduct');
Route::put('/updateaddress/{id_address}', 'ApiController@putAddress');
Route::put('/putambulance/{id_ambulance}', 'ApiController@updateAmbulance');
Route::put('/deleteproduct/{id_product}', 'ApiController@deleteProduct');
Route::put('/updatetransaksi/{id_transaction}', 'ApiController@putTransaction');
Route::put('/updatetestlab/{id_test_lab}', 'ApiController@putTestlab');
Route::put('/updateresep/{id_resep}', 'ApiController@putResep');
Route::put('/updatedoctor/{id_doctor}', 'ApiController@putDoctor');

// Delete
Route::delete('/deletewishlist/{id_product}/{id_user}', 'ApiController@deleteWishlist');
Route::delete('/deletefeed/{id_feed}', 'ApiController@deleteFeed');
Route::delete('/deletecart/{id_cart}', 'ApiController@deleteCart');
Route::delete('/unlikes/{id_feed}/{id_user}', 'ApiController@unlikeFeed');
Route::delete('/deletetestlab/{id_test_lab}', 'ApiController@deleteTestLab');