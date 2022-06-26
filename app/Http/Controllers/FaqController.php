<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function regist(Request $request)
    {
        
        $return = new \stdClass;   

        $login_user = Auth::user();
        

        $result = Faq::insertGetId([
            'title'=> $request->title ,
            'content'=> $request->content ,
            'writer'=> $login_user->getId(),
            'created_at'=> Carbon::now(),
        ]);

        if($result){ //DB 입력 성공
            $return->status = "200";
            $return->msg = "success";
        }else{
            $return->status = "501";
            $return->msg = "fail";
        }
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    }

    public function list(Request $request){


        $rows = Faq::select(DB::raw('*','(select nickname from users where faqs.writer = users.id order by order_no asc limit 1 ) as writer'))->get();

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

        $rows = Faq::where('id','=',$id)->get();

        $return = new \stdClass;

        $return->status = "200";
        $return->data = $rows ;

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

            $grant = Faq::where('id',$request->id)->where('writer',$user_id)->count();
        
            if($grant){

                $result = Faq::where('id',$request->id)->where('writer',$user_id)->update([
                    'title'=> $request->title ,
                    'content'=> $request->content ,
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

    public function faq_list(Request $request){


        $page_no = 1;
        if($request->page_no){
            $page_no = $request->page_no;
        }

        $row = 50;
        
        $offset = (($page_no-1) * $row);


        $rows = Faq::orderBy('id', 'desc')
                ->offset($offset)
                ->limit($row)->get();
                        

        $count = Faq::count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->page_no = $request->page_no;
        $list->total_cnt = $count;

        $list->total_page = floor($count/$row)+1;
        $list->data = $rows;
        
        return view('faq_list', ['list' => $list]);

    }

    public function save(Request $request)
    {
        
        $return = new \stdClass;   

        $login_user = Auth::user();

        $resutl;
        
        if($request->id){
            $result = Faq::where('id',$request->id)
                ->update([
                    'title'=> $request->title ,
                    'content'=> $request->content ,
                    'writer'=> $login_user->getId(),
                    'created_at'=> Carbon::now(),
                ]);
            
        }else{

            $result = Faq::insertGetId([
                'title'=> $request->title ,
                'content'=> $request->content ,
                'writer'=> $login_user->getId(),
                'created_at'=> Carbon::now(),
            ]);
        }
        

        if($result){ //DB 입력 성공
            $return->status = "200";
            $return->msg = "success";
        }else{
            $return->status = "501";
            $return->msg = "fail";
        }
        

        return redirect()->route('faq_list');
    }

    public function delete(Request $request)
    {
        
        $return = new \stdClass;   

        $login_user = Auth::user();

        $result = Faq::where('id',$request->id)->delete();

        if($result){ //DB 입력 성공
            $return->status = "200";
            $return->msg = "success";
        }else{
            $return->status = "501";
            $return->msg = "fail";
        }
        

        return redirect()->route('faq_list');
    }

    



}
