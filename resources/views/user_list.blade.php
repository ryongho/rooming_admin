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
                        <select class="form-select search_type mb-1" id="leave" aria-label=".search_type" style="width:auto;float:left;margin-right:10px;">
                            <option selected value="">전체</option>
                            <option value="N">가입자</option>
                            <option value="Y">탈퇴자</option>
                        </select>
                        <input type="hidden" id="temp_search_type" value="{{$list->search_type}}"/>
                        <input type="hidden" id="temp_leave" value="{{$list->leave}}"/>
                        <input type="text" id="datePicker-start" class="form-control" style="width:10%;float:left;margin-right:10px;" value="{{$list->start_date}}" />
                        <input type="text" id="datePicker-end" class="form-control" style="width:10%;float:left;margin-right:10px;" value="{{$list->end_date}}" />
                        <input class="form-control border-1" id="search_keyword" type="search" placeholder="Search" value="{{$list->search_keyword}}" style="width:30%;float:left;margin-right:\10px;">
                        <button type="button" class="btn btn-outline-secondary m-2" id="btn_search" style="width:auto;float:left;margin:0px;" onclick="get_list(1)" >검색</button>
                        <div class="div_total_cnt" style="background:#009CFF;color:white;width:100px;height:40px;float:right;right:10px;padding:10px;text-align:center;margin-right:10%;">총 {{$list->total_cnt}} 명</div>
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
                                        <th scope="col" style="width:50px;">예약 건</th>
                                        
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
                                            @if($data['reservation_cnt'] > 0)
                                                <td><span onclick="pop_reservations('{{ $data['id'] }}')" style="cursor:pointer;color:blue;">{{ $data['reservation_cnt'] }}</span></td>
                                            @else
                                                <td>{{ $data['reservation_cnt'] }}</td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">조회된 예약이 없습니다.</td>
                                        </tr>
                                    @endforelse   
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">예약 내역</h5>
                                    <button type="button" class="close btn_close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
    
                                     <table class="table table-bordered" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">예약 번호</th>
                                                <th scope="col">예약 상품</th>
                                                <th scope="col">예약 일시</th>
                                                <th scope="col">상태</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reservation_table">
                                            
                                        </tbody>
                                    </table>

                                    <input type="hidden" id="modal_id" value="">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn_close" data-dismiss="modal">Close</button>
                                </div>
                            </div>
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
            $("#leave").val($("#temp_leave").val()).prop("selected", true);
        });
        const get_list = function(page_no){
            const search_type = $("#search_type").val();
            const start_date = $("#datePicker-start").val();
            const end_date = $("#datePicker-end").val();
            const search_keyword = $("#search_keyword").val();
            const leave = $("#leave").val();

            $url = '/user_list?page_no='+page_no+'&start_date='+start_date+'&end_date='+end_date+'&search_type='+search_type+'&search_keyword='+search_keyword+'&leave='+leave;

            window.location.replace($url);
            
        }

        const pop_reservations = function(user_id){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $.ajax({
                method: "POST",
                url: "/get_reservation_list_by_user",
                data: { user_id : user_id},
            })
            .done(function(data) {
                const status = data.status;
                //alert(data.status);
                if(data.status == "200"){
                    
                    const title = data.user_info['name']+"님의 예약 내용";
                    let content = "";

                    data.data.forEach(function(row){

                        let date = new Date(row['created_at']);
                        let created_at = date.getFullYear()+"-"+date.getMonth()+"-"+date.getDate()+" "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
                        content += "<tr><td>"+row['reservation_no']+"</td><td>"+row['goods_name']+"</td><td>"+created_at+"</td><td>"+row['status']+"</td></tr>";
                        
                    });

                    $("exampleModalLabel").val(title);
                    $("#reservation_table").html(content);
                    $("#modal_id").val(user_id);

                    $('#detailModal').modal('toggle');

                }
            });
        
            
        }

        $(".btn_close").click(function(){

            $("#modal_content").val("");
            $("#modal_id").val("");

            $('#detailModal').modal('hide');


        });

        
    </script>

@endsection