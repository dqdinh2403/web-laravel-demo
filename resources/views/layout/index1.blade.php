<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>EventTechnology - @yield('title')</title>
    
    <base href="{{asset('')}}">
    <!-- Bootstrap -->
	  <link href="frontend/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />    
    <!-- Jquery -->
    <script src="frontend/js/jquery-1.11.0.min.js"></script>
    
    <!-- CSS default -->
    <link rel="stylesheet" type="text/css" media="screen" href="frontend/css/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="frontend/css/style.css">
    <link rel="stylesheet" type="text/css" media="screen" href="frontend/css/grid_12.css">
    
    <!-- CSS slider show -->
    {{-- <link rel="stylesheet" type="text/css" media="screen" href="frontend/css/slider.css"> --}}
    
    <!-- CSS google font -->
    <link href='http://fonts.googleapis.com/css?family=Condiment' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    
    <!-- JS slider show -->
    {{-- <script src="frontend/js/jquery-1.7.min.js"></script>
    <script src="frontend/js/jquery.easing.1.3.js"></script>
    <script src="frontend/js/tms-0.4.x.js"></script> --}}
        
    <!-- Custom file -->
	  <link href="frontend/css/responsive.css" rel="stylesheet" type="text/css" media="all"  />
    <link rel="stylesheet" type="text/css" href="frontend/css/main.css" />
    <script src="frontend/js/bootstrap.min.js"></script>
    <script src="frontend/js/jquery.dataTables.min.js"></script>
    <script src="frontend/js/dataTables.bootstrap.min.js"></script>
     
    {{-- [if lt IE 9]>
    <script src="frontend/js/html5.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="frontend/css/ie.css">
    <![endif] --}}

</head>
    
    <!-- Phần xử lý tìm kiếm: kiểm tra từ khóa và chuyển sang trang tìm kiếm
        - Vào: từ khóa của khung search
        - Ra: route -> timkiem + tukhoa
    -->
    <script language="javascript">
  		function timkiem(){
  			tukhoa=document.getElementById('txtTuKhoa').value;						
  			if(tukhoa == ""){
  				alert("Nhập từ khóa tìm kiếm !");
  				return false;	
  			}			
  			else{
  				window.location = "timkiem/"+tukhoa;
  			}
  		}
   	</script>

	<!-- Modal đăng nhập -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog"> 
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title" align="center" style="color:black; font-weight:bold">Đăng Nhập</h3>
        </div>
        <div class="modal-body" style="color:black;">
         <form id="formDangNhap" name="formDangNhap" method="POST" action="dangnhap">
            {{csrf_field()}}
            <div class="form-group">
              <label for="txtTenDangNhap1">Tài khoản(*):</label>
              <input type="text" class="form-control" id="txtTenDangNhap1" name="txtTenDangNhap1" placeholder="Tên đăng nhập" value="{{old('txtTenDangNhap1')}}" required="">
            </div>
            <div class="form-group">
              <label for="txtMatKhau">Mật khẩu(*):</label>
              <input type="password" class="form-control" id="txtMatKhau" name="txtMatKhau" placeholder="Mật khẩu" value="" required="">
            </div>
            <div>
              <a href="quenmatkhau" >Quên mật khẩu ?
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="btnLogin" id="btnLogin">Đăng nhập</button>
              <button type="button" class="btn btn-primary" data-dismiss="modal">Hủy</button>
            </div>
            </form>              
          </div>
        </div>
    </div>
  </div>
  <!-- /Modal đăng nhập -->

<body>
    <div class="main">
      <!--==============================header=================================-->
      <div class="top_header" style="vertical-align: center">
      	<ul> 
        @if(Session::has('front_login_tendangnhap')) 
          @if(Session::has('front_login_quyen') && Session::get('front_login_quyen')==2)   
            <li><a href="guest/giaodich" class="btn btn-default btn-md" style="color: #fff; background-color: black; border-color: black">
                  <span class="glyphicon glyphicon-time"></span> Giao dịch
            </a></li>          
            <li><a href="guest/ttkhachhang" class="btn btn-default btn-md" style="color: #fff; background-color: black; border-color: black">
                <span class="glyphicon glyphicon-user"></span> Chào 
                  <font color="#3C3CFF">                      
                    @if(strlen(Session::get('front_login_tendangnhap')) > 6)
                        {{substr(Session::get('front_login_tendangnhap'),0,6)."..."}}
                    @else
                        {{Session::get('front_login_tendangnhap')}}
                    @endif                  
                  </font>
            </a></li>          
          @else
            <li><a href="staff/lichsuthamgia" class="btn btn-default btn-md" style="color: #fff; background-color: black; border-color: black">
                  <span class="glyphicon glyphicon-time"></span> Lịch sử
            </a></li>  
            <li><a href="staff/thamgiasukien" class="btn btn-default btn-md" style="color: #fff; background-color: black; border-color: black">
                  <span class="glyphicon glyphicon-calendar"></span> Sự kiện
            </a></li>    
            <li><a href="staff/ttnhanvien" class="btn btn-default btn-md" style="color: #fff; background-color: black; border-color: black">
                <span class="glyphicon glyphicon-user"></span> Chào 
                  <font color="#3C3CFF">                      
                    @if(strlen(Session::get('front_login_tendangnhap')) > 6)
                        {{substr(Session::get('front_login_tendangnhap'),0,6)."..."}}
                    @else
                        {{Session::get('front_login_tendangnhap')}}
                    @endif
                  </font>
            </a></li>
          @endif      	         
            <li><a href="dangxuat" class="btn btn-default btn-md" style="color: #fff; background-color: black; border-color: black">
                  <span class="glyphicon glyphicon-log-out"></span> Đăng xuất
            </a></li>     
        @else  
          	<li><a href="dangky" class="btn btn-default btn-md" style="color: #fff; background-color: black; border-color: black">
                  <span class="glyphicon glyphicon-pencil"></span> Đăng ký
            </a></li>	

            <li><button type="button" class="btn btn-default btn-md" data-toggle="modal" data-target="#myModal" style="color: #fff; background-color: black; border-color: black">
                  <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Đăng nhập
            </button></li>
        @endif          
      	</ul>
      </div>
      <!--==============================/header=================================-->

      <!--==============================menu=================================-->
      <header>
        <h1><a href="trangchu"><img src="frontend/images/logo.png" width="350px" height="100px" alt=""></a></h1>
        <!-- Phần search -->
        <div class="form-search">      
          <form id="form-search" method="post" action="javascript:void(0)">
            {{csrf_field()}} 
            <input type="text" placeholder="Search" name="txtTuKhoa" id="txtTuKhoa" />
            <a onclick="timkiem()" class="search_button"></a>         
          </form>
        </div>
        <!-- /Phần search -->
        <div class="clear"></div>
        <!-- Menu -->
        <nav class="box-shadow">
          <div>
            <ul class="menu">
              <li class="home-page {{Request::is('trangchu') ? 'current' : ''}}"><a href="trangchu"><span></span></a></li>
              <li class="{{Request::is('gioithieu') ? 'current' : ''}}" ><a href="gioithieu">Giới Thiệu</a></li>
              <li class="{{Request::is('sukien') ? 'current' : ''}}"><a href="sukien">Sự Kiện</a></li>
              <li class="{{Request::is('dungcu') ? 'current' : ''}}"><a href="dungcu">Thiết Bị</a></li>
              <li class="{{Request::is('album_anh') ? 'current' : ''}}"><a href="album_anh">Album</a></li>
              <li class="{{Request::is('lienhe') ? 'current' : ''}}"><a href="lienhe">Liên Hệ</a></li>
              <li class="{{Request::is('gopy') ? 'current' : ''}}"><a href="gopy">Góp ý</a></li>            
            </ul>
            <div class="social-icons">
            	<span>Follow us:</span>
                <a href="https://plus.google.com/" target="_blank" class="icon-3"></a>
                <a href="https://vi-vn.facebook.com/" target="_blank" class="icon-2"></a>
                <a href="https://twitter.com/" target="_blank" class="icon-1"></a> 
            </div>
            <div class="clear"></div>
          </div>
        </nav>
        <!-- /Menu -->
      </header>
      <!--==============================/menu=================================-->
      
      <!--==============================content================================-->
      @yield('content')
      <!--==============================/content=================================-->
    </div> 
    <!-- /class="main" -->
    <!--==============================footer=================================-->
    <footer>
      <p>© 2018 EventTechnology</p>
    </footer>
    <!--==============================/footer=================================-->
</body>
</html>
