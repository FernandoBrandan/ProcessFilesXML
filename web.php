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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::auth();

Route::get('/', function(){
	return view('auth.login');
})->name('login');

Route::post('login', 'Auth\LoginController@login')->name('login.login');

Route::get('register', function(){
	return view('auth.register');
})->name('register');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	Route::post('cancel/order/{id}', 'OrderController@destroy')->name('cancel.order');
	Route::post('show/voucher', 'OrderController@voucherTienda')->name('show.voucher');
	Route::get('items', 'ItemController@index');
	Route::get('items/show/{id}', 'ItemController@show')->name('items.show');
	Route::get('items/edit/{id}', 'ItemController@edit')->name('items.edit');
	Route::put('items/update/{id}', 'ItemController@update')->name('items.update');
	Route::post('items/storeassoc', 'ItemController@store')->name('items.store');
	
	
	Route::get('vouchermail', 'MailVoucherController@index');
	Route::post('vouchermail/store', 'MailVoucherController@store')->name('vouchermail.store');
	Route::delete('vouchermail/delete/{id}', 'MailVoucherController@destroy')->name('vouchermail.delete');

	#########################################################################################################
	############### USERS ####################### USERS ############################ USERS ############

	Route::post('users/create', 'ProfileController@newUser')->name('users.create');
	Route::get('user/edit/{id}', 'UserController@edit')->name('user.edit');
	Route::put('user/update/{id}', 'UserController@update')->name('user.update');
	Route::post('user/module/update', 'UserController@moduleUpdate')->name('user/module/update');
	Route::post('user/sub_module/update', 'UserController@subModuleUpdate')->name('user/sub_module/update');

	#########################################################################################################
	############### STORES ####################### STORES ############################ STORES ############

	Route::get('tiendas', 'ListTiendaController@index')->name('tiendas');
	Route::post('tienda/store', 'ListTiendaController@store')->name('tienda.store');
	Route::get('tiendas/showing', 'ListTiendaController@showing')->name('tiendas.showing');
	Route::post('tiendas/addortake', 'ListTiendaController@storesOnMeli')->name('tiendas.addortake');
	Route::delete('tienda/destroy/{id}', 'ListTiendaController@destroyStore')->name('tienda.destroy');
	
	#########################################################################################################
	############### MODULES ####################### MODULES ############################ MODULES ############

	Route::get('modules', 'ModuleController@index')->name('modules');
	Route::get('modules/edit/{id}', 'ModuleController@edit')->name('modules.edit');
	Route::put('module/update/{id}', 'ModuleController@updateModule')->name('module.update');
	Route::get('module/create', 'ModuleController@create')->name('module/create');
	Route::post('module/store', 'ModuleController@store')->name('module/store');
	Route::get('module/linkadd/{id}', 'ModuleController@linkModuleCreate')->name('module/linkadd');
	Route::post('module/linkadd', 'ModuleController@linkModuleInsert')->name('module/linkadd');
	Route::get('module/linkedit/{id}', 'ModuleController@linkModuleEdit');
	Route::post('module/linkdelete', 'ModuleController@linkModuleDelete')->name('module/linkdelete');
	Route::post('module/linkupdate', 'ModuleController@linkModuleUpdate')->name('module/linkupdate');
	Route::delete('module/delete/{id}', 'ModuleController@moduleDestroy')->name('module/delete');
	Route::get('submodule/create', 'SubModuleController@create')->name('sub_module.create');
	Route::post('submodule/store', 'SubModuleController@store')->name('sub_module.store');
	Route::get('submodule/edit/{id}', 'SubModuleController@edit')->name('sub_module.edit');
	Route::put('submodule/update/{id}', 'SubModuleController@updateSmodule')->name('submodule.update');

#########################################################################################################
################ ORDERS ########################## ORDERS ######################## ORDERS ###############

	Route::get('orders/comercial', 'OrderController@indexComercial');
	Route::get('orders/comercial/show', 'OrderController@show')->name('orders.comercial.show');
	Route::get('orders', 'OrderController@index');
	Route::get('order/comercial/view/{id}', 'OrderController@view');
	Route::get('order/comercial/history/view', 'OrderController@searchHistory')->name('order/comercial/history/view');
	Route::get('orders/comercial/store', 'OrderController@comercialStore');

#########################################################################################################
################ ERRORS ########################## ERRORS ######################## ERRORS ###############

	Route::get('mlerroraudits', 'MLerrorauditsController@index');

#########################################################################################################
################ FACTURA DE CREDITO ########################## FACTURA DE CREDITO ######################## FACTURA DE CREDITO ###############
	Route::get('credit_invoice/export/{status?}', 'CreditInvoiceController@export')->name('credit_invoice.export');
	Route::get('credit_invoice/search', 'CreditInvoiceController@search')->name('credit_invoice.search');
	Route::get('credit_invoice/confirm/', 'CreditInvoiceController@confirm')->name('credit_invoice.confirm');
	Route::get('credit_invoice/reject/', 'CreditInvoiceController@reject')->name('credit_invoice/reject');

	Route::get('credit_invoice/get_afip_api', 'CreditInvoiceController@get_afip_api_web')->name('credit_invoice/get_afip_api');
	Route::get('credit_invoice/search_afip', 'CreditInvoiceController@search_afip')->name('credit_invoice.search_afip');

	Route::get('credit_invoice/afip_confirm/{cuitemisor}/{ptovta}/{nrocmp}', 'CreditInvoiceController@afip_confirm')->name('credit_invoice.afip_confirm');
	Route::get('credit_invoice/afip_reject/', 'CreditInvoiceController@afip_reject')->name('credit_invoice/afip_reject');
	Route::get('credit_invoice/afip', 'CreditInvoiceController@afip')->name('credit_invoice/afip');
	Route::get('credit_invoice', 'CreditInvoiceController@index')->name('credit_invoice');
	Route::get('credit_invoice/data/afip/view', 'CreditInvoiceController@afipView');
	Route::get('credit_invoice/data/afip', 'CreditInvoiceController@datAfip')->name('afip.data');
	Route::post('credit_invoice/afip/toexcel', 'CreditInvoiceController@afipToExcel')->name('afip.toexcel');
	Route::post('credit_invoice/mbsfce/toexcel', 'CreditInvoiceController@mbsToExcel')->name('credit_invoice.mbstoexcel');
	Route::get('credit_invoice/edit/{invoice_no}', 'CreditInvoiceController@editCredit');
	Route::put('credit_invoice/update/{invoice_no}', 'CreditInvoiceController@updateCredit')->name('credit_invoice.update');

#########################################################################################################
################ Informe Z ########################## Informe Z  ######################## Informe Z  ###############
	
	Route::get('Informe_z/export/', 'InformeZController@export')->name('Informe_z.export');
	Route::get('Informe_z/search', 'InformeZController@search')->name('Informe_z.search');
	Route::get('Informe_z/exportb/', 'InformeZController@exportb')->name('Informe_z.exportb');
	Route::get('Informe_z/searchb', 'InformeZController@searchb')->name('Informe_z.searchb');

####################################################################################################################
########### Remito Carnico ##################### Remito Carnico  ################### Remito Carnico  ###############
	
	Route::get('afip', 'AfipController@index');
	Route::get('afip/carnico', 'CarnicoController@index');
	Route::put('afip/carnico/fileread', 'CarnicoController@fileProcess');

####################################################################################################################
########### Integraciones ##################### Integraciones  ################### Integraciones  ##################
#################### DEVOLUCION DE BONIFICACIÓN ########################
	
	Route::get('integration', 'IntegrationsController@index');
	Route::get('integration/devbon', 'DevBonController@index');
	Route::get('integration/devbon/getinfo', 'DevBonController@getCust');
	Route::get('integration/devbon/getinfo/invoiceline', 'DevBonController@getHistInvoice');
	Route::post('integration/devbon/storeinfo', 'DevBonController@store');
	Route::get('integration/devbon/get/devoluciones', 'DevBonController@getDevols');
	Route::post('integration/devbon/mod/status', 'DevBonController@changeStatus')->name('devbon.modstatus');
	Route::post('integration/devbon/get/devdetalle', 'DevBonController@getDevDetalles');
	Route::get('integration/devbon/get/voucher/{id}', 'DevBonController@getVoucher');

######################## INTERESES ####################################
	
	Route::get('integration/interes', 'InteresController@index');
	Route::get('integration/interes/get/comps', 'InteresController@getComps');
	// Route::post('integration/interes/post/novedad/{till_no}/{invoice_no}/{invoice_type}/{credit_no}', 'InteresController@novedad')->name('integration.interest.novedad');
	Route::get('integration/interes/get/interes', 'InteresController@getInteres');
	Route::post('integration/interes/post/makepdf/{order}/{connection}/{cust_store?}', 'InteresController@makePdf')->name('integration.interes.makepdf');
	Route::delete('integration/interest/delete/novedad', 'InteresController@deleteNovedad')->name('integration.interest.delete.novedad');
	Route::post('integration/interes/getfacturas', 'InteresController@getFacturas')->name('integration.interes.getfacturas');
	Route::get('integration/interes/edit/exceptions', 'InteresController@editExceptions');
	Route::post('integration/interes/store/exceptions', 'InteresController@storeExceptions')->name('integration.interes.store.exceptions');
	Route::get('integration/interes/get/exceptions', 'InteresController@getExceptions');
	Route::delete('integration/interes/delete/exceptions/{id}', 'InteresController@deleteExceptions')->name('integration.interes.delete.exceptions');
	Route::post('integration/interes/send/novedad', 'InteresController@sendNovedad')->name('integration.interes.send.novedad');
	Route::post('integration/interes/exportxcel', 'InteresController@exportExcel')->name('integration.interes.exportexcel');
	Route::post('integration/interes/exportxcelfact', 'InteresController@exportExcelFact')->name('integration.interes.exportexcelfact');
	

######################## FAST TRACK ####################################
	
	Route::get('integration/fastrack', 'FastTrackController@index');
	Route::get('integration/fastrack/user', 'FastTrackController@user');
	Route::post('integration/fastrack/user', 'FastTrackController@store')->name('fastrack.user');
	Route::get('integration/fastrack/user/get/{store}', 'FastTrackController@getUsers');
	Route::get('integration/fastrack/card', 'FastTrackController@card');
	Route::post('integration/fastrack/card', 'FastTrackController@newCard')->name('fastrack.card');
	Route::get('integration/fastrack/user/print/{user}', 'FastTrackController@userCard');

###################################  BCT ####################################
Route::get('integration/bct', 'BctController@index');
Route::get('integration/bct/get', 'BctController@getAll')->name('integration.bct.get');
Route::post('integration/bct/data/{tienda}', 'BctController@getData')->name('integration.bct.data');
Route::get('integration/bct/get/detail/{id}', 'BctController@getDetail');
Route::post('integration/bct/toexcel', 'BctController@toExcel')->name('integration.bct.toexcel');
});


Route::get('arelocot', function () {
	return bcrypt('M4kR0232020.');
});
Route::get('supllier/file', 'Supllier@file');
Route::get('supllier/down/{id}', 'Supllier@down');

Route::get('testingmail', 'CreditInvoiceController@testingmail');


Route::get('wachinango', 'SalidaController@loquillo');


Route::get('archivoxml', 'ProcesarXML@principal');
