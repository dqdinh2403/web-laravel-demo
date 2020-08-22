@extends('layout.index1')

@section('title')
	{{"Staff: Tham gia sự kiện"}}
@endsection

@section('content')	
<section id="content">       
    <div class="container_12">
      <div class="grid_12"> 

      		<!-- Phần confirm tham gia -->
		    <script language="javascript">
				function joinConfirm(){
					if(confirm("Bạn có chắc chắn muốn tham gia sự kiện ?")){
						return true;	
					}
					else
						return false;
				}   	   
		    </script>

		    <!-- Phần cài đặt data table -->
		    <script language="javascript">
				$(document).ready(function() {
					var table = $('#tableThamGia').DataTable( {
						responsive: true,
						"language": {
							"lengthMenu": "Hiển thị _MENU_ dòng dữ liệu trên một trang", 
							"info": "Hiển thị _START_ trong tổng số _TOTAL_ dòng dữ liệu",
							"infoEmpty": "Dữ liệu rỗng",
							"emptyTable": "Chưa có dữ liệu nào",
							"processing": "Đang xử lý...",
							"search": "Tìm kiếm:",
							"loadingRecords": "Đang load dữ liệu...",
							"zeroRecords": "Không tìm thấy dữ liệu",
							"infoFiltered": "(Được từ tổng số _MAX_ dòng dữ liệu)",
							"paginate":{
								"first": "|<",
								"last": ">|",
								"next": ">>",
								"previous": "<<"
							}
						},
						"lengthMenu": [[10,15,20,25,30,-1],[10,15,20,25,30,"Tất cả"]]
					});
					new $.fn.dataTable.FexidHeader(table);
				});	
		    </script>

		    <!-- Phần hiển thị thông báo -->
		    <div class="container">
		    	@if(session('thongbao'))
		    		<div class="alert alert-success">
		    			{{session('thongbao')}}
		    		</div>
		    	@endif

		    	@if(session('loi'))
		    		<div class="alert alert-danger">
		    			{{session('loi')}}
		    		</div>
		    	@endif
		    </div>
    
		<!-- Phần giao diện tham gia sự kiện -->
    	<br />		
		<h3>Tham gia sự kiện:</h3> <br>			
		<table id="tableThamGia" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th style="width:5%; text-align:center"><strong>STT</strong></th>
					<th style="width:25%; text-align:center"><strong>Tên sự kiện</strong></th>
					<th style="width:10%; text-align:center"><strong>Ngày tổ chức</strong></th>
					<th style="width:20%; text-align:center"><strong>Địa điểm</strong></th>
					<th style="width:20%; text-align:center"><strong>Công việc</strong></th>
					<th style="width:10%; text-align:center"><strong>Số lượng nhân viên</strong></th>
					<th style="width:10%; text-align:center"><strong>Tham gia</strong></th>					
				</tr>
			 </thead>
	
			<tbody>     
				<?php $stt=1; ?>    
				@foreach($sukien as $sk)           
					<tr>
						<td align="center">{{$stt}}</td>
						<td align="left">{{$sk->sk_ten}}</td>
						<td align="center">{{date_format(date_create($sk->sk_thoigianbatdaud),'d/m/Y')}}</td>						
						<td align="left">{{$sk->sk_diadiem}}</td>
						<td align="left">{{$sk->cv_ten}}</td>
						<td align="right">{{$sk->sk_cv_nv_soluongnhanvien}}</td>					
						<td align='center'>
							<a href="staff/xlthamgiasukien/{{$sk->sk_ma}}/{{$sk->cv_ma}}" onclick="return joinConfirm()"><img src='frontend/images/add.png' border='0' /></a>
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