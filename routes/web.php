<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', 'PageController@test');

/* 
# Phần route điều hướng chính của website.
# Bao gồm 3 group chính:
#	- admin: điều hướng các chức năng của quản lý -> AdminController
#	- guest: điều hướng các chức năng của khách hàng -> GuestController
#	- staff: điều hướng các chức năng của nhân viên -> StaffController
#	- nhóm các điều hướng các chức năng cơ bản của website -> PageController
#	['middleware'=>['web']] : sử dụng session
*/

Route::group(['middleware'=>['web']], function(){
	
	Route::post('dangnhap','PageController@postDangnhap');
	Route::get('dangxuat','PageController@getDangxuat');

	Route::get('quenmatkhau','PageController@getQuenmatkhau');
	Route::post('quenmatkhau','PageController@postQuenmatkhau');

	Route::get('dangky','PageController@getDangky');
	Route::post('dangky','PageController@postDangKy');
	Route::get('kichhoat/{id}/{code}','PageController@kichhoat');

	Route::get('timkiem/{tukhoa}','PageController@timkiem');

	Route::get('trangchu','PageController@trangchu');
	Route::get('chitietsukien/{id}/{TenKhongDau}.html','PageController@chitietsukien');

	Route::get('gioithieu','PageController@gioithieu');

	Route::get('sukien','PageController@sukien');

	Route::get('dungcu','PageController@dungcu');

	Route::get('album_anh','PageController@albumanh');
	Route::get('danhsachhinhanh/{id}/{TenKhongDau}.html','PageController@danhsachhinhanh');

	Route::get('lienhe','PageController@lienhe');

	Route::get('gopy','PageController@getGopy');
	Route::post('gopy','PageController@postGopy');
});


/* Các điều hướng chức năng của "khách hàng":
#	- Thông tin khách hàng
#	- Đăng ký sự kiện
#	- Lịch sử giao dịch
#	- Cập nhật sự kiện
#	- Xác nhận sự kiện
#	- Xem sự kiện
#	- Xóa sự kiện
*/
Route::group(['prefix'=>'guest', 'middleware'=>['web']], function(){

	Route::get('ttkhachhang','GuestController@getThongtinKH');
	Route::post('ttkhachhang','GuestController@postThongtinKH');

	Route::get('dangkysukien/{id}','GuestController@getDangkysukien');
	Route::post('dangkysukien/{id}','GuestController@postDangkysukien');

	Route::get('giaodich','GuestController@getGiaodich');

	Route::get('capnhatsukien/{id}','GuestController@getCapnhatsukien');
	Route::post('capnhatsukien/{id}','GuestController@postCapnhatsukien');

	Route::get('xacnhansukien/{id}','GuestController@getXacnhansukien');
	Route::post('xacnhansukien/{id}','GuestController@postXacnhansukien');

	Route::get('xemsukien/{id}','GuestController@getXemsukien');

	Route::get('xoasukien/{id}','GuestController@getXoasukien');

});


/* Các điều hướng chức năng của "nhân viên":
#	- Thông tin nhân viên
#	- Đăng ký tham gia sự kiện
#	- Ghi chú sự kiện
#	- Lịch sử tham gia sự kiện
*/
Route::group(['prefix'=>'staff', 'middleware'=>['web']], function(){

	Route::get('ttnhanvien','StaffController@getThongtinNV');
	Route::post('ttnhanvien','StaffController@postThongtinNV');

	Route::get('thamgiasukien','StaffController@getThamgiasukien');
	Route::get('xlthamgiasukien/{skma}/{cvma}','StaffController@getXLthamgiasukien');

	Route::get('ghichusukien/{skma}/{cvma}','StaffController@getGhichuSK');
	Route::post('ghichusukien/{skma}/{cvma}','StaffController@postGhichuSK');

	Route::get('lichsuthamgia','StaffController@getLichsuthamgia');

});


/* Các điều hướng chức năng của "quản lý":
#	- Quản lý "nhân viên"
#	- Quản lý "khách hàng"
#	- Quản lý "đối tác"
#	- Quản lý "góp ý"
#	- Quản lý "loại sự kiện"
#	- Quản lý "công việc"
#	- Quản lý "sự kiện"
#	- Quản lý "nhà cung cấp"
#	- Quản lý "dụng cụ"
#	- Quản lý "quyền"
#	- Quản lý "chức năng"
#	- Quản lý "biểu mẫu"
#	- Quản lý "thống kê thu chi"
*/
Route::group(['prefix'=>'admin', 'middleware'=>['web']], function(){

	Route::get('login','AdminController@getLogin');
	Route::post('login','AdminController@postLogin');

	Route::get('logout','AdminController@logout');

	Route::post('forgetPassword','AdminController@forgetPassword');

	Route::get('ttquanly','AdminController@getThongtinQL');
	Route::post('ttquanly','AdminController@postThongtinQL');

	/* Điều hướng trang quản lý chung */
	Route::get('dashboard','AdminController@dashboard');


	/* Điều hướng quản lý "nhân viên":
	#	- Danh sách
	#	- Thông tin tham gia sự kiện
	#	- Thêm mới
	# 	- Cập nhật
	#	- Xóa
	*/
	Route::group(['prefix'=>'nhanvien'], function(){

		Route::get('danhsach','AdminController@getDanhsachNV');

		Route::get('thamgia/{id}','AdminController@getThamgiaNV');

		Route::get('themmoi','AdminController@getThemmoiNV');
		Route::post('themmoi','AdminController@postThemmoiNV');

		Route::get('capnhat/{id}','AdminController@getCapnhatNV');
		Route::post('capnhat/{id}','AdminController@postCapnhatNV');

		Route::get('xoa/{id}','AdminController@getXoaNV');
	});

	/* Điều hướng quản lý "khách hàng":
	#	- Danh sách
	# 	- Cập nhật
	#	- Xóa
	*/
	Route::group(['prefix'=>'khachhang'], function(){

		Route::get('danhsach','AdminController@getDanhsachKH');

		Route::get('capnhat/{id}','AdminController@getCapnhatKH');
		Route::post('capnhat/{id}','AdminController@postcapnhatKH');

		Route::get('xoa/{id}','AdminController@getXoaKH');
	});

	/* Điều hướng quản lý "đối tác":
	#	- Danh sách
	#	- Thêm mới
	# 	- Cập nhật
	#	- Xóa
	*/
	Route::group(['prefix'=>'doitac'], function(){

		Route::get('danhsach','AdminController@getDanhsachDT');

		Route::get('themmoi','AdminController@getThemmoiDT');
		Route::post('themmoi','AdminController@postThemmoiDT');

		Route::get('capnhat/{id}','AdminController@getCapnhatDT');
		Route::post('capnhat/{id}','AdminController@postCapnhatDT');

		Route::get('xoa/{id}','AdminController@getXoaDT');
	});

	/* Điều hướng quản lý "góp ý":
	#	- Danh sách
	#	- Xóa
	*/
	Route::group(['prefix'=>'gopy'],function(){

		Route::get('danhsach','AdminController@getDanhsachGY');

		Route::get('xoa/{id}','AdminController@getXoaGY');
	});

	/* Điều hướng quản lý "loại sự kiện":
	#	- Danh sách
	#	- Thêm mới
	# 	- Cập nhật
	#	- Xóa
	*/
	Route::group(['prefix'=>'loaisukien'],function(){

		Route::get('danhsach','AdminController@getDanhsachLSK');

		Route::get('themmoi','AdminController@getThemmoiLSK');
		Route::post('themmoi','AdminController@postThemmoiLSK');

		Route::get('capnhat/{id}','AdminController@getCapnhatLSK');
		Route::post('capnhat/{id}','AdminController@postCapnhatLSK');

		Route::get('xoa/{id}','AdminController@getXoaLSK');
	});

	/* Điều hướng quản lý "công việc":
	#	- Danh sách
	#	- Thêm mới
	# 	- Cập nhật
	#	- Xóa
	*/
	Route::group(['prefix'=>'congviec'],function(){

		Route::get('danhsach','AdminController@getDanhsachCV');

		Route::get('themmoi','AdminController@getThemmoiCV');
		Route::post('themmoi','AdminController@postThemmoiCV');

		Route::get('capnhat/{id}','AdminController@getCapnhatCV');
		Route::post('capnhat/{id}','AdminController@postCapnhatCV');

		Route::get('xoa/{id}','AdminController@getXoaCV');
	});

	/* Điều hướng quản lý "sự kiện":
	#	- Danh sách
	#	- Duyệt sự kiện
	#	- Tạo hợp đồng
	#	- Xuất hợp đồng
	#	- Báo cáo sự kiện
	#	- Quản lý hình ảnh
	#	- Xóa sự kiện
	#	- Thống kê sự kiện
	#	- Xuất báo cáo
	*/
	Route::group(['prefix'=>'sukien'],function(){

		Route::get('dssknohd','AdminController@getDSSKnoHD');
		Route::get('dsskyeshd','AdminController@getDSSKyesHD');

		Route::get('duyet/{id}','AdminController@getDuyetSK');
		Route::post('duyet/{id}','AdminController@postDuyetSK');

		Route::get('taohopdong/{id}','AdminController@getTaohopdongSK');
		Route::post('taohopdong/{id}','AdminController@postTaohopdongSK');

		Route::get('baocaomotsk/{id}','AdminController@getBaocaoMotSK');

		Route::get('xuathopdong/{id}','AdminController@getXuathopdongSK');

		Route::get('hinhanh/{id}','AdminController@getHinhanhSK');
		Route::post('hinhanh/{id}','AdminController@postHinhanhSK');
		Route::get('xoahinh/{mask}/{maha}','AdminController@getXoahinhSK');

		Route::get('xoa/{id}','AdminController@getXoaSK');

		Route::get('thongke','AdminController@getThongkeSK');
		Route::post('thongke','AdminController@postThongkeSK');

		Route::get('xuatbaocao/{tungay}/{denngay}','AdminController@getXuatbaocaoSK');
	});

	/* Điều hướng quản lý "nhà cung cấp":
	#	- Danh sách
	#	- Thêm mới
	# 	- Cập nhật
	#	- Xóa
	*/
	Route::group(['prefix'=>'nhacungcap'],function(){

		Route::get('danhsach','AdminController@getDanhsachNCC');

		Route::get('themmoi','AdminController@getThemmoiNCC');
		Route::post('themmoi','AdminController@postThemmoiNCC');

		Route::get('capnhat/{id}','AdminController@getCapnhatNCC');
		Route::post('capnhat/{id}','AdminController@postCapnhatNCC');

		Route::get('xoa/{id}','AdminController@getXoaNCC');
	});

	/* Điều hướng quản lý "dụng cụ":
	#	- Lập phiếu nhập
	#	- In phiếu nhập
	#	- Danh sách
	# 	- Cập nhật
	#	- Xóa
	#	- Danh sách yêu cầu
	#	- Duyệt (mượn, trả)
	*/
	Route::group(['prefix'=>'dungcu'],function(){

		Route::get('lapphieunhap','AdminController@getLapphieunhap');
		Route::post('lapphieunhap','AdminController@postLapphieunhap');

		Route::get('danhsachphieunhap','AdminController@getDanhsachPN');
		Route::get('xuatphieunhap/{param1}/{param2}/{param3}/{param4}','AdminController@getXuatphieunhap');

		Route::get('danhsach','AdminController@getDanhsachDC');

		Route::get('capnhat/{id}','AdminController@getCapnhatDC');
		Route::post('capnhat/{id}','AdminController@postCapnhatDC');

		Route::get('xoa/{id}','AdminController@getXoaDC');

		Route::get('danhsachyeucau','AdminController@getDanhsachYC');

		Route::get('duyetmuon/{id}','AdminController@getDuyetMuon');
		Route::post('duyetmuon/{id}','AdminController@postDuyetMuon');

		Route::get('duyettra/{id}','AdminController@getDuyetTra');
		Route::post('duyettra/{id}','AdminController@postDuyetTra');
	});

	/* Điều hướng quản lý "quyền":
	#	- Danh sách
	#	- Thêm mới
	#	- Phân quyền
	# 	- Cập nhật
	#	- Xóa
	*/
	Route::group(['prefix'=>'quyen'],function(){

		Route::get('danhsach','AdminController@getDanhsachQ');

		Route::get('themmoi','AdminController@getThemmoiQ');
		Route::post('themmoi','AdminController@postThemmoiQ');

		Route::get('phanquyen/{id}','AdminController@getPhanquyenQ');
		Route::post('phanquyen/{id}','AdminController@postPhanquyenQ');

		Route::get('capnhat/{id}','AdminController@getCapnhatQ');
		Route::post('capnhat/{id}','AdminController@postCapnhatQ');

		Route::get('xoa/{id}','AdminController@getXoaQ');
	});

	/* Điều hướng quản lý "chức năng":
	#	- Danh sách
	#	- Thêm mới
	# 	- Cập nhật
	#	- Xóa
	*/
	Route::group(['prefix'=>'chucnang'],function(){

		Route::get('danhsach','AdminController@getDanhsachCN');

		Route::get('themmoi','AdminController@getThemmoiCN');
		Route::post('themmoi','AdminController@postThemmoiCN');

		Route::get('capnhat/{id}','AdminController@getCapnhatCN');
		Route::post('capnhat/{id}','AdminController@postCapnhatCN');

		Route::get('xoa/{id}','AdminController@getXoaCN');
	});

	/* Điều hướng quản lý "biểu mẫu":
	#	- Danh sách
	#	- Thêm mới
	# 	- Cập nhật
	#	- Phục hồi
	#	- Xóa
	*/
	Route::group(['prefix'=>'bieumau'],function(){

		Route::get('danhsach','AdminController@getDanhsachBM');

		Route::get('themmoi','AdminController@getThemmoiBM');
		Route::post('themmoi','AdminController@postThemmoiBM');

		Route::get('capnhat/{id}','AdminController@getCapnhatBM');
		Route::post('capnhat/{id}','AdminController@postCapnhatBM');

		Route::get('phuchoi/{id}','AdminController@getPhuchoiBM');

		Route::get('xoa/{id}','AdminController@getXoaBM');
	});

	/* Điều hướng quản lý "thống kê thu chi":
	#	- Biểu đồ
	*/
	Route::group(['prefix'=>'thongkethuchi'],function(){

		Route::get('bieudo','AdminController@getBieudoTKTC');
		Route::post('bieudo','AdminController@postBieudoTKTC');

	});

});
