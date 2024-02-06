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

Auth::routes();


Route::group([
    'middleware' => 'auth',
], function () {
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::resource('rkap', 'RkapController');
    Route::resource('fase', 'FaseController');

    Route::group([
        'prefix' => '/{jenis_id}/',
    ], function () {
        Route::resource('template', 'TemplateController');
    });

    Route::group([
        'prefix' => '/version/{version_id}',
    ], function () {
        Route::resource('sheet', 'SheetController');
        Route::post('/sheet/use', ['as' => 'sheet.save', 'uses' => 'SheetController@sheet_save']);
        Route::get('/sheet/setting/{id}', ['as' => 'sheet.setting', 'uses' => 'SheetController@setting']);
        Route::post('/sheet/import', ['as' => 'sheet.import', 'uses' => 'SheetController@import']);

        Route::resource('fileimport', 'FileImportController');

        Route::get('/fileimport/edit/{id}/{sheet_id}', ['as' => 'fileimport.editimport', 'uses' => 'FileImportController@edit_import']);
        Route::post('/fileimport/import/{id}', ['as' => 'fileimport.import', 'uses' => 'FileImportController@import']);
        Route::post('/sheet/import/use/{id}', ['as' => 'fileimport.import.use', 'uses' => 'FileImportController@import_use']);
        Route::put('/fileimport/update/{id}/{sheet_id}', ['as' => 'fileimport.updateimport', 'uses' => 'FileImportController@import_update']);
        Route::get('/sheet/export/use/{id}', ['as' => 'fileimport.export.use', 'uses' => 'FileImportController@export_use']);
        Route::post('/sheet/export/{id}', ['as' => 'fileimport.export', 'uses' => 'FileImportController@export']);
    });

    Route::get('/history/{id}', ['as' => 'history.index', 'uses' => 'HistoryController@index']);

    
    // 1.1 risk profile
    Route::get('/output/risk-profile', 'RiskProfileController@Risk_Profile');

    // 1.2 Mitigasi resiko
    Route::get('/output/mitigasi-risiko', 'MitigasiResikoController@Mitigasi_Resiko');

    // 2.0 Rencana kinerja
    Route::get('/output/rencana-kinerja', 'RencanaKinerjaController@Rencana_Kinerja');

    // 3.0 Program Strategis
    Route::get('/output/program-strategis', 'ProgramStrategisController@Program_Strategis');

    // 4.0 LR
    Route::get('/output/laba-rugi', 'LrController@LR');
    Route::get('/output/ShowDistrik', 'LrController@Distrik');

    // 5.0 Biaya Pemeliharaan
    Route::get('/output/biaya-pemeliharaan', 'BiayaPemeliharaanController@Biaya_Pemeliharaan');

    // 6.0 Status DMR 
    Route::get('/output/status-dmr', 'StatusDmrController@Status_Dmr');

    // 7.0 Rincian biaya har
    Route::get('/output/rincian-biaya-har', 'RincianBiayaHarController@Rincian_Biaya_Har');

    // 8.0 Rincian biaya har reimburse
    Route::get('/output/rincian-biaya-har-reimburse', 'RincianBiayaHarReimburseController@Rincian_Biaya_Har_Reimburse');

    // 9.1 Rincian Penetapan AI 
    Route::get('output/rincian-penetapan-ai', 'RincianPenetapanAiController@Rincian_Penetapan_Ai');

    // 9.2 Rincian AI Pengembangan Usaha
    Route::get('output/rincian-pengembangan-usaha', 'RincianPengembanganUsaha@Rincian_Pengembangan_Usaha');

     // 9.3 Rincian AI Penetapan PLN
    Route::get('/output/rincian-penetapan-pln', 'RincianPenetapanPlnController@Rincian_penetapan_Pln');

    // 10 Rincian Biaya Pegawai
    Route::get('/output/rincian-biaya-pegawai', 'RincianBiayaPegawaiController@Rincian_Biaya_Pegawai');

    // 11 Rincian Biaya Administrasi
    Route::get('/output/rincian-biaya-administrasi', 'RincianBiayaAdministrasiController@Rincian_Biaya_Administrasi');

    // 12 Rincian Energi Primer
    Route::get('/output/rincian-energi-primer', 'RincianEnergiPrimerController@Rincian_Energi_Primer');

    // 13 Form Luar Operasi
    Route::get('/output/form-luar-operasi', 'FormLuarOperasiController@Form_luar_operasi');

    // 14 Loader Ellipse
    Route::get('/output/loader-ellipse', 'LoaderEllipseController@Loader_Ellipse');

    // 15 List PRK
      Route::get('/output/list-prk', 'ListPrkController@List_Prk');


});

