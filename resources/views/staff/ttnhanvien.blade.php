@extends('layout.index1')

@section('title')
  {{"Staff: Cập nhật thông tin cá nhân"}}
@endsection

@section('content')
<!-- script password -->
<script type="text/javascript">
	$(document).ready(function(){
		$("#changePassword").change(function(){
			if($(this).is(":checked")){
				$(".password").removeAttr("disabled");
			}
			else{
				$(".password").attr("disabled",'');
			}
		});
	});	
</script>

<section id="content">       
    <div class="container_12">
      <div class="grid_12">  

	    <!-- Phần hiển thị thông báo -->
	     @if( count($errors)>0 )
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
 	
	 	 <!-- Phần giao diện cập nhật thông tin cá nhân -->
		<br /><h3>Cập nhật thông tin cá nhân:</h3><hr />
	    <form id="formCapNhatThongTinCaNhan" name="formCapNhatThongTinCaNhan" method="POST" action="staff/ttnhanvien" class="form-horizontal" role="form">
	    {{csrf_field()}}

	    <div class="form-group">    
	        <label for="txtTenDangNhap" class="col-sm-2 control-label">Tên tài khoản(*):  </label>
	        <div class="col-sm-10">
	              <input type="text" name="txtTenDangNhap" id="txtTenDangNhap" class="form-control" placeholder="Tên đăng nhập"
	               value="{{$nhanvien->tk_tendangnhap}}" disabled="" />
	        </div>
	    </div>  

	    <div class="form-group"> 
	    	<div class="col-sm-2 control-label"></div>
	    	<div class="col-sm-10">
	        	<input type="checkbox" name="changePassword" id="changePassword">
	    		<label for="changePassword">Đổi password</label>
	    	</div> 
	    </div>

	    <div class="form-group">   	
		        <label for="txtMatKhauOld" class="col-sm-2 control-label">Mật khẩu cũ(*):  </label>
		        <div class="col-sm-10">
		              <input type="password" name="txtMatKhauOld" id="txtMatKhauOld" class="form-control password" placeholder="Mật khẩu cũ" disabled="" />
		        </div>
		    </div>
	          
	    <div class="form-group">   	
	        <label for="txtMatKhau1" class="col-sm-2 control-label">Mật khẩu mới(*):  </label>
	        <div class="col-sm-10">
	              <input type="password" name="txtMatKhau1" id="txtMatKhau1" class="form-control password" placeholder="Mật khẩu mới" disabled="" />
	        </div>
	    </div>     
	    
	    <div class="form-group"> 
	        <label for="txtMatKhau2" class="col-sm-2 control-label">Nhập lại mật khẩu(*):  </label>
	        <div class="col-sm-10">
	              <input type="password" name="txtMatKhau2" id="txtMatKhau2" class="form-control password" placeholder="Xác nhận mật khẩu" disabled="" />
	        </div>
	    </div> 
	      
	    <div class="form-group">                               
	        <label for="txtTenNhanVien" class="col-sm-2 control-label">Tên nhân viên(*):  </label>
	        <div class="col-sm-10">
	              <input type="text" name="txtTenNhanVien" id="txtTenNhanVien" class="form-control" placeholder="Tên nhân viên"
	               value="{{$nhanvien->nv_tennhanvien}}"/>
	        </div>
	    </div>

	    <div class="form-group">  
	        <label for="grpGioiTinh" class="col-sm-2 control-label">Giới tính(*):  </label>
	        <div class="col-sm-10">                              
	              <label class="radio-inline"><input type="radio" name="grpGioiTinh" value="1" id="grpGioiTinh"
	              	@if($nhanvien->nv_gioitinh==1)
	              		{{"checked"}}
	              	@endif
	               />Nam</label>

	              <label class="radio-inline"><input type="radio" name="grpGioiTinh" value="0" id="grpGioiTinh"
	              	@if($nhanvien->nv_gioitinh==0)
	              		{{"checked"}}
	              	@endif
	               />Nữ</label>       
	        </div>
	    </div>
	    
	    <div class="form-group">      
	        <label for="txtEmail" class="col-sm-2 control-label">Email(*):  </label>
	        <div class="col-sm-10">
	              <input type="text" name="txtEmail" id="txtEmail" class="form-control" placeholder="Email"
	               value="{{$nhanvien->nv_email}}" disabled="" />
	        </div>
	    </div>
	           
	    <div class="form-group">   
	         <label for="txtDiaChi" class="col-sm-2 control-label">Địa chỉ(*):  </label>
	        <div class="col-sm-10">
	              <input type="text" name="txtDiaChi" id="txtDiaChi" class="form-control" placeholder="Địa chỉ"
	               value="{{$nhanvien->nv_diachi}}"/>
	        </div>
	    </div>  
	    
	     <div class="form-group">  
	        <label for="txtDienThoai" class="col-sm-2 control-label">Điện thoại(*):  </label>
	        <div class="col-sm-10">
	              <input type="text" name="txtDienThoai" id="txtDienThoai" class="form-control" placeholder="Điện thoại"
	               value="{{$nhanvien->nv_dienthoai}}"/>
	        </div>
	     </div> 
	     
	     <div class="form-group">  
	        <label for="txtNgaySinh" class="col-sm-2 control-label">Ngày sinh(*):  </label>
	        <div class="col-sm-10">
	              <input type="date" name="txtNgaySinh" id="txtNgaySinh" class="form-control" placeholder="Ngày sinh"
	               value="{{$nhanvien->nv_ngaysinh}}"/>
	        </div>
	     </div> 

	     <div class="form-group">  
	        <label for="txtCMND" class="col-sm-2 control-label">CMND:  </label>
	        <div class="col-sm-10">
	              <input type="text" name="txtCMND" id="txtCMND" class="form-control" placeholder="Chứng minh nhân dân"
	               value="{{$nhanvien->nv_cmnd}}"/>
	        </div>
	     </div>

	    <input type="hidden" name="txtMaNhanVien" id="txtMaNhanVien" value="{{$nhanvien->nv_ma}}">
	                            
	    <div class="form-group">
	        <div class="col-sm-2"></div>
	        <div class="col-sm-10">
	          <input type="submit"  class="btn btn-primary" name="btnCapNhat" id="btnCapNhat" value="Cập nhật"/>  
	          <input type="button" class="btn btn-primary" name="btnHuy"  id="btnHuy" value="Hủy"
	                    onClick="window.location='trangchu'" /> 
	        </div>
	    </div>
	    </form>
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
</section> 
@endsection