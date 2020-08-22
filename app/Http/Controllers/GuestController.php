<?php

namespace App\Http\Controllers;

use Session;
use Mail;
use Illuminate\Http\Request;

use App\BieuMau;
use App\ChiTietPhieuNhap;
use App\ChucNang;
use App\CongViec;
use App\DoiTac;
use App\DungCu;
use App\GopY;
use App\HinhAnh;
use App\HopDongToChucSuKien;
use App\KhachHang;
use App\LoaiSuKien;
use App\NhaCungCap;
use App\NhanVien;
use App\PhieuNhap;
use App\Quyen;
use App\Quyen_Chucnang;
use App\SuDung;
use App\SuKien;
use App\Sukien_Congviec_Nhanvien;
use App\Sukien_Doitac;
use App\User;

/* Phần xử lý chức năng của khách hàng */
class GuestController extends Controller
{
    
    /* Hiển thị form cập nhật thông tin cá nhân:
    #   - Vào:
    #   - Ra: object 'khachhang'
    #   - View: guest-ttkhachhang
    */
    public function getThongtinKH(){
        $khachhang = User::join('khachhang','users.tk_ma','khachhang.tk_ma')->where('users.tk_ma',Session::get('front_login_mataikhoan'))
            ->selectRaw('khachhang.kh_ma as kh_ma, khachhang.kh_tencongty as kh_tencongty, khachhang.kh_nguoidaidien as kh_nguoidaidien, 
            	khachhang.kh_diachi as kh_diachi, khachhang.kh_dienthoai as kh_dienthoai, khachhang.kh_email as kh_email, 
            	users.tk_ma as tk_ma, users.tk_tendangnhap as tk_tendangnhap')->first();
        
        return view('guest.ttkhachhang',['khachhang'=>$khachhang]);
    }

    /* Xử lý form cập nhật thông tin cá nhân:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao', 'loi'
    #   - View: guest-ttkhachhang
    */
    public function postThongtinKH(Request $request){
        $this->validate($request,
            [                            
                'txtTenCongTy'=>'required|unique:khachhang,kh_tencongty,'.$request->txtMaKhachHang.',kh_ma',
                'txtNguoiDaiDien'=>'required',                
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric'
            ],
            [                         
                'txtTenCongTy.required'=>'Chưa nhập tên công ty !',
                'txtTenCongTy.unique'=>'Tên công ty đã tồn tại !',
                'txtNguoiDaiDien.required'=>'Chưa nhập tên người đại diện !',                
                'txtDiaChi.required'=>'Chưa nhập địa chỉ !',
                'txtDienThoai.required'=>'Chưa nhập số điện thoại !',
                'txtDienThoai.numeric'=>'Số điện thoại chỉ bao gồm kí tự số !'
            ]
        );
            
        if($request->changePassword == "on"){
            $this->validate($request,
                [
                	'txtMatKhauOld'=>'required',
                    'txtMatKhau1'=>'required|min:5',
                    'txtMatKhau2'=>'required|same:txtMatKhau1'
                ],
                [
                	'txtMatKhauOld.required'=>'Chưa nhập mật khẩu cũ !',
                    'txtMatKhau1.required'=>'Chưa nhập mật khẩu mới !',
                    'txtMatKhau1.min'=>'Mật khẩu phải dài hơn 5 kí tự !',
                    'txtMatKhau2.required'=>'Chưa xác nhận lại mật khẩu !',
                    'txtMatKhau2.same'=>'Hai mật khẩu không giống nhau !'
                ]
            );

            $taikhoan = User::find(Session::get('front_login_mataikhoan'));
            if($taikhoan->tk_matkhau == md5($request->txtMatKhauOld)){
            	$taikhoan->tk_matkhau = md5($request->txtMatKhau1);
            	$taikhoan->save();
            }
            else{
            	return redirect('guest/ttkhachhang')->with('loi','Mật khẩu cũ không chính xác !');
            }
        }
        
        $khachhang = KhachHang::find($request->txtMaKhachHang);
        $khachhang->kh_tencongty = $request->txtTenCongTy;
        $khachhang->kh_nguoidaidien = $request->txtNguoiDaiDien;
        $khachhang->kh_diachi = $request->txtDiaChi;
        $khachhang->kh_dienthoai = $request->txtDienThoai;
        $khachhang->save();

        return redirect('guest/ttkhachhang')->with('thongbao','Cập nhật thông tin cá nhân thành công !');
    }

    /* Hiển thị form đăng ký sự kiện:
    #   - Vào: $id
    #   - Ra: object 'loaisukien', 'khachhang', session 'loi'
    #   - View: guest-dangkysukien / page-sukien
    */
    public function getDangkysukien($id){
        if(Session::has('front_login_quyen') && Session::get('front_login_quyen')==2){
            $loaisukien = LoaiSuKien::find($id);
            $khachhang = KhachHang::where('tk_ma',Session::get('front_login_mataikhoan'))->first();
            
            return view('guest.dangkysukien',['loaisukien'=>$loaisukien,'khachhang'=>$khachhang]);
        }
        else
            return redirect('sukien')->with('loi','Để đăng kí sự kiện, vui lòng đăng nhập với tư cách là khách hàng !');
    }

    /* Xử lý form đăng ký sự kiện:
    #   - Vào: $request form, $id
    #   - Ra: $errors, session 'thongbao'
    #   - View: guest-dangkysukien
    */
    public function postDangkysukien(Request $request, $id){        
        $this->validate($request,
            [
                'txtTenSuKien'=>'required|unique:sukien,sk_ten',
                'txtDiaDiem'=>'required',
                'txtTime'=>'required',
                'txtDate'=>'required',
                'txtThoiLuong'=>'required',
                'txtNoiDungSuKien'=>'required'
            ],
            [
                'txtTenSuKien.required'=>'Chưa nhập tên sự kiện !',
                'txtTenSuKien.unique'=>'Tên sự kiện đã tồn tại !',
                'txtDiaDiem.required'=>'Chưa nhập địa điểm !',
                'txtTime.required'=>'Chưa nhập giờ tổ chức !',
                'txtDate.required'=>'Chưa nhập ngày tổ chức !',
                'txtThoiLuong.required'=>'Chưa nhập thời lượng !',
                'txtNoiDungSuKien.required'=>'Chưa nhập nội dung sự kiện !'
            ]
        );

        $sohopdong = 'ET/HDTCSK/'.date('y').date('m')
            .date('d').'/'.date('H').date('i');

        $hd = new HopDongToChucSuKien;
        $hd->hdtcsk_sohopdong = $sohopdong;
        $hd->hdtcsk_giatrihopdong = 0;
        $hd->hdtcsk_sotientamung = null;
        $hd->hdtcsk_thanhtoan = 0;
        $hd->hdtcsk_noidunghopdong = '';
        $hd->hdtcsk_ngaytaohopdong = '2000-01-01';
        $hd->nv_taohopdong = 1;
        $hd->hdtcsk_ngayxuathopdong = null;
        $hd->nv_chiutrachnhiem = 1;
        $hd->hdtcsk_trangthai = 1;
        $hd->kh_ma = $request->txtMaKhachHang;
        $hd->bm_ma = 1;
        $hd->save();

        $sukien = new SuKien;
        $sukien->sk_ten = $request->txtTenSuKien;
        $sukien->sk_diadiem = $request->txtDiaDiem;
        $sukien->sk_toado = '';
        $sukien->sk_thoigianbatdaut = $request->txtTime;
        $sukien->sk_thoigianbatdaud = $request->txtDate;
        $sukien->sk_thoiluong = $request->txtThoiLuong;
        $sukien->sk_noidungsukien = $request->txtNoiDungSuKien;
        $sukien->sk_kinhphi = 0;
        $sukien->sk_hienthitrangchu = 0;
        $sukien->sk_trangthai = 1;
        $sukien->lsk_ma = $id;
        $sukien->hdtcsk_sohopdong = $sohopdong;
        $sukien->save();

        return redirect('guest/dangkysukien/'.$id)->with('thongbao','Đăng ký sự kiện thành công, vui lòng đợi nhân viên công ty tiếp nhận và phản hồi qua email (hoặc xem chi tiết trong phần lịch sử giao dịch) !');
    }

    /* Hiển thị danh sách giao dịch:
    #   - Vào:
    #   - Ra: object 'sukien', 'hopdong'
    #   - View: guest-giaodich
    */
    public function getGiaodich(){
        $khachhang = KhachHang::where('tk_ma',Session::get('front_login_mataikhoan'))->first();

        $sukien = KhachHang::join('hopdongtochucsukien','khachhang.kh_ma','hopdongtochucsukien.kh_ma')
            ->where('khachhang.kh_ma',$khachhang->kh_ma)
            ->where('hopdongtochucsukien.hdtcsk_trangthai',1)
            ->join('sukien','hopdongtochucsukien.hdtcsk_sohopdong','sukien.hdtcsk_sohopdong')
            ->whereIn('sukien.sk_trangthai',[1,2,3])
            ->join('loaisukien','sukien.lsk_ma','loaisukien.lsk_ma')
            ->selectRaw('sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, sukien.sk_trangthai as sk_trangthai, 
                loaisukien.lsk_ma as lsk_ma, loaisukien.lsk_ten as lsk_ten')
            ->orderBy('hopdongtochucsukien.hdtcsk_sohopdong','asc')
            ->get();

        $hopdong = KhachHang::join('hopdongtochucsukien','khachhang.kh_ma','hopdongtochucsukien.kh_ma')
            ->where('khachhang.kh_ma',$khachhang->kh_ma)
            ->where('hopdongtochucsukien.hdtcsk_trangthai',2)
            ->join('sukien','hopdongtochucsukien.hdtcsk_sohopdong','sukien.hdtcsk_sohopdong')
            ->where('sukien.sk_trangthai',4)
            ->join('loaisukien','sukien.lsk_ma','loaisukien.lsk_ma')
            ->selectRaw('hopdongtochucsukien.hdtcsk_sohopdong as hdtcsk_sohopdong, hopdongtochucsukien.hdtcsk_thanhtoan as hdtcsk_thanhtoan,
                sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, sukien.sk_thoigianbatdaud as sk_thoigianbatdaud')
            ->orderBy('hopdongtochucsukien.hdtcsk_sohopdong','asc')
            ->get();

        return view('guest.giaodich',['sukien'=>$sukien, 'hopdong'=>$hopdong]);
    }

    /* Hiển thị form cập nhật sự kiện:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'loaisukien', 'sk_doitac', 'sk_congivec', 'sk_dungcu'
    #   - View: guest-capnhatsukien
    */
    public function getCapnhatsukien($id){
        $sukien = SuKien::find($id);
        $loaisukien = SuKien::find($id)->loaisukien;

        $sk_doitac = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_doitac','sukien.sk_ma','sukien_doitac.sk_ma')
            ->join('doitac','sukien_doitac.dt_ma','doitac.dt_ma')
            ->where('doitac.dt_trangthai',1)
            ->selectRaw('doitac.dt_ma as dt_ma, doitac.dt_tencongty as dt_tencongty')
            ->get();
        $sk_congviec = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->where('congviec.cv_trangthai',1)
            ->selectRaw('congviec.cv_ma as cv_ma ,congviec.cv_ten as cv_ten, 
                sukien_congviec_nhanvien.sk_cv_nv_soluongnhanvien as sk_cv_nv_soluongnhanvien')
            ->get();
        $sk_dungcu = SuKien::where('sukien.sk_ma',$id)
            ->join('sudung','sukien.sk_ma','sudung.sk_ma')
            ->join('dungcu','sudung.dc_ma','dungcu.dc_ma')
            ->where('dungcu.dc_trangthai',1)
            ->selectRaw('dungcu.dc_ma as dc_ma, dungcu.dc_ten as dc_ten, sudung.sd_soluongmuon as sd_soluongmuon')
            ->get();

        return view('guest.capnhatsukien',['sukien'=>$sukien,'loaisukien'=>$loaisukien,'sk_doitac'=>$sk_doitac,'sk_congviec'=>$sk_congviec,'sk_dungcu'=>$sk_dungcu]);
    }

    /* Xử lý form cập nhật sự kiện:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: guest-capnhatsukien
    */
    public function postCapnhatsukien(Request $request, $id){        
        $this->validate($request,
            [
                'txtTenSuKien'=>'required|unique:sukien,sk_ten,'.$id.',sk_ma',
                'txtDiaDiem'=>'required',
                'txtTime'=>'required',
                'txtDate'=>'required',
                'txtThoiLuong'=>'required',
                'txtNoiDungSuKien'=>'required'
            ],
            [
                'txtTenSuKien.required'=>'Chưa nhập tên sự kiện !',
                'txtTenSuKien.unique'=>'Tên sự kiện đã tồn tại !',
                'txtDiaDiem.required'=>'Chưa nhập địa điểm !',
                'txtTime.required'=>'Chưa nhập giờ tổ chức !',
                'txtDate.required'=>'Chưa nhập ngày tổ chức !',
                'txtThoiLuong.required'=>'Chưa nhập thời lượng !',
                'txtNoiDungSuKien.required'=>'Chưa nhập nội dung sự kiện !'
            ]
        );

        $sukien = SuKien::find($id);
        $sukien->sk_ten = $request->txtTenSuKien;
        $sukien->sk_diadiem = $request->txtDiaDiem;
        $sukien->sk_thoigianbatdaut = $request->txtTime;
        $sukien->sk_thoigianbatdaud = $request->txtDate;
        $sukien->sk_thoiluong = $request->txtThoiLuong;
        $sukien->sk_noidungsukien = $request->txtNoiDungSuKien;
        $sukien->sk_trangthai = 1;
        $sukien->save();

        return redirect('guest/capnhatsukien/'.$id)->with('thongbao','Cập nhật sự kiện thành công, vui lòng đợi nhân viên công ty tiếp nhận và phản hồi qua email (hoặc xem chi tiết trong phần lịch sử giao dịch) !');
    }

    /* Hiển thị form xác nhận sự kiện:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'loaisukien', 'sk_doitac', 'sk_congviec', 'sk_dungcu'
    #   - View: guest-xacnhansukien
    */
    public function getXacnhansukien($id){
        $sukien = SuKien::find($id);
        $loaisukien = SuKien::find($id)->loaisukien;

        $sk_doitac = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_doitac','sukien.sk_ma','sukien_doitac.sk_ma')
            ->join('doitac','sukien_doitac.dt_ma','doitac.dt_ma')
            ->where('doitac.dt_trangthai',1)
            ->selectRaw('doitac.dt_ma as dt_ma, doitac.dt_tencongty as dt_tencongty')
            ->get();
        $sk_congviec = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->where('congviec.cv_trangthai',1)
            ->selectRaw('congviec.cv_ma as cv_ma ,congviec.cv_ten as cv_ten, 
                sukien_congviec_nhanvien.sk_cv_nv_soluongnhanvien as sk_cv_nv_soluongnhanvien')
            ->get();
        $sk_dungcu = SuKien::where('sukien.sk_ma',$id)
            ->join('sudung','sukien.sk_ma','sudung.sk_ma')
            ->join('dungcu','sudung.dc_ma','dungcu.dc_ma')
            ->where('dungcu.dc_trangthai',1)
            ->selectRaw('dungcu.dc_ma as dc_ma, dungcu.dc_ten as dc_ten, sudung.sd_soluongmuon as sd_soluongmuon')
            ->get();

        return view('guest.xacnhansukien',['sukien'=>$sukien,'loaisukien'=>$loaisukien,'sk_doitac'=>$sk_doitac,'sk_congviec'=>$sk_congviec,'sk_dungcu'=>$sk_dungcu]);
    }

    /* Xử lý form xác nhận sự kiện:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: guest-xacnhansukien
    */
    public function postXacnhansukien($id){  

        $sukien = SuKien::find($id);
        $sukien->sk_trangthai = 3;
        $sukien->save();

        return redirect('guest/xacnhansukien/'.$id)->with('thongbao','Xác nhận sự kiện thành công, vui lòng đợi nhân viên công ty tiếp nhận và tiến hành tạo lập hợp đồng (hoặc xem chi tiết trong phần lịch sử giao dịch) !');
    }

    /* Hiển thị xem sự kiện:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'loaisukien', 'sk_doitac', 'sk_congviec', 'sk_dungcu'
    #   - View: guest-xemsukien
    */
    public function getXemsukien($id){
        $sukien = SuKien::find($id);
        $loaisukien = SuKien::find($id)->loaisukien;

        $sk_doitac = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_doitac','sukien.sk_ma','sukien_doitac.sk_ma')
            ->join('doitac','sukien_doitac.dt_ma','doitac.dt_ma')
            ->where('doitac.dt_trangthai',1)
            ->selectRaw('doitac.dt_ma as dt_ma, doitac.dt_tencongty as dt_tencongty')
            ->get();
        $sk_congviec = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->where('congviec.cv_trangthai',1)
            ->selectRaw('congviec.cv_ma as cv_ma ,congviec.cv_ten as cv_ten, 
                sukien_congviec_nhanvien.sk_cv_nv_soluongnhanvien as sk_cv_nv_soluongnhanvien')
            ->get();
        $sk_dungcu = SuKien::where('sukien.sk_ma',$id)
            ->join('sudung','sukien.sk_ma','sudung.sk_ma')
            ->join('dungcu','sudung.dc_ma','dungcu.dc_ma')
            ->where('dungcu.dc_trangthai',1)
            ->selectRaw('dungcu.dc_ma as dc_ma, dungcu.dc_ten as dc_ten, sudung.sd_soluongmuon as sd_soluongmuon')
            ->get();

        return view('guest.xemsukien',['sukien'=>$sukien,'loaisukien'=>$loaisukien,'sk_doitac'=>$sk_doitac,'sk_congviec'=>$sk_congviec,'sk_dungcu'=>$sk_dungcu]);
    }

    /* Xử lý xóa sự kiện:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: guest-giaodich
    */
    public function getXoasukien($id){  

        $sukien = SuKien::find($id);
        $sukien->sk_trangthai = 0;
        $sukien->save();

        $hopdong = SuKien::find($id)->hopdongtochucsukien;
        $hopdong->hdtcsk_trangthai = 0;
        $hopdong->save();

        return redirect('guest/giaodich')->with('thongbao','Xóa sự kiện thành công !');
    }

}
