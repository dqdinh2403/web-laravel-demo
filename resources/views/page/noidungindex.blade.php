@extends('layout.index1') 

@section('title')
	{{"Trang Chủ"}}
@endsection

@section('content')
<!-- CSS slider show -->
<link rel="stylesheet" type="text/css" media="screen" href="frontend/css/slider.css">

<!-- JS slider show -->
<script src="frontend/js/jquery-1.7.min.js"></script>
<script src="frontend/js/jquery.easing.1.3.js"></script>
<script src="frontend/js/tms-0.4.x.js"></script>

<!-- CSS slider show (custom) -->
<style>
    .pagination{	
        right: 0px;
        top: 343px;
        margin-top: 0px;
        margin-bottom: 0px;
        width: 940px;
    }
    .pagination a {
        display:none;
    }
</style>

<!-- JS slider show -->
<script>
    $(document).ready(function(){				   	
        $('.slider')._TMS({
            show:0,
            pauseOnHover:true,
            prevBu:false,
            nextBu:false,
            playBu:false,
            duration:1000,
            preset:'fade',
            pagination:true,
            pagNums:false,
            slideshow:5000,
            numStatus:true,
            banners:'fromRight',
            waitBannerAnimation:false,
            progressBar:false
        })		
    });
</script>
         
<!-- Phần giao diện nội dung index -->
<section id="content">
    <!-- Slider show -->
    <div id="slide" class="box-shadow">
      <div class="slider">
        <ul class="items">
          <li><img src="frontend/images/anh1.jpg" alt="" />
            <div class="banner">Tổ chức giới thiệu sản phẩm, dịch vụ &nbsp;</div>
          </li>
          <li><img src="frontend/images/anh2.jpg" alt="" />
            <div class="banner">Tổ chức biểu điễn ca nhạc, nghệ thuật &nbsp;</div>
          </li>
          <li><img src="frontend/images/anh3.jpg" alt="" />
            <div class="banner">Tri ân khách hàng &nbsp;</div>
          </li>
        </ul>
      </div>
    </div>
    <!-- Kết thúc slider show -->
    <div class="container_12">
      <div class="grid_12">
        <!-- Câu slogan -->
        <div class="pad-0 border-1">
          <h2 class="top-1 p0">EventTechnology - Nơi bạn đặt đúng niềm tin!</h2>          
        </div>
        <!-- Kết thúc slogan -->
        <!-- Show danh sách sự kiện -->
        <?php $count=0; ?>
        
        @foreach($sukien as $sk)
        	@if( ($count+1)%3 == 1 )
        		{!!"<div class='wrap block-1 pad-1'>"!!}
        	@endif  
        	@if( ($count+1)%3 == 0 )
        		{!!"<div class='last'>"!!}
        	@else
        		{!!"<div>"!!}
        	@endif
        	<h3 style="height:60px">
        		@if( strlen($sk->sk_ten) > 40 )
        			{!!mb_substr($sk->sk_ten,0,40,'UTF-8')."..."!!}
        		@else
        			{{$sk->sk_ten}}
        		@endif          
        	</h3>
        	<img 
        		@if( $anh[$count] == '' )
        			{!!"src='frontend/album/no_image.gif'"!!}
        		@else
        			{!!"src='frontend/album/$anh[$count]'"!!}
        		@endif
        	 alt="" class="img-border" height="154px" width="270px">
        	<p>                  
                @if( strlen($sk->sk_noidungsukien) > 140 )
                	{!!mb_substr($sk->sk_noidungsukien,0,140,'UTF-8')."..."!!}
                @else
                	{!!$sk->sk_noidungsukien!!}
                @endif	
            </p>
            <a href="chitietsukien/{{$sk->sk_ma}}/{{str_slug($sk->sk_ten,'-')}}.html" class="button">Xem thêm</a> </div>
            @if( ($count+1)%3 == 0 )
            	{!!"</div>"!!} <!-- class="wrap block-1 pad-1" -->
            @endif
            <?php $count++; ?>             
        @endforeach
            </div> <!-- class="wrap block-1 pad-1" --> <!-- thẻ </div> này là thẻ trừ hao (do bị thiếu) -->
        <!-- Kết thúc show sự kiện -->
        </div>
        <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div>
    <!-- class="container_12" -->
</section>
@endsection      