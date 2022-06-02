<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', '추천 상품 목록')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">
            
            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" >
                        <h6 class="mb-4" style="margin:30px 10px;">추천 상품 목록</h6>
                    </div>
            
                    <div class="col-12">
                        <div class="bg-white rounded h-100 p-4">
                            
                            <table class="table table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">순번</th>
                                        <th scope="col">호텔 이름</th>
                                        <th scope="col">상품 이름</th>
                                        <th scope="col">상품 아이디</th>
                                    </tr>
                                </thead>
                                <tbody id="data_table">
                                    @forelse($list->data as $data)
                                        <tr>
                                            <td>{{ $data['order_no'] }}</td>
                                            <td>{{ $data['hotel_name'] }}</td>
                                            <td>{{ $data['goods_name'] }}</td>
                                            <td><input type="text"  class="form-control" id="goods_id_{{ $data['order_no'] }}" style="width:80px;float:left;height:30px;margin:8px;" value="{{ $data['goods_id'] }}"/> <input class="btn btn-outline-secondary m-2 btn-sm" style="float:left;" onclick ="change({{ $data['order_no'] }})" type="button" value="변경"/></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">조회된 상품이 없습니다.</td>
                                        </tr>
                                    @endforelse   
                                    
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
    
                </div>
            </div>
            <!-- Table End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <script>
        $().ready(function(){
            $("#search_type").val($("#temp_search_type").val()).prop("selected", true);
        });
        
        const change = function(order_no){
            const goods_id = $("#goods_id_"+order_no).val();
            
            $url = '/change_recommend?order_no='+order_no+'&goods_id='+goods_id;

            alert("변경되었습니다.");
            window.location.replace($url);

            
        }
    </script>

@endsection