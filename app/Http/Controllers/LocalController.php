<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Local;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LocalController extends Controller
{
    public function regist(Request $request)
    {
        
        $return = new \stdClass;        

        $recommend = Local::where('order_no',$request->order_no)->count();

        if($recommend){
            Local::where('order_no',$request->order_no)->delete();
        }
    
        $result = Local::insertGetId([
            'name'=> $request->name ,
            'latitude'=> $request->latitude ,
            'longtitude'=> $request->longtitude ,
            'order_no'=> $request->order_no ,
            'img_src'=> $request->img_src ,
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


        $rows = Local::orderBy('order_no','asc')
                        ->get();

        $return = new \stdClass;

        $return->status = "200";
        $return->cnt = count($rows);
        $return->data = $rows ;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function local_list(Request $request){


        $rows = Local::orderBy('order_no', 'asc')->get();
                        

        $count = Local::count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->total_cnt = $count;

        $list->data = $rows;
        
        return view('local_list', ['list' => $list]);

    }

    public function update_addr(Request $request){


        $result = Local::where('id',$request->id)
                ->update([
                    'latitude'=> $request->latitude ,
                    'longtitude'=> $request->longtitude,
                    'updated_at'=> Carbon::now(),
                ]);
        
        return redirect()->route('local_list');

    }

    public function update_orderno(Request $request){


        $result = Local::where('id',$request->id)
                ->update([
                    'order_no'=> $request->orderno ,
                    'updated_at'=> Carbon::now(),
                ]);
        
        return redirect()->route('local_list');

    }

    public function upload_local(Request $request){
        
        $images = array();
        $no =0;

        foreach($request->file() as $file){// 객실 이미지 업로드

            $images[$no] = Storage::disk('s3')->put("local_images", $file,'public');     
            $no++;
        } 

        $return = new \stdClass;        

        $order_no = (Local::select('order_no')->max('order_no'))+1;

        $result = Local::insertGetId([
            'name'=> $request->name ,
            'latitude'=> $request->latitude ,
            'longtitude'=> $request->longtitude ,
            'img_src'=> $images[0],
            'order_no'=> $order_no,
            'created_at'=> Carbon::now(),
        ]);

        if($result){ //DB 입력 성공
            $return->status = "200";
            $return->msg = "success";
        }else{
            $return->status = "501";
            $return->msg = "fail";
        }
        

        return redirect()->route('local_list');

    }

    public function delete(Request $request)
    {
        
        $return = new \stdClass;   

        $result = Local::where('id',$request->id)->delete();

        if($result){ //DB 입력 성공
            $return->status = "200";
            $return->msg = "success";
        }else{
            $return->status = "501";
            $return->msg = "fail";
        }
        

        return redirect()->route('local_list');
    }
    
    

    



}
