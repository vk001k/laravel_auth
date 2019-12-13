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


Route::get('/', function () {

    return view('auth.login');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::get('companies/destroy', 'CompaniesController@destroy')->name('companies.destroy');
    Route::get('companies/show/{id}', 'CompaniesController@show')->name('companies.show');
    Route::post('companies/update', 'CompaniesController@update')->name('companies.update');
    Route::post('companies/getCompany','CompaniesController@getCompany');
    Route::post('companies/store','CompaniesController@store');
    Route::resource('companies', 'CompaniesController');

    Route::get('employees/destroy', 'EmployeesController@destroy')->name('employees.destroy');
    Route::get('employees/show/{id}', 'EmployeesController@show')->name('employees.show');
    Route::post('employees/update', 'EmployeesController@update')->name('employees.update');
    Route::post('employees/getEmployeeDetails','EmployeesController@getEmployeeDetails');
    Route::resource('employees', 'EmployeesController');
});


Route::group(['middleware' => ['auth', 'company']], function() {
    Route::group(['prefix'=>'company','as'=>'company.'],function(){

        Route::get('employees/destroy', 'CompanyEmployeesController@destroy')->name('employees.destroy');
        Route::get('employees/show/{id}', 'CompanyEmployeesController@show')->name('employees.show');
        Route::post('employees/update', 'CompanyEmployeesController@update')->name('employees.update');
        Route::post('employees/getEmployeeDetails', 'CompanyEmployeesController@getEmployeeDetails')->name('employees.getEmployeeDetails');
        Route::resource('employees', 'CompanyEmployeesController');
    });
});




