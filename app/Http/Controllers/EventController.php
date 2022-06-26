<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function regist(Request $request)
    {
        
        $return = new \stdClass;   

        $login_user = Auth::user();
        

        $result = Event::insertGetId([
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

    public function save(Request $request)
    {
        
        $return = new \stdClass;   

        $login_user = Auth::user();

        $resutl;
        
        if($request->id){
            $result = Event::where('id',$request->id)
                ->update([
                    'title'=> $request->title ,
                    'content'=> $request->content ,
                    'writer'=> $login_user->getId(),
                    'created_at'=> Carbon::now(),
                ]);
            
        }else{

            $result = Event::insertGetId([
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
        

        return redirect()->route('event_list');
    }

    public function delete(Request $request)
    {
        
        $return = new \stdClass;   

        $login_user = Auth::user();

        $result = Event::where('id',$request->id)->delete();

        if($result){ //DB 입력 성공
            $return->status = "200";
            $return->msg = "success";
        }else{
            $return->status = "501";
            $return->msg = "fail";
        }
        

        return redirect()->route('event_list');
    }

    public function event_list(Request $request){


        $page_no = 1;
        if($request->page_no){
            $page_no = $request->page_no;
        }

        $row = 50;
        
        $offset = (($page_no-1) * $row);


        $rows = Event::orderBy('id', 'desc')
                ->offset($offset)
                ->limit($row)->get();
                        

        $count = Event::count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->page_no = $request->page_no;
        $list->total_cnt = $count;

        $list->total_page = floor($count/$row)+1;
        $list->data = $rows;
        
        return view('event_list', ['list' => $list]);

    }

    public function list(Request $request){


        $rows = Event::select(DB::raw('*','(select nickname from users where notices.writer = users.id order by order_no asc limit 1 ) as writer'))->get();

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

        $rows = Event::where('id','=',$id)->get();

        $return = new \stdClass;

        $return->status = "200";
        $return->data = $rows ;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    



}
