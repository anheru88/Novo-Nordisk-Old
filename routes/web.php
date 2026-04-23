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

use App\DocRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('home', 'HomeController@index')->name('home');

// Route Temporally shared docs clients
Route::get('/sharedfiles', function (Request $request) {
    $ids = $request->fichas;
    $expires = date('d-m-Y / h:m:s',$request->expires);
    $files = DocRepository::whereIn('id_doc', $ids)->with('folder')->get();
    return view('admin.files.linktemporary', ['files' => $files,'expires' => $expires]);
})->name('sharedfiles')->middleware('signed');

// COMENTE ESTAS OTRAS RUTAS TAMBIEN
/*
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);*/
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

// Registration routes...
Route::get('registro', 'Auth\AuthController@getRegister');
Route::post('registro', ['as' => 'registro', 'uses' => 'Auth\AuthController@postRegister']);

// Vue JS Querys
Route::post('getCities', 'LocationsController@getCities');

// auth0 routes
/*Route::get( '/auth0/callback', '\Auth0\Login\Auth0Controller@callback' )->name( 'auth0-callback' );
Route::get('/login', 'Auth\Auth0IndexController@login' )->name( 'login' );
Route::get('/logout', 'Auth\Auth0IndexController@logout' )->name( 'logout' )->middleware('auth');*/


Route::get('get-app-data', 'Auth0EndPoints@getAppData')->name('get-app-data')->middleware('jwt');
Route::post('create-user', 'Auth0EndPoints@createUser')->name('create-user')->middleware('jwt');
Route::post('delete-user', 'Auth0EndPoints@deleteUser')->name('delete-user')->middleware('jwt');
// download pdf for client email send
Route::get('clientquotapdf/{id}/pdf/', ['uses' => 'QuotationsController@createPDF'])->name('clientquotapdf.pdf');
Route::get('clientnegopdf/{id}/pdf/', ['uses' => 'NegotiationsController@createPDF'])->name('clientnegopdf.pdf');

Route::post('authorizequota', 'NovoAppController@authorizeQuote')->name('authorizequota');
Route::post('authorizenegoti', 'NovoAppController@authorizeNegotiation')->name('authorizenegoti');

Route::post('getQuotationData', 'QuotationsController@getQuotationData'); // Obtiene los datos de envio para el popup
//Permision Routes
Route::middleware(['auth'])->group(function () {
    //Users
    Route::resource('users', 'UsersController');
    Route::get('users/{id}/destroy', ['uses' => 'UsersController@destroy'])->name('users.destroy');
    Route::post('getUsers', 'UsersController@getUsers');

    //Roles
    Route::resource('roles', 'RolesController');
    Route::get('roles/{id}/destroy', ['uses' => 'RolesController@destroy'])->name('roles.destroy');

    //Products
    Route::resource('products', 'ProductsController');
    Route::get('products/{id}/destroy', ['uses' => 'ProductsController@destroy'])->name('products.destroy');
    Route::post('getProduct', 'ProductsPricesController@getProduct');
    Route::post('getPriceData', 'ProductsPricesController@getPriceData'); // Obtiene los datos de envio para el popup prices
    Route::post('reportProduct', 'ProductsController@reportProduct')->name('products.export');
    Route::post('passedInfo', 'ProductsController@passedInfo')->name('products.passed');

    // Tipos de productos
    Route::resource('productlines', 'ProductLinesController');
    Route::get('productlines/{id}/destroy', ['uses' => 'ProductLinesController@destroy'])->name('productlines.destroy');

    // Unidades de venta
    Route::resource('productunits', 'ProductUnitsController');
    Route::get('productunits/{id}/destroy', ['uses' => 'ProductUnitsController@destroy'])->name('productunits.destroy');

    // Usos adicionales
    Route::resource('productuses', 'AditionalUsesController');
    Route::get('productuses/{id}/destroy', ['uses' => 'AditionalUsesController@destroy'])->name('productuses.destroy');

    //Products Price
    Route::resource('prices', 'ProductsPricesController');
    Route::post('prices.updatePrice', 'ProductsPricesController@updatePrice')->name('prices.updatePrice');
    Route::post('prices/update', ['uses' => 'ProductsPricesController@updatedate'])->name('prices.updatedate');


    //Product Authorization
    Route::resource('authlevel', 'AuthLevelController', ['except' => ['destroy', 'create', 'update', 'edit', 'show']]);

    //Clients
    Route::resource('clients', 'ClientsController');
    Route::get('clients/{id}/destroy', ['uses' => 'ClientsController@destroy'])->name('clients.destroy');
    Route::post('getClient', 'ClientsController@getClient');
    Route::post('reportClient', 'ClientsController@reportClient')->name('clients.export');
    Route::get('clients/{id}/pdfcertificate/', ['uses' => 'ClientsController@createPDFCertificate'])->name('client.pdfcertificate');

    // Tipos de clientes
    Route::resource('clientstype', 'ClientTypesController');
    Route::get('clientstype/{id}/destroy', ['uses' => 'ClientTypesController@destroy'])->name('clientstype.destroy');

    // Brands
    Route::resource('brands', 'BrandsController');
    Route::get('brands/{id}/destroy', ['uses' => 'BrandsController@destroy'])->name('brands.destroy');

    // Metodos de Pago
    Route::resource('paymethods', 'PayMethodsController');
    Route::get('paymethods/{id}/destroy', ['uses' => 'PayMethodsController@destroy'])->name('paymethods.destroy');

    // Conceptos de Negociación
    Route::resource('concepts', 'NegotiationConceptsController');
    Route::get('concepts/{id}/destroy', ['uses' => 'NegotiationConceptsController@destroy'])->name('concepts.destroy');

    //Quotations - Cotizaciones
    Route::resource('cotizaciones', 'QuotationsController');

    Route::post('getPreviousProduct', 'QuotationsController@getPreviousProduct');
    Route::post('calcProductQuota', 'QuotationsController@calcProductQuota');
    Route::post('getEditProducts', 'QuotationsController@getEditProducts');
    Route::post('getPayForm', 'QuotationsController@getPayForm');
    Route::post('getQuotaTotal', 'QuotationsController@getQuotaTotal');
    Route::post('getProductsClient', 'QuotationsController@getProductsClient');
    Route::post('getAuthorizers', 'QuotationsController@getAuthorizers');
    Route::post('getHistoryProduct', 'QuotationsController@getHistoryProduct');

    Route::get('cotizaciones/{cotizacione}/editdate', ['uses' => 'QuotationsController@editdate'])->name('cotizaciones.editdate');
    Route::post('cotizaciones/update', ['uses' => 'QuotationsController@updatedate'])->name('cotizaciones.updatedate');
    Route::put('cotizaciones/{id}/aprobar', ['uses' => 'QuotationsController@preaprobado'])->name('cotizaciones.preaprobacion');
    Route::get('cotizaciones/{id}/destroy', ['uses' => 'QuotationsController@destroy'])->name('cotizaciones.destroy');
    Route::get('cotizaciones_estado/{estado}/{quantity}', ['uses' => 'QuotationsController@indexFilter'])->name('cotizaciones.filter');
    Route::get('cotizaciones/{id}/pdf/', ['uses' => 'QuotationsController@createPDF'])->name('cotizacion.pdf');
    Route::put('cotizaciones/{id}/anular', ['uses' => 'QuotationsController@cancelQuota'])->name('cotizaciones.anular');

    // Genera el PDF
    Route::put('cotizaciones/{id}', ['uses' => 'QuotationsController@sendEmail'])->name('cotizaciones.pdf');

    //Envio de cotizacion por email
    Route::post('quotationSendEmail', 'QuotationsController@quotationSendEmail')->name('cotizaciones.sendEmail'); // Obtiene los datos de envio para el popup

    //Negociaciones
    Route::resource('negociaciones', 'NegotiationsController');
    Route::post('getProductsClientQuota', 'NegotiationsController@getProductsClientQuota');
    Route::post('getProductsClientDesc', 'NegotiationsController@getProductsClientDesc');
    Route::post('calcDiscount', 'NegotiationsController@calcDiscount'); // verifica los descuentos
    Route::post('negociacionAsistida', 'NegotiationsController@negociacionAsistida'); // realiza la negociacion de los productos con escala
    Route::post('negociacionAsistidaxConcepto', 'NegotiationsController@negociacionAsistidaxConcepto'); // realiza la negociacion de los productos con escala
    Route::post('saveNegotiation', 'NegotiationsController@store')->name('saveNegotiation'); // realiza la negociacion de los productos con escala

    Route::get('negociaciones/{negociacione}/editdate', ['uses' => 'NegotiationsController@editdate'])->name('negociaciones.editdate');
    Route::post('negociaciones/update', ['uses' => 'NegotiationsController@updatedate'])->name('negociaciones.updatedate');

    Route::post('getNegotiData', 'NegotiationsController@getNegotiData'); // Obtiene los datos de envio para el popup negoti
    Route::put('negociaciones/{id}/anular', ['uses' => 'NegotiationsController@cancelNego'])->name('negociaciones.anular');
    Route::post('negociaciones/files', ['uses' => 'NegotiationsController@storeNegotiationFiles'])->name('negociaciones.files');

    // Genera el PDF
    Route::get('negociaciones/{id}/pdf/', ['uses' => 'NegotiationsController@createPDF'])->name('negociaciones.pdf');

    //Envio de negociación por email
    Route::post('negotiationSendEmail', 'NegotiationsController@negotiationSendEmail')->name('negociaciones.sendEmail'); // Obtiene los datos de envio para el popup

    // Edicion de Negociaciones
    Route::post('getProductsClientNego','NegotiationsController@getProductsClientNego');
    Route::post('updateNegotiation', 'NegotiationsController@update');

    //Escalas
    Route::resource('escalas', 'ScalesController');
    Route::post('getScales', 'ScalesController@getScales');
    Route::post('saveScales', 'ScalesController@store');
    Route::post('updateModalScales','ScalesController@update');
    Route::post('editScales', 'ScalesController@editScales');
    Route::post('updateScales', 'ScalesController@updateScales');
    Route::post('destroyScales', 'ScalesController@destroy');
    Route::post('getProductUnit', 'ScalesController@getProductUnit');

    //Autorizaciones
    Route::resource('autorizaciones', 'AutorizationsController');
    Route::get('autorizarlist/{id}', 'AutorizationsController@pricelist')->name('autorizaciones.lista');
    Route::get('approved', 'AutorizationsController@approved')->name('autorizaciones.aprobadas');
    Route::get('rejected', 'AutorizationsController@rejected')->name('autorizaciones.rechazadas');
    Route::post('listapproved', 'AutorizationsController@listapproved')->name('lista.aprobada');
    Route::post('listrejected', 'AutorizationsController@listrejected')->name('lista.rechazada');

    Route::get('autorizarcotizacion/{id}', 'AutorizationsController@quotations')->name('autorizaciones.cotizacion');
    Route::put('autorizarcotizacion/{id}', 'AutorizationsController@autorizeQuotation')->name('autorizaciones.cotizacion');

    Route::get('autorizarnegociacion/{id}', 'AutorizationsController@negotiations')->name('autorizaciones.negociacion');
    Route::put('autorizarnegociacion/{id}', 'AutorizationsController@autorizeNegotiation')->name('autorizaciones.negociacion');
    Route::post('validateAutorizers','AutorizationsController@validateAutorizers')->name('autorizaciones.validate');

    // Preautorizaciones
    Route::resource('preautorizaciones', 'PreAutorizationsController');
    Route::get('preautorizarcotizacion/{id}', 'PreAutorizationsController@quotations')->name('preautorizaciones.cotizacion');
    Route::get('preautorizarnegociacion/{id}', 'PreAutorizationsController@negotiations')->name('preautorizaciones.negociacion');
    Route::get('preautorizarlist/{id}', 'PreAutorizationsController@pricelist')->name('preautorizaciones.lista');

    // Repositorio de documentos de clientes
    Route::resource('files', 'FilesController', ['except' => ['create', 'show']]);
    Route::post('filesUpload', 'ClientsController@filesUpload')->name('filesUpload');
    Route::get('files/{id}/destroy', ['uses' => 'FilesController@destroy'])->name('files.destroy');

    // Repositorio de documentos generales
    Route::resource('documentos', 'DocsController');
    Route::get('repositorio',  ['uses' => 'DocsController@indexdocs'])->name('documentos.genericos');
    Route::post('createfile',  ['uses' => 'DocsController@createFile'])->name('documentos.createfile');
    Route::get('documentos/{id}/destroy', ['uses' => 'DocsController@destroy'])->name('documentos.destroy');
    Route::get('repositorio/{id}',  ['uses' => 'DocsController@viewFolder'])->name('documentos.viewfolder');
    Route::post('createfolder',  ['uses' => 'DocsController@createFolder'])->name('documentos.createfolder');
    Route::post('editFolder', 'DocsController@editFolder')->name('folder.edit');
    Route::get('folder/{id}/destroy', ['uses' => 'DocsController@destroyFolder'])->name('folder.destroy');

    //Route Descargar archivos compartidos
    Route::post('filesresponse', 'FilesController@sharedDocs')->name('filesresponse');
    Route::post('sharedgenericdocs', 'FilesController@sharedGenericDocs')->name('sharedgenericdocs');
    //Envio de link temporal de archivos por email
    Route::post('sharedDocsSendEmail', 'FilesController@sharedDocsSendEmail')->name('sharedDocs.sendEmail');
    // Agregar archivos temporales para compartir
    Route::post('addSharedFiles','FilesController@addSharedFiles')->name('sharedFiles');
    Route::post('getSharedFiles','FilesController@getSharedFiles')->name('getSharedFiles');
    Route::post('removeSharedFiles','FilesController@removeSharedFiles')->name('removeSharedFiles');

    //Reportes
    Route::resource('reportes', 'ReportsController');
    Route::post('reportesSearch', 'ReportsController@search')->name('reportes.search');
    Route::post('getClientReport', 'ReportsController@getClients');

    /*Excel import export*/
    Route::post('export', 'ReportsController@export')->name('export');
    // Route::get('importExportView', 'ReportsController@importExportView');
    // Route::post('import', 'ReportsController@import')->name('import');

    // NOTAS
    Route::get('notas', 'ReportsController@notas')->name('notas');
    Route::post('notasMasive', 'ReportsController@notasMasive')->name('notas.masive');
    Route::post('notasDownload', 'ReportsController@notasDownload')->name('notas.download');
    Route::get('notas/{id}/destroy', 'ReportsController@notasDestroy')->name('notas.destroy');

    // ARCHIVO PLANO SAP
    Route::get('sapNotes', 'SapController@index')->name('sapnotes');
    Route::get('importNotes','SapController@importCreditNotes')->name('sapnotes.import');
    Route::post('importNotes','SapController@importCreditNotes')->name('sapnotes.import');
    Route::post('sapNotesCsv','SapController@generateCsv')->name('sapnotes.csv');
    Route::get('sapNotes/{id}/destroy','SapController@destroy')->name('sapnotes.destroy');
    Route::get('sapNotesCsv', 'SapController@generateCsv')->name('sapnotes.csv');
    Route::post('sapNotesXls', 'SapController@generateExcel')->name('sapnotes.xls');


    // Importación excel
    Route::post('clientsMasive', 'ClientsController@clientsMasive')->name('clients.masive');
    Route::post('productMasive', 'ProductsController@productsMasive')->name('products.masive');
    Route::post('priceMasive', 'ProductsPricesController@pricesMasive')->name('prices.masive');

    // Formatos de documentos de envio al cliente
    Route::resource('formats', 'DocFormatsController')->except('edit', 'update');
    Route::get('formats.types',  ['uses' => 'DocFormatsController@indexbytype'])->name('formats.types');
    Route::get('formats/{format}/{type}/editcer',  ['uses' => 'DocFormatsController@edit_cer'])->name('formats.editcer');
    Route::get('formats/{format}/{type}/editcot',  ['uses' => 'DocFormatsController@edit_cot'])->name('formats.editcot');
    Route::put('formats/{format}/{type}',  ['uses' => 'DocFormatsController@update'])->name('formats.update');
    Route::post('upload', 'DocFormatsController@uploadImage')->name('upload');

    // Notificaciones
    Route::resource('notifications', 'NotificationsController');
    Route::get('notify', 'NotificationsController@getNotificationsData');
    Route::get('notifycountall', 'NotificationsController@getNotificationsDataAll');
    Route::put('markAsRead', 'NotificationsController@markAsRead');
    Route::post('notificationView', 'NotificationsController@notificationView')->name('notificationView');
    Route::post('/mark-as-read', 'NotificationsController@markNotification')->name('markNotification');

    // Configuracion ARP
    Route::resource('arp','ArpController', ['except' => ['destroy', 'create', 'update', 'show']]);
    Route::get('arp/{id}/destroy','ArpController@destroy')->name('arp.destroy');

    // Simulacion ARP
    Route::resource('simulatorArp','ArpSimulationsController', ['except' => ['destroy', 'create', 'update', 'show']]);
    Route::post('importArp','ArpSimulationsController@importArp')->name('arp.import');
    Route::post('arpNotesXls', 'ArpSimulationsController@generateExcel')->name('arp.xls');
    Route::get('simulatorArp/{id}/destroy','ArpSimulationsController@destroy')->name('simulator.destroy');

    //Servicios ARP
    Route::post('serviceArp','ServiceArpController@store')->name('serviceArp.store');
    Route::post('serviceArp/{id}/update','ServiceArpController@update')->name('serviceArp.update');
    Route::get('serviceArp/{id}/destroy','ServiceArpController@destroy')->name('serviceArp.destroy');
    Route::post('getServiceArp','ServiceArpController@getServiceArp');
    Route::post('pbcArp','BusinessArpController@store')->name('pbcArp.store');
    Route::post('pbcArpUpdate','BusinessArpController@update')->name('pbcArp.update');
    Route::post('getPbcArp','BusinessArpController@getPbcArp')->name('getPbcArp');

});

Route::prefix('auth0')->group(function () {
    Route::get('get-app-data', 'NovoUserController@getAppData')->name('auth0.getAppData');
    Route::post('create-user', 'NovoUserController@createUser')->name('auth0.createUser');
    Route::post('delete-user', 'NovoUserController@deleteUser')->name('auth0.deleteUser');
    Route::get('login', 'NovoUserController@getLogin')->name('auth0.getLogin');
    Route::post('login', 'NovoUserController@login')->name('auth0.login');
});
