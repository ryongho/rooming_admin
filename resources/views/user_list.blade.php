<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'child')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">
            
            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" >
                        <h6 class="mb-4" style="margin:30px 10px;">회원 목록</h6>
                    </div>
                    <div class="col-12 bg-white px-4 py-0" style="float:left;">
                        <select class="form-select search_type mb-1" id="search_type" aria-label=".search_type" style="width:auto;float:left;margin-right:10px;">
                            <option selected value="">전체</option>
                            <option value="name">고객명</option>
                            <option value="email">이메일</option>
                            <option value="phone">전화번호</option>
                        </select>
                        <inptu type="hidden" id="start_date" />
                        <inptu type="hidden" id="end_date" />
                        <input type="text" id="datePicker-start" class="form-control" style="width:10%;float:left;margin-right:10px;" value="2021-01-01" />
                        <input type="text" id="datePicker-end" class="form-control" style="width:10%;float:left;margin-right:10px;" value="2022-12-31" />
                        <input class="form-control border-1" id="search_keyword" type="search" placeholder="Search" style="width:30%;float:left;margin-right:\10px;">
                        <button type="button" class="btn btn-outline-secondary m-2" id="btn_search" style="width:auto;float:left;margin:0px;" onclick="get_list(1)" >검색</button>
                    </div>
            
                    <div class="col-12">
                        <div class="bg-white rounded h-100 p-4">
                            
                            <table class="table table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">회원 아이디</th>
                                        <th scope="col">고객명</th>
                                        <th scope="col">연락처</th>
                                        <th scope="col">이메일</th>
                                        <th scope="col">닉네임</th> 
                                        <th scope="col">등록일</th>
                                        <th scope="col">수정일</th>
                                        <th scope="col" style="width:50px;">탈퇴여부</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="data_table">
                                    @forelse($list->data as $data)
                                        <tr>
                                            <td>{{ $data['id'] }}</td>
                                            <td>{{ $data['name'] }}</td>
                                            <td>{{ $data['phone'] }}</td>
                                            <td>{{ $data['email'] }}</td>
                                            <td>{{ $data['nickname'] }}</td>
                                            <td>{{ $data['created_at'] }}</td>
                                            <td>{{ $data['updated_at'] }}</td>
                                            <td>{{ $data['leave'] }}</td>
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

                        @for($i= 1; $i <= $list->total_page; $i++)
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
        const get_list = function(page_no){
            const search_type = $("#search_type").val();
            const start_date = $("#datePicker-start").val();
            const end_date = $("#datePicker-end").val();
            const search_keyword = $("#search_keyword").val();

            $url = '/user_list?page_no='+page_no+'&start_date='+start_date+'&end_date='+end_date+'&search_type='+search_type+'&search_keyword='+search_keyword;

            window.location.replace($url);
            
        }
    </script>

@endsection