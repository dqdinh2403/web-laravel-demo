@extends('layout.index2')

@section('title')
	{{"Quản lý nhân viên"}}
@endsection

@section('javascript1')
	<!-- page specific plugin scripts -->
	<script src="backend/js/jquery.dataTables.min.js"></script>
	<script src="backend/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="backend/js/dataTables.buttons.min.js"></script>
	<script src="backend/js/buttons.flash.min.js"></script>
	<script src="backend/js/buttons.html5.min.js"></script>
	<script src="backend/js/buttons.print.min.js"></script>
	<script src="backend/js/buttons.colVis.min.js"></script>
	<script src="backend/js/dataTables.select.min.js"></script>
@endsection

@section('javascript2')
	<!-- Phần confirm xóa -->
    <script language="javascript">
		function deleteConfirm(ten){
			if(confirm("Bạn có chắc chắn muốn xóa nhân viên '" + ten + "' ?")){
				return true;	
			}
			else
				return false;
		}   	   
    </script>

	<!-- inline scripts related to this page -->
	<script type="text/javascript">
		jQuery(function($) {
			//initiate dataTables plugin
			var myTable = 
			$('#dynamic-table')
			//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
			.DataTable( {
				bAutoWidth: false,
				"aoColumns": [
				  null, null, null, null, null,
				  { "bSortable": false }, { "bSortable": false }, { "bSortable": false }
				],
				"aaSorting": [],
				
				
				//"bProcessing": true,
		        //"bServerSide": true,
		        //"sAjaxSource": "http://127.0.0.1/table.php"	,
		
				//,
				//"sScrollY": "200px",
				//"bPaginate": false,
		
				//"sScrollX": "100%",
				//"sScrollXInner": "120%",
				//"bScrollCollapse": true,
				//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
				//you may want to wrap the table inside a "div.dataTables_borderWrap" element
		
				//"iDisplayLength": 50
		
		
				select: {
					style: 'multi'
				}
		    } );
		
			
			
			$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
			
			new $.fn.dataTable.Buttons( myTable, {
				buttons: [
				  {
					"extend": "colvis",
					"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
					"className": "btn btn-white btn-primary btn-bold",
					columns: ':not(:first):not(:last)'
				  },
				  {
					"extend": "copy",
					"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
					"className": "btn btn-white btn-primary btn-bold"
				  },
				  {
					"extend": "csv",
					"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
					"className": "btn btn-white btn-primary btn-bold"
				  },
				  {
					"extend": "excel",
					"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
					"className": "btn btn-white btn-primary btn-bold"
				  },
				  {
					"extend": "pdf",
					"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
					"className": "btn btn-white btn-primary btn-bold"
				  },
				  {
					"extend": "print",
					"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
					"className": "btn btn-white btn-primary btn-bold",
					autoPrint: false,
					message: 'This print was produced using the Print button for DataTables'
				  }		  
				]
			} );
			myTable.buttons().container().appendTo( $('.tableTools-container') );
			
			//style the message box
			var defaultCopyAction = myTable.button(1).action();
			myTable.button(1).action(function (e, dt, button, config) {
				defaultCopyAction(e, dt, button, config);
				$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
			});
			
			
			var defaultColvisAction = myTable.button(0).action();
			myTable.button(0).action(function (e, dt, button, config) {
				
				defaultColvisAction(e, dt, button, config);
				
				
				if($('.dt-button-collection > .dropdown-menu').length == 0) {
					$('.dt-button-collection')
					.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
					.find('a').attr('href', '#').wrap("<li />")
				}
				$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
			});
		
			////
		
			setTimeout(function() {
				$($('.tableTools-container')).find('a.dt-button').each(function() {
					var div = $(this).find(' > div').first();
					if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
					else $(this).tooltip({container: 'body', title: $(this).text()});
				});
			}, 500);
			
			
			
			
			
			myTable.on( 'select', function ( e, dt, type, index ) {
				if ( type === 'row' ) {
					$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
				}
			} );
			myTable.on( 'deselect', function ( e, dt, type, index ) {
				if ( type === 'row' ) {
					$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
				}
			} );
		
		
		
		
			/////////////////////////////////
			//table checkboxes
			$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
			
			//select/deselect all rows according to table header checkbox
			$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
				var th_checked = this.checked;//checkbox inside "TH" table header
				
				$('#dynamic-table').find('tbody > tr').each(function(){
					var row = this;
					if(th_checked) myTable.row(row).select();
					else  myTable.row(row).deselect();
				});
			});
			
			//select/deselect a row when the checkbox is checked/unchecked
			$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
				var row = $(this).closest('tr').get(0);
				if(this.checked) myTable.row(row).deselect();
				else myTable.row(row).select();
			});
		
		
		
			$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
				e.stopImmediatePropagation();
				e.stopPropagation();
				e.preventDefault();
			});
			
			
			
			//And for the first simple table, which doesn't have TableTools or dataTables
			//select/deselect all rows according to table header checkbox
			var active_class = 'active';
			$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
				var th_checked = this.checked;//checkbox inside "TH" table header
				
				$(this).closest('table').find('tbody > tr').each(function(){
					var row = this;
					if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
					else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
				});
			});
			
			//select/deselect a row when the checkbox is checked/unchecked
			$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
				var $row = $(this).closest('tr');
				if($row.is('.detail-row ')) return;
				if(this.checked) $row.addClass(active_class);
				else $row.removeClass(active_class);
			});
		
			
		
			/********************************/
			//add tooltip for small view action buttons in dropdown menu
			$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
			
			//tooltip placement on right or left
			function tooltip_placement(context, source) {
				var $source = $(source);
				var $parent = $source.closest('table')
				var off1 = $parent.offset();
				var w1 = $parent.width();
		
				var off2 = $source.offset();
				//var w2 = $source.width();
		
				if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
				return 'left';
			}
			
			
			
			
			/***************/
			$('.show-details-btn').on('click', function(e) {
				e.preventDefault();
				$(this).closest('tr').next().toggleClass('open');
				$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
			});
			/***************/
			
			
			
			
			
			/**
			//add horizontal scrollbars to a simple table
			$('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
			  {
				horizontal: true,
				styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
				size: 2000,
				mouseWheelLock: true
			  }
			).css('padding-top', '12px');
			*/
		
		
		})
	</script>
@endsection

@section('breadcrumb')
	<li class="active">Quản lý nhân viên</li>
	<li class="active">Danh sách</li>
@endsection

@section('content')
	<div class="page-content">
		<div class="page-header">
			<h1>Quản lý nhân viên:</h1>
		</div><!-- /.page-header -->

		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->

				@if( session('thongbao') )
		    		<div class="alert alert-success alert-dismissible">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  {{session('thongbao')}}
					</div>
		    	@endif

				<!-- div.table-responsive -->

				<!-- div.dataTables_borderWrap -->
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="10%" class="center">STT</th>
								<th width="15%" class="center">Nhân viên</th>
								<th width="15%" class="center">Tài khoản</th>
								<th width="15%" class="center">Quyền</th>
								<th width="15%" class="center">Trạng thái</th>
								<th width="10%" class="center">SK đã phụ trách</th>
								<th width="10%" class="center">Cập nhật</th>
								<th width="10%" class="center">Xóa</th>
							</tr>
						</thead>

						<tbody>
							<?php $stt=1; ?>
							@foreach($nhanvien as $nv)
							<tr>
								<td class="center">{{$stt}}</td>
								<td class="left">{{$nv->nv_tennhanvien}}</td>
								<td class="left">{{$nv->tk_tendangnhap}}</td>
								<td class="left">{{$nv->q_ten}}</td>
								<td class="center">
									@if($nv->tk_trangthai==0)
										{!!"<span class='label label-sm label-inverse arrowed-in'>Dừng hoạt động</span>"!!}
									@else
										{!!"<span class='label label-sm label-info arrowed arrowed-righ'>Đang hoạt động</span>"!!}
									@endif
								</td>
								<td class="center">
									@if($nv->q_ma==1)
										{{"No action"}}
									@else
										<a class="blue" href="admin/nhanvien/thamgia/{{$nv->nv_ma}}">
											<i class="ace-icon fa fa-search-plus bigger-130"></i>
										</a>
									@endif
								</td>
								<td class="center">
									@if($nv->q_ma==1)
										{{"No action"}}
									@else
										<a class="green" href="admin/nhanvien/capnhat/{{$nv->tk_ma}}">
											<i class="ace-icon fa fa-pencil bigger-130"></i>
										</a>
									@endif
								</td>
								<td class="center">
									@if($nv->q_ma==1)
										{{"No action"}}
									@else
										<a class="red" href="admin/nhanvien/xoa/{{$nv->tk_ma}}" onclick="return deleteConfirm('{{$nv->nv_tennhanvien}}')">
											<i class="ace-icon fa fa-trash-o bigger-130"></i>
										</a>
									@endif
								</td>
							</tr>
							<?php $stt++; ?>
							@endforeach
						</tbody>
					</table>
				</div>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.page-content -->
@endsection