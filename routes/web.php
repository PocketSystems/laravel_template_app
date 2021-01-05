<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('dashboard');
});*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('dashboard');
Route::get('/logout', 'HomeController@logout');


/*Route::group(['prefix'=>'/module','as'=>'module.'],function (){
    Route::group(['prefix'=>'/vendors','as'=>'vendors.'],function (){
        Route::get('/','Modules\Vendors\VendorsController@index')->name('home');

        Route::get('/add','Modules\Vendors\VendorsController@add')->name('add');
        Route::post('/','Modules\Vendors\VendorsController@create')->name('create');

        Route::get('/{id}','Modules\Vendors\VendorsController@edit')->name('edit');
        Route::put('/','Modules\Vendors\VendorsController@update')->name('update');

        Route::delete('/{id}','Modules\Vendors\VendorsController@delete')->name('delete');
    });
});*/

Route::get('storage/app/{dir}/{filename}', function ($dir,$filename)
{
    $path = storage_path('app/'.$dir.'/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});


function createRoutes($moduleName){
    $GLOBALS['moduleName'] = $moduleName;
    return Route::group(['prefix'=>'/'.$moduleName,'as'=>$moduleName.'.'],function (){
        Route::get('/','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@index')->name('home');
        Route::get('/get','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@datatable')->name('datatable');
        Route::get('/add','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@add')->name('add');
        Route::post('/','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@create')->name('create');

        Route::get('/{id}','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@edit')->name('edit');
        Route::put('/','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@update')->name('update');

        Route::delete('/{id}','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@delete')->name('delete');
        Route::delete('/{id}/{field}','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@deleteFile')->name('deleteFile');
        Route::put('/{id}/{field}','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@status')->name('status');
    });
}
function createOrderRoutes($moduleName){
    $GLOBALS['moduleName'] = $moduleName;
    return Route::group(['prefix'=>'/'.$moduleName,'as'=>$moduleName.'.'],function (){
        Route::get('/','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@index')->name('home');
        Route::get('/get','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@datatable')->name('datatable');
        Route::get('/add','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@add')->name('add');
        Route::post('/','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@create')->name('create');

        Route::get('/{id}','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@viewOrder')->name('viewOrder');
        Route::get('/invoice/{id}','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@invoice')->name('invoice');

        Route::delete('/{id}','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@delete')->name('delete');
        Route::put('/{id}/{field}','Modules\\'.ucfirst($GLOBALS['moduleName']).'\\'.ucfirst($GLOBALS['moduleName']).'Controller@status')->name('status');
    });
}

function createReportRoutes($controller){
    $GLOBALS['moduleName'] = 'reports';
    $GLOBALS['controller'] = $controller;
    return Route::group(['prefix'=>'/'.$controller,'as'=>$controller.'.'],function () use($controller){
        Route::get('/','Modules\\'.ucfirst('reports').'\\'.$controller.'@index')->name('home');
        Route::get('/get','Modules\\'.ucfirst('reports').'\\'.$controller.'@datatable')->name('datatable');
        Route::get('/add','Modules\\'.ucfirst('reports').'\\'.$controller.'@add')->name('add');
        Route::post('/','Modules\\'.ucfirst('reports').'\\'.$controller.'@search')->name('search');
    });
}
Route::group(['prefix'=>'/module','as'=>'module.'],function (){
    createRoutes('suppliers');
    createRoutes('categories');
    createRoutes('items');

    createRoutes('expenseCategories');
    createRoutes('expenses');
    createRoutes('customers');
//    Reports
    createReportRoutes('PurchaseOrderReport');
    createOrderRoutes('purchaseOrders');
    createOrderRoutes('saleOrders');
});
