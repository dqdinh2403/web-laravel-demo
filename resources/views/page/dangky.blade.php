@extends('layout.index1')

@section('title')
  {{"Đăng ký thành viên"}}
@endsection

@section('content')
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
  
 	    <!-- Phần giao diện đăng ký -->
    	<br /><h3>Đăng ký thành viên:</h3><hr />
      <form id="formDangKy" name="formDangKy" method="POST" action="dangky" class="form-horizontal" role="form">
      {{csrf_field()}}

      <div class="form-group">    
          <label for="txtTenDangNhap" class="col-sm-2 control-label">Tên tài khoản(*):  </label>
          <div class="col-sm-10">
                <input type="text" name="txtTenDangNhap" id="txtTenDangNhap" class="form-control" placeholder="Tên đăng nhập"
                 value="{{old('txtTenDangNhap')}}"/>
          </div>
      </div>  
      
      <div class="form-group">   
          <label for="txtMatKhau1" class="col-sm-2 control-label">Mật khẩu(*):  </label>
          <div class="col-sm-10">
                <input type="password" name="txtMatKhau1" id="txtMatKhau1" class="form-control" placeholder="Mật khẩu" value=""/>
          </div>
      </div>     
      
      <div class="form-group"> 
          <label for="txtMatKhau2" class="col-sm-2 control-label">Nhập lại mật khẩu(*):  </label>
          <div class="col-sm-10">
                <input type="password" name="txtMatKhau2" id="txtMatKhau2" class="form-control" placeholder="Xác nhận mật khẩu" value=""/>
          </div>
      </div>     
      
      <div class="form-group">                               
          <label for="txtTenCongTy" class="col-sm-2 control-label">Tên công ty(*):  </label>
          <div class="col-sm-10">
                <input type="text" name="txtTenCongTy" id="txtTenCongTy" class="form-control" placeholder="Tên công ty"
                 value="{{old('txtTenCongTy')}}"/>
          </div>
      </div>
      
      <div class="form-group">                               
          <label for="txtNguoiDaiDien" class="col-sm-2 control-label">Người đại diện(*):  </label>
          <div class="col-sm-10">
                <input type="text" name="txtNguoiDaiDien" id="txtNguoiDaiDien" class="form-control" placeholder="Tên người đại diện"
                 value="{{old('txtNguoiDaiDien')}}"/>
          </div>
      </div> 
      
      <div class="form-group">      
          <label for="txtEmail" class="col-sm-2 control-label">Email(*):  </label>
          <div class="col-sm-10">
                <input type="text" name="txtEmail" id="txtEmail" class="form-control" placeholder="Email"
                 value="{{old('txtEmail')}}"/>
          </div>
      </div>  
      
      <div class="form-group">   
           <label for="txtDiaChi" class="col-sm-2 control-label">Địa chỉ(*):  </label>
          <div class="col-sm-10">
                <input type="text" name="txtDiaChi" id="txtDiaChi" class="form-control" placeholder="Địa chỉ"
                 value="{{old('txtDiaChi')}}"/>
          </div>
      </div>  
      
       <div class="form-group">  
          <label for="txtDienThoai" class="col-sm-2 control-label">Điện thoại(*):  </label>
          <div class="col-sm-10">
                <input type="text" name="txtDienThoai" id="txtDienThoai" class="form-control" placeholder="Điện thoại"
                 value="{{old('txtDienThoai')}}"/>
          </div>
       </div> 
                
      <div class="form-group">
          <div class="col-sm-2"></div>
          <div class="col-sm-10">
            <input type="submit"  class="btn btn-primary" name="btnDangKy" id="btnDangKy" value="Đăng ký"/>  
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