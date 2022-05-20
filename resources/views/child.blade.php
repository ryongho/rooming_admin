<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'child')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">
            
            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" style="float:left;">
                        <input type="hidden" id="type" value="W"/>
                        <div style="margin-top:80px;margin-bottom:40px;">
                            <button type="button" class="btn btn-lg btn-primary m-2 btn_status" id="btn_status_W" onclick="change_status('W')">확정대기 <span id="w_cnt"></span></button>
                            <button type="button" class="btn btn-lg btn-outline-secondary m-2 btn_status" id="btn_status_I" onclick="change_status('I')">진행중 <span id="i_cnt"></span></button>
                            <button type="button" class="btn btn-lg btn-outline-secondary m-2 btn_status" id="btn_status_S" onclick="change_status('S')">완료 <span id="s_cnt"></span></button>
                        </div>
                        <select class="form-select search_type mb-1" id="search_type" aria-label=".search_type" style="width:auto;float:left;margin-right:10px;">
                            <option selected value="">전체</option>
                            <option value="reservation_no">예약번호</option>
                            <option value="name">고객명</option>
                            <option value="email">이메일</option>
                            <option value="phone">전화번호</option>
                        </select>
                        <select class="form-select status mb-1" id="status" aria-label=".status" style="width:auto;float:left;margin-right:10px;">
                            <option selected value="">전체</option>
                            <option value="W">확정대기</option>
                            <option value="C">예약취소</option>
                        </select>
                        <inptu type="hidden" id="start_date" />
                        <inptu type="hidden" id="end_date" />
                        <input type="text" id="datePicker-start" class="form-control" style="width:10%;float:left;margin-right:10px;" value="2022-02-01" />
                        <input type="text" id="datePicker-end" class="form-control" style="width:10%;float:left;margin-right:10px;" value="2022-02-28" />
                        <input class="form-control border-1" id="search_keyword" type="search" placeholder="Search" style="width:30%;float:left;margin-right:\10px;">
                        <button type="button" class="btn btn-outline-secondary m-2" id="btn_search" style="width:auto;float:left;margin:0px;">검색</button>
                    </div>
            
                    <div class="col-12">
                        <div class="bg-white rounded h-100 p-4">
                            <h6 class="mb-4">신청 & 지원 현황</h6>
                            <table class="table table-bordered" style="font-size: 9px;">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">신청일시</th>
                                        <th scope="col">예약번호</th>
                                        <th scope="col">고객명</th>
                                        <th scope="col" style="width:70px;">서비스유형</th>
                                        <th scope="col">서비스상세</th>
                                        <th scope="col">방문주소</th> 
                                        <th scope="col">교육요일</th>
                                        <th scope="col" style="width:50px;">상태</th>
                                        <th scope="col">안내사항</th>
                                        <th scope="col">결제금액</th>
                                        <th scope="col"  style="width:70px;">전문가지원</th>
                                    </tr>
                                </thead>
                                <tbody id="data_table">
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" id="btn_cancel" style="margin-left:50px;width:100px;" onclick="cancel()">예약 취소</button>
                        <button type="button" class="btn btn-primary" id="btn_go_i" style="margin-left:30px;width:170px;" target="I" onclick="go('I')">진행중 리스트로 이동</button>
                        <button type="button" class="btn btn-primary" id="btn_go_s" style="margin-left:30px;width:170px;display:none;" target="S" onclick="go('S')">완료된 리스트로 이동</button>
                    </div>

                    <nav aria-label="Page navigation" style="width:300px; margin:auto;">
                        <ul class="pagination">
                          
                        </ul>
                    </nav>

                    <!-- Modal -->
                    
                    <div style="z-index: -1;" class="modal fade modal-dialog modal-xl position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">> 전문가 지원 현황</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" value="" id="selected_reservation_id" />
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">전문가명</th>
                                            <th scope="col">서비스 유형</th>
                                            <th scope="col">지역</th>
                                            <th scope="col">서비스 가능 거리</th>
                                            <th scope="col">사업자번호</th> 
                                            <th scope="col">구분</th>
                                            <th scope="col">직위</th>
                                            <th scope="col">상호명</th>
                                            <th scope="col">주소</th>
                                            <th scope="col">상담연락처</th>
                                        </tr>
                                    </thead>
                                    <tbody id="apply_list_table">
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="btn_close_apply_list" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn_matching" onclick="matching()" data-bs-dismiss="modal">매칭하기</button>
                            </div>
                        </div>
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

@endsection