<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Email;

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

    $email = new \stdClass;
    $email->email = "sales2@dnsolution.kr";
    $email->content = "<br/><br/> 숙소이름 : ".$request->hotel_name;
    $email->content .= "<br/>숙소형태 : ".$request->hotel_type;
    $email->content .= "<br/>지역 : ".$request->local;
    $email->content .= "<br/>홈페이지 : ".$request->homepage;
    $email->content .= "<br/>이메일 : ".$request->email;
    $email->content .= "<br/>연락처 : ".$request->phone;
    $email->content .= "<br/>문의 분류 : ".$request->type;
    $email->content .= "<br/>문의 제목 : ".$request->title;
    $email->content .= "<br/>문의 내용 : ".$request->content;
    $email->content .= "<br/>문의 시간 : ".Carbon::now();

    $email->title = "[제휴문의]".$request->title;

    Email::send($email);
    
    echo("<script>alert('등록되었습니다. 담당자가 확인이 연락 드릴 예정입니다.');</script>");
    echo("<script>window.location.href='/'</script>");
    
});

Route::get('/policy/print', [PolicyController::class, 'print']);

