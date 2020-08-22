@extends('layout.index1')

@section('title')
	{{"Album ".$sukien->sk_ten}}
@endsection   

@section('content')
<!-- Phần hiển thị các hình ảnh của album đã chọn -->
<section id="content">
    <div class="container_12">
      <div class="grid_12">
        <br /><h3>Album hình ảnh cho Sự kiện "<font color="red">{{$sukien->sk_ten}}</font>"</h3> <hr />
        <!-- Show hình ảnh -->
        <center>
          @foreach($hinhanh as $ha)
          	<img src='frontend/album/{{$ha->ha_tentaptin}}' alt='' class='img-border' height='400' width='700'>
          	<hr>
          @endforeach
        </center>
          <!-- Kết thúc show hình ảnh -->
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
</section>
@endsection   