<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>EventTechnology - @yield('title')</title>

		<meta name="description" content="EventTechnology" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<base href="{{asset('')}}">

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="backend/css/bootstrap.min.css" />
		<link rel="stylesheet" href="backend/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->
		@yield('css')

		<!-- text fonts -->
		{{-- <link rel="stylesheet" href="backend/css/fonts.googleapis.com.css" /> --}}

		<!-- CSS google font -->
	    <link href='http://fonts.googleapis.com/css?family=Condiment' rel='stylesheet' type='text/css'>
	    <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

		<!-- ace styles -->
		<link rel="stylesheet" href="backend/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="backend/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="backend/css/ace-skins.min.css" />
		<link rel="stylesheet" href="backend/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="backend/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="backend/js/ace-extra.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="backend/js/html5shiv.min.js"></script>
		<script src="backend/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin">
		<!--==============================navbar=================================-->
		<div id="navbar" class="navbar navbar-default ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="admin/dashboard" class="navbar-brand">
						<small>
							<i class="fa fa-home"></i>
							EventTechnology
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">

						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="backend/images/avatars/avatar2.png" alt="Avatar" />
								<span class="user-info">
									<small>Chào,</small>
									{{Session::get('back_login_tendangnhap')}}
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

								<li>
									<a href="admin/ttquanly">
										<i class="ace-icon fa fa-user"></i>
										Cập nhật thông tin
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="admin/logout">
										<i class="ace-icon fa fa-power-off"></i>
										Đăng xuất
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>
		<!--==============================/navbar=================================-->

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<!--==============================left-menu=================================-->
			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<ul class="nav nav-list">

				@foreach(Session::get('back_chucnangcha') as $cn_cha)

					@if(strpos($cn_cha->cn_lienket, "*"))

						<li class="{{Request::is($cn_cha->cn_lienket) ? 'active open' : ''}}">
							<a href="#" class="dropdown-toggle">
								<i class="menu-icon {{$cn_cha->cn_bieutuong}}"></i>
								<span class="menu-text"> {{$cn_cha->cn_ten}} </span>

								@if($cn_cha->cn_lienket == 'admin/sukien/*')
									@if(Session::has('back_sukien1'))
										<span class="badge badge-transparent tooltip-error" title="
											{{Session::get('back_sukien1')." sự kiện chưa duyệt"}}">
											<i class="ace-icon fa fa-exclamation-triangle red bigger-130"></i>
										</span>
									@endif

									@if(Session::has('back_sukien3'))
										<span class="badge badge-transparent tooltip-error" title="
											{{Session::get('back_sukien3')." sự kiện chưa tạo hợp đồng"}}">
											<i class="ace-icon fa fa-exclamation-triangle red bigger-130"></i>
										</span>
									@endif
								@endif

								<b class="arrow fa fa-angle-down"></b>
							</a>

							<b class="arrow"></b>

							<ul class="submenu">

							@foreach(Session::get('back_chucnangcon') as $cn_con)

								@if($cn_con->cn_cha==$cn_cha->cn_ma)
									
									<li class="{{Request::is($cn_con->cn_lienket) ? 'active' : ''}}">
										<a href="{{$cn_con->cn_lienket}}">
											<i class="menu-icon fa fa-caret-right"></i> {{$cn_con->cn_ten}}
										</a>
										<b class="arrow"></b>
									</li>

								@endif

							@endforeach

							</ul>
						</li>

					@else

						<li class="{{Request::is($cn_cha->cn_lienket) ? 'active' : ''}}">
							<a href="{{$cn_cha->cn_lienket}}">
								<i class="menu-icon {{$cn_cha->cn_bieutuong}}"></i>
								<span class="menu-text"> {{$cn_cha->cn_ten}} </span>
							</a>
							<b class="arrow"></b>
						</li>

					@endif

				@endforeach

				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
			<!--==============================/left-menu=================================-->

			<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="admin/dashboard">Trang chủ</a>
							</li>
							@yield('breadcrumb')
						</ul><!-- /.breadcrumb -->

					</div>

				<!--==============================content================================-->
			    	@yield('content')
			    <!--==============================/content=================================-->

				</div>
			</div><!-- /.main-content -->

			<!--==============================footer=================================-->
			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							{{-- <span class="blue bolder">Ace</span> --}}
							&copy; EventTechnology 2018
						</span>

					</div>
				</div>
			</div>
			<!--==============================/footer=================================-->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="backend/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="backend/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='backend/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="backend/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		@yield('javascript1')

		<!--[if lte IE 8]>
		  <script src="backend/js/excanvas.min.js"></script>
		<![endif]-->

		<!-- ace scripts -->
		<script src="backend/js/ace-elements.min.js"></script>
		<script src="backend/js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		@yield('javascript2')
	</body>
</html>
