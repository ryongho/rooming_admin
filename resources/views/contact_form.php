<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>D&Solution</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <!-- Fonts -->
        <style>
            body {
                width:100%;
                margin:0px;
                padding:0px;
            }
            .total{
                width:100%;
                max-width:800px;
                
                padding:0px;

            }
            .main{
                width:100%;
                max-width:800px;
                margin:0px auto;
                padding:0px;
            }
            .main_img{
                width:100%;
                height:auto;
                max-width:800px;
                margin:0px auto;
                padding:0px;
            }
            .top{
                width:100%;
                max-width:800px;
                margin:20px auto;

            }
            .logo_img{
                width:20%;
                min-width:120px;
                margin-left: 6%;
            }
            .form_div{
                width:100%;
                max-width:800px;
                height:1100px;
                margin:0px auto;
                background-color:#efefef;
                padding:0px;
            }
            .text_contact{
                width:33%;
                min-width:120px;
                max-width:170px;
                float:left;
                margin:0px;
                padding:0px;
                font-weight:bold;
                font-size:23px;
                text-align:right;
                margin-right:30px;
                background-color:#efefef;
            }
            table{
                margin:30px 0px;
                width:100%;
            }
            .form{
                max-width:80%;
                background-color:white;
                float:left;
                margin:0px 10%;
                padding:0px;
            }
            th{
                font-weight:bold;
                font-size:12px;
                width:20%;
                padding:20px;
            }
            td{
               width:80%; 
               padding-right:10%;
            }
            .contact_img{
                margin:20px auto;
                width:15%;
                min-width:100px;
                float:right;
                margin-right:10%;
                cursor:pointer;
                z-index:1;
            }
       
      


        </style>
    </head>
    <body>
            <total class="total">
                <div class="top">
                    <img src="/images/btn/logo.png" class="logo_img"/>
                </div>
                <div class="main">
                    <img src="/images/sub_01.png" class="main_img"/>
                    <div class="form_div">
                        <div class="text_contact">CONTACT</div>
                        <div class="form">
                            <table>
                                <tr>
                                <th><label for="exampleFormControlInput1" class="form-label">숙소명</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput1">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput2" class="form-label">숙소유형</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput2">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput3" class="form-label">지역</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput3">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput4" class="form-label">홈페이지 / SNS</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput4">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput5" class="form-label">이메일</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput5">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput6" class="form-label">연락처</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput6">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput7" class="form-label">문의 분류</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput7">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput8" class="form-label">문의 제목</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput8">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput9" class="form-label">문의 내용</label></th>
                                    <td>
                                    <textarea class="form-control" id="exampleFormControlInput9" rows="10"></textarea>
                                    </td>
                                </tr>
                            </table>

                            <div class="main">
                                <a href="/contact_form"><img src="/images/btn/contact.png" class="contact_img"/></a>
                            </div>
                        </div>
                    </div>
                    <img src="/images/main_04.png" class="main_img"/>
                </div>
            </total>
            
            
    </body>
    
</html>
