<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Http\Controllers\UserController;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


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

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login_proc', [UserController::class, 'login'])->name('login_proc');

Route::middleware('auth:sanctum')->get('/', function () {
    return view('main');
})->name('main');

Route::get('/child', function () {
    return view('child');
})->name('child');

Route::get('/child2', function () {
    return view('child2');
})->name('child2');

Route::get('/user_list', [UserController::class, 'user_list'])->name('user_list');

Route::get('/hotel_list', function () {
    return view('child2');
})->name('hotel_list');
Route::get('/reservation_list', function () {
    return view('reservation_list');
})->name('reservation_list');


Route::get('/partner', function () {
    return view('partner');
});

Route::get('/contact', function () {
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


    $title = $email->title; 
        $subject = "=?EUC-KR?B?".base64_encode(iconv("UTF-8","EUC-KR",$title))."?=";
        
        $content = $email->content;
        
        $mail = new PHPMailer(true);         
        $return = new \stdClass;
        $result =  true;
        
        try {
            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = env('MAIL_HOST');                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = env('MAIL_USERNAME');                     // SMTP username
            $mail->Password   = env('MAIL_PASSWORD');                               // SMTP password
            $mail->CharSet = 'utf-8'; 
            $mail->Encoding = "base64";
            $mail->SMTPSecure = 'ssl';          
            $mail->Port       = env('MAIL_PORT');                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            

            //Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), '루밍');

            $mail->addAddress($email->email);     // Add a recipient
            
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $content;

            $email->state = $mail->send();

            //echo 'Message has been sent';
            //$result =  true;
            echo("<script>alert('등록되었습니다. 담당자가 확인이 연락 드릴 예정입니다.');</script>");
            echo("<script>window.location.href='/'</script>");
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $email->state = false;
            echo("<script>alert('등록실패했습니다. 담당자에게 문의 하세요.');</script>");
            echo("<script>window.location.href='/'</script>");
        }
    
    
    
});

Route::get('/policy/print', [PolicyController::class, 'print']);

