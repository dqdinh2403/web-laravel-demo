@extends('layout.index1')

@section('title')
	{{"Đăng ký sự kiện: ".$loaisukien->lsk_ten}}
@endsection	

@section('content')
<!-- Thư viện CKeditor -->  
<script type="text/javascript" src="common/ckeditor/ckeditor.js"></script>

<section id="content">       
    <div class="container_12">
      <div class="grid_12">  

      <!-- Phần hiển thị thông báo -->
      @if( count($errors)>0 )
        <div class="alert alert-danger">
          @foreach($errors->all() as $er)
            {{$er}} <br>
          @endforeach
        </div>
      @endif

      @if( session('thongbao') )
        <div class="alert alert-success">
          {{session('thongbao')}}
        </div>
      @endif
      @if( session('loi') )
        <div class="alert alert-danger">
          {{session('loi')}}
        </div>
      @endif
    
      <!-- Phần giao diện đăng ký sự kiện -->	
  		<br /><h3>Tổ chức sự kiện:</h3><hr />
      <form id="formToChucSuKien" name="formToChucSuKien" method="POST" action="guest/dangkysukien/{{$loaisukien->lsk_ma}}" class="form-horizontal" role="form">
          {{csrf_field()}}
          <div class="form-group">	    
            <label for="txtLoaiSuKien" class="col-sm-2 control-label">Loại sự kiện(*):  </label>
              <div class="col-sm-10">
              <input type="text" class="form-control" name="txtLoaiSuKien" id="txtLoaiSuKien" aria-describedby="basic-addon1" value="{{$loaisukien->lsk_ten}}" disabled="">                 
               </div>
           </div>  
                                                       
         <div class="form-group"> 
              <label for="txtTenSuKien" class="col-sm-2 control-label">Tên sự kiện(*):  </label>
              <div class="col-sm-10">
                    <input type="text" name="txtTenSuKien" id="txtTenSuKien" class="form-control" placeholder="Tên sự kiện"
                    	 value="{{old('txtTenSuKien')}}"/>
              </div>
          </div>
              
           <div class="form-group"> 
              <label for="txtDiaDiem" class="col-sm-2 control-label">Địa điểm(*):  </label>
              <div class="col-sm-10">
                    <input type="text" name="txtDiaDiem" id="txtDiaDiem" class="form-control" placeholder="Địa điểm"
                    	 value="{{old('txtDiaDiem')}}"/>
              </div>
          </div>
                             
          <div class="form-group"> 
              <label for="txtThoiGian" class="col-sm-2 control-label">Thời gian tổ chức(*):  </label>
              <div class="col-sm-3">
                <input type="time" name="txtTime" id="txtTime" class="form-control" value="{{old('txtTime')}}"/>
              </div>
              <div class="col-sm-7">
                <input type="date" name="txtDate" id="txtDate" class="form-control" value="{{old('txtDate')}}"/>
              </div>
          </div>    

          <div class="form-group">                               
              <label for="txtThoiLuong" class="col-sm-2 control-label">Thời lượng(*):  </label>
              <div class="col-sm-10">
                 <input type="number" name="txtThoiLuong" id="txtThoiLuong" class="form-control" placeholder="Thời lượng"
                      value="{{old('txtThoiLuong')}}"/>
              </div>
          </div>
          
          <div class="form-group">	    
            <label for="txtKhachHang" class="col-sm-2 control-label">Khách hàng(*):  </label>
              <div class="col-sm-10">
              <input type="text" class="form-control" name="txtKhachHang" id="txtKhachHang" aria-describedby="basic-addon1" value="{{$khachhang->kh_tencongty}}" disabled="">         
               </div>
           </div>    
                      
          <div class="form-group">   
           <label for="txtNoiDungSuKien" class="col-sm-2 control-label">Nội dung sự kiện(*):  </label>
              <div class="col-sm-10">
                   <textarea name="txtNoiDungSuKien" rows="4" class="ckeditor">{{old('txtNoiDungSuKien')}}</textarea>
                      <script language="javascript">
                          CKEDITOR.replace( 'txtNoiDungSuKien',
                          {
                              skin : 'kama',
                              extraPlugins : 'uicolor',
                              uiColor: '#eeeeee',
                              toolbar : [ ['Source','DocProps','-','Save','NewPage','Preview','-','Templates'],
                                  ['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
                                  ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
                                  ['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
                                  ['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
                                  ['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote'],
                                  ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
                                  ['Link','Unlink','Anchor', 'NumberedList','BulletedList','-','Outdent','Indent'],
                                  ['Image','Flash','Table','Rule','Smiley','SpecialChar'],
                                  ['Style','FontFormat','FontName','FontSize'],
                                  ['TextColor','BGColor'],[ 'UIColor' ] ]
                          });                     
                      </script>          
              </div>
          </div> 

          <input type="hidden" name="txtMaKhachHang" value="{{$khachhang->kh_ma}}">

          <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit"  class="btn btn-primary" name="btnDangKy" id="btnDangKy" value="Đăng ký"/>
                    <input type="button" class="btn btn-primary" name="btnHuy"  id="btnHuy" value="Hủy"
                          onClick="window.location='trangchu'" /> 
              </div>
          </div>
          </form>
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
@endsection