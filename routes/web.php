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




