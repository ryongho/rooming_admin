<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Rooming</title>
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
                margin:0px auto;

            }
            .logo_img{
                width:20%;
                min-width:120px;
                margin-left: 1%;
                margin-top:20px;
            }
            .contact_top_img{
                width:5%;
                min-width:80px;
                float:right;
                margin-top:20px;
                margin-right:1%;
                cursor:pointer;
                z-index:1;
            }
            .form_div{
                width:100%;
                max-width:800px;
                height:1100px;
                margin:0px auto;
                background-color:#fff7f6;
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
                background-color:#fff7f6;
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
                    <a href="/"><img src="/images/contact/logo.png" class="logo_img"/></a>
                    <a href="https://partner.rooming.link/"><img src="/images/contact/partner.png" class="contact_top_img"/></a>
                    <a href="/contact"><img src="/images/contact/contact.png" class="contact_top_img"/></a>
                    <a href="/download/숙박직거래_앱_루밍_소개서.pdf"><img src="/images/contact/intro.png" class="contact_top_img"/></a>
                </div>
                <div class="main">
                    
                    <div class="form_div">
                        <img src="/images/contact/contact_title.png" class="main_img"/>
                        <div class="form">
                        <form action="/regist" method="GET" id="form1">
                            <table>
                                <tr>
                                <th><label for="exampleFormControlInput1" class="form-label">숙소명</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput1" name="hotel_name">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput2" class="form-label">숙소유형</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput2" name="hotel_type">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput3" class="form-label">지역</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput3"  name="local">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput4" class="form-label">홈페이지 / SNS</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput4"  name="homepage">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput5" class="form-label">이메일</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput5"  name="email">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput6" class="form-label">연락처</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput6"  name="phone">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput7" class="form-label">문의 분류</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput7"  name="type">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput8" class="form-label">문의 제목</label></th>
                                    <td>
                                        <input type="text" class="form-control" id="exampleFormControlInput8"  name="title">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="exampleFormControlInput9" class="form-label">문의 내용</label></th>
                                    <td>
                                    <textarea class="form-control" id="exampleFormControlInput9" rows="10"  name="content"></textarea>
                                    </td>
                                </tr>
                            </table>
                            

                            <div class="main">
                                <a href="#" onclick="document.getElementById('form1').submit();"><img src="/images/contact/contact_btn.png" class="contact_img"/></a>
                            </div>
                            <form>
                        </div>
                    </div>
                    <img src="/images/partner/footer.png" class="main_img"/>
                </div>
            </total>
            
            
    </body>
    
</html>
