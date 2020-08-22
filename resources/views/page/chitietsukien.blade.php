@extends('layout.index1')

@section('title')
	{{$sukien->sk_ten}}
@endsection

@section('content')
<!-- Phần giao diện hiển thị nội dung chi tiết của sự kiện -->
<section id="content"> 	
	<div class="container_12">
		<div class="grid_12">
			<br><h3>{{$sukien->sk_ten}}</h3><br>
	        <p style="padding-left:40px">
	        	<b>Loại sự kiện: </b>{{$loaisukien->lsk_ten}}<br>
	        	<b>Địa điểm tổ chức: </b>{{$sukien->sk_diadiem}}<br>
	        	<b>Thời gian bắt đầu: </b>
	        		{{date_format(date_create($sukien->sk_thoigianbatdaut),'H:i:s')}} - 
	        		{{date_format(date_create($sukien->sk_thoigianbatdaud),'d/m/Y')}}
	        	<br>
	        	<b>Thời lượng sự kiện: </b>{{$sukien->sk_thoiluong}} giờ<br>
				<b>Kinh phí: </b>{{number_format($sukien->sk_kinhphi,0,',','.')}} VNĐ<br>
		        <b>Trạng thái: </b>
		        	@if($sukien->sk_trangthai == 4)
		        		{{"Đã hoàn tất"}}
		        	@else
		        		{{"Chưa hoàn tất"}}
					@endif        		
		        <br>
		        <b>Nội dung sự kiện: </b>{!!$sukien->sk_noidungsukien!!}<br>
		        <b>Một số hình ảnh của sự kiện:</b> <hr>
	        </p>
				<center>
	        		@foreach($hinhanh as $ha)
	        			<img src='frontend/album/{{$ha->ha_tentaptin}}' alt='' class='img-border' height='250' width='450'>
	        		@endforeach            
				</center> 
	        <br /> 
	    </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->                          	
</section>
@endsection