<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', '예약목록')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">
            
            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" >
                        <h6 class="mb-4" style="margin:30px 10px;">예약 목록</h6>
                    </div>
                    <div class="col-12 bg-white px-4 py-0" style="float:left;">
                    <select class="form-select search_type mb-1" id="search_type" aria-label=".search_type" style="width:auto;float:left;margin-right:10px;">
                            <option selected value="">전체</option>
                            <option value="name">고객명</option>
                            <option value="hotel_name">숙소명</option>
                            <option value="reservation_no">예약번호</option>
                            <option value="phone">전화번호</option>
                        </select>
                        <select class="form-select search_type mb-1" id="status_type"aria-label=".status_type" style="width:auto;float:left;margin-right:10px;">
                            <option selected value="">전체</option>
                            <option value="W">예약대기</option>
                            <option value="S">예약확정</option>
                            <option value="C">예약취소</option>
                        </select>
                        <input type="hidden" id="temp_search_type" value="{{$list->search_type}}"/>
                        <input type="text" id="datePicker-start" class="form-control" style="width:10%;float:left;margin-right:10px;" value="{{$list->start_date}}" />
                        <input type="text" id="datePicker-end" class="form-control" style="width:10%;float:left;margin-right:10px;" value="{{$list->end_date}}" />
                        <input class="form-control border-1" id="search_keyword" type="search" placeholder="Search" value="{{$list->search_keyword}}" style="width:30%;float:left;margin-right:\10px;">
                        <button type="button" class="btn btn-outline-secondary m-2" id="btn_search" style="width:auto;float:left;margin:0px;" onclick="get_list(1)" >검색</button>
                        <div class="div_total_cnt" style="background:#009CFF;color:white;width:220px;height:40px;float:right;right:5px;padding:10px;text-align:center;margin-right:5%;">총 {{$list->total_cnt}} 건 / {{number_format($list->total_price)}} 원</div>
                    </div>
            
                    <div class="col-12">
                        <div class="bg-white rounded h-100 p-4">
                            
                            <table class="table table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">예약일</th>
                                        <th scope="col">회원 이름</th>
                                        <th scope="col">예약자명</th>
                                        <th scope="col">연락처</th>
                                        <th scope="col">예약 숙소</th>
                                        <th scope="col">예약 객실</th> 
                                        <th scope="col">예약 상품</th>
                                        <th scope="col">투숙일</th>
                                        <th scope="col" style="width:60px;">현재상태</th>
                                        <th scope="col" style="width:150px;">판매가(원가)</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="data_table">
                                    @forelse($list->data as $data)
                                        <tr>
                                            <td>{{ $data['created_at'] }}</td>
                                            <td>{{ $data['user_name'] }}</td>
                                            <td>{{ $data['name'] }}</td>
                                            <td>{{ $data['phone'] }}</td>
                                            <td>{{ $data['hotel_name'] }}</td>
                                            <td>{{ $data['room_name'] }}</td>
                                            <td>{{ $data['goods_name'] }}</td>
                                            <td>{{ $data['start_date'] }} ~ {{ $data['end_date'] }}</td>
                                            <td>{{ $list->status_arr[$data['status']] }}</td>
                                            <td>{{ number_format($data['reservation_price']) }}원 ({{ number_format($data['price']) }}원)</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">조회된 회원이 없습니다.</td>
                                        </tr>
                                    @endforelse   
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    

                    <nav aria-label="Page navigation" style="width:100%;">
                        <ul class="pagination" style="width:300px;margin:auto;">

                        @for($i= 1; $i < $list->total_page; $i++)
                            @if($i == $list->page_no)
                                <li class="page-item active"><a class="page-link" href="#" onclick="get_list({{$i}})" value="'{{$i}}'">{{$i}}</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="#" onclick="get_list({{$i}})" value="'{{$i}}'">{{$i}}</a></li>
                            @endif
                        @endfor
                        
                        </ul>
                    </nav>
    
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
            $("#status_type").val("{{$list->status_type}}").prop("selected", true);
        });
        
        const get_list = function(page_no){
            const search_type = $("#search_type").val();
            const start_date = $("#datePicker-start").val();
            const end_date = $("#datePicker-end").val();
            const search_keyword = $("#search_keyword").val();
            const status_type = $("#status_type").val();

            $url = '/reservation_list?page_no='+page_no+'&start_date='+start_date+'&end_date='+end_date+'&search_type='+search_type+'&search_keyword='+search_keyword+'&status_type='+status_type;

            window.location.replace($url);
            
        }
    </script>

@endsection