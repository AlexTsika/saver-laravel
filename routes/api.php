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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// get all users
Route::get('/users', function() {
    return DB::table('users')->get();
});
// post user
Route::post('/users', function() {
    $id = DB::table('users')->insertGetId([
    'username' => request('username'),
    'password' => bcrypt(request('password'))
    ]);
    return $id;
});
// delete user
Route::delete('/users/{id}', function ($id) {
    $todo = DB::table('users')->where('id', $id)->delete();
    return "deleted";
});
// patch password
Route::patch('/users/{id}', function ($id) {
    DB::table('users')->where('id', $id)->update([
    'password' => bcrypt(request('password'))
    ]);
    return "password updated";
});
