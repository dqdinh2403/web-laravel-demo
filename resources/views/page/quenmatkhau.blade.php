@extends('layout.index1') 

@section('title')
	{{"Quên mật khẩu"}}
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

      <!-- Phần giao diện quên mật khẩu -->
    	<br /><h3>Lấy lại mật khẩu</h3><hr />
        <form id="formLayLaiMatKhau" name="formLayLaiMatKhau" method="POST" action="quenmatkhau" class="form-horizontal" role="form">
        {{csrf_field()}}  

        <div class="form-group">    
            <label for="txtTenDangNhap" class="col-sm-2 control-label">Tên tài khoản(*):  </label>
            <div class="col-sm-10">
                  <input type="text" name="txtTenDangNhap" id="txtTenDangNhap" class="form-control" placeholder="Tên đăng nhập"
                   value="{{old('txtTenDangNhap')}}"/>
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
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
              <input type="submit"  class="btn btn-primary" name="btnDongY" id="btnDongY" value="Đồng ý"/>  
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