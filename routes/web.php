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

//Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::get('pagenotfound', ['as'=> 'notfound', 'uses'=> 'HomeController@pagenotfound']);
Route::get('noaccess', ['as'=> 'noaccess', 'uses'=> 'HomeController@noaccess']);
//switch role manajemen akses
Route::get('/switchrole/{role_id}', [ 'uses' => 'HomeController@switchRole']);
//end of switch role manajemen akses
//switch strategi bisnis
Route::get('/switchsb/{sb_id}', [ 'uses' => 'HomeController@switchSb']);
//end of switch strategi bisnis
//switch grupdiv
Route::get('/switchrolegrupdiv/{grupdiv_id}', [ 'uses' => 'HomeController@switchRoleGrupdiv']);
//end of switch grupdiv

// ------------------
// AJAX
// ------------------

//Summary Form
Route::get('/output/summary/ajax/{id}',array('as'=>'output/summary.ajax','uses'=>'SummaryFormController@Ajax'));
Route::get('/output/summary/ajax2/{id}',array('as'=>'output/summary.ajax2','uses'=>'SummaryFormController@Ajax2'));

//Summary Dmr
Route::get('/output/summary/dmr/ajax/{id}',array('as'=>'output/summary.dmr.ajax','uses'=>'SummaryDmrController@Ajax'));
Route::get('/output/summary/dmr/ajax2/{id}',array('as'=>'output/summary.dmr.ajax2','uses'=>'SummaryDmrController@Ajax2'));

// Lokasi
Route::get('/lokasi/daftar_lokasi/ajax/{id}',array('as'=>'lokasi/daftar.ajax','uses'=>'LokasiController@Ajax'));
Route::get('/lokasi/daftar_lokasi/ajax2/{id}',array('as'=>'lokasi/daftar.ajax','uses'=>'LokasiController@myformAjax2'));
Route::get('/lokasi/create/ajax/{id}',array('as'=>'lokasi/create.ajax','uses'=>'LokasiController@Ajax'));

// Data Realisasi
Route::get('/data/input/realisasi/ajax',array('as'=>'data/realisasi.ajax','uses'=>'Pengendalian\LRInputProduksiPenjualanController@DataAjax'));
Route::post('/realisasi/store/ajax','Pengendalian\LRInputProduksiPenjualanController@StoreDataAjax');

// Dashboard dinamis
Route::get('/dashboard/page/ajax','Pengendalian\ReportDashboardDinamisController@page');
Route::get('/dashboardDinamis/source/ajax','Pengendalian\ReportDashboardDinamisController@source');
Route::get('/dashboardDinamis/jenis/ajax','Pengendalian\ReportDashboardDinamisController@jenis');
Route::get('/dashboardDinamis/macamSheet/ajax/{id}','Pengendalian\ReportDashboardDinamisController@macamsheet');
Route::post('/dashboardDinamis/kolomstore/ajax','Pengendalian\ReportDashboardDinamisController@storeKolom');
Route::post('/dashboardDinamis/copyTahun/ajax','Pengendalian\ReportDashboardDinamisController@copyTahun');

// Anggaran PRK Form dan No. PRK Form
Route::post('/output/anggaran_no_prk.ajax', 'DmrController@anggaran_no_prk_ajax');
// DMR ajax sesuai lokasi
Route::post('/output/dmr.ajax', 'TorController@dmr_ajax');


// Manajamen User
Route::post('HandlerParentId', array('uses' => 'AdminMemberController@getParentId', 'as' => 'permission.ajax.parentId.get'));
Route::post('HandlerParentMenu', array('uses' => 'AdminMemberController@getParentMenu', 'as' => 'permission.ajax.parentMenu.get'));

// Distrik
Route::get('/distrik/daftar/ajax/{id}',array('as'=>'distrik/daftar.ajax','uses'=>'DistrikController@Ajax'));
Route::get('/distrik/daftar/ajax2/{id}',array('as'=>'distrik/daftar.ajax','uses'=>'DistrikController@myformAjax2'));

// Entitas
Route::get('/entitas/daftar_entitas/ajax/{id}',array('as'=>'entitas/daftar.ajax','uses'=>'EntitasController@Ajax'));
Route::get('/entitas/daftar_entitas/ajax2/{id}',array('as'=>'entitas/daftar.ajax','uses'=>'EntitasController@myformAjax2'));
Route::get('/entitas/create/ajax/{id}',array('as'=>'entitas/create.ajax','uses'=>'EntitasController@Ajax'));
Route::get('/entitas/create/ajax2/{id}',array('as'=>'entitas/create.ajax','uses'=>'EntitasController@myformAjax2'));

// Unit
Route::get('/daftar_unit/ajax/{id}',array('as'=>'daftar_unit.ajax','uses'=>'UnitController@Ajax'));
Route::get('/daftar_unit/ajax2/{id}',array('as'=>'daftar_unit.ajax','uses'=>'UnitController@myformAjax2'));

// Risk Profile
// (Tidak jadi dipakai)
// Route::get('/risk_profile/ajax/{id}',array('as'=>'risk_profile.ajax','uses'=>'RiskProfileController@Ajax'));
// Route::get('/risk_profile/ajax2/{id}',array('as'=>'risk_profile.ajax','uses'=>'RiskProfileController@myformAjax2'));
// Route::get('/rencana_kerja/ajax/{id}',array('as'=>'rencana_kerja.ajax','uses'=>'RencanaKerjaController@Ajax'));
// Route::get('/rencana_kerja/ajax2/{id}',array('as'=>'rencana_kerja.ajax','uses'=>'RencanaKerjaController@myformAjax2'));


// Approval Form
Route::get('/approval/daftar/ajax/{id}',array('as'=>'approval/daftar.ajax','uses'=>'FileApprovalController@Ajax'));
Route::get('/approval/daftar/ajax2/{id}',array('as'=>'approval/daftar.ajax2','uses'=>'FileApprovalController@myformAjax2'));

// Pencarian Report Dashboard Perencanaan
Route::get('/output/pencarian/ajax/{id}',array('as'=>'output/pencarian.ajax','uses'=>'PencarianReportDashboardController@Ajax'));
Route::get('/output/pencarian/ajax2/{id}',array('as'=>'output/pencarian.ajax2','uses'=>'PencarianReportDashboardController@myformAjax2'));
Route::get('/output/pencarian/ajax_fase',array('as'=>'output/pencarian.ajax_fase','uses'=>'PencarianReportDashboardController@ajax_fase'));
Route::get('/output/pencarian/ajax3/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax3','uses'=>'PencarianReportDashboardController@ajax_draft_rkau'));
Route::get('/output/pencarian/ajax4/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax4','uses'=>'PencarianReportDashboardController@ajax_draft_form_6_reimburse'));
Route::get('/output/pencarian/ajax5/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax5','uses'=>'PencarianReportDashboardController@ajax_draft_form_6_rutin'));
Route::get('/output/pencarian/ajax6/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax6','uses'=>'PencarianReportDashboardController@ajax_draft_form_10_pengembangan_usaha'));
Route::get('/output/pencarian/ajax7/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax7','uses'=>'PencarianReportDashboardController@ajax_draft_form_10_penguatan_kit'));
Route::get('/output/pencarian/ajax8/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax8','uses'=>'PencarianReportDashboardController@ajax_draft_form_10_pln'));
Route::get('/output/pencarian/ajax9/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax9','uses'=>'PencarianReportDashboardController@ajax_draft_form_bahan_bakar'));
Route::get('/output/pencarian/ajax10/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax10','uses'=>'PencarianReportDashboardController@ajax_draft_form_penyusutan'));
Route::get('/output/pencarian/ajax11/{id_lokasi}/{id_tahun}/{id_fase}',array('as'=>'output/pencarian.ajax11','uses'=>'PencarianReportDashboardController@ajax_draft_risk_profile'));

// Pencarian Report Dashboard Pengendalian
//<!--CHANGE 20210921-->
Route::get('/output/pencarian-pengendalian/ajax/{id}',array('as'=>'output/pencarian-pengendalian.ajax','uses'=>'PencarianReportDashboardPengendalianController@Ajax'));
Route::get('/output/pencarian-pengendalian/ajax2/{id}',array('as'=>'output/pencarian-pengendalian.ajax2','uses'=>'PencarianReportDashboardPengendalianController@myformAjax2'));
Route::get('/output/pencarian-pengendalian/ajax_fase',array('as'=>'output/pencarian-pengendalian.ajax_fase','uses'=>'PencarianReportDashboardPengendalianController@ajax_fase'));
Route::get('/output/pencarian-pengendalian/ajax_pencarian/{id_lokasi}/{id_tahun}/{id_fase}/{jenis_id}', array('as' => 'output/pencarian-pengendalian.ajax_pencarian', 'uses' => 'PencarianReportDashboardPengendalianController@ajax_draft_pencarian'));

// 1.1 Risk Profile
Route::get('/output/risk-profile/ajax/{id}',array('as'=>'output/risk-profile.ajax','uses'=>'RiskProfileController@Ajax'));
Route::get('/output/risk-profile/ajax2/{id}',array('as'=>'output/risk-profile.ajax','uses'=>'RiskProfileController@myformAjax2'));
Route::get('/output/risk-profile/ajax3/{jenis}/{lokasi}/{tahun}',array('as'=>'output/risk-profile.ajax','uses'=>'RiskProfileController@myformAjax3'));

// 1.2 Mitigasi resiko (Ajax + Unduh)
Route::get('output/mitigasi-risiko/unduhpdf','MitigasiResikoController@Mitigasi_Resiko');
Route::get('output/mitigasi-risiko/unduhexcel','MitigasiResikoController@Mitigasi_Resiko');
Route::get('/output/mitigasi-risiko/ajax/{id}',array('as'=>'output/mitigasi-risiko.ajax','uses'=>'MitigasiResikoController@Ajax'));
Route::get('/output/mitigasi-risiko/ajax2/{id}',array('as'=>'output/mitigasi-risiko.ajax','uses'=>'MitigasiResikoController@myformAjax2'));
Route::get('/output/mitigasi-risiko/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/mitigasi-risiko.ajax','uses'=>'MitigasiResikoController@ajax_draft_form_6_reimburse'));
Route::get('/output/mitigasi-risiko/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/mitigasi-risiko.ajax','uses'=>'MitigasiResikoController@ajax_draft_form_6_rutin'));
Route::get('/output/mitigasi-risiko/ajax5/{id_lokasi}/{id_tahun}',array('as'=>'output/mitigasi-risiko.ajax','uses'=>'MitigasiResikoController@ajax_draft_form_10_pengembangan_usaha'));
Route::get('/output/mitigasi-risiko/ajax6/{id_lokasi}/{id_tahun}',array('as'=>'output/mitigasi-risiko.ajax','uses'=>'MitigasiResikoController@ajax_draft_form_10_penguatan_kit'));
Route::get('/output/mitigasi-risiko/ajax7/{id_lokasi}/{id_tahun}',array('as'=>'output/mitigasi-risiko.ajax','uses'=>'MitigasiResikoController@ajax_draft_form_10_pln'));
Route::get('/output/mitigasi-risiko/ajax8/{id_lokasi}/{id_tahun}',array('as'=>'output/mitigasi-risiko.ajax','uses'=>'MitigasiResikoController@ajax_draft_form_risk_register'));

// 2.0 Rencana kinerja
Route::get('/output/rencana-kinerja/ajax/{id}',array('as'=>'output/rencana-kinerja.ajax','uses'=>'RencanaKinerjaController@Ajax'));
Route::get('/output/rencana-kinerja/ajax2/{id}',array('as'=>'output/rencana-kinerja.ajax','uses'=>'RencanaKinerjaController@myformAjax2'));
Route::get('/output/rencana-kinerja/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'RencanaKinerjaController@ajax_draft_rkau'));

// 3.0 Program Strategis
Route::get('/output/program-strategis/ajax/{id}',array('as'=>'output/program-strategis.ajax','uses'=>'ProgramStrategisController@Ajax'));
Route::get('/output/program-strategis/ajax2/{id}',array('as'=>'output/program-strategis.ajax','uses'=>'ProgramStrategisController@myformAjax2'));
Route::get('/output/program-strategis/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/program-strategis.ajax','uses'=>'ProgramStrategisController@ajax_draft_form_6_reimburse'));
Route::get('/output/program-strategis/ajax5/{id_lokasi}/{id_tahun}',array('as'=>'output/program-strategis.ajax','uses'=>'ProgramStrategisController@ajax_draft_form_6_rutin'));
Route::get('/output/program-strategis/ajax6/{id_lokasi}/{id_tahun}',array('as'=>'output/program-strategis.ajax','uses'=>'ProgramStrategisController@ajax_draft_form_10_pengembangan_usaha'));
Route::get('/output/program-strategis/ajax7/{id_lokasi}/{id_tahun}',array('as'=>'output/program-strategis.ajax','uses'=>'ProgramStrategisController@ajax_draft_form_10_penguatan_kit'));
Route::get('/output/program-strategis/ajax8/{id_lokasi}/{id_tahun}',array('as'=>'output/program-strategis.ajax','uses'=>'ProgramStrategisController@ajax_draft_form_10_pln'));

// 4.0 LR
Route::get('/output/laba-rugi/ajax/{id}',array('as'=>'output/laba-rugi.ajax','uses'=>'LrController@myformAjax'));
Route::get('/output/laba-rugi/ajax2/{id}',array('as'=>'output/laba-rugi.ajax','uses'=>'LrController@myformAjax2'));
Route::get('/output/laba-rugi/ajax3/{fase_id}/{lokasi_id}/{tahun}',array('as'=>'output/laba-rugi.ajax','uses'=>'LrController@myformAjax3'));

// 5.0 Biaya Pemeliharaan (Ajax + Unduh)
Route::get('/output/biaya-pemeliharaan/Excel','BiayaPemeliharaanController@Biaya_Pemeliharaan');
Route::get('/output/biaya-pemeliharaan/Pdf','BiayaPemeliharaanController@Biaya_Pemeliharaan');
Route::get('/output/biaya-pemeliharaan/ajax/{id}',array('as'=>'output/biaya-pemeliharaan.ajax','uses'=>'BiayaPemeliharaanController@Ajax'));
Route::get('/output/biaya-pemeliharaan/ajax2/{id}',array('as'=>'output/biaya-pemeliharaan.ajax','uses'=>'BiayaPemeliharaanController@myformAjax2'));
Route::get('/output/biaya-pemeliharaan/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/biaya-pemeliharaan.ajax','uses'=>'BiayaPemeliharaanController@ajax_draft_form_6_reimburse'));
Route::get('/output/biaya-pemeliharaan/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/biaya-pemeliharaan.ajax','uses'=>'BiayaPemeliharaanController@ajax_draft_form_6_rutin'));
Route::get('/output/biaya-pemeliharaan/faseAjax1',array('as'=>'output/biaya-pemeliharaan.ajax','uses'=>'BiayaPemeliharaanController@faseAjax1'));
Route::get('/output/biaya-pemeliharaan/faseAjax/{id}',array('as'=>'output/biaya-pemeliharaan.ajax','uses'=>'BiayaPemeliharaanController@faseAjax'));

// 5.1 Biaya Pemeliharaan Rutin (Ajax + Unduh)
Route::get('/output/biaya-pemeliharaan-rutin/Excel','BiayaPemeliharaanRutinController@Biaya_Pemeliharaan');
Route::get('/output/biaya-pemeliharaan-rutin/Pdf','BiayaPemeliharaanRutinController@Biaya_Pemeliharaan');
Route::get('/output/biaya-pemeliharaan-rutin/ajax/{id}',array('as'=>'output/biaya-pemeliharaan-rutin.ajax','uses'=>'BiayaPemeliharaanRutinController@Ajax'));
Route::get('/output/biaya-pemeliharaan-rutin/ajax2/{id}',array('as'=>'output/biaya-pemeliharaan-rutin.ajax','uses'=>'BiayaPemeliharaanRutinController@myformAjax2'));
Route::get('/output/biaya-pemeliharaan-rutin/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/biaya-pemeliharaan-rutin.ajax','uses'=>'BiayaPemeliharaanRutinController@ajax_draft_form_6_reimburse'));
Route::get('/output/biaya-pemeliharaan-rutin/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/biaya-pemeliharaan-rutin.ajax','uses'=>'BiayaPemeliharaanRutinController@ajax_draft_form_6_rutin'));
Route::get('/output/biaya-pemeliharaan-rutin/faseAjax1',array('as'=>'output/biaya-pemeliharaan-rutin.ajax','uses'=>'BiayaPemeliharaanRutinController@faseAjax1'));
Route::get('/output/biaya-pemeliharaan-rutin/faseAjax/{id}',array('as'=>'output/biaya-pemeliharaan-rutin.ajax','uses'=>'BiayaPemeliharaanRutinController@faseAjax'));

// 5.2 Biaya Pemeliharaan Reimburse (Ajax + Unduh)
Route::get('/output/biaya-pemeliharaan-reimburse/Excel','BiayaPemeliharaanReimburseController@Biaya_Pemeliharaan');
Route::get('/output/biaya-pemeliharaan-reimburse/Pdf','BiayaPemeliharaanReimburseController@Biaya_Pemeliharaan');
Route::get('/output/biaya-pemeliharaan-reimburse/ajax/{id}',array('as'=>'output/biaya-pemeliharaan-reimburse.ajax','uses'=>'BiayaPemeliharaanReimburseController@Ajax'));
Route::get('/output/biaya-pemeliharaan-reimburse/ajax2/{id}',array('as'=>'output/biaya-pemeliharaan-reimburse.ajax','uses'=>'BiayaPemeliharaanReimburseController@myformAjax2'));
Route::get('/output/biaya-pemeliharaan-reimburse/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/biaya-pemeliharaan-reimburse.ajax','uses'=>'BiayaPemeliharaanReimburseController@ajax_draft_form_6_reimburse'));
Route::get('/output/biaya-pemeliharaan-reimburse/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/biaya-pemeliharaan-reimburse.ajax','uses'=>'BiayaPemeliharaanReimburseController@ajax_draft_form_6_rutin'));
Route::get('/output/biaya-pemeliharaan-reimburse/faseAjax1',array('as'=>'output/biaya-pemeliharaan-reimburse.ajax','uses'=>'BiayaPemeliharaanReimburseController@faseAjax1'));
Route::get('/output/biaya-pemeliharaan-reimburse/faseAjax/{id}',array('as'=>'output/biaya-pemeliharaan-reimburse.ajax','uses'=>'BiayaPemeliharaanReimburseController@faseAjax'));

// 6.0 Status DMR
Route::get('/output/status-dmr/ajax/{id_lokasi}/{id_tahun}/{id_jenis}',array('as'=>'output/status-dmr.ajax_draft','uses'=>'StatusDmrController@ajax_draft'));
Route::get('/output/status-dmr/ajax2/{id}',array('as'=>'output/status-dmr.ajax','uses'=>'StatusDmrController@myformAjax2'));

// 7.0 Rincian biaya har
Route::get('/output/rincian-biaya-har/ajax/{id}',array('as'=>'output/rincian-biaya-har.ajax','uses'=>'RincianBiayaHarController@Ajax'));
Route::get('/output/rincian-biaya-har/ajax2/{id}',array('as'=>'output/rincian-biaya-har.ajax','uses'=>'RincianBiayaHarController@myformAjax2'));
Route::get('/output/rincian-biaya-har/ajax3/{fase_id}/{lokasi_id}/{tahun}',array('as'=>'output/rincian-biaya-har.ajax','uses'=>'RincianBiayaHarController@myformAjax3'));
Route::get('/output/rincian-biaya-har/ajax4/{fase_id}/{lokasi_id}',array('as'=>'output/rincian-biaya-har.ajax','uses'=>'RincianBiayaHarController@myformAjax4'));
Route::get('/output/rincian-biaya-har/ajax4/{draft}/{lokasi}',array('as'=>'output/rincian-biaya-har.ajax','uses'=>'RincianBiayaHarController@myformAjax4'));

// 8.0 Rincian biaya har reimburse
Route::get('/output/rincian-biaya-har-reimburse/ajax/{id}',array('as'=>'output/rincian-biaya-har-reimburse.ajax','uses'=>'RincianBiayaHarReimburseController@Ajax'));
Route::get('/output/rincian-biaya-har-reimburse/ajax2/{id}',array('as'=>'output/rincian-biaya-har-reimburse.ajax2','uses'=>'RincianBiayaHarReimburseController@myformAjax2'));
Route::get('/output/rincian-biaya-har-reimburse/ajax3/{fase_id}/{lokasi_id}/{tahun}',array('as'=>'output/rincian-biaya-har-reimburse.ajax3','uses'=>'RincianBiayaHarReimburseController@myformAjax3'));

// 9.1 Rincian Penetapan AI
Route::get('/output/rincian-penetapan-ai/ajax/{id}',array('as'=>'output/rincian-penetapan-ai.ajax','uses'=>'RincianPenetapanAiController@Ajax'));
Route::get('/output/rincian-penetapan-ai/ajax2/{id}',array('as'=>'output/rincian-penetapan-ai.ajax','uses'=>'RincianPenetapanAiController@myformAjax2'));
Route::get('/output/rincian-penetapan-ai/ajax3/{id}/{tahun}',array('as'=>'output/rincian-penetapan-ai.ajax','uses'=>'RincianPenetapanAiController@myformAjax3'));

// 9.2 Rincian AI Pengembangan Usaha
Route::get('/output/rincian-pengembangan-usaha/ajax/{id}',array('as'=>'output/rincian-pengembangan-usaha.ajax','uses'=>'RincianPengembanganUsahaController@Ajax'));
Route::get('/output/rincian-pengembangan-usaha/ajax2/{id}',array('as'=>'output/rincian-pengembangan-usaha.ajax','uses'=>'RincianPengembanganUsahaController@myformAjax2'));
Route::get('/output/rincian-pengembangan-usaha/ajax3/{id}/{tahun}',array('as'=>'output/rincian-pengembangan-usaha.ajax','uses'=>'RincianPengembanganUsahaController@myformAjax3'));

// 9.3 Rincian AI Penetapan PLN
Route::get('/output/rincian-penetapan-pln/ajax/{id}',array('as'=>'output/rincian-penetapan-pln.ajax','uses'=>'RincianPenetapanPlnController@Ajax'));
Route::get('/output/rincian-penetapan-pln/ajax2/{id}',array('as'=>'output/rincian-penetapan-pln.ajax','uses'=>'RincianPenetapanPlnController@myformAjax2'));
Route::get('/output/rincian-penetapan-pln/ajax3/{id}/{tahun}',array('as'=>'output/rincian-penetapan-pln.ajax','uses'=>'RincianPenetapanPlnController@myformAjax3'));

// 10 Rincian Biaya Pegawai (Ajax + Unduh)
Route::get('/output/rincian-biaya-pegawai/ajax/{id}',array('as'=>'output/rincian-biaya-pegawai.ajax','uses'=>'RincianBiayaPegawaiController@Ajax'));
Route::get('/output/rincian-biaya-pegawai/ajax2/{id}',array('as'=>'output/rincian-biaya-pegawai.ajax','uses'=>'RincianBiayaPegawaiController@myformAjax2'));
Route::get('/output/rincian-biaya-pegawai/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/rincian-biaya-pegawai.ajax','uses'=>'RincianBiayaPegawaiController@ajax_draft_rkau'));
Route::get('/output/rincian-biaya-pegawai/downloadpdf', 'RincianBiayaPegawaiController@exportToPDF');
Route::get('/output/rincian-biaya-pegawai/downloadexcel', 'RincianBiayaPegawaiController@exportToExcel');

// 11 Rincian Biaya Administrasi (Ajax + Unduh)
Route::get('/output/rincian-biaya-administrasi/ajax/{id}',array('as'=>'output/rincian-biaya-administrasi.ajax','uses'=>'RincianBiayaAdministrasiController@Ajax'));
Route::get('/output/rincian-biaya-administrasi/ajax2/{id}',array('as'=>'output/rincian-biaya-administrasi.ajax','uses'=>'RincianBiayaAdministrasiController@myformAjax2'));
Route::get('/output/rincian-biaya-administrasi/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/rincian-biaya-pegawai.ajax','uses'=>'RincianBiayaAdministrasiController@ajax_draft_rkau'));
Route::get('/output/rincian-biaya-administrasi/downloadpdf', 'RincianBiayaAdministrasiController@exportToPDF');
Route::get('/output/rincian-biaya-administrasi/downloadexcel', 'RincianBiayaAdministrasiController@exportToExcel');

// 12 Rincian Energi Primer
Route::get('/output/rincian-energi-primer/ajax/{id}',array('as'=>'output/rincian-energi-primer.ajax','uses'=>'RincianEnergiPrimerController@Ajax'));
Route::get('/output/rincian-energi-primer/ajax2/{id}',array('as'=>'output/rincian-energi-primer.ajax','uses'=>'RincianEnergiPrimerController@myformAjax2'));
Route::get('/output/rincian-energi-primer/ajax3/{id_strategi_bisnis}/{id_lokasi}/{id_tahun}',array('as'=>'output/rincian-energi-primer.ajax','uses'=>'RincianEnergiPrimerController@ajax_draft'));

// 13 Form Luar Operasi
Route::get('/output/form-luar-operasi/ajax/{id}',array('as'=>'output/form-luar-operasi.ajax','uses'=>'FormLuarOperasiController@Ajax'));
Route::get('/output/form-luar-operasi/ajax2/{id}',array('as'=>'output/form-luar-operasi.ajax','uses'=>'FormLuarOperasiController@myformAjax2'));
Route::get('/output/form-luar-operasi/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/form-luar-operasi.ajax','uses'=>'FormLuarOperasiController@ajax_draft_rkau'));

// 14 Loader Ellipse
Route::get('/output/loader-ellipse/ajax/{id}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@Ajax'));
Route::get('/output/loader-ellipse/ajax2/{id}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@myformAjax2'));
Route::get('/output/loader-ellipse/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@ajax_draft_rkau'));
Route::get('/output/loader-ellipse/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@ajax_draft_form_6_reimburse'));
Route::get('/output/loader-ellipse/ajax5/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@ajax_draft_form_6_rutin'));
Route::get('/output/loader-ellipse/ajax6/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@ajax_draft_form_10_pengembangan_usaha'));
Route::get('/output/loader-ellipse/ajax7/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@ajax_draft_form_10_penguatan_kit'));
Route::get('/output/loader-ellipse/ajax8/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@ajax_draft_form_10_pln'));
Route::get('/output/loader-ellipse/ajax9/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@ajax_draft_form_bahan_bakar'));
Route::get('/output/loader-ellipse/ajax10/{id_lokasi}/{id_tahun}',array('as'=>'output/loader-ellipse.ajax','uses'=>'LoaderEllipseController@ajax_draft_form_penyusutan'));

// 15 List PRK
Route::get('/output/list-prk/ajax/{id}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@Ajax'));
Route::get('/output/list-prk/ajax2/{id}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@myformAjax2'));
Route::get('/output/list-prk/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_rkau'));
Route::get('/output/list-prk/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_6_reimburse'));
Route::get('/output/list-prk/ajax5/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_6_rutin'));
Route::get('/output/list-prk/ajax6/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_10_pengembangan_usaha'));
Route::get('/output/list-prk/ajax7/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_10_penguatan_kit'));
Route::get('/output/list-prk/ajax8/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_10_pln'));
Route::get('/output/list-prk/ajax9/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_bahan_bakar'));
Route::get('/output/list-prk/ajax10/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_penyusutan'));

// 16 Monitoring PRK AO
Route::get('/output/monitoring-prk-ao/ajax/{id}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@Ajax'));

Route::get('/output/monitoring-prk-ao/ajax2/{id}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@myformAjax2'));

Route::get('/output/monitoring-prk-ao/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@ajax_draft_rkau'));

Route::get('/output/monitoring-prk-ao/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@ajax_draft_form_6_reimburse'));

Route::get('/output/monitoring-prk-ao/ajax5/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@ajax_draft_form_6_rutin'));

Route::get('/output/monitoring-prk-ao/ajax6/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@ajax_draft_form_10_pengembangan_usaha'));

Route::get('/output/monitoring-prk-ao/ajax7/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@ajax_draft_form_10_penguatan_kit'));

Route::get('/output/monitoring-prk-ao/ajax8/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@ajax_draft_form_10_pln'));
Route::get('/output/monitoring-prk-ao/ajax9/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@ajax_draft_form_bahan_bakar'));
Route::get('/output/monitoring-prk-ao/ajax10/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ao.ajax','uses'=>'MonitoringPrkAOController@ajax_draft_form_penyusutan'));

// 17 Monitoring PRK AI
Route::get('/output/monitoring-prk-ai/ajax/{id}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@Ajax'));

Route::get('/output/monitoring-prk-ai/ajax2/{id}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@myformAjax2'));

Route::get('/output/monitoring-prk-ai/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@ajax_draft_rkau'));

Route::get('/output/monitoring-prk-ai/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@ajax_draft_form_6_reimburse'));

Route::get('/output/monitoring-prk-ai/ajax5/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@ajax_draft_form_6_rutin'));

Route::get('/output/monitoring-prk-ai/ajax6/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@ajax_draft_form_10_pengembangan_usaha'));

Route::get('/output/monitoring-prk-ai/ajax7/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@ajax_draft_form_10_penguatan_kit'));

Route::get('/output/monitoring-prk-ai/ajax8/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@ajax_draft_form_10_pln'));
Route::get('/output/monitoring-prk-ai/ajax9/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@ajax_draft_form_bahan_bakar'));
Route::get('/output/monitoring-prk-ai/ajax10/{id_lokasi}/{id_tahun}',array('as'=>'output/monitoring-prk-ai.ajax','uses'=>'MonitoringPrkAIController@ajax_draft_form_penyusutan'));

// DMR
Route::get('/dmr/daftar/ajax/{id}',array('as'=>'dmr/daftar.ajax','uses'=>'DmrController@Ajax'));
Route::get('/dmr/daftar/ajax2/{id}',array('as'=>'dmr/daftar.ajax','uses'=>'DmrController@myformAjax2'));
Route::get('dmr/download_attachment/{id}', 'DmrController@download_attachment');
Route::get('dmr/dmr_attachment/{id}', 'DmrController@dmr_attachment');
Route::get('dmr/review_attachment/{id}', 'DmrController@review_attachment');

// Approval DMR
Route::get('/approval_dmr/daftar/ajax/{id}',array('as'=>'approval_dmr/daftar.ajax','uses'=>'ApprovalDmrController@Ajax'));
Route::get('/approval_dmr/daftar/ajax2/{id}',array('as'=>'approval_dmr/daftar.ajax','uses'=>'ApprovalDmrController@myformAjax2'));

// File Import
Route::get('/fileimport/ajax_distrik/{id}',array('as'=>'output/ajax_distrik','uses'=>'FileImportController@ajax_distrik'));

// TOR Search by FFR 
Route::get('/tor/daftar/ajax/{id}',array('as'=>'tor/daftar.ajax','uses'=>'TorController@Ajax'));
Route::get('/tor/daftar/ajax2/{id}',array('as'=>'tor/daftar.ajax','uses'=>'TorController@myformAjax2'));
Route::get('/approval_tor/daftar/ajax/{id}',array('as'=>'approval_tor/daftar.ajax','uses'=>'ApprovalTorController@Ajax'));
Route::get('/approval_tor/daftar/ajax2/{id}',array('as'=>'approval_tor/daftar.ajax','uses'=>'ApprovalTorController@myformAjax2'));

// End of AJAX
// ------------------

// ------------------
// SEARCH
// ------------------
// Lokasi
Route::get('/lokasi/search','LokasiController@search');

// Strategi Bisnis
Route::get('search', 'StrategiBisnisController@search');

// Distrik
Route::get('search','DistrikController@search');

// Unit
Route::get('search','UnitController@search');

// End of SEARCH
// ------------------

// ------------------
// SEND EMAIL
// ------------------
Route::get('approval_dmr/send_email', 'ApprovalDmrController@send_email');
// End of SEND EMAIL
// ------------------

Route::group([
     'middleware' => 'locationRedirect',
], function () {
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

    //Testing Query PBC
    Route::get('query/pbcao',array('as'=>'query/pbcao','uses'=>'QueryPBCAOController@index'));

    //Testing Query MSF900
    Route::get('query/msf900',array('as'=>'query/msf900','uses'=>'QueryMsf900Controller@index'));

    //Testing Query PLJPRK
    Route::get('query/pljprkao',array('as'=>'query/pljprkao','uses'=>'QueryPljprkAoController@index'));
    Route::get('query/pljprkai',array('as'=>'query/pljprkai','uses'=>'QueryPljprkAiController@index'));
    Route::get('query/pljprkaipln',array('as'=>'query/pljprkaipln','uses'=>'QueryPljprkAiPlnController@index'));

    //LOKASI
    Route::get('lokasi/daftar',array('as'=>'lokasi/daftar','uses'=>'LokasiController@index'));
    Route::get('/lokasi/create','LokasiController@create');
    Route::post('/lokasi/create',array('before' => 'csrf',
                                 'uses' => 'LokasiController@create'));
    Route::get('/lokasi/update/{id}','LokasiController@update');
    Route::post('/lokasi/update/{id}',array('before' => 'csrf',
                                    'uses' => 'LokasiController@update'));
    Route::get('/lokasi/delete/{id}','LokasiController@delete');
    Route::get('/lokasi/detail/{id}','LokasiController@detail');
    Route::post('/lokasi/detail/{id}',array('before' => 'csrf',
                                    'uses' => 'LokasiController@detail'));

    /* Manajemen Akses */
    /* Kelola User */
    Route::get('/users/manage', ['as' => 'admin.user.list', 'uses' => 'AdminMemberController@getList']);
    Route::get('/users/create', ['as' => 'admin.user.add.view', 'uses' => 'AdminMemberController@getAddUser']);
    Route::post('/users/create', ['as' => 'admin.user.add.action', 'uses' => 'AdminMemberController@postAddUser']);
    Route::get('/users/edit/{id}', ['as' => 'admin.user.edit.view', 'uses' => 'AdminMemberController@getEditUser']);
    Route::post('/users/edit/{id}', ['as' => 'admin.user.edit.action', 'uses' => 'AdminMemberController@postEditUser']);
    Route::get('/users/view/{id}', ['as' => 'admin.user.view.view', 'uses' => 'AdminMemberController@getViewUser']);
    Route::post('/users/edit_view/{id}', ['as' => 'admin.user.edit.view.action', 'uses' => 'AdminMemberController@postEditViewUser']);
    Route::get('/users/delete/{id}', ['as' => 'user.delete.action', 'uses' => 'AdminMemberController@postDeleteUser']);
    Route::get('/users/delete/{id}/{role_id}', ['as' => 'user.delete.action', 'uses' => 'AdminMemberController@postDeleteUser']);
    Route::get('/users/role/delete/{id_role}/{id_user}', ['as' => 'admin.user.role.delete', 'uses' => 'AdminMemberController@delete_user_role']);
    Route::get('/users/grupdiv/delete/{id_role}/{id_user}', ['as' => 'admin.user.grupdiv.delete', 'uses' => 'AdminMemberController@delete_user_grupdiv']);

    // Modul kelola Group
    Route::get('/roles/manage', ['as' => 'admin.role.list', 'uses' => 'AdminMemberController@getRoleList']);
    Route::get('/roles/create', ['as' => 'admin.role.add.view', 'uses' => 'AdminMemberController@getAddRole']);
    Route::post('/roles/create', ['as' => 'admin.role.add.action', 'uses' => 'AdminMemberController@postAddRole']);
    Route::get('/roles/edit/{id}', ['as' => 'admin.role.edit.view', 'uses' => 'AdminMemberController@getEditRole']);
    Route::post('/roles/edit/{id}', ['as' => 'admin.role.edit.action', 'uses' => 'AdminMemberController@postEditRole']);
    Route::get('/roles/view/{id}', ['as' => 'admin.role.view.view', 'uses' => 'AdminMemberController@getViewRole']);
    Route::get('/roles/delete/{id}', ['as' => 'role.delete.action', 'uses' => 'AdminMemberController@postDeleteRole']);

    Route::post('/roles/add-user', ['as' => 'role.user.add.action', 'uses' => 'AdminMemberController@postAddRoleUser']);
    Route::get('/roles/delete-user/{role_id}/{user_id}', ['as' => 'role.user.delete.action', 'uses' => 'AdminMemberController@postDeleteRoleUser']);

    // Modul kelola Permission
    Route::get('/permission/manage', ['as' => 'admin.permission.list', 'uses' => 'AdminMemberController@getPermissionList']);
    Route::get('/permission/create', ['as' => 'admin.permission.add.view', 'uses' => 'AdminMemberController@getAddPermission']);
    Route::post('/permission/create', ['as' => 'admin.permission.add.action', 'uses' => 'AdminMemberController@postAddPermission']);
    Route::get('/permission/edit/{id}', ['as' => 'admin.permission.edit.view', 'uses' => 'AdminMemberController@getEditPermission']);
    Route::post('/permission/edit/{id}', ['as' => 'admin.permission.edit.action', 'uses' => 'AdminMemberController@postEditPermission']);
    Route::get('/permission/view/{id}', ['as' => 'admin.permission.view.view', 'uses' => 'AdminMemberController@getViewPermission']);
    Route::get('/permission/delete/{id}', ['as' => 'permission.delete.action', 'uses' => 'AdminMemberController@postDeletePermission']);
    Route::post('/permission/add-role', ['as' => 'permission.role.add.action', 'uses' => 'AdminMemberController@postAddPermissionRole']);
    Route::get('/permission/delete-role/{permission_id}/{role_id}', ['as' => 'permission.role.delete.action', 'uses' => 'AdminMemberController@postDeletePermissionRole']);
    /*end of Manajemen Akses*/

    //STRATEGI BISNIS
    Route::get('daftar_strategi_bisnis', ['as' => 'strategi_bisnis.daftar', 'uses' => 'StrategiBisnisController@index']);
    Route::get('detail_strategi_bisnis/{id}', 'StrategiBisnisController@detail');
    Route::get('tambah_strategi_bisnis', 'StrategiBisnisController@create');
    Route::post('tambah_strategi_bisnis', array('before' => 'csrf',
                                        'uses' => 'StrategiBisnisController@create'));
    Route::get('update_strategi_bisnis/{id}', 'StrategiBisnisController@update');
    Route::post('update_strategi_bisnis/{id}', array('before' => 'csrf',
                                            'uses' => 'StrategiBisnisController@update'));
    Route::get('delete_strategi_bisnis/{id}', 'StrategiBisnisController@delete');

    //DISTRIK
    Route::get('/distrik/daftar', ['as' => 'distrik.daftar', 'uses' => 'DistrikController@index']);
    Route::get('/distrik/create', 'DistrikController@create');
    Route::post('/distrik/create', array('before' => 'csrf',
                                        'uses' => 'DistrikController@create'));
    Route::get('/distrik/update/{id}', 'DistrikController@update');
    Route::post('/distrik/update/{id}', array('before' => 'csrf',
                                            'uses' => 'DistrikController@update'));
    Route::get('/distrik/delete/{id}', 'DistrikController@delete');
    Route::get('/distrik/detail/{id}', 'DistrikController@detail');
    Route::post('/distrik/detail/{id}', array('before' => 'csrf',
                                            'uses' => 'DistrikController@detail'));

    // Entitas
    Route::get('entitas/daftar',array('as'=>'entitas/daftar','uses'=>'EntitasController@index'));
    Route::get('entitas/create', 'EntitasController@tambah');
    Route::get('entitas/unit', 'EntitasController@unit');
    Route::post('entitas/create', array('before' => 'csrf', 'uses' => 'EntitasController@tambah'));
    Route::get('entitas/update/{id}', 'EntitasController@edit');
    Route::post('entitas/update/{id}', array('before' => 'csrf', 'uses' => 'EntitasController@edit'));
    Route::get('entitas/detail/{id}', 'EntitasController@detail');
    Route::post('entitas/detail/{id}', array('before' => 'csrf', 'uses' => 'EntitasController@detail'));
    Route::get('entitas/delete/{id}', 'EntitasController@delete');

    //UNIT
    Route::get('daftar_unit',array('as'=>'unit/daftar','uses'=>'UnitController@index'));
    Route::get('tambah_unit', 'UnitController@create');
    Route::post('tambah_unit', array('before' => 'csrf', 'uses' => 'UnitController@create'));
    Route::get('update_unit/{id}', 'UnitController@update');
    Route::post('update_unit/{id}', array('before' => 'csrf', 'uses' => 'UnitController@update'));
    Route::get('delete_unit/{id}', 'UnitController@delete');
    Route::get('detail_unit', 'UnitController@detail');

    //RISK_PROFILE
    //(Tidak jadi dipakai)
    // Route::get('risk_profile', 'RiskProfileController@index');
    // Route::get('tambah_risk_profile', 'RiskProfileController@create');
    // Route::post('tambah_risk_profile', array('before' => 'csrf',
    //                                  'uses' => 'RiskProfileController@create'));
    // Route::get('update_risk_profile/{id}', 'RiskProfileController@update');
    // Route::post('update_risk_profile/{id}', array('before' => 'csrf',
    //                                      'uses' => 'RiskProfileController@update'));
    // Route::get('delete_risk_profile/{id}', 'RiskProfileController@delete');

    //PRK
    //(Tidak jadi dipakai)
    // Route::get('prk/daftar','PrkController@index');
    // Route::get('prk/create','PrkController@tambah');
    // Route::post('prk/create',array('before' => 'csrf', 'uses' => 'PrkController@tambah'));
    // Route::get('prk/update/{id}','PrkController@update');
    // Route::post('prk/update/{id}',array('before' => 'csrf', 'uses' => 'PrkController@update'));
    // Route::get('prk/delete/{id}','PrkController@delete');

    // PRK Parent
    Route::get('prk_parent/daftar',array('as'=>'prk_parent/daftar','uses'=>'PrkParentController@index'));
    Route::get('prk_parent/create','PrkParentController@create');
    Route::post('prk_parent/create',array('before' => 'csrf', 'uses' => 'PrkParentController@create'));
    Route::get('prk_parent/update/{id}','PrkParentController@update');
    Route::post('prk_parent/update/{id}',array('before' => 'csrf', 'uses' => 'PrkParentController@update'));
    Route::get('prk_parent/delete/{id}','PrkParentController@delete');

    //PRK Inti
    Route::get('prkinti/daftar',array('as'=>'prkinti/daftar','uses'=>'PrkIntiController@index'));
    Route::get('prkinti/create','PrkIntiController@tambah');
    Route::post('prkinti/create',array('before' => 'csrf', 'uses' => 'PrkIntiController@tambah'));
    Route::get('prkinti/update/{id}','PrkIntiController@update');
    Route::post('prkinti/update/{id}',array('before' => 'csrf', 'uses' => 'PrkIntiController@update'));
    Route::get('prkinti/delete/{id}','PrkIntiController@delete');

    // Master Approval
    Route::get('approval/daftar',array('as'=>'masterapproval/daftar','uses'=>'ApprovalController@index'));
    Route::get('approval/create', 'ApprovalController@create');
    Route::post('approval/create', array('before' => 'csrf', 'uses' => 'ApprovalController@create'));
    Route::get('approval/update/{id}', 'ApprovalController@update');
    Route::post('approval/update/{id}', array('before' => 'csrf', 'uses' => 'ApprovalController@update'));
    Route::get('approval/delete/{id}', 'ApprovalController@delete');

    //RISK PROFILE
    //(Tidak jadi dipakai)
    // Route::get('rencana_kerja', 'RencanaKerjaController@index');

    // Route::get('tambah_rencana_kerja', 'RencanaKerjaController@create');
    // Route::post('tambah_rencana_kerja', array('before' => 'csrf',
    //                                  'uses' => 'RencanaKerjaController@create'));
    // Route::get('update_rencana_kerja/{id}', 'RencanaKerjaController@update');
    // Route::post('update_rencana_kerja/{id}', array('before' => 'csrf',
    //                                      'uses' => 'RencanaKerjaController@update'));
    // Route::get('delete_rencana_kerja/{id}', 'RencanaKerjaController@delete');

    //ALOKASI
    //(Tidak jadi dipakai)
    // Route::get('alokasi', 'AlokasiController@index');
    // Route::get('tambah_alokasi', 'AlokasiController@create');
    // Route::post('tambah_alokasi', array('before' => 'csrf',
    //                                  'uses' => 'AlokasiController@create'));
    // Route::get('update_alokasi/{id}', 'AlokasiController@update');
    // Route::post('update_alokasi/{id}', array('before' => 'csrf',
    //                                      'uses' => 'AlokasiController@update'));
    // Route::get('delete_alokasi/{id}', 'AlokasiController@delete');

    //JENIS BAHAN BAKAR
    //(Tidak jadi dipakai)
    // Route::get('bahanbakar/daftar', 'JenisBahanBakarController@index');
    // Route::get('bahanbakar/create', 'JenisBahanBakarController@tambah');
    // Route::post('bahanbakar/create', array('before' => 'csrf', 'uses' => 'JenisBahanBakarController@tambah'));
    // Route::get('bahanbakar/update/{id}', 'JenisBahanBakarController@edit');
    // Route::post('bahanbakar/update/{id}', array('before' => 'csrf', 'uses' => 'JenisBahanBakarController@edit'));
    // Route::get('bahanbakar/delete/{id}', 'JenisBahanBakarController@delete');    Route::resource('rkap', 'RkapController');

    //FASE
    //(Tidak jadi dipakai)
    // Route::resource('fase', 'FaseController');

    //Perencanaan
    Route::group([
        'prefix' => '/{jenis_id}/',
    ], function () {
        Route::resource('template', 'TemplateController');
    });

    //Pengendalian
    Route::group([
        'prefix' => '/{jenis_id}/',
    ], function () {
        Route::resource('templatepengendalian', 'TemplatePengendalianController');

        // Route::resource('sheetpengendalian', 'SheetPengendalianController');
        // Route::put('sheetpengendalian/update', 'SheetPengendalianController@update');
        // Route::put('sheet/update/{id}', ['as' => 'sheetpengendalian.update', 'uses' => 'SheetPengendalianController@update']);
        // Route::get('sheetpengendalian/update_setting/{id}', 'SheetPengendalianController@edit');

    });


    Route::group([
        'prefix' => '/version/{version_id}',
    ], function () {
        Route::resource('sheet', 'SheetController');
        Route::post('/sheet/use', ['as' => 'sheet.save', 'uses' => 'SheetController@sheet_save']);
        Route::get('/sheet/setting/{id}', ['as' => 'sheet.setting', 'uses' => 'SheetController@setting']);
        Route::post('/sheet/import', ['as' => 'sheet.import', 'uses' => 'SheetController@import']);

		// ------------------- Pengendalian

        Route::resource('sheetpengendalian', 'SheetPengendalianController');
        Route::post('/sheetpengendalian/use', ['as' => 'sheetpengendalian.save', 'uses' => 'SheetPengendalianController@sheet_save']);
        Route::get('/sheetpengendalian/setting/{id}', ['as' => 'sheetpengendalian.setting', 'uses' => 'SheetPengendalianController@setting']);
        Route::post('/sheetpengendalian/import', ['as' => 'sheetpengendalian.import', 'uses' => 'SheetPengendalianController@import']);
        Route::get('/fileimportpengendalian/show/{id}/{sheet_id}', ['as' => 'fileimportpengendalian.showsheet', 'uses' => 'FileImportPengendalianController@show_sheet']);

        // -------------------

        Route::resource('fileimport', 'FileImportController');

        Route::get('/fileimport/show/{id}/{sheet_id}', ['as' => 'fileimport.showsheet', 'uses' => 'FileImportController@show_sheet']);
        Route::get('/fileimport/edit/{id}/{sheet_id}', ['as' => 'fileimport.editimport', 'uses' => 'FileImportController@edit_import']);
        Route::post('/fileimport/import/{id}', ['as' => 'fileimport.import', 'uses' => 'FileImportController@import']);
        Route::post('/sheet/import/use/{id}', ['as' => 'fileimport.import.use', 'uses' => 'FileImportController@import_use']);
        Route::put('/fileimport/update/{id}/{sheet_id}', ['as' => 'fileimport.updateimport', 'uses' => 'FileImportController@import_update']);
        Route::get('/sheet/export/use/{id}', ['as' => 'fileimport.export.use', 'uses' => 'FileImportController@export_use']);
        Route::get('/sheet/export/use/', 'FileImportController@export_use_null');
        Route::post('/sheet/export/{id}', ['as' => 'fileimport.export', 'uses' => 'FileImportController@export']);
        Route::get('download/{id}', 'FileImportController@download');


        // Route untuk detail Pengendalian
        Route::resource('fileimportpengendalian', 'FileImportPengendalianController');
        Route::post('/sheetpengendalian/import/use/{id}', ['as' => 'fileimportpengendalian.import.use', 'uses' => 'FileImportPengendalianController@import_use']);
        Route::post('/fileimportpengendalian/import/{id}', ['as' => 'fileimportpengendalian.import', 'uses' => 'FileImportPengendalianController@import']);

        // Route untuk add data Pengendalian
        Route::get('/fileimportpengendalian/add','FileImportPengendalianController@adddatanull');
        Route::get('/fileimportpengendalian/add/{id}', ['as' => 'fileimportpengendalian.add.data', 'uses' => 'FileImportPengendalianController@adddata'])->where('id', '[0-9]+');
        Route::post('/sheetpengendalian/import/use/add/excel/{id}', ['as' => 'fileimportpengendalian.import.use.add.excel', 'uses' => 'FileImportPengendalianController@import_use_add_excel']);
        Route::post('/fileimportpengendalian/import/add/excel/{id}', ['as' => 'fileimportpengendalian.import.add.excel', 'uses' => 'FileImportPengendalianController@import_add_excel']);

        // Route untuk update data Pengendalian
        Route::get('/fileimportpengendalian/update/{id}', ['as' => 'fileimportpengendalian.updatedata', 'uses' => 'FileImportPengendalianController@updatedata']);
        Route::post('/sheetpengendalian/import/use/update/excel/{id}', ['as' => 'fileimportpengendalian.import.use.update.excel', 'uses' => 'FileImportPengendalianController@import_use_update_excel']);
        Route::post('/fileimportpengendalian/import/update/excel/{id}', ['as' => 'fileimportpengendalian.import.update.excel', 'uses' => 'FileImportPengendalianController@import_update_excel']);

        // Route untuk show sheet
        Route::get('/fileimportpengendalian/show/{id}/{sheet_id}', ['as' => 'fileimportpengendalian.showsheet', 'uses' => 'FileImportPengendalianController@show_sheet']);
        Route::get('/fileimportpengendalian/edit/{id}/{sheet_id}', ['as' => 'fileimportpengendalian.editimport', 'uses' => 'FileImportPengendalianController@edit_import']);
        Route::put('/fileimportpengendalian/update/{id}/{sheet_id}', ['as' => 'fileimportpengendalian.updateimport', 'uses' => 'FileImportPengendalianController@import_update']);

        // Route untuk download processed excel
        Route::get('/sheetpengendalian/export/use/{id}', ['as' => 'fileimportpengendalian.export.use', 'uses' => 'FileImportPengendalianController@export_use']);
        Route::get('/sheetpengendalian/export/use/', 'FileImportPengendalianController@export_use_null');
        Route::post('/sheetpengendalian/export/{id}', ['as' => 'fileimportpengendalian.export', 'uses' => 'FileImportPengendalianController@export']);
        Route::get('downloadpengendalian/{id}', 'FileImportPengendalianController@download');

    });

    // History edit file langusng dari sistem
    Route::get('/history/{id}', ['as' => 'history.index', 'uses' => 'HistoryController@index']);

    // Approval Form
    Route::get('assignment', ['as' => 'assignment', 'uses' => 'FileApprovalController@assignment']);
    Route::get('approval/daftar_per_jenis/{jenis_id}', ['as' => 'approval.daftar_per_jenis', 'uses' => 'FileApprovalController@index']);
    Route::get('approval/detail/{tahun_anggaran_id}/{lokasi_id}/{jenis_id}/{fase_id}/{id}', 'FileApprovalController@detail');
    Route::post('approval/detail/{tahun_anggaran_id}/{lokasi_id}/{jenis_id}/{fase_id}/{id}', array('before' => 'csrf', 'uses' => 'FileApprovalController@detail'));
    Route::get('approval/detail_ketetapan_selain_usulan_unit/{tahun_anggaran_id}/{lokasi_id}/{jenis_id}/{fase_id}/{id}', 'FileApprovalController@detail_ketetapan_selain_usulan_unit');
    Route::get('approval/delete/{id}', 'FileApprovalController@delete');

	//DMR
    Route::get('dmr/daftar', ['as' => 'dmr.daftar', 'uses' => 'DmrController@index']);
    Route::get('dmr/create', 'DmrController@create');
    Route::post('dmr/create', array('before' => 'csrf', 'uses' => 'DmrController@create'));
    Route::get('dmr/update/{id}', 'DmrController@update');
    Route::post('dmr/update/{id}', array('before' => 'csrf', 'uses' => 'DmrController@update'));
    Route::get('dmr/detail/{id}', 'DmrController@detail');
    Route::post('dmr/detail/{id}', array('before' => 'csrf', 'uses' => 'DmrController@detail'));
    Route::get('dmr/delete/{id}', 'DmrController@delete');
	
    //APPROVAL DMR
	Route::get('approval_dmr/daftar', ['as' => 'approval_dmr.daftar', 'uses' => 'ApprovalDmrController@index']);
    Route::get('approval_dmr/publish', ['as' => 'approval_dmr.publish', 'uses' => 'ApprovalDmrController@publish']);
	Route::get('approval_dmr/detail/{id}', 'ApprovalDmrController@detail');
	Route::post('approval_dmr/detail/{id}',array('before' => 'csrf',
	                                                'uses' => 'ApprovalDmrController@detail'));
	Route::get('approval_dmr/approval/{id}', 'ApprovalDmrController@approval');
	Route::post('approval_dmr/approval/{id}',array('before' => 'csrf',
	                                                'uses' => 'ApprovalDmrController@approval'));

    // Pencarian Report Dashboard Perencanaan
    Route::get('/output/pencarian',array('as'=>'output/pencarian','uses'=>'PencarianReportDashboardController@pencarian'));

    // Pencarian Report Dashboard Pengendalian
    Route::get('/output/pencarian-pengendalian',array('as'=>'output/pencarian-pengendalian','uses'=>'PencarianReportDashboardPengendalianController@pencarian'));

    // Summary Form
    Route::get('/output/summary',array('as'=>'output/summary','uses'=>'SummaryFormController@summary'));
    Route::post('/output/summary',array('as'=>'output/summary', 'uses'=>'SummaryFormController@summary'));

	//TOR
    Route::get('tor/daftar', ['as' => 'tor.daftar', 'uses' => 'TorController@index']);
    Route::get('tor_published/daftar', ['as' => 'tor_published.daftar', 'uses' => 'TorController@publish']);
    Route::get('tor/create', 'TorController@create');
    Route::post('tor/create', array('before' => 'csrf', 'uses' => 'TorController@create'));
    Route::get('tor/update/{id}', 'TorController@update');
    Route::post('tor/update/{id}', array('before' => 'csrf', 'uses' => 'TorController@update'));
    Route::get('tor/detail/{id}', 'TorController@detail');
    Route::post('tor/detail/{id}', array('before' => 'csrf', 'uses' => 'TorController@detail'));
    Route::get('tor/delete/{id}', 'TorController@delete');
    Route::get('tor/download_attachment/{id}', 'TorController@download_attachment');
	
	//APPROVAL TOR
	Route::get('approval_tor/daftar', ['as' => 'approval_tor.daftar', 'uses' => 'ApprovalTorController@index']);
	Route::get('approval_tor/detail/{id}', 'ApprovalTorController@detail');
	Route::post('approval_tor/detail/{id}',array('before' => 'csrf',
	                                                'uses' => 'ApprovalTorController@detail'));
	Route::get('approval_tor/approval/{id}', 'ApprovalTorController@approval');
	Route::post('approval_tor/approval/{id}',array('before' => 'csrf',
	                                                'uses' => 'ApprovalTorController@approval'));
	
    // 1.1 Risk Profile
    Route::get('/output/risk-profile',array('as'=>'output/risk-profile','uses'=>'RiskProfileController@Risk_Profile'));

    // 1.2 Mitigasi resiko
    Route::get('/output/mitigasi-risiko',array('as'=>'output/mitigasi-risiko','uses'=>'MitigasiResikoController@Mitigasi_Resiko'));

    // 2.0 Rencana kinerja
    Route::get('/output/rencana-kinerja',array('as'=>'output/rencana-kinerja','uses'=>'RencanaKinerjaController@Rencana_Kinerja'));

    // 3.0 Program Strategis
    Route::get('/output/program-strategis',array('as'=>'output/program-strategis','uses'=>'ProgramStrategisController@Program_Strategis'));

    // 4.0 LR
    Route::get('/output/laba-rugi',array('as'=>'output/laba-rugi','uses'=>'LrController@LR'));

    // 5.0 Biaya Pemeliharaan
    Route::get('/output/biaya-pemeliharaan',array('as'=>'output/biaya-pemeliharaan','uses'=>'BiayaPemeliharaanController@Biaya_Pemeliharaan'));

    // 5.1 Biaya Pemeliharaan Rutin
    Route::get('/output/biaya-pemeliharaan-rutin',array('as'=>'output/biaya-pemeliharaan-rutin','uses'=>'BiayaPemeliharaanRutinController@Biaya_Pemeliharaan'));

    // 5.2 Biaya Pemeliharaan Reimburse
    Route::get('/output/biaya-pemeliharaan-reimburse',array('as'=>'output/biaya-pemeliharaan-reimburse','uses'=>'BiayaPemeliharaanReimburseController@Biaya_Pemeliharaan'));

    // 6.0 Status DMR
    Route::get('/output/status-dmr',array('as'=>'output/status-dmr','uses'=>'StatusDmrController@Status_Dmr'));

    // 7.0 Rincian biaya har
    Route::get('/output/rincian-biaya-har',array('as'=>'output/rincian-biaya-har','uses'=>'RincianBiayaHarController@Rincian_Biaya_Har'));

    // 8.0 Rincian biaya har reimburse
    Route::get('/output/rincian-biaya-har-reimburse',array('as'=>'output/rincian-biaya-har-reimburse','uses'=>'RincianBiayaHarReimburseController@Rincian_Biaya_Har_Reimburse'));

    // 9.1 Rincian Penetapan AI
    Route::get('/output/rincian-penetapan-ai',array('as'=>'output/rincian-penetapan-ai','uses'=>'RincianPenetapanAiController@Rincian_Penetapan_Ai'));

    //Route::get('/output/rincian-penetapan-ai/downloadexcel', 'RincianBiayaAdministrasiController@exportToExcel');

    // 9.2 Rincian AI Pengembangan Usaha
    Route::get('/output/rincian-pengembangan-usaha',array('as'=>'output/rincian-pengembangan-usaha','uses'=>'RincianPengembanganUsahaController@Rincian_Pengembangan_Usaha'));

    // 9.3 Rincian AI Penetapan PLN
    Route::get('/output/rincian-penetapan-pln',array('as'=>'output/rincian-penetapan-pln','uses'=>'RincianPenetapanPlnController@Rincian_penetapan_Pln'));

    // 10 Rincian Biaya Pegawai
    Route::get('/output/rincian-biaya-pegawai',array('as'=>'output/rincian-biaya-pegawai','uses'=>'RincianBiayaPegawaiController@Rincian_Biaya_Pegawai'));

    // 11 Rincian Biaya Administrasi
    Route::get('/output/rincian-biaya-administrasi',array('as'=>'output/rincian-biaya-administrasi','uses'=>'RincianBiayaAdministrasiController@Rincian_Biaya_Administrasi'));

    // 12 Rincian Energi Primer
    Route::get('/output/rincian-energi-primer',array('as'=>'output/rincian-energi-primer','uses'=>'RincianEnergiPrimerController@Rincian_Energi_Primer'));

    // 13 Form Luar Operasi
    Route::get('/output/form-luar-operasi',array('as'=>'output/form-luar-operasi','uses'=>'FormLuarOperasiController@Form_luar_operasi'));

    // 14 Loader Ellipse
    Route::get('/output/loader-ellipse',array('as'=>'output/loader-ellipse','uses'=>'LoaderEllipseController@Loader_Ellipse'));

    // 15 List PRK
    Route::get('/output/list-prk',array('as'=>'output/list-prk','uses'=>'ListPrkController@List_Prk'));

    // 16 Monitoring PRK AO
    Route::get('/output/monitoring-prk-ao',array('as'=>'output/monitoring-prk-ao','uses'=>'MonitoringPrkAOController@Monitoring_PRK_AO'));

    // 17 Monitoring PRK AI
    Route::get('/output/monitoring-prk-ai',array('as'=>'output/monitoring-prk-ai','uses'=>'MonitoringPrkAIController@Monitoring_PRK_AI'));

    // 18 Loader Ellipse Pengendalian
    Route::get('/output/loader-ellipse-pgdl',array('as'=>'output/loader-ellipse-pgdl','uses'=>'LoaderEllipsePengendalianController@Loader_Ellipse'));

    //  Route::get('/output/list-prk',array('as'=>'output/list-prk','uses'=>'ListPrkController@List_Prk'));

    //  Route::get('/output/list-prk/ajax/{id}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@Ajax'));

    //  Route::get('/output/list-prk/ajax2/{id}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@myformAjax2'));

    //  Route::get('/output/list-prk/ajax3/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_rkau'));

    // Route::get('/output/list-prk/ajax4/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_6_reimburse'));

    // Route::get('/output/list-prk/ajax5/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_6_rutin'));

    // Route::get('/output/list-prk/ajax6/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_10_pengembangan_usaha'));

    // Route::get('/output/list-prk/ajax7/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_10_penguatan_kit'));

    // Route::get('/output/list-prk/ajax8/{id_lokasi}/{id_tahun}',array('as'=>'output/list-prk.ajax','uses'=>'ListPrkController@ajax_draft_form_10_pln'));

    // Route::get('/output/list-prk/downloadexcel', 'ListPrkController@exportToExcel');

    // Dashboard LR Pengendalian
	Route::get('/output/pengendalian/lr', 'Pengendalian\LRController@index');
	Route::get('/output/pengendalian/lr/downloadexcel', 'Pengendalian\LRController@exportToExcel');

    // Dashboard AI PJB Pengendalian
	Route::get('/output/pengendalian/ai_pjb', 'Pengendalian\AIPJBController@index');
	Route::get('/output/pengendalian/ai_pjb/downloadexcel', 'Pengendalian\AIPJBController@exportToExcel');

    // Dashboard History Log Pengendalian
	Route::get('/output/pengendalian/history_log/ai', 'Pengendalian\HistoryLogController@indexai');
	Route::get('/output/pengendalian/history_log/ao', 'Pengendalian\HistoryLogController@indexao');
	Route::get('/output/pengendalian/history_log/downloadexcel', 'Pengendalian\HistoryLogController@exportToExcel');

	Route::get('/output/pengendalian/rekap_lr', 'Pengendalian\RekapLRController@index');
	Route::get('/output/pengendalian/rekap_lr/downloadexcel', 'Pengendalian\RekapLRController@exportToExcel');

	Route::get('/output/pengendalian/monitoring_prk_ai_pu_pk', 'Pengendalian\PRKAIPUPKController@index');
	Route::get('/output/pengendalian/monitoring_prk_ai_pu_pk/downloadexcel', 'Pengendalian\PRKAIPUPKController@exportToExcel');

	Route::get('/output/pengendalian/monitoring_prk_ai_pln_rei', 'Pengendalian\PRKAIPLNReiController@index');
	Route::get('/output/pengendalian/monitoring_prk_ai_pln_rei/downloadexcel', 'Pengendalian\PRKAIPLNReiController@exportToExcel');

	Route::get('/pengendalian/input_realisasi_produksi_penjualan', ['as' => 'pgdl.input.realisasiproduksipenjualan', 'uses' => 'Pengendalian\LRInputProduksiPenjualanController@index']);
	Route::post('/pengendalian/input_realisasi_produksi_penjualan', 'Pengendalian\LRInputProduksiPenjualanController@store');

	// input status AI PJB
	Route::get('/pengendalian/input_status_ai_pjb', ['as' => 'pgdl.input.statusaipjb', 'uses' => 'Pengendalian\InputStatusAIPJBController@index']);
	Route::post('/pengendalian/input_status_ai_pjb', 'Pengendalian\InputStatusAIPJBController@store');

	// Input Report & Dashboard Dinamis
	Route::get('/pengendalian/input_report_dashboard_dinamis', ['as' => 'pgdl.input.reportdashboard', 'uses' => 'Pengendalian\ReportDashboardDinamisController@index']);
	Route::post('/pengendalian/input_report_dashboard_dinamis', 'Pengendalian\ReportDashboardDinamisController@store');

	// Kode Parent dan Pos LR
	Route::get('/pengendalian/input_kode_parent_pos_lr',
		['as' => 'pgdl.input.kodeparentLR', 'uses' => 'Pengendalian\KodeParentPosLRController@index']);
    Route::post('/pengendalian/input_kode_parent_pos_lr', 'Pengendalian\KodeParentPosLRController@store');
    

    // Tambahan CR DMR dan Tor Maret 2020

        Route::post('dmr/daftar', ['as' => 'dmr.daftar', 'uses' => 'DmrController@index']);

        Route::get('dmr/daftar/excel', 'DmrController@exportToExcel');

        Route::get('output/tor.ajax', 'TorController@tor_ajax');

        Route::get('/output/summary/dmr',array('as'=>'dmr.summary','uses'=>'SummaryDmrController@index'));
        Route::post('/output/summary/dmr',array('as'=>'dmr.summary', 'uses'=>'SummaryDmrController@index'));

        // Route untuk update all prk dan anggaran dmr
        Route::get('update/all/dmr', 'DmrController@updateAll');



        // Change request form input bahan bakar Mei 2021
        Route::get('download/excel/form_bahan_bakar/{id}', 'FileInputBahanBakar\FileInputBahanBakarController@exportExcel')->name('excel_detail_form_bahan_bakar');
        Route::get('form_bahan_bakar/download/{id}', 'FileInputBahanBakar\FileInputBahanBakarController@download');
        Route::resource('form_bahan_bakar', 'FileInputBahanBakar\FileInputBahanBakarController')->except(['edit', 'update']);



    // KKP ALL * JUNI 2023
    Route::get('/kkp/daftar/ajax/{id}',array('as'=>'dmr/daftar.ajax','uses'=>'KkpController@Ajax'));
	Route::get('/kkp/daftar/ajax2/{id}',array('as'=>'dmr/daftar.ajax','uses'=>'KkpController@myformAjax2'));
	Route::get('kkp/download_attachment/{id}', 'KkpController@download_attachment');
	Route::get('kkp/dmr_attachment/{id}', 'KkpController@dmr_attachment');
	Route::get('kkp/review_attachment/{id}', 'KkpController@review_attachment');

	Route::get('kkp/daftar', ['as' => 'kkp.daftar', 'uses' => 'KkpController@index']);
    Route::get('kkp/create', 'KkpController@create');
    Route::post('kkp/create', array('before' => 'csrf', 'uses' => 'KkpController@create'));
    Route::get('kkp/update/{id}', 'KkpController@update');
    Route::post('kkp/update/{id}', array('before' => 'csrf', 'uses' => 'KkpController@update'));
    Route::get('kkp/detail/{id}', 'KkpController@detail');
    Route::post('kkp/detail/{id}', array('before' => 'csrf', 'uses' => 'KkpController@detail'));
    Route::get('kkp/set_appr/{id}', 'KkpController@setappr');
    Route::post('kkp/set_appr/{id}', array('before' => 'csrf', 'uses' => 'KkpController@setappr'));
    Route::get('kkp/delete/{id}', 'KkpController@delete');

    Route::post('kkp/daftar', ['as' => 'kkp.daftar', 'uses' => 'KkpController@index']);

    Route::get('kkp/daftar/excel', 'KkpController@exportToExcel');

    Route::get('/output/summary/kkp',array('as'=>'kkp.summary','uses'=>'SummaryKkpController@index'));
    Route::post('/output/summary/kkp',array('as'=>'kkp.summary', 'uses'=>'SummaryKkpController@index'));



    //APPROVAL KKP JUNI 2023

	Route::get('/approval_kkp/daftar/ajax/{id}',array('as'=>'approval_kkp/daftar.ajax','uses'=>'ApprovalKkpController@Ajax'));
	Route::get('/approval_kkp/daftar/ajax2/{id}',array('as'=>'approval_kkp/daftar.ajax','uses'=>'ApprovalKkpController@myformAjax2'));

	Route::get('approval_kkp/daftar', ['as' => 'approval_kkp.daftar', 'uses' => 'ApprovalKkpController@index']);
    Route::get('approval_kkp/publish', ['as' => 'approval_kkp.publish', 'uses' => 'ApprovalKkpController@publish']);
	Route::get('approval_kkp/detail/{id}', 'ApprovalKkpController@detail');
	Route::post('approval_kkp/detail/{id}',array('before' => 'csrf',
	                                                'uses' => 'ApprovalKkpController@detail'));
	Route::get('approval_kkp/approval/{id}', 'ApprovalKkpController@approval');
	Route::post('approval_kkp/approval/{id}',array('before' => 'csrf',
	                                                'uses' => 'ApprovalKkpController@approval'));

	Route::get('approval_kkp/send_email', 'ApprovalKkpController@send_email');



	// MASTER STATUS APPROVAL KKP *AGUSTUS 2023
	Route::get('/status_appr/daftar/ajax/{id}',array('as'=>'status_appr/daftar.ajax','uses'=>'StatusApprController@Ajax'));
	Route::get('/status_appr/daftar/ajax2/{id}',array('as'=>'status_appr/daftar.ajax','uses'=>'StatusApprController@myformAjax2'));

	// Route::get('/fileimport/ajax_distrik/{id}',array('as'=>'output/ajax_distrik','uses'=>'FileImportController@ajax_distrik'));

	Route::get('search','StatusApprController@search'); 

    Route::get('/status_appr/daftar', ['as' => 'status_appr.daftar', 'uses' => 'StatusApprController@index']);
    Route::get('/status_appr/create', 'StatusApprController@create');
    Route::post('/status_appr/create', array('before' => 'csrf',
                                        'uses' => 'StatusApprController@create'));
    Route::get('/status_appr/update/{id}', 'StatusApprController@update');
    Route::post('/status_appr/update/{id}', array('before' => 'csrf',
                                            'uses' => 'StatusApprController@update'));
    Route::get('/status_appr/delete/{id}', 'StatusApprController@delete');
    Route::get('/status_appr/detail/{id}', 'StatusApprController@detail');
    Route::post('/status_appr/detail/{id}', array('before' => 'csrf',
                                            'uses' => 'StatusApprController@detail'));


    // MASTER CONDITION AI CLUSTER *AGUSTUS 2023
	Route::get('/kondisi_aicluster/daftar/ajax/{id}',array('as'=>'kondisi_aicluster/daftar.ajax','uses'=>'KondisiAIClusterController@Ajax'));
	Route::get('/kondisi_aicluster/daftar/ajax2/{id}',array('as'=>'kondisi_aicluster/daftar.ajax','uses'=>'KondisiAIClusterController@myformAjax2'));

	// Route::get('/fileimport/ajax_distrik/{id}',array('as'=>'output/ajax_distrik','uses'=>'FileImportController@ajax_distrik'));

	Route::get('search','KondisiAIClusterController@search'); 

    Route::get('/kondisi_aicluster/daftar', ['as' => 'kondisi_aicluster.daftar', 'uses' => 'KondisiAIClusterController@index']);
    Route::get('/kondisi_aicluster/create', 'KondisiAIClusterController@create');
    Route::post('/kondisi_aicluster/create', array('before' => 'csrf',
                                        'uses' => 'KondisiAIClusterController@create'));
    Route::get('/kondisi_aicluster/update/{id}', 'KondisiAIClusterController@update');
    Route::post('/kondisi_aicluster/update/{id}', array('before' => 'csrf',
                                            'uses' => 'KondisiAIClusterController@update'));
    Route::get('/kondisi_aicluster/delete/{id}', 'KondisiAIClusterController@delete');
    Route::get('/kondisi_aicluster/detail/{id}', 'KondisiAIClusterController@detail');
    Route::post('/kondisi_aicluster/detail/{id}', array('before' => 'csrf',
                                            'uses' => 'KondisiAIClusterController@detail'));


    // MASTER BIDANG/DIVISI *2023
	Route::get('/bidang_divisi/daftar/ajax/{id}',array('as'=>'bidang_divisi/daftar.ajax','uses'=>'BidangDivisiController@Ajax'));
	Route::get('/bidang_divisi/daftar/ajax2/{id}',array('as'=>'bidang_divisi/daftar.ajax','uses'=>'BidangDivisiController@myformAjax2'));

	// Route::get('/fileimport/ajax_distrik/{id}',array('as'=>'output/ajax_distrik','uses'=>'FileImportController@ajax_distrik'));

	Route::get('search','BidangDivisiController@search'); 

    Route::get('/bidang_divisi/daftar', ['as' => 'bidang_divisi.daftar', 'uses' => 'BidangDivisiController@index']);
    Route::get('/bidang_divisi/sinkron', 'BidangDivisiController@sinkron');
    Route::get('/bidang_divisi/create', 'BidangDivisiController@create');
    Route::post('/bidang_divisi/create', array('before' => 'csrf',
                                        'uses' => 'BidangDivisiController@create'));
    Route::get('/bidang_divisi/update/{id}', 'BidangDivisiController@update');
    Route::post('/bidang_divisi/update/{id}', array('before' => 'csrf',
                                            'uses' => 'BidangDivisiController@update'));
    Route::get('/bidang_divisi/delete/{id}', 'BidangDivisiController@delete');
    Route::get('/bidang_divisi/detail/{id}', 'BidangDivisiController@detail');
    Route::post('/bidang_divisi/detail/{id}', array('before' => 'csrf',
                                            'uses' => 'BidangDivisiController@detail'));


    // MASTER JABATAN *2023
	Route::get('/jabatan/daftar/ajax/{id}',array('as'=>'jabatan/daftar.ajax','uses'=>'JabatanController@Ajax'));
	Route::get('/jabatan/daftar/ajax2/{id}',array('as'=>'jabatan/daftar.ajax','uses'=>'JabatanController@myformAjax2'));

	// Route::get('/fileimport/ajax_distrik/{id}',array('as'=>'output/ajax_distrik','uses'=>'FileImportController@ajax_distrik'));

	Route::get('search','JabatanController@search'); 

    Route::get('/jabatan/daftar', ['as' => 'jabatan.daftar', 'uses' => 'JabatanController@index']);
    Route::get('/jabatan/sinkron', 'JabatanController@sinkron');
    Route::get('/jabatan/tree', ['as' => 'jabatan.tree', 'uses' => 'JabatanController@tree']);
    Route::get('/jabatan/create', 'JabatanController@create');
    Route::post('/jabatan/create', array('before' => 'csrf',
                                        'uses' => 'JabatanController@create'));
    Route::get('/jabatan/update/{id}', 'JabatanController@update');
    Route::post('/jabatan/update/{id}', array('before' => 'csrf',
                                            'uses' => 'JabatanController@update'));
    Route::get('/jabatan/delete/{id}', 'JabatanController@delete');
    Route::get('/jabatan/detail/{id}', 'JabatanController@detail');
    Route::post('/jabatan/detail/{id}', array('before' => 'csrf',
                                            'uses' => 'JabatanController@detail'));


    // MASTER USER INTERNAL *2023
    Route::get('/user_internal/manage', ['as' => 'admin.userinternal.list', 'uses' => 'UserInternalController@index']);
    Route::get('/user_internal/detail/{id}', ['as' => 'admin.userinternal.view.view', 'uses' => 'UserInternalController@detail']);
    Route::get('/user_internal/sinkron', ['as' => 'admin.userinternal.sinkron', 'uses' => 'UserInternalController@sinkron']);
    // Route::get('/users_internal/create', ['as' => 'admin.userinternal.add.view', 'uses' => 'UserInternalController@getAddUser']);
    // Route::post('/users_internal/create', ['as' => 'admin.userinternal.add.action', 'uses' => 'UserInternalController@postAddUser']);
    // Route::get('/users_internal/edit/{id}', ['as' => 'admin.userinternal.edit.view', 'uses' => 'UserInternalController@getEditUser']);
    // Route::post('/users_internal/edit/{id}', ['as' => 'admin.userinternal.edit.action', 'uses' => 'UserInternalController@postEditUser']);
    // Route::post('/users_internal/edit_view/{id}', ['as' => 'admin.userinternal.edit.view.action', 'uses' => 'UserInternalController@postEditViewUser']);
    // Route::get('/users_internal/delete/{id}', ['as' => 'userinternal.delete.action', 'uses' => 'UserInternalController@postDeleteUser']);
    // Route::get('/users_internal/delete/{id}/{role_id}', ['as' => 'userinternal.delete.action', 'uses' => 'UserInternalController@postDeleteUser']);
    // Route::get('/users_internal/role/delete/{id_role}/{id_user}', ['as' => 'admin.userinternal.role.delete', 'uses' => 'UserInternalController@delete_user_role']);


    // Modul kelola Group *2023
    Route::get('/grup_divpembinaunit/manage', ['as' => 'admin.grupdiv.list', 'uses' => 'GroupDivisiPembinaUnitController@getGrupDivList']);

    Route::get('/grup_divpembinaunit/jenpembydistrik/{id}', ['as'=>'grupdiv/jenpembydistrik.ajax','uses'=>'GroupDivisiPembinaUnitController@AjaxJenpembydistrik']);

    Route::get('/grup_divpembinaunit/create', ['as' => 'admin.grupdiv.add.view', 'uses' => 'GroupDivisiPembinaUnitController@getAddGrupDiv']);
    Route::post('/grup_divpembinaunit/create', ['as' => 'admin.grupdiv.add.action', 'uses' => 'GroupDivisiPembinaUnitController@postAddGrupdiv']);

    Route::get('/grup_divpembinaunit/edit/{id}', ['as' => 'admin.grupdiv.edit.view', 'uses' => 'GroupDivisiPembinaUnitController@getEditGrupDiv']);
    Route::post('/grup_divpembinaunit/edit/{id}', ['as' => 'admin.grupdiv.edit.action', 'uses' => 'GroupDivisiPembinaUnitController@postEditGrupDiv']);

    Route::get('/grup_divpembinaunit/view/{id}', ['as' => 'admin.grupdiv.view.view', 'uses' => 'GroupDivisiPembinaUnitController@getViewGrupDiv']);

    Route::get('/grup_divpembinaunit/delete/{id}', ['as' => 'grupdiv.delete.action', 'uses' => 'GroupDivisiPembinaUnitController@postDeleteGrupDiv']);

    Route::post('/grup_divpembinaunit/add-user', ['as' => 'grupdiv.user.add.action', 'uses' => 'GroupDivisiPembinaUnitController@postAddGrupdivUser']);
    Route::get('/grup_divpembinaunit/delete-user/{role_id}/{user_id}', ['as' => 'grupdiv.user.delete.action', 'uses' => 'GroupDivisiPembinaUnitController@postDeleteGrupdivUser']);

    Route::post('/grup_divpembinaunit/add-jabatan', ['as' => 'grupdiv.jabatan.add.action', 'uses' => 'GroupDivisiPembinaUnitController@postAddGrupdivJabatan']);
    Route::get('/grup_divpembinaunit/delete-jabatan/{role_id}/{user_id}', ['as' => 'grupdiv.jabatan.delete.action', 'uses' => 'GroupDivisiPembinaUnitController@postDeleteGrupdivJabatan']);



    // Modul kelola Role KKP *2023
    Route::get('/role_kkp/manage', ['as' => 'admin.rolekkp.list', 'uses' => 'RoleKkpController@getRoleKkpList']);

    Route::get('/role_kkp/create', ['as' => 'admin.rolekkp.add.view', 'uses' => 'RoleKkpController@getAddRoleKkp']);
    Route::post('/role_kkp/create', ['as' => 'admin.rolekkp.add.action', 'uses' => 'RoleKkpController@postAddRoleKkp']);

    Route::get('/role_kkp/edit/{id}', ['as' => 'admin.rolekkp.edit.view', 'uses' => 'RoleKkpController@getEditRoleKkp']);
    Route::post('/role_kkp/edit/{id}', ['as' => 'admin.rolekkp.edit.action', 'uses' => 'RoleKkpController@postEditRoleKkp']);

    Route::get('/role_kkp/view/{id}', ['as' => 'admin.rolekkp.view.view', 'uses' => 'RoleKkpController@getViewRoleKkp']);
    
    Route::get('/role_kkp/delete/{id}', ['as' => 'rolekkp.delete.action', 'uses' => 'RoleKkpController@postDeleteRoleKkp']);

    Route::post('/role_kkp/add-user', ['as' => 'rolekkp.user.add.action', 'uses' => 'RoleKkpController@postAddRoleUser']);
    Route::get('/role_kkp/delete-user/{role_id}/{user_id}', ['as' => 'rolekkp.user.delete.action', 'uses' => 'RoleKkpController@postDeleteRoleUser']);


    // MASTER JENIS PEMBANGKIT *2023
	Route::get('/jenis_pembangkit/daftar/ajax/{id}',array('as'=>'jenis_pembangkit/daftar.ajax','uses'=>'JenisPembangkitController@Ajax'));
	Route::get('/jenis_pembangkit/daftar/ajax2/{id}',array('as'=>'jenis_pembangkit/daftar.ajax','uses'=>'JenisPembangkitController@myformAjax2'));

	// Route::get('/fileimport/ajax_distrik/{id}',array('as'=>'output/ajax_distrik','uses'=>'FileImportController@ajax_distrik'));

	Route::get('search','JenisPembangkitController@search'); 

    Route::get('/jenis_pembangkit/daftar', ['as' => 'jenis_pembangkit.daftar', 'uses' => 'JenisPembangkitController@index']);
    Route::get('/jenis_pembangkit/create', 'JenisPembangkitController@create');
    Route::post('/jenis_pembangkit/create', array('before' => 'csrf',
                                        'uses' => 'JenisPembangkitController@create'));
    Route::get('/jenis_pembangkit/update/{id}', 'JenisPembangkitController@update');
    Route::post('/jenis_pembangkit/update/{id}', array('before' => 'csrf',
                                            'uses' => 'JenisPembangkitController@update'));
    Route::get('/jenis_pembangkit/delete/{id}', 'JenisPembangkitController@delete');
    Route::get('/jenis_pembangkit/detail/{id}', 'JenisPembangkitController@detail');
    Route::post('/jenis_pembangkit/detail/{id}', array('before' => 'csrf',
                                            'uses' => 'JenisPembangkitController@detail'));
});
