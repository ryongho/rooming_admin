<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', '공지 관리')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">
            
            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" >
                        <h6 class="mb-4" style="margin:30px 10px;">공지관리</h6>
                        <button type="button" class="btn btn-primary" style="float:right;margin:10px" onclick="regist()">추가등록</button>
                    </div>
                    <div class="col-12">
                        <div class="bg-white rounded h-100 p-4">
                            
                            <table class="table table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">아이디</th>
                                        <th scope="col">공지 제목</th>
                                        <th scope="col">관리</th>
                                    </tr>
                                </thead>
                                <tbody id="data_table">
                                    @forelse($list->data as $data)
                                        <tr>
                                            <td>{{ $data['id'] }}</td>
                                            <td id="title_{{ $data['id'] }}" >{{ $data['title'] }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning m-2" id="btn_search" style="width:auto;float:left;margin:0px;" onclick="detail({{ $data['id'] }})" >상세내용</button>
                                                <button type="button" class="btn btn-danger m-2" id="btn_search" style="width:auto;float:left;margin:0px;" onclick="remove({{ $data['id'] }})" >삭제</button>
                                                <input type="hidden" id="content_{{ $data['id'] }}" value="{{ $data['content'] }}">
                                            </td>
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">조회된 공지내역이 없습니다.</td>
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
                                <h5 class="modal-title" id="exampleModalLabel">상세보기</h5>
                                <button type="button" class="close btn_close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="text" class="form-control" id="modal_title" vaue=""/>
                                <textarea class="form-control" id="modal_content" value=""> </textarea>
                                <input type="hidden" id="modal_id" value="">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn_close" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="save()">Save changes</button>
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

        });
        

        const save = function(){
            
            const title = $("#modal_title").val();
            const content = $("#modal_content").val();
            const id = $("#modal_id").val();

            $url = '/save_notice?id='+id+'&title='+title+'&content='+content;

            window.location.replace($url);
        }

        const remove = function(id){

            $url = '/delete_notice?id='+id;

            window.location.replace($url);
        }

        const detail = function(id){
            
            const title = $("#title_"+id).text();
            const content = $("#content_"+id).val();
            $("#modal_title").val(title);
            $("#modal_content").val(content);
            $("#modal_id").val(id);

            $('#detailModal').modal('toggle');
        }

        const regist = function(){
            
            $("#modal_title").val();
            $("#modal_content").val();
            $("#modal_id").val();

            $('#detailModal').modal('toggle');
        }



        $(".btn_close").click(function(){

            $("#modal_title").val("");
            $("#modal_content").val("");
            $("#modal_id").val("");

            $('#detailModal').modal('hide');


        });
        
    </script>

@endsection