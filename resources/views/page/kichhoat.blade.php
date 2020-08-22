@extends('layout.index1')

@section('title')
	{{"Kích hoạt tài khoản"}}
@endsection

@section('content')
<section id="content">       
    <div class="container_12">
      <div class="grid_12">

      	<!-- Phần hiển thị thông báo -->
		  @if( !empty($thongbao) )
		    <div class="alert alert-success">
		        {{$thongbao}}
		    </div>
		  @endif
		  @if( !empty($loi) )
		    <div class="alert alert-danger">
		        {{$loi}}
		    </div>
		  @endif
      
        <!-- Phần giao diện -->
      	@for($i=1;$i<=10;$i++)
          {!!"<br>"!!}
        @endfor
        
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
</section>
@endsection