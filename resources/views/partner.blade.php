<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
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
                margin:0px auto;
                background-color:#f3746e;

            }
            .logo_img{
                width:20%;
                min-width:120px;
                margin-left: 6%;
            }
            .contact_top_img{
                width:5%;
                min-width:80px;
                float:right;
                margin-top:9px;
                margin-right:1%;
                cursor:pointer;
                z-index:1;
            }
            .contact_img{
                margin:60px auto;
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
                <div class="top">
                <a href="/"><img src="/images/partner/logo.png" class="logo_img"/></a>
                    <a href="https://partner.rooming.link/"><img src="/images/partner/menu_03.png" class="contact_top_img"/></a>
                    <a href="/contact"><img src="/images/partner/menu_02.png" class="contact_top_img"/></a>
                    <a href="/download/숙박직거래_앱_루밍_소개서.pdf"><img src="/images/partner/menu_01.png" class="contact_top_img"/></a>
                </div>
                <div class="main">
                    <img src="/images/partner/title.png" class="main_img"/>
                    <a href="https://play.google.com/store/apps/details?id=link.rooming.app"><img src="/images/partner/google_btn.png" class="btn_google"/></a>
                    <a href="https://apps.apple.com/kr/app/%EB%A3%A8%EB%B0%8D-rooming/id1591176190"><img src="/images/partner/apple_btn.png" class="btn_apple"/></a>
                    <img src="/images/partner/content.png" class="main_img"/>
                    <a href="https://partner.rooming.link/"><img src="/images/partner/partner_btn.png" class="contact_img"/></a>
                    <img src="/images/partner/footer.png" class="main_img"/>
                </div>
            </total>
            
            
    </body>
    
</html>
