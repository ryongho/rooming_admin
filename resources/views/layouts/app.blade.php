<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <style type="css/style">
        .form-floating > .bi-calendar-date + .datepicker_input + label {
            padding-left: 3.5rem;
            z-index: 3;
        }
        </style>
    </head>

    <!-- JavaScript Libraries -->
            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
                <script src="lib/chart/chart.min.js"></script>
                <script src="lib/easing/easing.min.js"></script>
                <script src="lib/waypoints/waypoints.min.js"></script>
                <script src="lib/owlcarousel/owl.carousel.min.js"></script>
                <script src="lib/tempusdominus/js/moment.min.js"></script>
                <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
                <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

                <!-- Template Javascript -->
                <script src="js/main.js"></script>

                <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous"></script>
            <script src="js/jquery.cookie.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

            
    <body>
        @section('sidebar')
        <div class="container-xxl position-relative  bg-light d-flex p-0" style="max-width:2000px;">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary">관리자 페이지</h3>
                </a>
                <div class="navbar-nav w-100">
                    <div class="nav-item dropdown">
                        <a href="{{route('user_list')}}" class="dropdown-item">회원 목록</a>
                        <a href="{{route('partner_list')}}" class="dropdown-item">파트너 목록</a>
                        <a href="{{route('reservation_list')}}" class="dropdown-item">예약 목록</a>
                        <a href="{{route('hotel_list')}}" class="dropdown-item">호텔 목록</a> 
                        <a href="{{route('room_list')}}" class="dropdown-item">객실 목록</a> 
                        <a href="{{route('goods_list')}}" class="dropdown-item">상품 목록</a>
                        <a href="{{route('recommend_list')}}" class="dropdown-item">추천 상품 목록</a> 
                    </div>
                    <!--<a href="index.html" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Elements</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="button.html" class="dropdown-item">Buttons</a>
                            <a href="typography.html" class="dropdown-item">Typography</a>
                            <a href="element.html" class="dropdown-item">Other Elements</a>
                        </div>
                    </div>
                    <a href="widget.html" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Widgets</a>
                    <a href="form.html" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Forms</a>
                    <a href="table.html" class="nav-item nav-link active"><i class="fa fa-table me-2"></i>Tables</a>
                    <a href="chart.html" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Charts</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Pages</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="signin.html" class="dropdown-item">Sign In</a>
                            <a href="signup.html" class="dropdown-item">Sign Up</a>
                            <a href="404.html" class="dropdown-item">404 Error</a>
                            <a href="blank.html" class="dropdown-item">Blank Page</a>
                        </div>
                    </div>
                    --> 
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->
        @show

        

        <div class="container">
            @yield('content')

            
            <script id="rendered-js">
                $(function () {
                    $('#datePicker-start')
                        .datepicker({
                            format: 'yyyy-mm-dd', //데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
                            autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                            clearBtn: false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
                            templates: {
                                leftArrow: '&laquo;',
                                rightArrow: '&raquo;',
                            }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징
                            showWeekDays: true, // 위에 요일 보여주는 옵션 기본값 : true
                            todayHighlight: true, //오늘 날짜에 하이라이팅 기능 기본값 :false
                            toggleActive: true, //이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
                            weekStart: 0, //달력 시작 요일 선택하는 것 기본값은 0인 일요일
                            language: 'ko', //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
                        })
                        .on('changeDate', function (e) {
                            $("#start_date").val($("#datePicker-start").val());                
                        });
                });

                $(function () {
                    $('#datePicker-end')
                        .datepicker({
                            format: 'yyyy-mm-dd', //데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
                            autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                            clearBtn: false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
                            templates: {
                                leftArrow: '&laquo;',
                                rightArrow: '&raquo;',
                            }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징
                            showWeekDays: true, // 위에 요일 보여주는 옵션 기본값 : true
                            todayHighlight: true, //오늘 날짜에 하이라이팅 기능 기본값 :false
                            toggleActive: true, //이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
                            weekStart: 0, //달력 시작 요일 선택하는 것 기본값은 0인 일요일
                            language: 'ko', //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
                        })
                        .on('changeDate', function (e) {
                            //console.log(e);
                            $("#end_date").val($("#datePicker-end").val());
                        });
                });

            </script>
        </div>
    </body>
</html>