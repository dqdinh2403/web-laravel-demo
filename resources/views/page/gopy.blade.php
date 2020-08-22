@extends('layout.index1')

@section('title')
	{{"Góp ý"}}
@endsection

@section('content')
<!-- Thư viện CKEditor -->
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
 
        <!-- Phần giao diện góp ý --> 
        <br /><h3>Góp ý</h3> <hr />
        <form id="formGopy" name="formGopy" method="POST" action="gopy" class="form-horizontal" role="form">
            {{csrf_field()}}        
        
            <div class="form-group">      
                <label for="txtTieude" class="col-sm-2 control-label">Tiêu đề(*):  </label>
                <div class="col-sm-10">
                      <input type="text" name="txtTieude" id="txtTieude" class="form-control" placeholder="Nhập tiêu đề"
                       value="{{old('txtTieude')}}"/>
                </div>
            </div>  
    
            <div class="form-group">   
                <label for="txtNoidung" class="col-sm-2 control-label">Nội dung(*):  </label>
                <div class="col-sm-10">
                     <textarea name="txtNoidung" class="ckeditor">{!!old('txtNoidung')!!}</textarea>
                            <script language="javascript">
                                CKEDITOR.replace( 'txtNoidung',
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
      
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <input type="submit" class="btn btn-primary" name="btnGopy" id="btnGopy" value="Góp ý"/>  
                  <input type="button" class="btn btn-primary" name="btnHuy"  id="btnHuy" value="Hủy"
                            onClick="window.location='trangchu'" />             
                </div>
            </div>
        </form>
        </div> <!-- class="grid_12" -->                      
      <div class="clear"></div>
    </div> <!-- class="container_12" -->
</section>
@endsection  