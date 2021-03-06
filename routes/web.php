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

use App\Http\Controllers\applicationController;

Route::view('/vacancies', 'vacancies/vacancies');
Route::get('/vacancies/{Id}', 'vacancyController@vacancyView');
Route::get('/vacancies/{Id}/apply', 'applicationController@getFormView');//->middleware('auth');
Route::post('/vacancies/{Id}/apply', function ($vacancyId){
    $msg = request()->get('message');
    $type = 'Application';
    
    $c = new applicationController();
    return $c->createMessage($vacancyId, $msg, $type) ? 
            redirect('/vacancies/' . $vacancyId . '/chat') : redirect('ERROR');
});//->middleware('auth');
Route::get('/vacancies/{vacancyId}/chat', function ($vacancyId){
return view('applications/applications_log', ['vacancy' => \App\vacancy::get($vacancyId)]);
});//->middleware('auth');
Route::get('/vacancies/{vacancyId}/chat/messages', function ($vacancyId){
    $c = new applicationController();
    return $c->getApplicationAndReplies($vacancyId);
});//->middleware('auth');
Route::post('/vacancies/{vacancyId}/chat/messages', function ($vacancyId){
    $msg = request()->get('msg');
    $type = 'Reply';
    
    $c = new applicationController();
    return $c->createMessage($vacancyId, $msg, $type);
});//->middleware('auth');


Route::get('/', 'FrontController@index');

Auth::routes();
Route::get('profile', 'UserController@profile');
Route::post('profile', 'UserController@update_avatar');
Route::get('editUser', 'editUserController@index');
Route::post('editUser', 'editUserController@editUser');
Route::get('/login/{social}','Auth\LoginController@socialLogin')
    ->where('social' , 'linkedin');
Route::get('/login/{social}/callback','Auth\LoginController@handleProviderCallback')
    ->where('social' , 'linkedin');
Route::prefix('admin')->group(function() {
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
});
Route::get('admin', 'AdminController@display_users');
//Route::get('vacancies', 'VacanciesController@vacancies');


