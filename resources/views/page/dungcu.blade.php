@extends('layout.index1')

@section('title')
	{{"Danh sách thiết bị cho thuê"}}
@endsection

@section('content')
<!-- Phần cài đặt data table -->
<script language="javascript">
	$(document).ready(function() {
		var table = $('#tableDungCu').DataTable( {
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

<!-- Phần giao diện hiển thị danh sách dụng cụ-->
<section id="content">       
    <div class="container_12">
      <div class="grid_12">
	    	<br /><h3>Danh sách thiết bị cho thuê:</h3> <br />
			<table id="tableDungCu" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th style="width:10%; text-align:center"><strong>STT</strong></th>
						<th style="width:30%; text-align:center"><strong>Tên thiết bị</strong></th>
	                    <th style="width:20%; text-align:center"><strong>Nhà cung cấp</strong></th>
	                    <th style="width:20%; text-align:center"><strong>Đơn giá</strong></th>
						<th style="width:20%; text-align:center"><strong>Trạng thái (thiết bị)</strong></th>			
					</tr>
				 </thead>
		
				<tbody>         
					<?php $dem=1; $count=0; ?>
					@foreach($dungcu as $dc)   
					<tr>
						<td align="center">{{$dem}}</td>
						<td align="left">{{$dc->dc_ten}}</td>
						<td align="left">
							<ul>
							@foreach($nhacungcap[$count] as $ncc)
								<li>{{$ncc->ncc_ten}}</li>
							@endforeach
							</ul>
						</td>
						<td align="right">
							<ul>
							@foreach($nhacungcap[$count] as $ncc)
								<li>{{number_format($ncc->ctpn_dongia,0,',','.')." VNĐ"}}</li>
							@endforeach
							</ul>
						</td>
						<td align="center">
							@if($dc->dc_trangthai == 1)
								{{"Cho thuê"}}
							@else
								{{"Ngừng cho thuê"}}
							@endif
						</td>					
					</tr>       
					<?php $dem++; $count++; ?>             
					@endforeach
				</tbody>       
			</table>      
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
</section>
@endsection