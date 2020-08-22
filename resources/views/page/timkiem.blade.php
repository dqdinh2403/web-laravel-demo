@extends('layout.index1')

@section('title')
	{{"Tìm kiếm: ".$tukhoa}}
@endsection

@section('content')
<!-- Phần giao diện hiển thị kết quả tìm kiếm -->
<section id="content">       
    <div class="container_12">
      <div class="grid_12">      	
        <br><h3>Có {{$count}} kết quả tìm kiếm cho từ khóa "<font color="red">{{$tukhoa}}</font>"</h3> <hr>
        @if( $count == 0 )
        	@for($i=1;$i<=10;$i++)
                {!!"<br>"!!}
            @endfor
        @endif
                
        <!-- Show sự kiện -->
        <?php $dem=0; ?>
        @foreach($sukien as $sk)                    
        	@if( ($dem+1)%3==1 )
        		{!!"<div class='wrap block-1 pad-1'>"!!}
        	@endif

        	@if( ($dem+1)%3==0 )
        		{!!"<div class='last'>"!!}
        	@else
        		{!!"<div>"!!}
        	@endif
           
        	<h3>
        		@if( strlen($sk->sk_ten)>30 )
        			{!!mb_substr($sk->sk_ten,0,30,'UTF-8')."..."!!}
        		@else
        			{!!$sk->sk_ten!!}
        		@endif
        	</h3>
           
            <img 
            	@if( $anh[$dem] == '' )
                    {!!"src='frontend/album/no_image.gif'"!!}
                @else
                    {!!"src='frontend/album/$anh[$dem]'"!!}
                @endif
             alt="" class="img-border" height="154px" width="270px">

            <p>
            @if( strlen($sk->sk_noidungsukien)>90 )
    			{!!mb_substr($sk->sk_noidungsukien,0,100,'UTF-8')."..."!!}
    		@else
    			{!!$sk->sk_noidungsukien!!}
    		@endif                  
            </p>

            <a href="chitietsukien/{{$sk->sk_ma}}/{{str_slug($sk->sk_ten,'-')}}.html" class="button">Xem thêm</a> </div>    

            @if( ($dem+1)%3==0 )  
            	{!!"</div>"!!}   {{-- class="wrap block-1 pad-1" --}}
            @endif
	
            <?php $dem++; ?>
        @endforeach
            </div> <!-- class="wrap block-1 pad-1" --> <!-- Thẻ </div> này là thẻ trừ hao (do bị thiếu) -->
          <!-- Kết thúc show sự kiện -->
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
</section>
@endsection