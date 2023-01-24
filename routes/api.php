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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// get all users
Route::get('/users', function() {
    return DB::table('users')->get();
});

// secure route for all users -  token: 2|TwlhWYjUZ7MDX3ksz3xXyhdIqo4OrWVHOOqsPa1z
// Route::middleware(['auth:sanctum'])->get('/users', function() {

//  return DB::table('users')->get();
    // return DB::select('select * from users');
// });

// route for user by id
Route::get('/users/id/{id}', function($id) {
    return DB::table('users')->where('id', $id)->first();
});

// secure route for user by id
// Route::middleware(['auth:sanctum'])->get('/users/id/{id}', function($id) {
//     return DB::table('users')->where('id', $id)->first();
// });

// route for user by name
Route::get('/users/name/{name}', function($name) {
    return DB::table('users')->where('name', $name)->first();
});

// secure route for user by name
// Route::middleware(['auth:sanctum'])->get('/users/name/{name}', function($name) {
//     return DB::table('users')->where('name', $name)->first();
// });

// // post user
Route::post('/users', function() {
    $id = DB::table('users')->insertGetId([
    'name' => request('name'),
    'password' => bcrypt(request('password'))
    ]);
    return response()->json([
        'message' => 'User created'
    ], 201);
});

// post user advanced
    Route::post('/users', function (Request $request) {
    $name = $request->input('name');
    $email = $request->input('email');
    $password = $request->input('password');

    if (DB::table('users')->where('email', $email)->exists()) {
        return response()->json([
            'message' => 'email already used'
        ], 409);
    }

    if (DB::table('users')->where('name', $name)->exists()) {
        return response()->json([
            'message' => 'username already used'
        ], 409);
    }

    DB::table('users')->insert([
        'name' => $name,
        'password' => Hash::make($password),
        'email' => $email
    ]);

    return response()->json([
        'message' => 'User created'
    ],201);
});


// delete user
Route::delete('/users/id/{id}', function ($id) {
    DB::table('users')->where('id', $id)->delete();
    return response()->json([
        'message' => 'User deleted'
    ]);
});

// secure route for delete user
// Route::middleware(['auth:sanctum'])->delete('/users/id/{id}', function ($id) {
//     DB::table('users')->where('id', $id)->delete();
//     return response()->json([
//         'message' => 'User created'
//     ]);
// });

// patch password
Route::patch('/users/id/{id}', function ($id) {
    DB::table('users')->where('id', $id)->update([
    'password' => bcrypt(request('password'))
    ]);
    return "password updated";
});

// // secure route patch password
// Route::middleware(['auth:sanctum'])->patch('/users/id/{id}', function ($id) {
//     DB::table('users')->where('id', $id)->update([
//     'password' => bcrypt(request('password'))
//     ]);
//     return "password updated";
// });
// create tokens with post (needs at least 1 user)
Route::post('/tokens/create', function (Request $request) {
    $user = App\Models\User::find($request->user_id);
    $token = $user->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});