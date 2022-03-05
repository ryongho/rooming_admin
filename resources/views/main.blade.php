<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="naver-site-verification" content="09c150b3c1a0f3424253abd1ce4ed1fe3cf06fe3" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Rooming</title>

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
                height:100%;
            }
            .main_img{
                width:100%;
                float:left;
                margin:0px;
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
            .btn_google{
                width:50%;
                float:left;
                margin:0px;
                padding:0px;
            }
            .btn_apple{
                width:50%;
                float:left;
                margin:0px;
                padding:0px;
            }
            a{
                cursor:pointer;
            }



        </style>
    </head>
    <body>
            <total class="total">
                <!--<div class="top">
                    <img src="/images/btn/logo.png" class="logo_img"/>
                    <a href="/contact_form"><img src="/images/btn/contact_top.png" class="contact_top_img"/></a>
                </div>-->
                <div class="main">
                    <img src="/images/main_01.png" class="main_img"/>
                    <a href="https://play.google.com/store/apps/details?id=link.rooming.app"><img src="/images/main_02_01.png" class="btn_google"/></a>
                    <a href="https://apps.apple.com/kr/app/%EB%A3%A8%EB%B0%8D-rooming/id1591176190"><img src="/images/main_02_02.png" class="btn_apple"/></a>
                    <img src="/images/main_03.png" class="main_img"/>
                    <a href="/partner"><img src="/images/main_04_01.png" class="btn_google"/></a>
                    <a href="https://partner.rooming.link/"><img src="/images/main_04_02.png" class="btn_apple"/></a>
                    <img src="/images/main_05.png" class="main_img"/>
                </div>
            </total>
            
            
    </body>
    
</html>
