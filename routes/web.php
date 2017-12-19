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

	if(Auth::check()) return redirect('/home');
	else 
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/contacts', 'HomeController@contacts');
Route::get('/meetings', 'HomeController@meetings');
Route::get('/meetings/{meeting}', 'HomeController@meeting');

Route::post('/sms/send', 'SmsController@sendSms');




//Api Group -- user based
Route::group(['prefix' => 'api', 'middleware' => 'auth'], function () {

	Route::group(['prefix' => 'contacts'], function () {
		Route::post('all', 'ContactsController@getAllContacts');

		Route::post('show/{contact}', 'ContactsController@getContactById');

		Route::post('add', 'ContactsController@addContact');

		Route::get('{contact}/edit', 'ContactsController@editContact');

		Route::get('{contact}/delete', 'ContactsController@deleteContact');

		Route::post('/delete', 'ContactsController@deleteContacts');

	});


	Route::group(['prefix' => 'meetings'], function () {
		Route::post('all', 'MeetingsController@getAllMeetings');

		Route::post('show/{meeting}', 'MeetingsController@getMeetingById');

		Route::post('add', 'MeetingsController@addMeeting');

		// Route::post('add-contacts/{meeting}', 'MeetingsController@addContacts');

		Route::get('{meeting}/edit', 'MeetingsController@editMeeting');

		Route::get('{meeting}/delete', 'MeetingsController@deleteMeeting');

		Route::post('/delete', 'MeetingsController@deleteMeetings');


	});

	Route::group(['prefix' => 'meeting-contacts'], function () {

		Route::post('{meeting}/add', 'MeetingsController@addMeetingContacts');

		Route::post('{meeting}/delete', 'MeetingsController@deleteMeetingContacts');

	});

}); //Api Group ends here

