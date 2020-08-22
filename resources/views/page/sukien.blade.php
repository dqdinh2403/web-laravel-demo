@extends('layout.index1')

@section('title')
	{{"Danh sách loại sự kiện"}}
@endsection

@section('content')
<section id="content">       
    <div class="container_12">
      <div class="grid_12">	

    	@if(session('loi'))
	        <div class="alert alert-danger">
	            {{session('loi')}}
	        </div>
	    @endif
            
    	<br><h3>Danh sách loại các sự kiện mà công ty hỗ trợ tổ chức: </h3> <hr>
        <ul style="padding-left:25px">
        	@foreach($loaisukien as $lsk)         
                <li><a href="guest/dangkysukien/{{$lsk->lsk_ma}}">	
                    <img src='frontend/images/page3-img4.png' height='40' width='40'>{{$lsk->lsk_ten}}</a>
                    <br />
                    Mô tả: 
                    @if( $lsk->lsk_mota == '' )
                    	{{'không có mô tả'}}
                    @else
                    	{!!$lsk->lsk_mota!!}
                    @endif
                </li>
                <hr>                                
			@endforeach
        </ul>  
                       			                     	
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
</section>            	
@endsection