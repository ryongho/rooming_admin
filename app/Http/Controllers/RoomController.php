<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function regist(Request $request)
    {
        //dd($request);

        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->name;

        $hotel_id = $request->hotel_id;

        $login_user = Auth::user();
        $user_id = $login_user->getId();
        $user_type = $login_user->getType();

        $cnt = Hotel::where('partner_id',$user_id)->where('id',$hotel_id)->count();
        
        if($cnt == 0 || $user_id == ""){// 아이디 존재여부
            $return->status = "601";
            $return->msg = "해당 호텔에 객실을 등록 할 수 없는 계정입니다.";
            $return->data = $request->name ;
        }elseif( $user_type == 0 ){//일반회원
            $return->status = "602";
            $return->msg = "일반 회원입니다.";
            $return->data = $request->name ;
        }else{
            $result = Room::insertGetId([
                'hotel_id'=> $request->hotel_id ,
                'name'=> $request->name ,
                'size'=> $request->size ,
                'bed'=> $request->bed ,
                'amount'=> $request->amount ,
                'peoples'=> $request->peoples ,
                'max_peoples'=> $request->max_peoples ,
                'options'=> $request->options ,
                'price'=> $request->price ,
                'checkin'=> $request->checkin ,
                'checkout'=> $request->checkout ,
                'created_at'=> Carbon::now(),
            ]);

            if($result){ //DB 입력 성공

                $no = 1; 

                $images = explode(",",$request->images);
                foreach( $images as $image){
                
                    $result_img = RoomImage::insertGetId([
                        'room_id'=> $result ,
                        'file_name'=> $image ,
                        'order_no'=> $no ,
                        'created_at' => Carbon::now()
                    ]);
    
                    $no++;
                }

    
                $return->status = "200";
                $return->msg = "success";
                $return->insert_id = $result ;

            }
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;    

    }

    public function room_list(Request $request){

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


        $rows = Room::join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
                ->select('*',
                    'hotels.name as hotel_name',
                    DB::raw('(select name from hotels where rooms.hotel_id = hotels.id limit 1 ) as hotel_name'),
                    DB::raw('(select file_name from room_images where room_images.room_id = rooms.id order by order_no asc limit 1 ) as thumb_nail')
                )
                ->when($start_date, function ($query, $start_date) {
                    return $query->where('rooms.created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('rooms.created_at' ,"<=", $end_date);
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    if($search_arr[0] == "hotel_name"){
                        return $query->where("hotels.name" ,"like", "%".$search_arr[1]."%");
                    }else{
                        return $query->where("rooms.".$search_arr[0] ,"like", "%".$search_arr[1]."%");
                    }
                })
                ->orderBy('rooms.id', 'desc')
                ->offset($offset)
                ->limit($row)->get();
                        

        $count = Room::join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
                ->when($start_date, function ($query, $start_date) {
                    return $query->where('rooms.created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('rooms.created_at' ,"<=", $end_date);
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    if($search_arr[0] == "hotel_name"){
                        return $query->where("hotels.name" ,"like", "%".$search_arr[1]."%");
                    }else{
                        return $query->where("rooms.".$search_arr[0] ,"like", "%".$search_arr[1]."%");
                    }
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
        
        return view('room_list', ['list' => $list]);

    }

    public function list_for_select(Request $request){

        $login_user = Auth::user();

        $hotel_info = Hotel::where('partner_id',$login_user->id)->get();

        $rows = Room::select('*',DB::raw('(select file_name from room_images where room_images.room_id = rooms.id order by order_no asc limit 1 ) as thumb_nail'))
                ->where('hotel_id',$hotel_info[0]->id)->orderBy('id', 'desc')->get();

        $return = new \stdClass;

        $return->status = "200";
        $return->cnt = count($rows);
        $return->data = $rows ;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }


    public function list_by_hotel(Request $request){

        $hotel_id = $request->hotel_id;

        $hotel_info = Hotel::where('id',$hotel_id)->get();

        $rows = Room::select('*',DB::raw('(select file_name from room_images where room_images.room_id = rooms.id order by order_no asc limit 1 ) as thumb_nail'))
                ->where('hotel_id',$hotel_info[0]->id)->orderBy('id', 'desc')->get();

        $return = new \stdClass;

        $return->status = "200";
        $return->cnt = count($rows);
        $return->data = $rows ;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function list_by_partner(Request $request){

        $login_user = Auth::user();

        $hotel_info = Hotel::select('id')->where('hotels.partner_id',$login_user->id)->get();

        $rows = array();
        $rn = 0;

        foreach($hotel_info as $hotel){
            $rows[$rn] = Room::select('*',DB::raw('(select file_name from room_images where room_images.room_id = rooms.id order by order_no asc limit 1 ) as thumb_nail'))
                ->where('hotel_id',$hotel->id)
                ->orderBy('rooms.id', 'desc')->get();
            
            $rn++;

        }        

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

        $rows = Room::join('hotels', 'rooms.hotel_id', '=', 'hotels.id')->select('*',
                            'rooms.id as room_id',
                            'rooms.options as room_options',
                            'hotels.options as hotel_options',
                            'rooms.name as name',
                            DB::raw('(select file_name from room_images where room_images.room_id = rooms.id order by order_no asc limit 1 ) as thumb_nail')
                            )
                    ->where('rooms.id','=',$id)->get();
        $images = RoomImage::where('room_id','=',$id)->orderBy('order_no')->get();

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

        $login_user = Auth::user();
        $user_id = $login_user->getId();
        $user_type = $login_user->getType();

        /* 중복 체크 - start*/
        
        
        $id_cnt = User::where('id',$user_id)->count();

        if($id_cnt == 0 || $user_id == ""){// 아이디 존재여부
            $return->status = "601";
            $return->msg = "fail";
            $return->reason = "유효하지 않은 파트너 아이디 입니다." ;
            $return->data = $request->name ;
        }elseif( $user_type == 0 ){//일반회원
            $return->status = "602";
            $return->msg = "fail";
            $return->reason = "유효하지 않은 파트너 아이디 입니다." ;

            $return->data = $request->name ;
        }else{

            $grant = Hotel::where('id',$request->hotel_id)->where('partner_id',$user_id)->count();
        
            if($grant){

                $result = Room::where('id',$request->id)->where('hotel_id',$request->hotel_id)->update([
                    'name'=> $request->name ,
                    'size'=> $request->size ,
                    'bed'=> $request->bed ,
                    'amount'=> $request->amount ,
                    'peoples'=> $request->peoples ,
                    'max_peoples'=> $request->max_peoples ,
                    'options'=> $request->options ,
                    'price'=> $request->price ,
                    'checkin'=> $request->checkin ,
                    'checkout'=> $request->checkout 
                ]);

                if($result){
                    $return->status = "200";
                    $return->msg = "success";
                    $return->updated_id = $result ;
    
                }else{
                    $return->status = "500";
                    $return->msg = "fail";
                }

            }else{
                $return->status = "500";
                $return->msg = "fail";
                $return->reason = "권한이 없습니다." ;
            }            
            
        }
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;   

    }

    public function delete(Request $request)
    {
        //dd($request);
        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";

        $login_user = Auth::user();
        $user_id = $login_user->getId();
        $user_type = $login_user->getType();

        /* 중복 체크 - start*/
        
        
        $id_cnt = User::where('id',$user_id)->count();

        

        $room_info = Room::where('id',$request->room_id)->first();

        if(!$room_info){
            $return->status = "603";
            $return->msg = "fail";
            $return->reason = "해당 데이터가 존재 하지 않습니다." ;
        }elseif($id_cnt == 0 || $user_id == ""){// 아이디 존재여부
            $return->status = "601";
            $return->msg = "fail";
            $return->reason = "유효하지 않은 파트너 아이디 입니다." ;
            $return->data = $request->name ;
        }elseif( $user_type == 0 ){//일반회원
            $return->status = "602";
            $return->msg = "fail";
            $return->reason = "유효하지 않은 파트너 아이디 입니다." ;

            $return->data = $request->name ;
        }else{
            
            
            $grant = Hotel::where('id',$room_info->hotel_id)->where('partner_id',$user_id)->count();
            
            if($grant){

                $result = Room::where('id',$request->room_id)->delete();

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
                $return->reason = "권한이 없습니다." ;
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

            $room_id = $request->room_id;
            $file_name = $request->file_name;
            $order_no = $request->order_no;

            $room_image_cnt = RoomImage::where('room_id',$room_id)->where('order_no', $order_no)->count();
            $result;
            $room_info = Room::where('id',$room_id)->first();

            $grant = Hotel::where('id',$room_info->hotel_id)->where('partner_id',$user_id)->count();

            if($grant){

                if($room_image_cnt){ // 해당 호텔 이미지가 있는 경우는 update
                    $result = RoomImage::where('room_id',$room_id)->where('order_no', $order_no)->update([
                        'room_id'=> $room_id,
                        'file_name'=> $file_name ,
                        'order_no'=> $order_no,
                        
                    ]);
                }else{
                    $result = RoomImage::insert([
                        'room_id'=> $room_id,
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

            }else{
                $return->status = "500";
                $return->msg = "fail";
                $return->reason = "권한이 없습니다." ;
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

            $room_id = $request->room_id;
            $file_name = $request->file_name;
            $order_no = $request->order_no;

            $room_image_cnt = RoomImage::where('room_id',$room_id)->where('order_no', $order_no)->count();
            $result;
            $room_info = Room::where('id',$room_id)->first();

            $grant = Hotel::where('id',$room_info->hotel_id)->where('partner_id',$user_id)->count();

            if($grant){
                $result = RoomImage::where('room_id',$room_id)->where('order_no', $order_no)->delete();

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
