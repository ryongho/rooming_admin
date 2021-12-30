<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>D&Solution</title>

        <!-- Fonts -->
        <style>
            body {
                width:100%;
                margin:0px;
                padding:0px;
            }
            .total{
                width:100%;
                max-width:900px;
                
                padding:0px;

            }
            .main{
                width:100%;
                max-width:900px;
                margin:0px auto;
            }
            .main_img{
                width:100%;
                height:auto;
                max-width:900px;
                margin:0px auto;
            }
            .top{
                width:100%;
                max-width:900px;
                margin:20px auto;

            }
            .logo_img{
                width:20%;
                min-width:120px;
                margin-left: 6%;
            }
            .contact_top_img{
                width:15%;
                min-width:100px;
                float:right;
                margin-top:9px;
                margin-right:6%;
                cursor:pointer;
                z-index:1;
            }
            .contact_img{
                margin:20px auto;
                width:15%;
                min-width:100px;
                position: relative; left: 50%; top: 50%; 
                transform: translate(-50%, -50%); text-align: cente;
                cursor:pointer;
                z-index:1;
            }
            .main_down{
                width:100%;
                height:388px;
                max-width:900px;
                z-index:1;
                position:relative;
                background:url('/images/main_02.png');
                background-repeat:no-repeat;
                background-position:center center;
                background-size:contain;
            }
            .btn_google{
                float:right;
                position:relative;
                top:280px;
                
                width:15%;
                min-width:100px;
                cursor:pointer;
            }

            .btn_apple{
                float:right;
                position:relative;
                top:330px;
                
                width:15%;
                min-width:100px;
                cursor:pointer;
            }


        </style>
    </head>
    <body>
            <total class="total">
                <div class="top">
                    <img src="/images/btn/logo.png" class="logo_img"/>
                    <a href="/contact_form"><img src="/images/btn/contact_top.png" class="contact_top_img"/></a>
                </div>
                <div class="main">
                    <img src="/images/main_01.png" class="main_img"/>
                </div>
                <div class="main main_down">
                    <a href="https://play.google.com/store/apps/details?id=link.rooming.app"><img src="/images/btn/btn_down_google.png" class="btn_google"/></a>
                    <a href="https://apps.apple.com/kr/app/%EB%A3%A8%EB%B0%8D-rooming/id1591176190"><img src="/images/btn/btn_down_apple.png" class="btn_apple"/></a>
                </div>
                <div class="main">
                    <img src="/images/main_03.png" class="main_img"/>
                </div>
                <div class="main">
                    <a href="/contact_form"><img src="/images/btn/contact.png" class="contact_img"/></a>
                </div>
                <div class="main">
                    <img src="/images/main_04.png" class="main_img"/>
                </div>
            </total>
            
            
    </body>
    
</html>
