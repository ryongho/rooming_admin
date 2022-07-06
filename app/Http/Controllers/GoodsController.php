<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goods;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel;
use App\Models\GoodsImage;
use App\Models\Wish;
use App\Models\Review;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Quantity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    public function regist(Request $request)
    {
        //dd($request);

        //dd($request);
        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->name ;

        $login_user = Auth::user();
        $user_id = $login_user->getId();
        $user_type = $login_user->getType();

        $cnt = Hotel::where('partner_id',$user_id)->where('id',$request->hotel_id)->count();
        
        if($cnt == 0 || $user_id == ""){// 아이디 존재여부
            $return->status = "601";
            $return->msg = "해당 호텔에 상품을 등록 할 수 없는 계정입니다.";
            $return->data = $request->name ;
        }elseif( $user_type == 0 ){//일반회원
            $return->status = "602";
            $return->msg = "일반 회원입니다.";
            $return->data = $request->name ;
        }else{
            
            $result = Goods::insertGetId([
                'hotel_id'=> $request->hotel_id ,
                'room_id'=> $request->room_id ,
                'goods_name'=> $request->goods_name ,
                'content'=> $request->content ,
                'start_date'=> $request->start_date ,
                'end_date'=> $request->end_date ,
                'nights'=> $request->nights ,
                'options'=> $request->options ,
                'type'=> $request->type ,
                'price'=> $request->price ,
                'sale_price'=> $request->sale_price ,
                'amount'=> $request->amount ,
                'min_nights'=> $request->min_nights ,
                'max_nights'=> $request->max_nights ,
                'breakfast'=> $request->breakfast ,
                'parking'=> $request->parking ,
                'checkin'=> $request->checkin ,
                'checkout'=> $request->checkout ,
                'sale'=> $request->sale ,
                'created_at'=> Carbon::now(),
            ]);

            if($result){ //DB 입력 성공

                $no = 1; 

                $images = explode(",",$request->images);
                foreach( $images as $image){
                
                    $result_img = GoodsImage::insertGetId([
                        'goods_id'=> $result ,
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
        ]);
    }

    public function list(Request $request){
        $s_no = $request->start_no;
        $row = $request->row;

        $orderby = "goods.id";
        $order = "desc";
       

        if($request->orderby == 'price'){
            $orderby = "goods.sale_price";
            $order = "asc";
        }else if($request->orderby == "distance"){
            $orderby = "distance";
            $order = "asc";
        }else if($request->orderby == "review"){
            //$orderby = "review";
            //$order = "desc";
            $orderby = "distance";
            $order = "asc";
        }else if($request->orderby == "grade"){
            //$orderby = "grade";
            //$order = "desc";
            $orderby = "distance";
            $order = "asc";
        }
        
        $user_id = "s";
        
        if($request->bearerToken() != ""){
            $tokens = explode('|',$request->bearerToken());
            $token_info = DB::table('personal_access_tokens')->where('id',$tokens[0])->first();
            $user_id = $token_info->tokenable_id;
        }

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        
        $not_goods = array();                
       
        if($start_date){
            $dates = Quantity::where('date','>=', $start_date )
            ->where('date','<', $end_date )
            ->where('qty','<','1')
            ->where('goods_id','>=',$s_no)
            ->get();

            if(count($dates)){
                $i =0;
                foreach($dates as $date){
                    $not_goods[$i] = $date['goods_id'];
                    $i++;
                }
                
            }
        }
        

        $rows = Goods::join('hotels', 'goods.hotel_id', '=', 'hotels.id')
                        ->join('rooms', 'goods.room_id', '=', 'rooms.id')
                        ->select(   'goods.id as goods_id', 
                                    'hotels.type as shop_type', 
                                    'rooms.name as room_name',
                                    'hotels.name as hotel_name',
                                    'goods.goods_name as goods_name', 
                                    'goods.price as price',
                                    'hotels.address as address',
                                    'goods.sale_price as sale_price',
                                    'goods.checkin as checkin',
                                    'goods.checkout as checkout',
                                    'goods.breakfast as breakfast',
                                    'hotels.parking as parking',
                                    'hotels.latitude as latitude',
                                    'hotels.longtitude as longtitude',
                                    'goods.id as goods_id',
                                    'goods.sale as sale',
                                    Hotel::raw('(6371 * acos( cos( radians('.$request->target_latitude.') ) * cos( radians( hotels.latitude ) ) * cos( radians( hotels.longtitude ) - radians('.$request->target_longtitude.') ) + sin( radians('.$request->target_latitude.') ) * sin( radians( hotels.latitude ) ) ) ) as distance'),
                                    DB::raw('(select count(*) from wishes where goods.id = wishes.goods_id and wishes.user_id="'.$user_id.'" ) as wished '),
                                    DB::raw('(select file_name from goods_images where goods_images.goods_id = goods.id order by order_no asc limit 1 ) as thumb_nail'),
                                    DB::raw('(select avg(grade) from reviews where reviews.goods_id = goods.id) as grade'),
                                    DB::raw('(select count(grade) from reviews where reviews.goods_id = goods.id) as grade_cnt'),
                        )         
                        ->where('goods.id','>=',$s_no)
                        ->where('goods.sale','Y')
                        ->whereNotIn('goods.id', $not_goods)
                        ->whereBetween('hotels.latitude', [$request->a_latitude, $request->b_latitude])
                        ->whereBetween('hotels.longtitude', [$request->a_longtitude, $request->b_longtitude])
                        ->when($start_date, function ($query, $start_date) {
                            return $query->where('start_date' ,"<=", $start_date);
                        })
                        ->when($end_date, function ($query, $end_date) {
                            return $query->where('end_date' ,">=", $end_date);
                        })
                        //->where('start_date' ,"<=", $request->start_date)
                        //->where('end_date' ,">=", $request->end_date)
                        ->orderBy($orderby, $order)
                        ->limit($row)->get();

        


        $return = new \stdClass;

        $return->status = "200";
        $return->cnt = count($rows);
        $return->data = $rows ;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);

    }


    public function goods_list(Request $request){

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

        $sale = $request->sale;


        $rows = Goods::join('hotels', 'goods.hotel_id', '=', 'hotels.id')
                        ->join('rooms', 'goods.room_id', '=', 'rooms.id')
                        ->select(   'goods.id as goods_id', 
                                    'hotels.type as shop_type', 
                                    'rooms.name as room_name',
                                    'hotels.name as hotel_name',
                                    'goods.goods_name as name', 
                                    'goods.price as price',
                                    'hotels.address as address',
                                    'goods.sale_price as sale_price',
                                    'goods.start_date as start_date',
                                    'goods.end_date as end_date',
                                    'hotels.latitude as latitude',
                                    'hotels.longtitude as longtitude',
                                    'goods.id as goods_id',
                                    'goods.sale as sale',
                                    'goods.created_at as created_at',
                                    DB::raw('(select file_name from goods_images where goods_images.goods_id = goods.id order by order_no asc limit 1 ) as thumb_nail'),
                                    DB::raw('(select avg(grade) from reviews where reviews.goods_id = goods.id) as grade'),
                                    DB::raw('(select count(grade) from reviews where reviews.goods_id = goods.id) as grade_cnt'),
                        )         
                        ->when($start_date, function ($query, $start_date) {
                            return $query->where('goods.created_at' ,">=", $start_date);
                        })
                        ->when($sale, function ($query, $sale) {
                            return $query->where('goods.sale', $sale);
                        })
                        ->when($end_date, function ($query, $end_date) {
                            return $query->where('goods.created_at' ,"<=", $end_date);
                        })
                        ->when($search , function ($query, $search) {
                            $search_arr = explode(',',$search);
                            if($search_arr[0] == "hotel_name"){
                                return $query->where("hotels.name" ,"like", "%".$search_arr[1]."%");
                            }else{
                                return $query->where("goods.goods_".$search_arr[0] ,"like", "%".$search_arr[1]."%");
                            }
                        })
                        ->orderBy('goods.id', 'desc')
                        ->offset($offset)
                        ->limit($row)->get();
                        

        $count = Goods::join('hotels', 'goods.hotel_id', '=', 'hotels.id')
                ->when($start_date, function ($query, $start_date) {
                    return $query->where('goods.created_at' ,">=", $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->where('goods.created_at' ,"<=", $end_date);
                })
                ->when($sale, function ($query, $sale) {
                    return $query->where('goods.sale', $sale);
                })
                ->when($search , function ($query, $search) {
                    $search_arr = explode(',',$search);
                    if($search_arr[0] == "hotel_name"){
                        return $query->where("hotels.name" ,"like", "%".$search_arr[1]."%");
                    }else{
                        return $query->where("goods.goods_".$search_arr[0] ,"like", "%".$search_arr[1]."%");
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
        $list->sale = $sale;

        $list->total_page = floor($count/$row)+1;
        $list->data = $rows;
        
        return view('goods_list', ['list' => $list]);

    }

    public function list_by_hotel(Request $request){
        $hotel_id = $request->hotel_id;

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $sale = $request->sale;

        $orderby = "goods.id";
        $order = "desc";

        if($request->orderby == 'price'){
            $orderby = "goods.sale_price";
            $order = "asc";
        }else if($request->orderby == "review"){
            //$orderby = "review";
            //$order = "desc";
            $orderby = "goods.sale_price";
            $order = "asc";
        }else if($request->orderby == "grade"){
            //$orderby = "grade";
            //$order = "desc";
            $orderby = "goods.sale_price";
            $order = "asc";
        }

        $not_goods = array();                
       
        if($start_date){
            $dates = Quantity::where('date','>=', $start_date )
            ->where('date','<', $end_date )
            ->where('qty','<','1')
            ->get();

            if(count($dates)){
                $i =0;
                foreach($dates as $date){
                    $not_goods[$i] = $date['goods_id'];
                    $i++;
                }
                
            }
        }
        
        $rows = Goods::join('hotels', 'goods.hotel_id', '=', 'hotels.id')
                        ->join('rooms', 'goods.room_id', '=', 'rooms.id')
                        ->select(   'hotels.type as shop_type', 
                                    'rooms.name as room_name',
                                    'hotels.name as hotel_name',
                                    'goods.goods_name as goods_name', 
                                    'goods.price as price',
                                    'hotels.address as address',
                                    'goods.sale_price as sale_price',
                                    'goods.checkin as checkin',
                                    'goods.checkout as checkout',
                                    'goods.breakfast as breakfast',
                                    'hotels.parking as parking',
                                    'hotels.latitude as latitude',
                                    'hotels.longtitude as longtitude',
                                    'goods.id as goods_id',
                                    'hotels.id as hotel_id',
                                    'rooms.id as room_id',
                                    'goods.sale as sale',
                                    DB::raw('(select file_name from goods_images where goods_images.goods_id = goods.id order by order_no asc limit 1 ) as thumb_nail'),
                                    DB::raw('(select avg(grade) from reviews where reviews.goods_id = goods.id) as grade'),
                                    DB::raw('(select count(grade) from reviews where reviews.goods_id = goods.id) as grade_cnt'),
                        )         
                        ->where('hotels.id','=',$hotel_id)
                        ->whereNotIn('goods.id', $not_goods)
                        ->when($sale, function ($query, $sale) {
                            return $query->where('goods.sale' ,"=", $sale);
                        })
                        ->when($start_date, function ($query, $start_date) {
                            return $query->where('goods.start_date' ,"<=", $start_date);
                        })
                        ->when($end_date, function ($query, $end_date) {
                            return $query->where('goods.end_date' ,">=", $end_date);
                        })
                        ->orderBy($orderby, $order)
                        ->get();

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

        $rows = Goods::join('hotels', 'goods.hotel_id', '=', 'hotels.id')
                        ->join('rooms', 'goods.room_id', '=', 'rooms.id')
                        ->select(   'hotels.type as shop_type', 
                                    'rooms.name as room_name',
                                    'hotels.name as hotel_name',
                                    'goods.goods_name as goods_name', 
                                    'goods.price as price',
                                    'hotels.address as address',
                                    'goods.sale_price as sale_price',
                                    'goods.checkin as checkin',
                                    'goods.checkout as checkout',
                                    'goods.breakfast as breakfast',
                                    'hotels.parking as parking',
                                    'hotels.latitude as latitude',
                                    'hotels.longtitude as longtitude',
                                    'goods.id as goods_id',
                                    'goods.options as options',
                                    'goods.amount as amount',
                                    'goods.sale as sale',
                                    DB::raw('(select file_name from goods_images where goods_images.goods_id = goods.id order by order_no asc limit 1 ) as thumb_nail'),
                        )         
                        ->where('hotels.partner_id','=',$login_user->id)
                        ->orderBy('goods.id', 'desc')
                        ->get();


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

        $user_id = "s";
        
        if($request->bearerToken() != ""){
            $tokens = explode('|',$request->bearerToken());
            $token_info = DB::table('personal_access_tokens')->where('id',$tokens[0])->first();
            $user_id = $token_info->tokenable_id;
        }

        $rows = Goods::join('hotels', 'goods.hotel_id', '=', 'hotels.id')
                ->join('rooms', 'goods.room_id', '=', 'rooms.id')
                ->select(   'hotels.type as shop_type', 
                'rooms.name as room_name',
                'hotels.name as hotel_name',
                'goods.goods_name as goods_name', 
                'goods.content as content', 
                'goods.price as price',
                'goods.min_nights as min_nights',
                'goods.max_nights as max_nights',
                'hotels.address as address',
                'goods.sale_price as sale_price',
                'goods.checkin as checkin',
                'goods.checkout as checkout',
                'goods.breakfast as breakfast',
                'goods.parking as parking',
                'hotels.latitude as latitude',
                'hotels.longtitude as longtitude',
                'hotels.tel as tel',
                'goods.id as goods_id',
                'hotels.cs_info as cs_info',
                'hotels.receipt_info as receipt_info',
                'hotels.common_info as common_info',
                'hotels.id as hotel_id',
                'rooms.id as room_id',
                'goods.options as options',
                'goods.amount as amount',
                'goods.start_date as start_date',
                'goods.end_date as end_date',
                'goods.sale as sale',
                'rooms.peoples as room_peoples',
                'rooms.bed as room_bed',
                'rooms.amount as room_amount',
                'rooms.options as room_options',
                DB::raw('(select count(*) from wishes where goods.id = wishes.goods_id and wishes.user_id="'.$user_id.'" ) as wished '),
                )
                ->where('goods.id','=',$id)
                ->get();

        $grade = Review::where('goods_id','=',$id)->whereNotNull('grade')->avg('grade');
        
        $images = GoodsImage::where('goods_id','=',$id)->orderBy('order_no')->get();

        $return = new \stdClass;

        $return->status = "200";
        $rows[0]->grade = $grade;
        $return->data = $rows ;
        $return->images = $images ;
        
        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function get_qty(Request $request){
        $goods_id = $request->goods_id;
        $date = $request->date;

        $goods_info = Quantity::where('goods_id','=',$goods_id) 
                ->where('date','=',$date)
                ->first();
    
        $return = new \stdClass;

    
        if($goods_info){
            $return->status = "200";
            $return->qty = $goods_info->qty ;
            $return->date = $goods_info->date ;
        }else{
            $return->status = "200";
            $return->qty = 0;
            $return->qty = $goods_info->date ;
        }
        
        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function get_qty_list(Request $request){
        $goods_id = $request->goods_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        

        $qty_info = Quantity::select('date','qty')
                ->where('goods_id','=',$goods_id) 
                ->where('date','>=',$start_date)
                ->where('date','<=',$end_date)
                ->orderBy('date','asc')
                ->get();
    
        $return = new \stdClass;

        if($qty_info){
            $return->status = "200";
            $return->qty_info = $qty_info;
        }else{
            $return->status = "500";
        }
        
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

                $result = Goods::where('id',$request->goods_id)->where('hotel_id',$request->hotel_id)->update([
                    'hotel_id'=> $request->hotel_id ,
                    'room_id'=> $request->room_id ,
                    'goods_name'=> $request->goods_name ,
                    'content'=> $request->content ,
                    'start_date'=> $request->start_date ,
                    'end_date'=> $request->end_date ,
                    'nights'=> $request->nights ,
                    'options'=> $request->options ,
                    'type'=> $request->type ,
                    'price'=> $request->price ,
                    'sale_price'=> $request->sale_price ,
                    'amount'=> $request->amount ,
                    'min_nights'=> $request->min_nights ,
                    'max_nights'=> $request->max_nights ,
                    'breakfast'=> $request->breakfast ,
                    'checkin'=> $request->checkin ,
                    'checkout'=> $request->checkout ,
                    'sale'=> $request->sale ,
                ]);

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

    public function update_by_key(Request $request)
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

            $goods_info = Goods::where('id',$request->goods_id)->first();

            $grant = Hotel::where('id',$goods_info->hotel_id)->where('partner_id',$user_id)->count();
        
            if($grant){

                $result = Goods::where('id',$request->goods_id)->where('hotel_id',$goods_info->hotel_id)->update([
                    $request->key => $request->value 
                ]);

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

        $goods_info = Goods::where('id',$request->goods_id)->first();

        if(!$goods_info){
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
            
            
            $grant = Hotel::where('id',$goods_info->hotel_id)->where('partner_id',$user_id)->count();
            
            if($grant){

                $result = Goods::where('id',$request->goods_id)->delete();

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

            $goods_id = $request->goods_id;
            $file_name = $request->file_name;
            $order_no = $request->order_no;

            $goods_image_cnt = GoodsImage::where('goods_id',$goods_id)->where('order_no', $order_no)->count();
            $result;
            $goods_info = Goods::where('id',$goods_id)->first();

            $grant = Hotel::where('id',$goods_info->hotel_id)->where('partner_id',$user_id)->count();

            if($grant){

                if($goods_image_cnt){ // 해당 호텔 이미지가 있는 경우는 update
                    $result = GoodsImage::where('goods_id',$goods_id)->where('order_no', $order_no)->update([
                        'goods_id'=> $goods_id,
                        'file_name'=> $file_name ,
                        'order_no'=> $order_no,
                        
                    ]);
                }else{
                    $result = GoodsImage::insert([
                        'goods_id'=> $goods_id,
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

            $goods_id = $request->goods_id;
            $file_name = $request->file_name;
            $order_no = $request->order_no;
            
            $result;
            $goods_info = Goods::where('id',$goods_id)->first();

            $grant = Hotel::where('id',$goods_info->hotel_id)->where('partner_id',$user_id)->count();

            if($grant){
                $result = GoodsImage::where('goods_id',$goods_id)->where('order_no', $order_no)->delete();

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
