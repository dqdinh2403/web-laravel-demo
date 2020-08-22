<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>EventTechnology - Đăng Nhập</title>

		<meta name="description" content="EventTechnology" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<base href="{{asset('')}}">

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="backend/css/bootstrap.min.css" />
		<link rel="stylesheet" href="backend/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- text fonts -->
		{{-- <link rel="stylesheet" href="backend/css/fonts.googleapis.com.css" /> --}}

		<!-- CSS google font -->
	    <link href='http://fonts.googleapis.com/css?family=Condiment' rel='stylesheet' type='text/css'>
	    <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

		<!-- ace styles -->
		<link rel="stylesheet" href="backend/css/ace.min.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="backend/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="backend/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="backend/css/ace-ie.min.css" />
		<![endif]-->

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="backend/js/html5shiv.min.js"></script>
		<script src="backend/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<span class="white" id="id-text2">EventTechnology</span>
								</h1>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger" style="text-align: center;">
												<i class="ace-icon fa fa-coffee green"></i>
												Đăng Nhập
											</h4>

											<div class="space-6"></div>

											<!-- Phần hiển thị thông báo -->
											@if( count($errors) > 0 )
								    			<div class="alert alert-danger">
								    			@foreach($errors->all() as $er)
								    				{{$er}} <br>
								    			@endforeach
								    			</div>
								    		@endif

								    		@if( session('thongbao') )
								    			<div class="alert alert-success">
								    				{{session('thongbao')}}
								    			</div>
								    		@endif
								    		@if( session('loi') )
								    			<div class="alert alert-danger">
								    				{{session('loi')}}
								    			</div>
								    		@endif

											<form id="formDangNhap" name="formDangNhap" method="POST" action="admin/login">
												{{csrf_field()}}
												<fieldset>
													<label class="block clearfix">
														<label for="txtTenDangNhap1">Tài khoản(*):</label>
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" id="txtTenDangNhap1" name="txtTenDangNhap1" placeholder="Tên đăng nhập" value="{{old('txtTenDangNhap1')}}" required="" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<label for="txtMatKhau">Mật khẩu(*):</label>
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" id="txtMatKhau" name="txtMatKhau" placeholder="Mật khẩu" required="" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
														<button type="submit" class="width-40 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Đăng Nhập</span>
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													Quên Mật Khẩu ?
												</a>
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Lấy lại mật khẩu
											</h4>

											<div class="space-6"></div>
											<form id="formLayLaiMatKhau" name="formLayLaiMatKhau" method="POST" action="admin/forgetPassword">
												{{csrf_field()}}
												<fieldset>
													<label class="block clearfix">
														<label for="txtEmail">Email(*):</label>
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Email" required="" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<div class="clearfix">
														<button type="submit" class="width-25 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Gửi</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Quay lại trang đăng nhập
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->
							</div><!-- /.position-relative -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
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

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
		</script>
	</body>
</html>
