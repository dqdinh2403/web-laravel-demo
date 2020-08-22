@extends('layout.index1')

@section('title')
	{{"Guest: Xác nhận sự kiện"}}
@endsection	

@section('content')
<section id="content">       
    <div class="container_12">
      <div class="grid_12">  

      <!-- Phần confirm xác nhận -->
	    <script language="javascript">
			function deleteConfirm(ten){
				if(confirm("Bạn có chắc chắn muốn xác nhận sự kiện '" + ten + "' ?")){
					return true;	
				}
				else
					return false;
			}   	   
	    </script>

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
    
  <!-- Phần giao diện xác nhận sự kiện -->
	<br /><h3>Xác nhận sự kiện:</h3><hr />
    <form id="formXacNhanSuKien" name="formXacNhanSuKien" method="POST" action="guest/xacnhansukien/{{$sukien->sk_ma}}" class="form-horizontal" role="form">
        {{csrf_field()}}
        <div class="form-group">	    
          <label for="lblLoaiSuKien" class="col-sm-2 control-label">Loại sự kiện(*):  </label>
            <div class="col-sm-10">
            <input type="text" class="form-control" name="txtLoaiSuKien" aria-describedby="basic-addon1" value="{{$loaisukien->lsk_ten}}" disabled="">                 
             </div>
         </div>  
                                                     
       <div class="form-group"> 
            <label for="txtTenSuKien" class="col-sm-2 control-label">Tên sự kiện(*):  </label>
            <div class="col-sm-10">
                  <input type="text" name="txtTenSuKien" id="txtTenSuKien" class="form-control" placeholder="Tên sự kiện"
                  	 value="{{$sukien->sk_ten}}" disabled="" />
            </div>
        </div>
            
         <div class="form-group"> 
            <label for="txtDiaDiem" class="col-sm-2 control-label">Địa điểm(*):  </label>
            <div class="col-sm-10">
                  <input type="text" name="txtDiaDiem" id="txtDiaDiem" class="form-control" placeholder="Địa điểm"
                  	 value="{{$sukien->sk_diadiem}}" disabled="" />
            </div>
        </div>
                           
        <div class="form-group"> 
          <label for="txtThoiGian" class="col-sm-2 control-label">Thời gian tổ chức(*):  </label>
          <div class="col-sm-3">
            <input type="time" name="txtTime" id="txtTime" class="form-control" value="{{$sukien->sk_thoigianbatdaut}}" disabled="" />
          </div>
          <div class="col-sm-7">
            <input type="date" name="txtDate" id="txtDate" class="form-control" value="{{$sukien->sk_thoigianbatdaud}}" disabled="" />
          </div>
      </div>    

        <div class="form-group">                               
            <label for="txtThoiLuong" class="col-sm-2 control-label">Thời lượng(*):  </label>
            <div class="col-sm-10">
               <input type="number" name="txtThoiLuong" id="txtThoiLuong" class="form-control" placeholder="Thời lượng"
                    value="{{$sukien->sk_thoiluong}}" disabled="" />
            </div>
        </div>
                 
        <br>                                           
        <div class="form-group">                               
            <label for="txtNoiDungSuKien" class="col-sm-2 control-label">Nội dung sự kiện(*):  </label>
            <div class="col-sm-10">                  
              <p>{!!$sukien->sk_noidungsukien!!}</p>               
            </div>
        </div> 

        <hr /><h3>Danh sách đối tác:</h3><br /> 
        <ul style="padding-left: 50px">
        	@foreach($sk_doitac as $dt)
        		<li>- {{$dt->dt_tencongty}}</li>
        	@endforeach
        </ul>

        <hr /><h3>Danh sách công việc:</h3><br />
	      <table class="table table-bordered table-hover dataTable" id="TableCongViec">
	        <thead>
	            <tr>
	                <th style="width:15%; text-align:center">STT</th>
	                <th style="width:70%; text-align:center">Công việc</th>
	                <th style="width:15%; text-align:center">Số lượng</th>                   
	            </tr>
	        </thead>
	        <tbody style="text-align:center">
	            <?php $stt=1 ?>
	            @foreach($sk_congviec as $cv)
	                <tr>
	                  <td style="text-align: center">{{$stt}}</td>
	                  <td style="text-align: left">{{$cv->cv_ten}}</td>
	                  <td style="text-align: right">{{$cv->sk_cv_nv_soluongnhanvien}}</td>
	                </tr>
	            <?php $stt++ ?>
	            @endforeach
	        </tbody>
	      </table>
   
	      <hr /><h3>Danh sách dụng cụ:</h3><br />
	      <table class="table table-bordered table-hover dataTable" id="TableDungCu">
	        <thead>
	            <tr>
	                <th style="width:15%; text-align:center">STT</th>
	                <th style="width:70%; text-align:center">Dụng cụ</th>
	                <th style="width:15%; text-align:center">Số lượng</th>                   
	            </tr>
	        </thead>
	        <tbody style="text-align:center">
	            <?php $stt=1 ?>
	            @foreach($sk_dungcu as $dc)
	                <tr>
	                  <td style="text-align: center">{{$stt}}</td>
	                  <td style="text-align: left">{{$dc->dc_ten}}</td>
	                  <td style="text-align: right">{{$dc->sd_soluongmuon}}</td>
	                </tr>
	            <?php $stt++ ?>
	            @endforeach
	        </tbody>
	      </table>           
    	<hr />

    <div class="form-group">                               
        <label for="txtKinhPhi" class="col-sm-2 control-label">Giá trị hợp đồng (tạm tính)(*):  </label>
        <div class="col-sm-10">
           <input type="text" name="txtKinhPhi" id="txtKinhPhi" class="form-control" placeholder="Kinh phí"
                value="{{number_format($sukien->sk_kinhphi,0,',','.')." VNĐ"}}" disabled="" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
              <input type="submit"  class="btn btn-primary" name="btnXacNhan" id="btnXacNhan" value="Xác nhận" onclick="return deleteConfirm('{{$sukien->sk_ten}}')" />
              <input type="button" class="btn btn-primary" name="btnHuy"  id="btnHuy" value="Hủy"
                    onClick="window.location='guest/giaodich'" /> 
        </div>
    </div>
    </form>
    </div> <!-- class="grid_12" -->                      
  <div class="clear"></div>
</div> <!-- class="container_12" -->
@endsection