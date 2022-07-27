<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use PHPExcel; 
use PHPExcel_IOFactory;
use Illuminate\Support\Facades\DB;


class PartnerController extends Controller
{
    public function regist(Request $request)
    {
        //dd($request);

        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->user_id;

        /* 중복 체크 - start*/
        $id_cnt = Partner::where('user_id',$request->user_id)->count();
        $email_cnt = Partner::where('email',$request->email)->count();
        $phone_cnt = Partner::where('phone',$request->phone)->count();

        if($id_cnt){
            $return->status = "601";
            $return->msg = "사용중인 아이디";
            $return->data = $request->user_id;
        }else if($email_cnt){
            $return->status = "602";
            $return->msg = "사용중인 이메일";
            $return->data = $request->email;
        }else if ($phone_cnt){
            $return->status = "603";
            $return->msg = "사용중인 폰 번호";
            $return->data = $request->phone;
        /* 중복 체크 - end*/
        }else{
            $result = User::insert([
                'name'=> $request->name ,
                'email' => $request->email, 
                'password' => $request->password, 
                'user_id' => $request->user_id,
                'phone' => $request->phone, 
                'user_type' => 1,
                'created_at' => Carbon::now(),
                'password' => Hash::make($request->password)
                
            ]);

            if($result){
                $return->status = "200";
                $return->msg = "success";
                $return->data = $request->user_id;
            }
        }
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;     

    }


    public function login(Request $request){
        $partner = User::where('user_id' , $request->user_id)->where('user_type', 1 )->first();

        $return = new \stdClass;

        if (Hash::check($request->password, $partner->password)) {
            //dd($partner);
            //echo("로그인 확인");
            Auth::loginUsingId($partner->id);
            $login_user = Auth::user();

            $token = $login_user->createToken('partner');

            $return->status = "200";
            $return->msg = "success";
            $return->token = $token;

        }else{
            $return->status = "500";
            $return->msg = "아이디 또는 패스워드가 일치하지 않습니다.";
            $return->data = $request->user_id;
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    }

    public function logout(Request $request){
        $user = Auth::user(); 
        Auth::logout();
        
    }

    public function login_check(Request $request){
        
        $login_user = Auth::user();
        dd($login_user);
        

        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->user_id;
        $return->user_type = "partner";

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    
        //$result = auth('api')->check();
        //dd($result);
        //return $request->user();
        
    }

    public function find_partner_id(Request $request){
        $partner = Partner::where('phone' , $request->phone)->first();
        
        $msg = "";
        if (isset($partner->id)) {
            $msg = "파트너 아이디는 ".$partner->partner_id." 입니다.";
        }else{
            $msg = "등록되지 않은 연락처 입니다.";  
        }
        $return = new \stdClass;
        
        $return->status = "200";
        $return->msg = $msg;
        $return->partner_id = $partner->partner_id ;
        
        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    }

    public function partner_list(Request $request){
        $page_no = 1;
        if($request->page_no){
            $page_no = $request->page_no;
        }

        $row = 50;
        
        $offset = (($page_no-1) * $row);

        $start_date = "2021-01-01";
        $end_date = date('Y-m-d H:i:s');
        if($request->start_date){
            $start_date = $request->start_date;
        }

        if($request->end_date){
            $end_date = $request->end_date;
        }
        $leave = null;
        if($request->leave != ""){
            $leave = $request->leave;
        }

        $search_type = $request->search_type;
        $search_keyword = $request->search_keyword;

        $search = null;
        if($search_type){
            $search = $request->search_type.",".$request->search_keyword;
        }
        
        
        $rows = User::leftJoin('hotels', 'users.id', '=', 'hotels.partner_id')
                ->select('users.id as id',
                        'users.name as name',
                        'users.phone as phone',
                        'users.email as email',
                        'hotels.name as hotel_name',
                        'users.leave as leave',
                        'users.created_at as created_at',
                        'users.updated_at as updated_at',
                        'hotels.type as hotel_type',
                )
                ->where('user_type','1')
                ->when($start_date, function ($query, $start_date) {
                    return $query->where('users.created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('users.created_at' ,"<=", $end_date);
                })
                ->when($leave , function ($query, $leave) {
                    if($leave == "X"){
                        return $query->whereNull('hotels.id');
                    }else{
                        return $query->where('users.leave', $leave);
                    }
                    
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    return $query->where('users.'.$search_arr[0] ,"like", "%".$search_arr[1]."%");
                })
                ->offset($offset)
                ->orderBy('users.id', 'desc')
                ->limit($row)->get();

        $count = User::leftJoin('hotels', 'users.id', '=', 'hotels.partner_id')
                ->where('user_type','1')
                ->when($start_date, function ($query, $start_date) {
                    return $query->where('users.created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('users.created_at' ,"<=", $end_date);
                })
                ->when($leave , function ($query, $leave) {
                    if($leave == "X"){
                        return $query->whereNull('hotels.id');
                    }else{
                        return $query->where('users.leave', $leave);
                    }
                    
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    return $query->where('users.'.$search_arr[0] ,"like", "%".$search_arr[1]."%");
                })->count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->page_no = $request->page_no;
        $list->start_date = $start_date;
        $list->end_date = $end_date;
        $list->search_type = $request->search_type;
        $list->search_keyword = $request->search_keyword;
        $list->leave = $request->leave;

        $list->total_cnt = $count;

        $list->total_page = floor($count/$row)+1;
        $list->data = $rows;
        
        return view('partner_list', ['list' => $list]);
        
    }

    
    public function list_download(Request $request){
        ob_start();
        $start_no = $request->start_no;
        $row = $request->row;
        
        $rows = User::select(   'id',
                                'name',
                                'nickname',
                                'phone',
                                'email',
                                'user_id',
                                'created_at',
                                'updated_at',
                                'leave',
                )->where('id' ,">=", $start_no)->where('user_type','1')->orderBy('id', 'desc')->orderBy('id')->limit($row)->get();

        
        $list = array();
        $i = 0;

        foreach($rows as $row){
            
            $list[$i]['id'] = $row->id;
            $list[$i]['user_id'] = $row->user_id;
            $list[$i]['name'] = $row->name;
            $list[$i]['phone'] = $row->phone;
            $list[$i]['email'] = $row->email;
            $list[$i]['created_at'] = $row->created_at;
            $list[$i]['updated_at'] = $row->updated_at;
            $list[$i]['nickname'] = $row->nickname;
            $list[$i]['leave'] = $row->leave;

            $i++;
        }
        //dd($list);
        
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Seoul');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        set_time_limit(120); 
        ini_set("memory_limit", "256M");

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                    ->setLastModifiedBy("Maarten Balliauw")
                                    ->setTitle("Office 2007 XLSX Test Document")
                                    ->setSubject("Office 2007 XLSX Test Document")
                                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Test result file");


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '유저아이디')
                    ->setCellValue('B1', '이름')
                    ->setCellValue('C1', '연락처')
                    ->setCellValue('D1', '이메일')
                    ->setCellValue('E1', '닉네임')
                    ->setCellValue('F1', '등록일')
                    ->setCellValue('G1', '수정일')
                    ->setCellValue('H1', '탈퇴여부');
        $i = 2;
        foreach ($list as $row){

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $row['user_id'])
                        ->setCellValue('B'.$i, $row['name'])
                        ->setCellValue('C'.$i, $row['phone'])
                        ->setCellValue('D'.$i, $row['email'])
                        ->setCellValue('E'.$i, $row['nickname'])
                        ->setCellValue('F'.$i, $row['created_at'])
                        ->setCellValue('G'.$i, $row['updated_at'])
                        ->setCellValue('H'.$i, $row['leave']);
            $i++;
        }
                                
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('partner_list');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="partner_list.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
    }
}
