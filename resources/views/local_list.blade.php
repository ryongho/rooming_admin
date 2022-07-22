<!-- resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', '지역 관리')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="content bg-white">
            
            <!-- Table Start -->
                    <div class="col-12 bg-white px-4 py-0" >
                        <h6 class="mb-4" style="margin:30px 10px;">지역 관리</h6>
                        <button type="button" class="btn btn-primary" style="float:right;margin:10px" onclick="regist()">추가등록</button>
                    </div>
                    <div class="col-12">
                        <div class="bg-white rounded h-100 p-4">
                            
                            <table class="table table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">지역명</th>
                                        <th scope="col">좌표</th>
                                        <th scope="col">순번</th>
                                        <th scope="col">삭제</th>
                                    </tr>
                                </thead>
                                <tbody id="data_table">
                                    @forelse($list->data as $data)
                                        <tr>
                                            <td id="title_{{ $data['id'] }}" >{{ $data['name'] }}</td>
                                            <td><input type="text" class="input" value="{{ $data['latitude'] }}" id="lat_{{ $data['id'] }}"/> / <input type="text" class="input" value="{{ $data['longtitude'] }}" id="lot_{{ $data['id'] }}"/>  <button type="button" class="btn btn-sm btn-warning m-2" id="btn_search" style="width:auto;margin:0px;" onclick="update_addr({{ $data['id'] }})" >수정</button></td>
                                            <td><input type="text" class="input" value="{{ $data['order_no'] }}" id="orderno_{{ $data['id'] }}"/><button type="button" class="btn btn-warning m-2  btn-sm" id="btn_search" style="width:auto;" onclick="update_orderno({{ $data['id'] }})" >수정</button></td>
                                            <td><button type="button" class="btn btn-danger m-2  btn-sm" id="btn_search" style="width:auto;" onclick="remove({{ $data['id'] }})" >삭제</button></td>
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" align="center">조회된 질문내역이 없습니다.</td>
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
                                <h5 class="modal-title" id="exampleModalLabel">지역 추가</h5>
                                <button type="button" class="close btn_close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" action="/upload_local" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                
                                    <h6 class="modal-title" id="exampleModalLabel">지역명</h6>
                                    <input type="text" class="form-control" name="name" vaue=""/>
                                    <h6 class="modal-title" id="exampleModalLabel">위도</h6>
                                    <input type="text" class="form-control" name="latitude" vaue=""/>
                                    <h6 class="modal-title" id="exampleModalLabel">경도</h6>
                                    <input type="text" class="form-control" name="longtitude" vaue=""/>
                                    <input type="file" class="form-control" name="image" value="">
                                
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn_close" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</submit>
                            </div>
                            </form>
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

    <script>
        $().ready(function(){

        });
        

        const update_addr = function(id){

            const latitude = $("#lat_"+id).val();
            const longtitude = $("#lot_"+id).val();
        
            $url = '/update_local_addr?id='+id+'&latitude='+latitude+'&longtitude='+longtitude;
            
            window.location.replace($url);
        }

        const update_orderno = function(id){

            const orderno = $("#orderno_"+id).val();

            $url = '/update_local_orderno?id='+id+'&orderno='+orderno;

            window.location.replace($url);
        }

        const remove = function(id){

            $url = '/delete_local?id='+id;

            window.location.replace($url);
        }

        const regist = function(){
            
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