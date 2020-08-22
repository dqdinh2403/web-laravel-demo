@extends('layout.index1')

@section('title')
  {{"Staff: Lịch sử tham gia sự kiện"}}
@endsection

@section('content')
<section id="content">       
    <div class="container_12">
      <div class="grid_12"> 

      <!-- Phần hiển thị thông báo -->
      @if( session('thongbao') )
        <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{session('thongbao')}}
      </div>
      @endif

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
          "lengthMenu": [[5,10,15,20,25,30,-1],[5,10,15,20,25,30,"Tất cả"]]
        });
        new $.fn.dataTable.FexidHeader(table);
      }); 
      </script>

 	    <!-- Phần giao diện danh sách sự kiện đã tham gia -->
    	<br /><h3>Danh sách sự kiện đã tham gia:</h3><hr />    
	    <table id="tableThamGia" class="table table-striped table-bordered" cellspacing="0" width="100%">
	      <thead>
	        <tr>
	          <th style="width:5%; text-align:center"><strong>STT</strong></th>
	          <th style="width:30%; text-align:center"><strong>Tên sự kiện</strong></th>
	          <th style="width:15%; text-align:center"><strong>Ngày tổ chức</strong></th>
	          <th style="width:25%; text-align:center"><strong>Công việc</strong></th> 
            <th style="width:15%; text-align:center"><strong>Trạng thái</strong></th>
            <th style="width:10%; text-align:center"><strong>Ghi chú</strong></th>             
	        </tr>
	       </thead>
	  
	      <tbody>     
	        <?php $stt=1; ?>    
	        @foreach($sukien as $sk)           
	          <tr>
	            <td align="center">{{$stt}}</td>
	            <td align="left">{{$sk->sk_ten}}</td>
	            <td align="center">{{date_format(date_create($sk->sk_thoigianbatdaud),'d/m/Y')}}</td>
	            <td align="left">{{$sk->cv_ten}}</td>
	            <td align="center">
                @if($sk->sk_cv_nv_trangthai == 1)
                  {{"Hoàn thành"}}
                @else
                  {{"Không hoàn thành"}}
                @endif
              </td>  
              <td align="center">
                <a href="staff/ghichusukien/{{$sk->sk_ma}}/{{$sk->cv_ma}}"><img src='frontend/images/edit.png' border='0' /></a>
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