<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function regist(Request $request)
    {
        //dd($request);
        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->name ;

        $login_user = Auth::user();
        $user_id = $login_user->getId();
        $user_type = $login_user->getType();

        /* 중복 체크 - start*/
        
        
        $id_cnt = User::where('id',$user_id)->count();
        
        if($id_cnt == 0 || $user_id == ""){// 아이디 존재여부
            $return->status = "601";
            $return->msg = "유효하지 않은 파트너 아이디 입니다.";
            $return->data = $request->name ;
        }elseif( $user_type == 0 ){//일반회원
            $return->status = "602";
            $return->msg = "일반 회원입니다.";
            $return->data = $request->name ;
        }else{

            


            $result = Hotel::insertGetId([
                'partner_id'=> $login_user->id ,
                'name'=> $request->name ,
                'content'=> $request->content ,
                'owner'=> $request->owner ,
                'reg_no'=> $request->reg_no ,
                'open_date'=> $request->open_date ,
                'address'=> $request->address ,
                'address_detail'=> $request->address_detail ,
                'tel'=> $request->tel ,
                'fax'=> $request->fax ,
                'email'=> $request->email ,
                'traffic'=> $request->traffic ,
                'level'=> $request->level ,
                'latitude' => $request->latitude ,
                'longtitude' => $request->longtitude ,
                'type' => $request->type ,
                'parking' => $request->parking ,
                'refund_rule' => $request->refund_rule ,
                'options' => $request->options ,
                'bank_name' => $request->bank_name ,
                'account_name' => $request->account_name ,
                'account_number' => $request->account_number ,
                'cs_info' => $request->cs_info ,
                'receipt_info' => $request->receipt_info ,
                'common_info' => $request->common_info ,
                'created_at' => Carbon::now()
            ]);

            $no = 1;
            $images = explode(",",$request->images);
            foreach( $images as $image){
            
                $result_img = HotelImage::insertGetId([
                    'hotel_id'=> $result ,
                    'file_name'=> $image ,
                    'order_no'=> $no ,
                    'created_at' => Carbon::now()
                ]);

                $no++;
            }
            

            if($result){
                $return->status = "200";
                $return->msg = "success";
                $return->insert_id = $result ;

            }
            
        }
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;  

    }

    public function list(Request $request){
        
        $s_no = $request->start_no;
        $row = $request->row;

        $rows = Hotel::where('id','>=',$s_no)
        ->whereBetween('latitude', [$request->a_latitude, $request->b_latitude])
        ->whereBetween('longtitude', [$request->a_longtitude, $request->b_longtitude])
        ->orderBy('id', 'desc')->limit($row)->get();

        $return = new \stdClass;

        $return->status = "200";
        $return->cnt = count($rows);
        $return->data = $rows ;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function hotel_list(Request $request){

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

        $search_type = $request->search_type;
        $search_keyword = $request->search_keyword;

        $search = null;
        if($search_type){
            $search = $request->search_type.",".$request->search_keyword;
        }


        $rows = Hotel::when($start_date, function ($query, $start_date) {
                    return $query->where('created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('created_at' ,"<=", $end_date);
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    return $query->where($search_arr[0] ,"like", "%".$search_arr[1]."%");
                })
                ->orderBy('id', 'desc')
                ->offset($offset)
                ->limit($row)->get();
                        

        $count = Hotel::when($start_date, function ($query, $start_date) {
                    return $query->where('created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('created_at' ,"<=", $end_date);
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    return $query->where($search_arr[0] ,"like", "%".$search_arr[1]."%");
                })
                ->count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->page_no = $request->page_no;
        $list->start_date = $start_date;
        $list->end_date = $end_date;
        $list->search_type = $request->search_type;
        $list->search_keyword = $request->search_keyword;
        $list->total_cnt = $count;

        $list->total_page = floor($count/$row)+1;
        $list->data = $rows;
        
        return view('hotel_list', ['list' => $list]);

    }

    public function list_by_partner(Request $request){

        $login_user = Auth::user();

        $rows = Hotel::where('partner_id',$login_user->id)->get();

        $return = new \stdClass;

        $return->status = "200";
        $return->cnt = count($rows);
        $return->data = $rows ;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function detail(Request $request){
        
        $id = $request->id;

        $rows = Hotel::where('id','=',$id)->get();
        $images = HotelImage::where('hotel_id','=',$id)->orderBy('order_no')->get();

        $return = new \stdClass;

        $return->status = "200";
        $return->data = $rows ;
        $return->images = $images ;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update(Request $request)
    {
        //dd($request);
        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->name ;

        $login_user = Auth::user();
        $user_id = $login_user->getId();
        $user_type = $login_user->getType();

        /* 중복 체크 - start*/
        
        
        $id_cnt = User::where('id',$user_id)->count();
        
        if($id_cnt == 0 || $user_id == ""){// 아이디 존재여부
            $return->status = "601";
            $return->msg = "유효하지 않은 파트너 아이디 입니다.";
            $return->data = $request->name ;
        }elseif( $user_type == 0 ){//일반회원
            $return->status = "602";
            $return->msg = "일반 회원입니다.";
            $return->data = $request->name ;
        }else{

            $result = Hotel::where('id',$request->id)->where('partner_id',$user_id)->update([
                'name'=> $request->name ,
                'content'=> $request->content ,
                'owner'=> $request->owner ,
                'reg_no'=> $request->reg_no ,
                'open_date'=> $request->open_date ,
                'address_detail'=> $request->address_detail ,
                'address'=> $request->address ,
                'tel'=> $request->tel ,
                'fax'=> $request->fax ,
                'email'=> $request->email ,
                'traffic'=> $request->traffic ,
                'level'=> $request->level ,
                'latitude' => $request->latitude ,
                'longtitude' => $request->longtitude ,
                'type' => $request->type ,
                'parking' => $request->parking ,
                'refund_rule' => $request->refund_rule ,
                'options' => $request->options ,
                'bank_name' => $request->bank_name ,
                'account_name' => $request->account_name ,
                'account_number' => $request->account_number ,
                'cs_info' => $request->cs_info ,
                'receipt_info' => $request->receipt_info ,
                'common_info' => $request->common_info ,
            ]);
            

            if($result){
                $return->status = "200";
                $return->msg = "success";
                $return->updated_id = $result ;

            }else{
                $return->status = "500";
                $return->msg = "fail";
            }
            
        }
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;    

    }

    public function image_update(Request $request)
    {
        //dd($request);
        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->name ;

        $login_user = Auth::user();
        $user_id = $login_user->getId();
        $user_type = $login_user->getType();

        /* 중복 체크 - start*/
        
        
        $id_cnt = User::where('id',$user_id)->count();
        
        if($id_cnt == 0 || $user_id == ""){// 아이디 존재여부
            $return->status = "601";
            $return->msg = "유효하지 않은 파트너 아이디 입니다.";
            $return->data = $request->name ;
        }elseif( $user_type == 0 ){//일반회원
            $return->status = "602";
            $return->msg = "일반 회원입니다.";
            $return->data = $request->name ;
        }else{

            $hotel_id = $request->hotel_id;
            $file_name = $request->file_name;
            $order_no = $request->order_no;

            $hotel_image_cnt = HotelImage::where('hotel_id',$request->hotel_id)->where('order_no', $order_no)->count();
            $result;

            if($hotel_image_cnt){ // 해당 호텔 이미지가 있는 경우는 update
                $result = HotelImage::where('hotel_id',$request->hotel_id)->where('order_no', $order_no)->update([
                    'hotel_id'=> $hotel_id,
                    'file_name'=> $file_name ,
                    'order_no'=> $order_no,
                    
                ]);
            }else{
                $result = HotelImage::insert([
                    'hotel_id'=> $hotel_id,
                    'file_name'=> $file_name ,
                    'order_no'=> $order_no,
                    'created_at' => Carbon::now()
                ]);
            }
            

            if($result){
                $return->status = "200";
                $return->msg = "success";

            }else{
                $return->status = "500";
                $return->msg = "fail";
            }
            
        }
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;   

    }

    public function image_delete(Request $request)
    {
        //dd($request);
        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->name ;

        $login_user = Auth::user();
        $user_id = $login_user->getId();
        $user_type = $login_user->getType();

        /* 중복 체크 - start*/
        
        
        $id_cnt = User::where('id',$user_id)->count();
        
        if($id_cnt == 0 || $user_id == ""){// 아이디 존재여부
            $return->status = "601";
            $return->msg = "유효하지 않은 파트너 아이디 입니다.";
            $return->data = $request->name ;
        }elseif( $user_type == 0 ){//일반회원
            $return->status = "602";
            $return->msg = "일반 회원입니다.";
            $return->data = $request->name ;
        }else{

            $hotel_id = $request->hotel_id;
            $order_no = $request->order_no;

            $grant = Hotel::where('id',$hotel_id)->where('partner_id',$user_id)->count();

            if($grant){
                $result = HotelImage::where('hotel_id',$request->hotel_id)->where('order_no', $order_no)->delete();

                if($result){
                    $return->status = "200";
                    $return->msg = "success";
                }else{
                    $return->status = "500";
                    $return->msg = "fail";    
                }
            }else{
                $return->status = "500";
                $return->msg = "fail";    
                $return->reason = "삭제 권한이 없습니다.";
            }

            
            
        }
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;   

    }

    

}
