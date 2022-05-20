<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use PHPExcel; 
use PHPExcel_IOFactory;

class UserController extends Controller
{
    public function regist(Request $request)
    {
        //dd($request);
        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->user_id;

        /* 중복 체크 - start*/
        $email_cnt = User::where('email',$request->email)->count();
        $phone_cnt = User::where('phone',$request->phone)->count();

        /*if($email_cnt){
            $return->status = "602";
            $return->msg = "사용중인 이메일";
            $return->data = $request->email;
        }else if ($phone_cnt){
            $return->status = "603";
            $return->msg = "사용중인 폰 번호";
            $return->data = $request->phone;
        //중복 체크 - end
        }else{*/
            $result = User::insertGetId([
                'name'=> $request->name ,
                'nickname'=> $request->nickname ,
                'email' => $request->email, 
                'password' => $request->password, 
                'user_id' => $request->user_id,
                'phone' => $request->phone, 
                'user_type' => $request->user_type,
                'push' => $request->push,
                'push_event' => $request->push_event,
                'created_at' => Carbon::now(),
                'password' => Hash::make($request->password)
            ]);

            if($result){

                Auth::loginUsingId($result);
                $login_user = Auth::user();

                $token = $login_user->createToken('user');

                $return->status = "200";
                $return->msg = "success";
                $return->data = $request->name;
                $return->token = $token->plainTextToken;
            }
        //}
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

        //return view('user.profile', ['user' => User::findOrFail($id)]);
    }

    public function login(Request $request){

        $user = User::where('email' , $request->email)->where('leave','N')->first();

        $return = new \stdClass;

        if(!$user){
            $return->status = "501";
            $return->msg = "존재하지 않는 아이디 입니다.";
            $return->email = $request->email;
            return redirect()->route('login');
        }else if (Hash::check($request->password, $user->password)) {
            //echo("로그인 확인");
            Auth::loginUsingId($user->id);
            $login_user = Auth::user();

            $token = $login_user->createToken('user');

            $return->status = "200";
            $return->msg = "성공";
            $return->dormant = $login_user->dormant;
            $return->token = $token->plainTextToken;
            
            //dd($token->plainTextToken);
            return redirect()->route('main');    
        }else{
            $return->status = "500";
            $return->msg = "아이디 또는 패스워드가 일치하지 않습니다.";
            $return->email = $request->email;
            return redirect()->route('login');
        }

        
    
    }

   

    public function logout(Request $request){
        $user_info = Auth::user();
        $user = User::where('id', $user_info->id)->first();
        $user->tokens()->delete();

        $return = new \stdClass;
        $return->status = "200";
        $return->msg = "success";

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    }

    public function login_check(Request $request){
        
        $return = new \stdClass;
        //$login_user = Auth::user();
        //$user_id = $login_user->getId();

        if(Auth::check()){
            $return->status = "200";
            $return->login_status = "Y";
        }    

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
        
    }
    

    public function find_user_id(Request $request){
        $user = User::where('phone' , $request->phone)->first();
        
        if (isset($user->id)) {
            echo("사용자 아이디는 ".$user->user_id." 입니다.");       
        }else{
            echo("등록되지 않은 연락처 입니다.");       
        }
    }

    public function user_list(Request $request){
        $page_no = 1;
        if($request->page_no){
            $page_no = $request->page_no;
        }

        $row = 50;
        
        $offset = (($page_no-1) * $row);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $search_type = $request->search_type;
        $search_keyword = $request->search_keyword;

        $search = null;
        if($search_type){
            $search = $request->search_type.",".$request->search_keyword;
        }
        
        
        $rows = User::where('user_type','0')
                ->when($start_date, function ($query, $start_date) {
                    return $query->where('created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('created_at' ,"<=", $end_date);
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    return $query->where($search_arr[0] ,"like", "%".$search_arr[1]."%");
                })
                ->offset($offset)
                ->orderBy('id', 'desc')
                ->limit($row)->get();

        $count = User::where('user_type','0')->count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->page_no = $request->page_no;
        $list->start_date = $request->start_date;
        $list->end_date = $request->end_date;
        $list->search_type = $request->search_type;
        $list->search_keyword = $request->search_keyword;

        $list->total_page = floor($count/20)+1;
        $list->data = $rows;
        
        return view('user_list', ['list' => $list]);
        
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
                )->where('id' ,">=", $start_no)->where('user_type','0')->orderBy('id', 'desc')->orderBy('id')->limit($row)->get();

        

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
        $objPHPExcel->getActiveSheet()->setTitle('user_list');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="user_list.xlsx"');
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


    

    public function info(){
        //dd($request);
        $return = new \stdClass;


        $login_user = Auth::user();

        $return->status = "200";
        $return->data = $login_user;

        if($login_user->user_type == 1){
            $hotel_info = Hotel::where('partner_id',$login_user->id)->first();
            if($hotel_info){
                $return->hotel_id = $hotel_info->id;
            }
            
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update(Request $request){
        //dd($request);
        $return = new \stdClass;


        $login_user = Auth::user();

        $return->status = "200";
        $return->msg = "변경 완료";
        $return->key = $request->key;
        $return->value = $request->value;

        $key = $request->key;
        $value = $request->value;
        $user_id = $login_user->id;

        if($key == "password"){
            $value = Hash::make($request->value);
        }

        $result = User::where('id', $user_id)->update([$key => $value]);

        if(!$result){
            $return->status = "500";
            $return->msg = "변경 실패";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update_password(Request $request){
        //dd($request);
        $return = new \stdClass;

        $return->key = $request->key;
        $return->value = $request->value;

        $key = $request->key;
        $value = $request->value;
        $email = $request->email;

        $user_info = User::where('email',$email)->first(); // 변경 요청 된 고객 정보
        $user_id= $user_info->id;

        $imp_uid = $request->imp_uid;

        
        $_api_url = env('IMPORT_GETTOKEN_URL');     // 본인인증 후 access_token 리턴
        $_param['imp_key'] = env('IMPORT_KEY');
        $_param['imp_secret'] = env('IMPORT_SECRET');    // 아임포트 시크릿
       
        $_curl = curl_init();
        curl_setopt($_curl,CURLOPT_URL,$_api_url);
        curl_setopt($_curl,CURLOPT_POST,true);
        curl_setopt($_curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($_curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($_curl,CURLOPT_POSTFIELDS,$_param);
        $_result = curl_exec($_curl);
        curl_close($_curl);
        $_result = json_decode($_result);
        
        $access_token = $_result->response->access_token;
        $headers = [
            'Authorization:'.$access_token
        ];
        $url = "https://api.iamport.kr/certifications/".$imp_uid; // 정보 요청 url - access_token 추가
        $_curl2 = curl_init();
        curl_setopt($_curl2,CURLOPT_URL,$url);
        curl_setopt($_curl2, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($_curl2,CURLOPT_RETURNTRANSFER,true);
        $_result2 = curl_exec($_curl2);
        $_result2 = json_decode($_result2);
            
        $user_infos= $_result2->response; // 인증 후 고객 정보
        
        $return = new \stdClass;
        
        $user_infos->phone = str_replace ( "-", "", $user_infos->phone); 
        
        if($user_infos->phone == $user_info->phone ){
            $value = Hash::make($request->password);
            $result = User::where('id', $user_id)->update(['password' => $value]);
            $return->status = "200";
            $return->updated_id = $user_id;
            $return->updated_email = $email;

        }else{
            $return->status = "500";
            $return->msg = "인증된 정보와 회원정보가 일치하지 않습니다.";
            $return->updated_id = $user_id;
            $return->updated_email = $email;
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update_info(Request $request){
        //dd($request);
        $return = new \stdClass;

        $return->status = "200";
        $return->msg = "변경 완료";
        
        $result = User::where('id', $request->user_id)->update([
            'name'=> $request->name ,
            'nickname'=> $request->nickname ,
            'email' => $request->email, 
            'phone' => $request->phone, 
            'user_type' => $request->user_type,
            'push' => $request->push,
            'push_event' => $request->push_event,
        ]);

        if(!$result){
            $return->status = "500";
            $return->msg = "변경 실패";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function leave(Request $request){
        //dd($request);
        $return = new \stdClass;
        $login_user = Auth::user();

        $return->status = "200";
        $return->msg = "탈퇴처리 완료";

        $user_id = $login_user->id;

        $result = User::where('id', $user_id)->update(['leave' => 'Y']);

        if(!$result){
            $return->status = "500";
            $return->msg = "탈퇴처리 실패";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }


    


}
