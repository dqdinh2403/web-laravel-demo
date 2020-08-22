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

/* Phần xử lý các chức năng cơ bản của website */
class PageController extends Controller
{

    public function test(){
        $taikhoan = User::find(Session::get('front_login_mataikhoan'));
        echo "<br>";
        echo($taikhoan);
        echo "<hr>";
        $taikhoan1 = User::where('tk_ma',Session::get('front_login_mataikhoan'))->get();
        echo "<br>";
        echo($taikhoan1);
        echo "<hr>";
        $taikhoan2 = User::where('tk_ma',Session::get('front_login_mataikhoan'))->first();
        echo "<br>";
        echo($taikhoan2);
        echo "<hr>";
        $taikhoan3 = User::all();
        echo "<br>";
        echo($taikhoan3);
        echo "<hr>";

        $test = array(1,array(2,3,4));
        array_push($test, array(5,6,7));

        $dungcu = DungCu::selectRaw('dc_ma, dc_ten, dc_mota, dc_trangthai')->get();

        $nhacungcap = array();
        // $count=0;
        foreach ($dungcu as $dc) {
            $data = DungCu::join('chitietphieunhap','dungcu.dc_ma','=','chitietphieunhap.dc_ma')
                ->where('dungcu.dc_ma',$dc->dc_ma)
                ->join('phieunhap','phieunhap.pn_maphieunhap','=','chitietphieunhap.pn_maphieunhap')
                ->join('nhacungcap','phieunhap.ncc_ma','=','nhacungcap.ncc_ma')
                ->selectRaw('nhacungcap.ncc_ten as ncc_ten, chitietphieunhap.ctpn_dongia as ctpn_dongia')
                ->get();
                array_push($nhacungcap, $data);
            // $nhacungcap[$count] = $data;
            // $count++;
        }
        var_export($nhacungcap);
        echo "<hr>";

        $date = "2018-10-09";
        echo 'abc/zax/'.date_format(date_create($date),'d').date_format(date_create($date),'m').date_format(date_create($date),'y');

        echo "<hr>";
        $sukien = SuKien::where('sk_trangthai',3)->get();
        echo sizeof($sukien);

        echo "<hr>";

        $sukien = KhachHang::join('hopdongtochucsukien','khachhang.kh_ma','hopdongtochucsukien.kh_ma')
            ->where('hopdongtochucsukien.hdtcsk_trangthai',0)
            ->join('sukien','hopdongtochucsukien.hdtcsk_sohopdong','sukien.hdtcsk_sohopdong')
            ->where('sukien.sk_trangthai',1)
            ->join('loaisukien','sukien.lsk_ma','loaisukien.lsk_ma')
            ->selectRaw('khachhang.kh_ma as kh_ma, khachhang.kh_tencongty as kh_tencongty, 
                hopdongtochucsukien.hdtcsk_sohopdong as hdtcsk_sohopdong, hopdongtochucsukien.hdtcsk_trangthai as hdtcsk_trangthai, 
                sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, sukien.sk_trangthai as sk_trangthai, 
                loaisukien.lsk_ma as lsk_ma, loaisukien.lsk_ten as lsk_ten')
            ->get();

        $sohopdong = array();
        foreach($sukien as $sk){
            array_push($sohopdong, $sk->hdtcsk_sohopdong);
        }

        var_dump($sohopdong);

        echo "<hr>abc";

         $sukien = SuKien::where('sk_trangthai',4)->get();
        echo count($sukien);

        echo "<hr>";
        $sohopdong = 'ET/HDTCSK/'.date('y').date('m')
            .date('d').'/'.date('H').date('i');
        echo $sohopdong;

        echo "<hr>";

        $sk_loaisukien = SuKien::find(1)->loaisukien;
        echo $sk_loaisukien;

        echo "<hr>";

        $data = SuKien::where('sukien.sk_ma',5)
            ->join('hopdongtochucsukien','sukien.hdtcsk_sohopdong','hopdongtochucsukien.hdtcsk_sohopdong')
            ->join('khachhang','hopdongtochucsukien.kh_ma','khachhang.kh_ma')
            ->selectRaw('khachhang.kh_tencongty as kh_tencongty, khachhang.kh_email as kh_email, 
                sukien.sk_ten as sk_ten')
            ->first();

        echo $data;

        echo "<hr>";

        echo date("Y-m-d");

        echo "<hr>";

         $data = SuKien::join('hopdongtochucsukien','sukien.hdtcsk_sohopdong','hopdongtochucsukien.hdtcsk_sohopdong')
            ->where('sukien.sk_ma',2)
            ->join('khachhang','hopdongtochucsukien.kh_ma','khachhang.kh_ma')
            ->selectRaw('khachhang.kh_tencongty as kh_tencongty, khachhang.kh_diachi as kh_diachi, khachhang.kh_dienthoai as kh_dienthoai, khachhang.kh_email as kh_email,
                hopdongtochucsukien.hdtcsk_sohopdong as hdtcsk_sohopdong, hopdongtochucsukien.hdtcsk_noidunghopdong as hdtcsk_noidunghopdong, 
                hopdongtochucsukien.hdtcsk_ngayxuathopdong as hdtcsk_ngayxuathopdong, hopdongtochucsukien.bm_ma as bm_ma')
            ->first();

        echo $data;

        echo "<hr> abc";

        $check = Sukien::find(1)->congviec->first();

        echo $check->pivot->sk_cv_nv_soluongnhanvien;

        echo "<hr>";

        $sukien = Sukien::join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->where('sukien_congviec_nhanvien.sk_ma',6)
            ->where('sukien_congviec_nhanvien.cv_ma',1)
            ->where('sukien_congviec_nhanvien.nv_ma',2)
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->selectRaw('sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, 
                congviec.cv_ma as cv_ma, congviec.cv_ten as cv_ten')
            ->get();

        echo $sukien;

        echo "<hr>";

        $phieunhap = PhieuNhap::join('nhacungcap','phieunhap.ncc_ma','nhacungcap.ncc_ma')
            ->where('phieunhap.pn_maphieunhap',"ET/PN/181020/2333")
            ->join('nhanvien','phieunhap.nv_lapphieu','nhanvien.nv_ma')
            ->selectRaw('nhacungcap.ncc_ten as ncc_ten, nhacungcap.ncc_dienthoai as ncc_dienthoai,
                nhanvien.nv_tennhanvien as nv_tennhanvien,
                phieunhap.pn_maphieunhap as pn_maphieunhap, phieunhap.pn_ngaynhap as pn_ngaynhap, 
                phieunhap.pn_ngayxuatphieu as pn_ngayxuatphieu, phieunhap.bm_ma as bm_ma')
            ->first();

        echo $phieunhap;
        
        echo "<hr>";

        $sudung = SuDung::where('sudung.sk_ma',7)
            ->where('sudung.sd_trangthai',0)
            ->get();

        echo $sudung;

        echo "<hr>";

        $info = SuDung::join('nhanvien','sudung.nv_muon','nhanvien.nv_ma')
            ->where('sudung.sk_ma',7)
            ->where('sudung.sd_trangthai',1)
            ->selectRaw('nhanvien.nv_tennhanvien as nv_tennhanvien, sudung.sd_ngaymuon as sd_ngaymuon')
            ->first();

        echo $info;

        echo "<hr>";

        $testbien = null;

        if($testbien=='')
            echo "ahihi";

        echo "<hr>";

        $chucnangcha = ChucNang::join('quyen_chucnang','chucnang.cn_ma','quyen_chucnang.cn_ma')
                    ->where('quyen_chucnang.q_ma',5)
                    ->where('chucnang.cn_trangthai',1)
                    ->selectRaw('chucnang.cn_ten as cn_ten, chucnang.cn_lienket as cn_lienket,
                        chucnang.cn_bieutuong as cn_bieutuong, chucnang.cn_vitri as cn_vitri, 
                        chucnang.cn_cha as cn_cha')
                    ->orderBy('chucnang.cn_vitri','asc')
                    ->get();

        $chucnangcon = ChucNang::whereNotNull('cn_cha')
            ->where('cn_trangthai',1)
            ->orderBy('chucnang.cn_vitri','asc')
            ->get();

        foreach(Session::get('back_chucnangcha') as $cnc){
            echo $cnc;
            echo "<br>";
        }


        return view('page.test');
    }



	/* Xử lý đăng nhập: create session
    #   - Vào: $request form
    #   - Ra: session 'loi'
    #   - View: page-noidungindex / page-quenmatkhau
    */
    public function postDangnhap(Request $request){
        $tendangnhap = $request->txtTenDangNhap1;
        $matkhau = md5($request->txtMatKhau);
        $taikhoan = User::where('tk_tendangnhap',$tendangnhap)->where('tk_matkhau',$matkhau)->where('tk_trangthai',1)->first();
 
        if(!empty($taikhoan)){
        	Session::put('front_login_mataikhoan',$taikhoan->tk_ma);
            Session::put('front_login_tendangnhap',$taikhoan->tk_tendangnhap);
            Session::put('front_login_quyen',$taikhoan->q_ma);

            return redirect('trangchu');
        }
        else{
            return redirect('quenmatkhau')->with('loi','Sai tài khoản hoặc mật khẩu, đăng nhập thất bại !');
        }
    }

    /* Đăng xuất: destroy session
    #   - Vào:
    #   - Ra:
    #   - View: page-noidungindex
    */
    public function getDangxuat(){
        Session::forget('front_login_mataikhoan');
        Session::forget('front_login_tendangnhap');
        Session::forget('front_login_quyen');

        return redirect('trangchu');
    }

    /* Hiển thị form quên mật khẩu:
    #   - Vào:
    #   - Ra:
    #   - View: page-quenmatkhau
    */
    public function getQuenmatkhau(){
        return view('page.quenmatkhau');
    }

    /* Xử lý form quên mật khẩu: send email
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao', 'loi'
    #   - View: page-quenmatkhau
    */
    public function postQuenmatkhau(Request $request){
        $this->validate($request,
            [
                'txtTenDangNhap'=>'required|regex:/^\S*$/u',
                'txtEmail'=>'required|email'
            ],
            [
                'txtTenDangNhap.required'=>'Chưa nhập tên người dùng !',
                'txtTenDangNhap.regex'=>'Tên người dùng không được có khoảng trắng !',
                'txtEmail.required'=>'Chưa nhập email !',
                'txtEmail.email'=>'Email không đúng định dạng !'
            ]
        );

        $khachhang = User::join('khachhang','users.tk_ma','khachhang.tk_ma')
                ->where('tk_tendangnhap',$request->txtTenDangNhap)
                ->where('kh_email',$request->txtEmail)
                ->selectRaw('users.tk_ma as tk_ma, khachhang.kh_tencongty as kh_tencongty')
                ->first();

        $nhanvien = User::join('nhanvien','users.tk_ma','nhanvien.tk_ma')
                ->where('tk_tendangnhap',$request->txtTenDangNhap)
                ->where('nv_email',$request->txtEmail)
                ->selectRaw('users.tk_ma as tk_ma, nhanvien.nv_tennhanvien as nv_tennhanvien')
                ->first();

        if(empty($khachhang) && empty($nhanvien)){
            return redirect('quenmatkhau')->with('loi','Tài khoản hoặc email không tồn tại !');
        }
        elseif(!empty($khachhang)){
            $tendangnhap = $request->txtTenDangNhap;
            $email = $request->txtEmail;
            $tencongty = $khachhang->kh_tencongty;
            $newpass = "EventTechnologyVN".rand();

            $user = User::find($khachhang->tk_ma);
            $user->tk_matkhau = md5($newpass);
            $user->save();

            Mail::send('page.mailquenmatkhauKH',array('tencongty'=>$tencongty,'tendangnhap'=>$tendangnhap,'matkhau'=>$newpass), function($message) use($email){
                $message->to($email,'Guest')->subject('Mail lấy lại mật khẩu tài khoản EventTechnologyVN');
            });

            return redirect('quenmatkhau')->with('thongbao',"Mật khẩu mới đã được gửi đến email '$email' !");
        }
        else{
            $tendangnhap = $request->txtTenDangNhap;
            $email = $request->txtEmail;
            $tennhanvien = $nhanvien->nv_tennhanvien;
            $newpass = "EventTechnologyVN".rand();

            $user = User::find($nhanvien->tk_ma);
            $user->tk_matkhau = md5($newpass);
            $user->save();

            Mail::send('page.mailquenmatkhauNV',array('tennhanvien'=>$tennhanvien,'tendangnhap'=>$tendangnhap,'matkhau'=>$newpass), function($message) use($email){
                $message->to($email,'Staff')->subject('Mail lấy lại mật khẩu tài khoản EventTechnologyVN');
            });

            return redirect('quenmatkhau')->with('thongbao',"Mật khẩu mới đã được gửi đến email '$email' !");
        } 
    }

    /* Hiển thị form đăng ký (khách hàng):
    #   - Vào:
    #   - Ra:
    #   - View: page-dangky
    */
    public function getDangky(){
        return view('page.dangky');
    }

    /* Xử lý form đăng ký (khách hàng): send email
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao'
    #   - View: page-dangky
    */
    public function postDangky(Request $request){
        $this->validate($request,
            [
                'txtTenDangNhap'=>'required|unique:users,tk_tendangnhap|regex:/^\S*$/u',
                'txtMatKhau1'=>'required|min:5',
                'txtMatKhau2'=>'required|same:txtMatKhau1',
                'txtTenCongTy'=>'required|unique:khachhang,kh_tencongty',
                'txtNguoiDaiDien'=>'required',
                'txtEmail'=>'required|email|unique:khachhang,kh_email',
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric'
            ],
            [
                'txtTenDangNhap.required'=>'Chưa nhập tên người dùng !',
                'txtTenDangNhap.unique'=>'Tên người dùng đã tồn tại !',
                'txtTenDangNhap.regex'=>'Tên người dùng không được có khoảng trắng !',
                'txtMatKhau1.required'=>'Chưa nhập mật khẩu !',
                'txtMatKhau1.min'=>'Mật khẩu phải lớn hơn 5 kí tự !',
                'txtMatKhau2.required'=>'Chưa xác nhận lại mật khẩu !',
                'txtMatKhau2.same'=>'Hai mật khẩu không giống nhau !',
                'txtTenCongTy.required'=>'Chưa nhập tên công ty !',
                'txtTenCongTy.unique'=>'Tên công ty đã tồn tại !',
                'txtNguoiDaiDien.required'=>'Chưa nhập tên người đại diện !',
                'txtEmail.required'=>'Chưa nhập email !',
                'txtEmail.email'=>'Email không đúng định dạng !',
                'txtEmail.unique'=>'Email đã tồn tại !',
                'txtDiaChi.required'=>'Chưa nhập địa chỉ !',
                'txtDienThoai.required'=>'Chưa nhập số điện thoại !',
                'txtDienThoai.numeric'=>'Số điện thoại chỉ bao gồm kí tự số !'
            ]
        );

        $randomcode = md5(rand());
        $tendangnhap = $request->txtTenDangNhap;
        $matkhau = $request->txtMatKhau1;

        $taikhoan = new User;
        $taikhoan->tk_tendangnhap = $request->txtTenDangNhap;
        $taikhoan->tk_matkhau = md5($request->txtMatKhau1);
        $taikhoan->tk_makichhoat = $randomcode;
        $taikhoan->tk_trangthai = 0;
        $taikhoan->q_ma = 2;
        $taikhoan->save();

        $ma = User::where('tk_tendangnhap',$request->txtTenDangNhap)->select('tk_ma')->get()->toArray();

        $tencongty = $request->txtTenCongTy;
        $email = $request->txtEmail;
        $mataikhoan = $ma[0]['tk_ma'];

        $khachhang = new KhachHang;
        $khachhang->kh_tencongty = $request->txtTenCongTy;
        $khachhang->kh_nguoidaidien = $request->txtNguoiDaiDien;
        $khachhang->kh_email = $request->txtEmail;
        $khachhang->kh_diachi = $request->txtDiaChi;
        $khachhang->kh_dienthoai = $request->txtDienThoai;
        $khachhang->tk_ma = $ma[0]['tk_ma'];
        $khachhang->save();
    
		Mail::send('page.maildangky',array('tencongty'=>$tencongty,'tendangnhap'=>$tendangnhap,'matkhau'=>$matkhau,'mataikhoan'=>$mataikhoan,'randomcode'=>$randomcode), function($message) use($email){
			$message->to($email,'Guest')->subject('Mail kích hoạt tài khoản EventTechnologyVN');
		});

        return redirect('dangky')->with('thongbao',"Đăng ký thành công, vui lòng kiểm tra hộp thư đến email '$email' !");
    }

    /* Hiển thị kết quả kích hoạt tài khoản:
    #   - Vào: $id, $code
    #   - Ra: session 'loi', 'thongbao'
    #   - View: page-kichhoat
    */
    public function kichhoat($id, $code){
        $taikhoan = User::where('tk_ma',$id)->where('tk_makichhoat',$code)->first();

        if(empty($taikhoan)){
            return view('page.kichhoat',['loi'=>'URL kích hoạt tài khoản không chính xác !']);
        }
        else{
            $taikhoan->tk_trangthai = 1;
            $taikhoan->save();
            return view('page.kichhoat',['thongbao'=>'Kích hoạt tài khoản thành công !']);
        }
    }

	/* Hiển thị kết quả tìm kiếm:
    #   - Vào: $tukhoa
    #   - Ra: object 'sukien', array 'anh', $tukhoa, $count
    #   - View: page-timkiem
    */
    public function timkiem($tukhoa){
        $tukhoa = trim($tukhoa);
        $sukien = SuKien::where('sk_hienthitrangchu','=',1)->where('sk_trangthai',4)->where(function($query) use($tukhoa){
                    $query->where('sk_ten','like',"%$tukhoa%")
                        ->orWhere('sk_diadiem','like',"%$tukhoa%")
                        ->orWhere('sk_noidungsukien','like',"%$tukhoa%");
                })->get();
        $anh = [];   
        $count = 0;
        foreach($sukien as $sk){
            $hinhdaidien = HinhAnh::where('sk_ma',$sk->sk_ma)->select('ha_tentaptin')->first()->toArray();
            foreach ($hinhdaidien as $key => $value) {
                $anh[$count] = $value;                
            }
            $count++;
        }
        return view('page.timkiem',['sukien'=>$sukien,'anh'=>$anh,'tukhoa'=>$tukhoa,'count'=>$count]);
    }
    
    /* Hiển thị thông tin trang chủ:
    #   - Vào:
    #   - Ra: object 'sukien', array 'anh'
    #   - View: page-noidungindex
    */
    public function trangchu(){
    	$sukien = SuKien::where('sk_hienthitrangchu',1)->where('sk_trangthai',4)->get();
        $anh = [];   
        $count = 0;
        foreach($sukien as $sk){
            $hinhdaidien = HinhAnh::where('sk_ma',$sk->sk_ma)->select('ha_tentaptin')->first();
            $anh[$count] = $hinhdaidien->ha_tentaptin;
            $count++;
        }
    
    	return view('page.noidungindex',['sukien'=>$sukien,'anh'=>$anh]);
    }

    /* Hiển thị nội dung chi tiết của 1 sự kiện:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'hinhanh', 'loaisukien'
    #   - View: page-chitietsukien
    */
    public function chitietsukien($id){
        $sukien = SuKien::find($id);
        $hinhanh = HinhAnh::where('sk_ma',$id)->get();
        $loaisukien = SuKien::find($id)->loaisukien;

        return view('page.chitietsukien',['sukien'=>$sukien,'hinhanh'=>$hinhanh,'loaisukien'=>$loaisukien]);
    }

    /* Hiển thị giới thiệu về công ty:
    #   - Vào:
    #   - Ra:
    #   - View: page-gioithieu
    */
    public function gioithieu(){
        return view('page.gioithieu');
    }

    /* Hiển thị danh sách loại sự kiện:
    #   - Vào:
    #   - Ra: object 'loaisukien'
    #   - View: page-sukien
    */
    public function sukien(){
        $loaisukien = LoaiSuKien::where('lsk_trangthai',1)->get();

        return view('page.sukien',['loaisukien'=>$loaisukien]);
    }

    /* Hiển thị danh sách dụng cụ:
    #   - Vào:
    #   - Ra: object 'dungcu', array 'nhacungcap'
    #   - View: page-dungcu
    */
    public function dungcu(){
        $dungcu = DungCu::selectRaw('dc_ma, dc_ten, dc_trangthai')->get();

        $nhacungcap = array();
        foreach ($dungcu as $dc) {
            $data = DungCu::join('chitietphieunhap','dungcu.dc_ma','=','chitietphieunhap.dc_ma')
                ->where('dungcu.dc_ma',$dc->dc_ma)
                ->join('phieunhap','phieunhap.pn_maphieunhap','=','chitietphieunhap.pn_maphieunhap')
                ->join('nhacungcap','phieunhap.ncc_ma','=','nhacungcap.ncc_ma')
                ->selectRaw('nhacungcap.ncc_ten as ncc_ten, chitietphieunhap.ctpn_dongia as ctpn_dongia')
                ->get();
            array_push($nhacungcap,$data);
        }

        return view('page.dungcu',['dungcu'=>$dungcu, 'nhacungcap'=>$nhacungcap]);
    }

    /* Hiển thị danh sách album ảnh sự kiện:
    #   - Vào:
    #   - Ra: object 'sukien', array 'anh'
    #   - View: page-album_anh
    */
    public function albumanh(){
        $sukien = SuKien::where('sk_hienthitrangchu',1)->where('sk_trangthai',4)->get();
        $anh = [];   
        $count = 0;
        foreach($sukien as $sk){
            $hinhdaidien = HinhAnh::where('sk_ma',$sk->sk_ma)->select('ha_tentaptin')->first();
            $anh[$count] = $hinhdaidien->ha_tentaptin;
            $count++;
        }
    
        return view('page.album_anh',['sukien'=>$sukien,'anh'=>$anh]);
    }

    /* Hiển thị danh sách hình ảnh của 1 sự kiện:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'hinhanh'
    #   - View: page-danhsachhinhanh
    */
    public function danhsachhinhanh($id){
        $sukien = SuKien::find($id);
        $hinhanh = HinhAnh::where('sk_ma',$id)->get();

        return view('page.danhsachhinhanh',['sukien'=>$sukien,'hinhanh'=>$hinhanh]);
    }

    /* Hiển thị thông tin liên hệ:
    #   - Vào:
    #   - Ra:
    #   - View: page-lienhe
    */
    public function lienhe(){
        return view('page.lienhe');
    }

    /* Hiển thị form góp ý:
    #   - Vào:
    #   - Ra:
    #   - View: page-gopy
    */
    public function getGopy(){
        return view('page.gopy');
    }

    /* Xử lý form góp ý:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao'
    #   - View: page-gopy
    */
    public function postGopy(Request $request){
        $this->validate($request,
            [
                'txtTieude'=>'required',
                'txtNoidung'=>'required'
            ],
            [
                'txtTieude.required'=>'Bạn chưa nhập tiêu đề !',
                'txtNoidung.required'=>'Bạn chưa nhập nội dung !'
            ]
        );

        $gopy = new GopY;
        $gopy->gy_tieude = $request->txtTieude;
        $gopy->gy_noidung = $request->txtNoidung;
        $gopy->gy_trangthai = 1;

        if(Session::has('front_login_mataikhoan')){
        	$gopy->tk_ma = Session::get('front_login_mataikhoan');
        }
        
        $gopy->save();

        return redirect('gopy')->with('thongbao','Gửi góp ý thành công !');
    }

}
