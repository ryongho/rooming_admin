<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
    return view('main');
});
Route::get('/contact_form', function () {
    return view('contact_form');
});
Route::get('/contact_regist', function () {
    return view('contact_form');
});
Route::get('/regist', function (Request $request) {
 
    DB::table('contacts')->insert([
        'hotel_name'=> $request->hotel_name ,
        'hotel_type'=> $request->hotel_type ,
        'local' => $request->local, 
        'homepage' => $request->homepage, 
        'email' => $request->email,
        'phone' => $request->phone, 
        'type' => $request->type,
        'title' => $request->title,
        'content' => $request->content,
        'created_at' => Carbon::now(),
    ]);

    echo("<script>alert('등록되었습니다. 담당자가 확인이 연락 드릴 예정입니다.');</script>");
    Redirect::to('/');
});

Route::get('/policy/print', [PolicyController::class, 'print']);

