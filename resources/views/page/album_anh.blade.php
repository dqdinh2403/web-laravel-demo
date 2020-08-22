 @extends('layout.index1')

 @section('title')
    {{"Album"}}
 @endsection 

 @section('content')      
<!-- Phần hiển thị các album hình ảnh -->
<section id="content">
    <div class="container_12">
      <div class="grid_12">
        <!-- Show album -->
        <?php $count=0; ?>
        @foreach($sukien as $sk)
            @if( ($count+1)%3 == 1 )
                {!!"<div class='wrap block-1 pad-1'>"!!}
            @endif
            @if( ($count+1)%3 == 0 )
                {!!"<div class='last' >"!!}
            @else
                {!!"<div>"!!}
            @endif
            <h3 style="height:60px"><a href="danhsachhinhanh/{{$sk->sk_ma}}/{{str_slug($sk->sk_ten,'-')}}.html">
                @if( strlen($sk->sk_ten)>40 )
                    {!!mb_substr($sk->sk_ten,0,40,'UTF-8')."..."!!}
                @else
                    {!!$sk->sk_ten!!}
                @endif
            </a></h3>
            <img 
                @if( $anh[$count] == '' )
                    {!!"src='frontend/album/no_image.gif'"!!}
                @else
                    {!!"src='frontend/album/$anh[$count]'"!!}
                @endif  
             alt="" class="img-border" height="154px" width="270px">
                </div> 
            @if( ($count+1)%3 == 0 )
                {!!"</div>"!!}  {{-- class="wrap block-1 pad-1" --}}
            @endif
            <?php  $count++; ?>
        @endforeach
            </div> <!-- class="wrap block-1 pad-1" --> <!-- thẻ </div> này là thẻ trừ hao (do bị thiếu) -->
          <!-- Kết thúc show sự kiện -->
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
</section>
@endsection    