<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\RecommendController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\FaqController;

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

Route::middleware('auth:sanctum')->get('/user_list', [UserController::class, 'user_list'])->name('user_list');
Route::middleware('auth:sanctum')->get('/partner_list', [PartnerController::class, 'partner_list'])->name('partner_list');
Route::middleware('auth:sanctum')->get('/reservation_list', [ReservationController::class, 'reservation_list'])->name('reservation_list');
Route::middleware('auth:sanctum')->get('/hotel_list', [HotelController::class, 'hotel_list'])->name('hotel_list');
Route::middleware('auth:sanctum')->get('/room_list', [RoomController::class, 'room_list'])->name('room_list');
Route::middleware('auth:sanctum')->get('/goods_list', [GoodsController::class, 'goods_list'])->name('goods_list');
Route::middleware('auth:sanctum')->get('/recommend_list', [RecommendController::class, 'recommend_list'])->name('recommend_list');
Route::middleware('auth:sanctum')->get('/change_recommend', [RecommendController::class, 'change_recommend'])->name('change_recommend');
Route::middleware('auth:sanctum')->get('/event_list', [EventController::class, 'event_list'])->name('event_list');
Route::middleware('auth:sanctum')->get('/notice_list', [NoticeController::class, 'notice_list'])->name('notice_list');
Route::middleware('auth:sanctum')->get('/faq_list', [FaqController::class, 'faq_list'])->name('faq_list');

Route::middleware('auth:sanctum')->get('/save_event', [EventController::class, 'save'])->name('save_event');
Route::middleware('auth:sanctum')->get('/delete_event', [EventController::class, 'delete'])->name('delete_event');

Route::middleware('auth:sanctum')->get('/save_notice', [NoticeController::class, 'save'])->name('save_notice');
Route::middleware('auth:sanctum')->get('/delete_notice', [NoticeController::class, 'delete'])->name('delete_notice');

Route::middleware('auth:sanctum')->get('/save_faq', [FaqController::class, 'save'])->name('save_faq');
Route::middleware('auth:sanctum')->get('/delete_faq', [FaqController::class, 'delete'])->name('delete_faq');
Route::post('/get_reservation_list_by_user', [ReservationController::class, 'get_list_by_user'])->name('get_list_by_user');






