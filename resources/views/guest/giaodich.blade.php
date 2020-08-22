@extends('layout.index1')

@section('title')
	{{"Guest: Lịch sử giao dịch"}}
@endsection

@section('content')	
<section id="content">
    <div class="container_12">
      <div class="grid_12">

      	<!-- Phần confirm xóa -->
	    <script language="javascript">
			function deleteConfirm(ten){
				if(confirm("Bạn có chắc chắn muốn hủy sự kiện '" + ten + "' ?")){
					return true;	
				}
				else
					return false;
			}   	   
	    </script>

	    <!-- Phần cài đặt data table -->
	    <script language="javascript">
			// $(document).ready(function() {
			// 	var table1 = $('#tableSuKienChuaDuyet').DataTable( {
			// 		responsive: true,
			// 		"language": {
			// 			"lengthMenu": "Hiển thị _MENU_ dòng dữ liệu trên một trang", 
			// 			"info": "Hiển thị _START_ trong tổng số _TOTAL_ dòng dữ liệu",
			// 			"infoEmpty": "Dữ liệu rỗng",
			// 			"emptyTable": "Chưa có dữ liệu nào",
			// 			"processing": "Đang xử lý...",
			// 			"search": "Tìm kiếm:",
			// 			"loadingRecords": "Đang load dữ liệu...",
			// 			"zeroRecords": "Không tìm thấy dữ liệu",
			// 			"infoFiltered": "(Được từ tổng số _MAX_ dòng dữ liệu)",
			// 			"paginate":{
			// 				"first": "|<",
			// 				"last": ">|",
			// 				"next": ">>",
			// 				"previous": "<<"
			// 			}
			// 		},
			// 		"lengthMenu": [[10,15,20,25,30,-1],[10,15,20,25,30,"Tất cả"]]
			// 	});
			// 	new $.fn.dataTable.FexidHeader(table1);
			// });	

			// $(document).ready(function() {
			// 	var table2 = $('#tableSuKienDaDuyet').DataTable( {
			// 		responsive: true,
			// 		"language": {
			// 			"lengthMenu": "Hiển thị _MENU_ dòng dữ liệu trên một trang", 
			// 			"info": "Hiển thị _START_ trong tổng số _TOTAL_ dòng dữ liệu",
			// 			"infoEmpty": "Dữ liệu rỗng",
			// 			"emptyTable": "Chưa có dữ liệu nào",
			// 			"processing": "Đang xử lý...",
			// 			"search": "Tìm kiếm:",
			// 			"loadingRecords": "Đang load dữ liệu...",
			// 			"zeroRecords": "Không tìm thấy dữ liệu",
			// 			"infoFiltered": "(Được từ tổng số _MAX_ dòng dữ liệu)",
			// 			"paginate":{
			// 				"first": "|<",
			// 				"last": ">|",
			// 				"next": ">>",
			// 				"previous": "<<"
			// 			}
			// 		},
			// 		"lengthMenu": [[10,15,20,25,30,-1],[10,15,20,25,30,"Tất cả"]]
			// 	});
			// 	new $.fn.dataTable.FexidHeader(table2);
			// });	
	    </script>

    	<!-- Phần hiển thị thông báo -->
    	@if( session('thongbao') )
    		<div class="alert alert-success alert-dismissible">
			  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			  {{session('thongbao')}}
			</div>
    	@endif
    
		<!-- Phần giao diện quản lý sự kiện -->
    	<br />		
		<h3>Danh sách sự kiện chưa duyệt:</h3> <br>						
		<table id="tableSuKienChuaDuyet" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th style="width:5%; text-align:center"><strong>STT</strong></th>
					<th style="width:15%; text-align:center"><strong>Loại sự kiện</strong></th>
					<th style="width:25%; text-align:center"><strong>Sự kiện</strong></th>
					<th style="width:15%; text-align:center"><strong>Ngày tổ chức</strong></th>
					<th style="width:10%; text-align:center"><strong>Trạng thái</strong></th>
					<th style="width:20%; text-align:center"><strong>Tùy chọn</strong></th>	
					<th style="width:10%; text-align:center"><strong>Hủy</strong></th>				
				</tr>
			 </thead>
	
			<tbody>         
				<?php $stt=1; ?>
				@foreach($sukien as $sk)           
					<tr>
						<td align="center">{{$stt}}</td>
						<td align="left">{{$sk->lsk_ten}}</td>
						<td align="left">{{$sk->sk_ten}}</td>
						<td align="center">{{date_format(date_create($sk->sk_thoigianbatdaud),'d/m/Y')}}</td>
						<td align="left">
							@if($sk->sk_trangthai==1)
								{{"Chưa duyệt"}}
							@elseif($sk->sk_trangthai==2)
								{{"Đã duyệt"}}
							@else
								{{"Đã xác nhận"}}
							@endif
						</td>
						<td align="center">
							@if($sk->sk_trangthai==1)
								(Không có hành động)
							@elseif($sk->sk_trangthai==2)
								<a href="guest/capnhatsukien/{{$sk->sk_ma}}"><img src='frontend/images/edit.png' border='0' /></a>
								&nbsp; (Cập nhật sự kiện)
								<br/>
								<a href="guest/xacnhansukien/{{$sk->sk_ma}}"><img src='frontend/images/image_edit.png' border='0' /></a>
								&nbsp; (Xác nhận sự kiện)
							@else
								(Không có hành động)
							@endif
						</td>
						<td align="center">
							@if($sk->sk_trangthai==1)
								<a onclick="return deleteConfirm('{{$sk->sk_ten}}')" href="guest/xoasukien/{{$sk->sk_ma}}">
								<img src='frontend/images/delete.png' border='0' /></a>
							@elseif($sk->sk_trangthai==2)
								<a onclick="return deleteConfirm('{{$sk->sk_ten}}')" href="guest/xoasukien/{{$sk->sk_ma}}">
								<img src='frontend/images/delete.png' border='0' /></a>			
							@else
								(Không có hành động)
							@endif
						</td>

					</tr> 
					<?php $stt++; ?>                   
				@endforeach  
			</tbody>       
		</table>        

		<br />		
		<h3>Danh sách sự kiện đã duyệt:</h3> <br>						
		<table id="tableSuKienDaDuyet" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th style="width:10%; text-align:center"><strong>STT</strong></th>
					<th style="width:20%; text-align:center"><strong>Số hợp đồng</strong></th>
					<th style="width:40%; text-align:center"><strong>Sự kiện</strong></th>
					<th style="width:15%; text-align:center"><strong>Ngày tổ chức</strong></th>
					<th style="width:15%; text-align:center"><strong>Thanh toán</strong></th>	
					<th style="width:10%; text-align:center"><strong>Xem</strong></th>		
				</tr>
			 </thead>
	
			<tbody>         
				<?php $stt=1; ?>
				@foreach($hopdong as $hd)           
					<tr>
						<td align="center">{{$stt}}</td>
						<td align="left">{{$hd->hdtcsk_sohopdong}}</td>
						<td align="left">{{$hd->sk_ten}}</td>
						<td align="center">{{date_format(date_create($hd->sk_thoigianbatdaud),'d/m/Y')}}</td>
						<td align="center">
							@if($hd->hdtcsk_thanhtoan == 1)
								{{"Đã thanh toán"}}
							@else
								{{"Chưa thanh toán"}}
							@endif
						</td>
						<td align="center">
							<a href="guest/xemsukien/{{$hd->sk_ma}}"><img src='frontend/images/light_on.png' border='0' /></a>
						</td>

					</tr> 
					<?php $stt++; ?>                   
				@endforeach  
			</tbody>       
		</table> 		
    </div> <!-- class="grid_12" -->                      
  <div class="clear"></div>
</div> <!-- class="container_12" -->
</section>
@endsection   