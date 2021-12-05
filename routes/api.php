<?php

use App\Http\Controllers\FirebaseController;
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
Route::post('login', 'AuthController@login');
Route::group(['middleware' => 'auth:sanctum'], function () {
    // manggil controller sesuai bawaan laravel 8
    Route::post('logout', 'AuthController@logout');
    // manggil controller dengan mengubah namespace di RouteServiceProvider.php biar bisa kayak versi2 sebelumnya
    Route::post('logoutall', 'AuthController@logoutall');
    Route::post('userInfo', 'AuthController@userInfo');
    Route::post('cekToken', 'AuthController@checkToken');
    Route::post('getPenggunaInfo', 'PenggunaController@index');
    Route::put('updateUser', 'PenggunaController@updateUser');

    //satria
    //pengguna
    Route::get('getPenggunaInfo', 'PenggunaController@index');
    Route::post('createUser', 'PenggunaController@createUser');
    Route::get('allPenggunaInfo','PenggunaController@allUser');
    Route::post('editUser/{request}','PenggunaController@editUser');
    Route::post('updateUser', 'PenggunaController@updateUser');
    Route::delete('deleteUser/{id}', 'PenggunaController@deleteUser');
    //disposisi
    // Route::get('allInfoDisposisi/{id}','DisposisiController@allInfoDisposisi');
    Route::get('allInfoDisposisiSM','DisposisiController@allInfoDisposisiSuratMasuk');
    Route::get('allInfoDisposisiSK','DisposisiController@allInfoDisposisiSuratKeluar');
    Route::get('createDisposisi','DisposisiController@createDisposisis');
    Route::get('getDisposisi/{id}','DisposisiController@getDisposisi');
    Route::post('createDisposisi','DisposisiController@createDisposisis');
    Route::post('editDisposisi','DisposisiController@editDisposisi');
    Route::post('updateDisposisi','DisposisiController@updateDisposisi');
    Route::delete('deleteDisposisi/{id}','DisposisiController@deleteDisposisi');
    // Route::delete('deleteUser', 'PenggunaController@deleteUser');
    Route::get('getDisposisiByID/{id}','DisposisiController@getDisposisiByID');
    // Route::get('allInfoDisposisiKeluar','DisposisiController@allInfoDisposisiSuratKeluar');
    Route::get('showData','DisposisiController@showData');
    Route::delete('delAllDisposisi','DisposisiController@delAllDisposisi');

    //nadia
    //pengingat
    Route::post('createPengingat', 'PengingatController@createPengingat');
    Route::get('allPengingatInfo', 'PengingatController@getAllPengingat');
    Route::get('getAllPengingatSuratMasuk', 'PengingatController@getAllPengingatSuratMasuk');
    Route::get('getAllPengingatSuratKeluar', 'PengingatController@getAllPengingatSuratKeluar');
    Route::get('getPengingat/{id}', 'PengingatController@getPengingat');
    Route::post('updatePengingat', 'PengingatController@updatePengingat');
    Route::delete('deletePengingat/{id}', 'PengingatController@deletePengingat');
    //exportpencatatan
    Route::get('exportDataSuratMasuk', 'SuratMasukController@exportDataSuratMasuk');
    Route::get('exportDataSuratKeluar', 'SuratKeluarController@exportDataSuratKeluar');
    //importpencatatan
    Route::post('importDataSuratMasuk', 'SuratMasukController@importDataSuratMasuk');
    Route::post('importDataSuratKeluar', 'SuratKeluarController@importDataSuratKeluar');

    //ihksan
    //Pencatatan
    Route::post('setPencatatan', 'PencatatanController@setPencatatan');
    Route::get('getPencatatanInfo/{id}', 'PencatatanController@getPencatatanInfo');
    Route::delete('delPencatatan/{id}', 'PencatatanController@delPencatatan');
    Route::post('updatePencatatan', 'PencatatanController@updatePencatatan');
    Route::get('getAllPencatatanInfo', 'PencatatanController@getAllPencatatanInfo');
    Route::delete('delAllPencatatan', 'PencatatanController@delAllPencatatan');
    //Surat Masuk
    Route::post('setSuratMasuk', 'SuratMasukController@setSuratMasuk');
    Route::get('getSuratMasuk/{id}', 'SuratMasukController@getSuratMasuk');
    Route::delete('delSuratMasuk/{id}', 'SuratMasukController@delSuratMasuk');
    Route::post('updateSuratMasuk', 'SuratMasukController@updateSuratMasuk');
    Route::get('getAllSuratMasuk', 'SuratMasukController@getAllSuratMasuk');
    Route::delete('delAllSuratMasuk', 'SuratMasukController@delAllSuratMasuk');
    Route::get('getLast', 'SuratMasukController@index');
    Route::get('detailSuratMasuk', 'SuratMasukController@getSuratDetail');

    //Jenis Surat
    Route::get('getJenisSurat/{id}', 'JenisSuratController@getJenisSurat');
    Route::get('getAllJenisSurat', 'JenisSuratController@getAllJenisSurat');
    Route::delete('delAllJenisSurat', 'JenisSuratController@delAllJenisSurat');
    Route::delete('delJenisSurat/{id}', 'JenisSuratController@delJenisSurat');
    Route::post('setJenisSurat', 'JenisSuratController@setJenisSurat');
    Route::post('updateJenisSurat', 'JenisSuratController@updateJenisSurat');

    //Sifat Surat
    Route::get('getSifatNaskah/{id}', 'KodeSifatNaskahController@getSifatNaskah');
    Route::get('getAllSifatNaskah', 'KodeSifatNaskahController@getAllSifatNaskah');
    Route::delete('delAllSifatNaskah', 'KodeSifatNaskahController@delAllSifatNaskah');
    Route::delete('delSifatNaskah/{id}', 'KodeSifatNaskahController@delSifatNaskah');
    Route::post('setSifatNaskah', 'KodeSifatNaskahController@setSifatNaskah');
    Route::post('updateSifatNaskah', 'KodeSifatNaskahController@updateSifatNaskah');

    //Kode Unit
    Route::get('getKodeUnit/{id}', 'KodeUnitKerjaController@getKodeUnit');
    Route::get('getAllKodeUnit', 'KodeUnitKerjaController@getAllKodeUnit');
    Route::delete('delAllKodeUnit', 'KodeUnitKerjaController@delAllKodeUnit');
    Route::delete('delKodeUnit/{id}', 'KodeUnitKerjaController@delKodeUnit');
    Route::post('setKodeUnit', 'KodeUnitKerjaController@setKodeUnit');
    Route::post('updateKodeUnit', 'KodeUnitKerjaController@updateKodeUnit');

    //Derajat Surat
    Route::get('getDerajatSurat/{id}', 'DerajatSuratController@getDerajatSurat');
    Route::get('getAllDerajatSurat', 'DerajatSuratController@getAllDerajatSurat');
    Route::delete('delAllDerajatSurat', 'DerajatSuratController@delAllDerajatSurat');
    Route::delete('delDerajatSurat/{id}', 'DerajatSuratController@delDerajatSurat');
    Route::post('setDerajatSurat', 'DerajatSuratController@setDerajatSurat');
    Route::post('updateDerajatSurat', 'DerajatSuratController@updateDerajatSurat');

    //firebase surat
    Route::post('addSurat', 'FirebaseController@setFile');
    Route::post('getSurat','FirebaseController@getFile');
    Route::post('donwloadFile','FirebaseController@donwloadFile');
    Route::delete('delSurat/{id}','FirebaseController@delSurat');
    Route::delete('cancelDownload/{id}','FirebaseController@cancelDownload');

    //Tujuan Pencatatan
    Route::post('setTujuanPencatatan', 'TujuanController@setTujuanPencatatan');
    Route::get('getTujuanPencatatan/{id}', 'TujuanController@getTujuanPencatatan');
    Route::delete('delTujuanPencatatan/{id}/{to}', 'TujuanController@delTujuanPencatatan');
    Route::post('upTujuanPencatatan', 'TujuanController@upTujuanPencatatan');
    Route::get('getDetailTujuanPencatatan/{id}','TujuanController@getDetailTujuanPencatatan');
    Route::delete('delAllTujuanPencatatan/{id}', 'TujuanController@delAllTujuanPencatatan');
    Route::delete('delAllTujuanSurat', 'TujuanController@delAllTujuanSurat');

    //Tujuan Disposisi
    Route::post('createTujuanDisposisi','TujuanController@createTujuanDisposisi');
    Route::get('getTujuanDisposisi/{id}','TujuanController@getDetailTujuanDisposisi');
    Route::get('getDetailTujuanDisposisi/{id}','TujuanController@getDetailTujuanDisposisi');
    Route::delete('delAllTujuanDisposisi/{id}','TujuanController@delAllTujuanDisposisi');
    Route::get('delTujuanDisposisi/{id}/{to}','TujuanController@delTujuanDisposisi');
    Route::post('upTujuanDisposisi','TujuanController@upTujuanDisposisi');

    //SuratKeluar
    Route::post('setSuratKeluar', 'SuratKeluarController@setSuratKeluar');
    Route::get('getSuratKeluar/{id}', 'SuratKeluarController@getSuratKeluar');
    Route::delete('delSuratKeluar/{id}/{no}', 'SuratKeluarController@delSuratKeluar');
    Route::post('updateSuratKeluar', 'SuratKeluarController@updateSuratKeluar');
    Route::get('getAllSuratKeluar', 'SuratKeluarController@getAllSuratKeluar');
    Route::delete('delAllSuratKeluar', 'SuratKeluarController@delAllSuratKeluar');
    Route::get('getLastSK', 'SuratKeluarController@index');
    Route::get('getSuratKeluarDetail', 'SuratKeluarController@getSuratKeluarDetail');
    Route::get('getLastNoAgenda', 'SuratKeluarController@getLastNoAgenda');

    //NomorSurat
    Route::post('setNomorSurat', 'NomorSuratKeluarController@setNomorSurat');
    Route::get('getNomorSurat/{id}', 'NomorSuratKeluarController@getNomorSurat');
    Route::delete('delNomorSurat/{id}', 'NomorSuratKeluarController@delNomorSurat');
    Route::post('updateNomorSurat', 'NomorSuratKeluarController@updateNomorSurat');
    Route::get('getLastNomorSurat', 'NomorSuratKeluarController@index');
    Route::get('getDetailNomorSurat', 'NomorSuratKeluarController@getDetailNomorSurat');
    Route::get('getLastNomorUrut', 'NomorSuratKeluarController@getLastNomorUrut');
    Route::delete('delAllNomorSuratKeluar', 'NomorSuratKeluarController@delAllNomorSuratKeluar');

    //Pemohon
    Route::post('setPemohon', 'PemohonController@setPemohon');
    Route::get('getPemohon/{id}', 'PemohonController@getPemohon');
    Route::delete('delPemohon/{id}', 'PemohonController@delPemohon');
    Route::post('updatePemohon', 'PemohonController@updatePemohon');
    Route::get('getLastPemohon', 'PemohonController@index');
    Route::get('getAllPemohon','PemohonController@getAllPemohon');

    //KODE HAL
    Route::get('getAllKodeHal','KodeHalController@getAllKodeHal');
    Route::get('getKodeHal/{id}','KodeHalController@getKodeHal');

    //searching
    // Route::get('searchSuratMasuk/{id}','SuratMasukController@searchSuratMasuk');
    // Route::get('searchSuratKeluar/{id}','SuratKeluarController@searchSuratKeluar');
    Route::post('searchSuratMasuk/','SuratMasukController@searchSuratMasuk');
    Route::post('searchSuratKeluar/','SuratKeluarController@searchSuratKeluar');
    Route::post('searchDisposisiSM/','DisposisiController@searchDisposisi');
    Route::post('searchDisposisiSK/','DisposisiController@searchDisposisi');
    //count data
    Route::get('getCountSK','SuratKeluarController@getCountSK');
    Route::get('getCountSM','SuratMasukController@getCountSM');
    Route::get('getCountDis','DisposisiController@getCountDis');
    Route::get('getCountPenc','PencatatanController@getCountPenc');
    Route::get('getCountUser','PenggunaController@getCountUser');

    //WD3
    Route::post('addMsgDisposisi','DisposisiController@addMsgDisposisi');

    //Riwayat aktivitas
    Route::get('testLog','LogController@index');
    Route::get('getAllLog','LogController@getAllLog');
    Route::get('getLog/{id}','LogController@getLog');
    Route::post('setLog','LogController@setLog');
    Route::delete('delLog','LogController@delLog');
});
