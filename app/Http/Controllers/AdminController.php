<?php

namespace App\Http\Controllers;

use Session;
use Mail;
use Illuminate\Http\Request;

use DB;
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

/* Phần xử lý chức năng của quản lý */
class AdminController extends Controller
{
    
    /* Hiển thị form đăng nhập:
    #   - Vào:
    #   - Ra:
    #   - View: admin-login
    */
	public function getLogin(){
		return view('admin.login');
	}

	/* Xử lý đăng nhập: create session
    #   - Vào: $request form
    #   - Ra: session 'loi'
    #   - View: admin-dashboard / admin-login
    */
    public function postLogin(Request $request){
        $tendangnhap = $request->txtTenDangNhap1;
        $matkhau = md5($request->txtMatKhau);
        $taikhoan = User::where('tk_tendangnhap',$tendangnhap)->where('tk_matkhau',$matkhau)->where('tk_trangthai',1)->first();

        if(!empty($taikhoan)){
        	$check = NhanVien::where('tk_ma',$taikhoan->tk_ma)->first();
        	if(!empty($check)){
        		Session::put('back_login_mataikhoan',$taikhoan->tk_ma);
	            Session::put('back_login_tendangnhap',$taikhoan->tk_tendangnhap);
	            Session::put('back_login_quyen',$taikhoan->q_ma);

	            return redirect('admin/dashboard');
        	}
        	else{
        		return redirect('admin/login')->with('loi','Bạn không có quyền truy cập. Đăng nhập thất bại !');
        	}
        	
        }
        else{
            return redirect('admin/login')->with('loi','Sai tài khoản hoặc mật khẩu. Đăng nhập thất bại !');
        }
    }

    /* Đăng xuất: destroy session
    #   - Vào:
    #   - Ra:
    #   - View: admin-login
    */
    public function logout(){
        Session::forget('back_login_mataikhoan');
        Session::forget('back_login_tendangnhap');
        Session::forget('back_login_quyen');

        Session::forget('back_chucnangcha');
        Session::forget('back_chucnangcon');

        return redirect('admin/login');
    }

    /* Xử lý form quên mật khẩu: send email
    #   - Vào: $request form
    #   - Ra: session 'thongbao', 'loi'
    #   - View: admin-login
    */
    public function forgetPassword(Request $request){
    	$nhanvien = NhanVien::join('users','nhanvien.tk_ma','users.tk_ma')
    				->where('nv_email',$request->txtEmail)
    				->selectRaw('users.tk_ma as tk_ma, users.tk_tendangnhap as tk_tendangnhap, nhanvien.nv_tennhanvien as nv_tennhanvien')
    				->first();

        if(empty($nhanvien)){
            return redirect('admin/login')->with('loi','Email không tồn tại !');
        }
        else{
            $tendangnhap = $nhanvien->tk_tendangnhap;
            $email = $request->txtEmail;
            $tennhanvien = $nhanvien->nv_tennhanvien;
            $newpass = "EventTechnologyVN".rand();

            $user = User::find($nhanvien->tk_ma);
            $user->tk_matkhau = md5($newpass);
            $user->save();

            Mail::send('admin.mailquenmatkhauNV',array('tennhanvien'=>$tennhanvien,'tendangnhap'=>$tendangnhap,'matkhau'=>$newpass), function($message) use($email){
                $message->to($email,'Staff')->subject('Mail lấy lại mật khẩu tài khoản EventTechnologyVN');
            });

            return redirect('admin/login')->with('thongbao',"Mật khẩu mới đã được gửi đến email '$email' !");
        } 
    }

    /* Hiển thị form cập nhật thông tin cá nhân:
    #   - Vào:
    #   - Ra: object 'quanly'
    #   - View: admin-ttquanly
    */
    public function getThongtinQL(){
        $quanly = User::join('nhanvien','users.tk_ma','nhanvien.tk_ma')->where('users.tk_ma',Session::get('back_login_mataikhoan'))
            ->selectRaw('nhanvien.nv_ma as nv_ma, nhanvien.nv_tennhanvien as nv_tennhanvien, nhanvien.nv_gioitinh as nv_gioitinh, 
                nhanvien.nv_diachi as nv_diachi, nhanvien.nv_dienthoai as nv_dienthoai, nhanvien.nv_email as nv_email, 
                nhanvien.nv_ngaysinh as nv_ngaysinh, nhanvien.nv_cmnd as nv_cmnd, 
                users.tk_ma as tk_ma, users.tk_tendangnhap as tk_tendangnhap')->first();

        return view('admin.ttquanly',['quanly'=>$quanly]);
    }

    /* Xử lý form cập nhật thông tin cá nhân:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao', 'loi'
    #   - View: admin-ttquanly
    */
    public function postThongtinQL(Request $request){
        $this->validate($request,
            [                             
                'txtTenNhanVien'=>'required',               
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric',
                'txtNgaySinh'=>'required'
            ],
            [                        
                'txtTenNhanVien.required'=>'Chưa nhập tên nhân viên !',                
                'txtDiaChi.required'=>'Chưa nhập địa chỉ !',
                'txtDienThoai.required'=>'Chưa nhập số điện thoại !',
                'txtDienThoai.numeric'=>'Số điện thoại chỉ bao gồm kí tự số !',
                'txtNgaySinh.required'=>'Chưa nhập ngày sinh !'
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

            $taikhoan = User::find(Session::get('back_login_mataikhoan'));
            if($taikhoan->tk_matkhau == md5($request->txtMatKhauOld)){
                $taikhoan->tk_matkhau = md5($request->txtMatKhau1);
                $taikhoan->save();
            }
            else{
                return redirect('admin/ttquanly')->with('loi','Mật khẩu cũ không chính xác !');
            }
        }
        
        $nhanvien = NhanVien::find($request->txtMaNhanVien);
        $nhanvien->nv_tennhanvien = $request->txtTenNhanVien;
        $nhanvien->nv_gioitinh = $request->grpGioiTinh;
        $nhanvien->nv_diachi = $request->txtDiaChi;
        $nhanvien->nv_dienthoai = $request->txtDienThoai;        
        $nhanvien->nv_ngaysinh = $request->txtNgaySinh;
        $nhanvien->nv_cmnd = $request->txtCMND;
        $nhanvien->save();

        return redirect('admin/ttquanly')->with('thongbao','Cập nhật thông tin cá nhân thành công !');
    }

	/* Hiển thị trang chủ quản lý webiste: create session
    #   - Vào: 
    #   - Ra: object 'sukien', array 'thongtin'
    #   - View: admin-dashboard
    */
    public function dashboard(){

        $chucnangcha = ChucNang::join('quyen_chucnang','chucnang.cn_ma','quyen_chucnang.cn_ma')
            ->where('quyen_chucnang.q_ma',Session::get('back_login_quyen'))
            ->where('chucnang.cn_trangthai',1)
            ->selectRaw('chucnang.cn_ma as cn_ma, chucnang.cn_ten as cn_ten, 
                chucnang.cn_lienket as cn_lienket, chucnang.cn_bieutuong as cn_bieutuong, 
                chucnang.cn_vitri as cn_vitri, chucnang.cn_cha as cn_cha')
            ->orderBy('chucnang.cn_vitri','asc')
            ->get();

        $chucnangcon = ChucNang::whereNotNull('cn_cha')
            ->where('cn_trangthai',1)
            ->orderBy('chucnang.cn_vitri','asc')
            ->get();

        Session::put('back_chucnangcha',$chucnangcha);
        Session::put('back_chucnangcon',$chucnangcon);

        
        $sukien1 = SuKien::where('sk_trangthai',1)->get();
        if(sizeof($sukien1) > 0){
            Session::put('back_sukien1',sizeof($sukien1));
        }
        else{
            Session::forget('back_sukien1');
        }

        $sukien3 = SuKien::where('sk_trangthai',3)->get();
        if(sizeof($sukien3) > 0){
            Session::put('back_sukien3',sizeof($sukien3));
        }
        else{
            Session::forget('back_sukien3');
        }


        $sukien = SuKien::join('loaisukien','sukien.lsk_ma','loaisukien.lsk_ma')
            ->whereIn('sk_trangthai',[3,4])
            ->selectRaw('loaisukien.lsk_ten as lsk_ten, sukien.sk_ma as sk_ma,
                sukien.sk_ten as sk_ten, sukien.sk_diadiem as sk_diadiem,
                sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, sukien.sk_thoigianbatdaut as sk_thoigianbatdaut,
                sukien.sk_thoiluong as sk_thoiluong')
            ->orderBy('sukien.sk_thoigianbatdaud','desc')
            ->get();

        $thongtin = array();
        foreach ($sukien as $sk) {
            $data = SuKien::join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
                ->where('sukien.sk_ma',$sk->sk_ma)
                ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
                ->join('nhanvien','sukien_congviec_nhanvien.nv_ma','nhanvien.nv_ma')
                ->selectRaw('congviec.cv_ten as cv_ten, nhanvien.nv_tennhanvien as nv_tennhanvien')
                ->get();
            array_push($thongtin,$data);
        }
        
    	return view('admin.dashboard',['sukien'=>$sukien, 'thongtin'=>$thongtin]);
    }


    /*================================Nhân viên===========================================*/
    /* Hiển thị danh sách nhân viên:
    #   - Vào:
    #   - Ra: object 'nhanvien'
    #   - View: admin-nhanvien-danhsach
    */
    public function getDanhsachNV(){
        $nhanvien = User::join('nhanvien','users.tk_ma','nhanvien.tk_ma')->join('quyen','users.q_ma','quyen.q_ma')
            ->selectRaw('nhanvien.nv_ma as nv_ma, nhanvien.nv_tennhanvien as nv_tennhanvien, 
                users.tk_ma as tk_ma, users.tk_tendangnhap as tk_tendangnhap, users.tk_trangthai as tk_trangthai, 
                quyen.q_ma as q_ma, quyen.q_ten as q_ten')->get();

        return view('admin.nhanvien.danhsach',['nhanvien'=>$nhanvien]);
    }

    /* Hiển thị lịch sử tham gia sự kiện của nhân viên:
    #   - Vào: $id
    #   - Ra: object 'nhanvien', 'sukien'
    #   - View: admin-nhanvien-thamgia
    */
    public function getThamgiaNV($id){  
        $nhanvien = NhanVien::find($id);
        $sukien = Sukien_Congviec_Nhanvien::where('nv_ma',$id)
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->join('sukien','sukien_congviec_nhanvien.sk_ma','sukien.sk_ma')
            ->selectRaw('sukien_congviec_nhanvien.sk_cv_nv_soluongnhanvien as sk_cv_nv_soluongnhanvien, sukien_congviec_nhanvien.sk_cv_nv_trangthai, 
                congviec.cv_ma as cv_ma, congviec.cv_ten as cv_ten, 
                sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, sukien.sk_diadiem as sk_diadiem, sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, sukien.sk_thoigianbatdaut as sk_thoigianbatdaut')->get();

        return view('admin.nhanvien.thamgia',['nhanvien'=>$nhanvien, 'sukien'=>$sukien]);
    } 

    /* Hiển thị form thêm mới nhân viên:
    #   - Vào:
    #   - Ra: object 'quyen'
    #   - View: admin-nhanvien-themmoi
    */
    public function getThemmoiNV(){
        $quyen = Quyen::where('q_trangthai',1)->get();

        return view('admin.nhanvien.themmoi',['quyen'=>$quyen]);
    }

    /* Xử lý form thêm mới nhân viên:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao', 'loi'
    #   - View: admin-nhanvien-themmoi
    */
    public function postThemmoiNV(Request $request){
        $this->validate($request,
            [
                'txtTenDangNhap'=>'required|unique:users,tk_tendangnhap|regex:/^\S*$/u',
                'txtMatKhau1'=>'required|min:5',
                'txtMatKhau2'=>'required|same:txtMatKhau1',
                'txtTenNhanVien'=>'required',
                'txtEmail'=>'required|email|unique:nhanvien,nv_email',
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric',
                'txtNgaySinh'=>'required'
            ],
            [
                'txtTenDangNhap.required'=>'Chưa nhập tên người dùng !',
                'txtTenDangNhap.unique'=>'Tên người dùng đã tồn tại !',
                'txtTenDangNhap.regex'=>'Tên người dùng không được có khoảng trắng !',
                'txtMatKhau1.required'=>'Chưa nhập mật khẩu !',
                'txtMatKhau1.min'=>'Mật khẩu phải dài hơn 5 kí tự !',
                'txtMatKhau2.required'=>'Chưa xác nhận lại mật khẩu !',
                'txtMatKhau2.same'=>'Hai mật khẩu không giống nhau !',
                'txtTenNhanVien.required'=>'Chưa nhập tên nhân viên !',      
                'txtEmail.required'=>'Chưa nhập email !',
                'txtEmail.email'=>'Email không đúng định dạng !',
                'txtEmail.unique'=>'Email đã tồn tại !',
                'txtDiaChi.required'=>'Chưa nhập địa chỉ !',
                'txtDienThoai.required'=>'Chưa nhập số điện thoại !',
                'txtDienThoai.numeric'=>'Số điện thoại chỉ bao gồm kí tự số !',
                'txtNgaySinh.required'=>'Chưa nhập ngày sinh !'
            ]
        );

        if($request->slQuyen==1){
            return redirect('admin/nhanvien/themmoi')->with('loi',"Không thể thêm mới nhân viên với quyền 'Quản trị viên' !");
        }
        elseif($request->slQuyen==2){
            return redirect('admin/nhanvien/themmoi')->with('loi',"Không thể thêm mới nhân viên với quyền 'Khách hàng' !");
        }
        else{
            $taikhoan = new User;
            $taikhoan->tk_tendangnhap = $request->txtTenDangNhap;
            $taikhoan->tk_matkhau = md5($request->txtMatKhau1);
            $taikhoan->tk_trangthai = 1;
            $taikhoan->q_ma = $request->slQuyen;
            $taikhoan->save();

            $tk_ma = User::where('tk_tendangnhap',$request->txtTenDangNhap)->select('tk_ma')->get()->toArray();
            $nhanvien = new NhanVien;
            $nhanvien->nv_tennhanvien = $request->txtTenNhanVien;
            $nhanvien->nv_gioitinh = $request->grpGioiTinh;
            $nhanvien->nv_diachi = $request->txtDiaChi;
            $nhanvien->nv_dienthoai = $request->txtDienThoai;
            $nhanvien->nv_email = $request->txtEmail;
            $nhanvien->nv_ngaysinh = $request->txtNgaySinh;
            $nhanvien->nv_cmnd = $request->txtCMND;
            $nhanvien->tk_ma = $tk_ma[0]['tk_ma'];
            $nhanvien->save();

            return redirect('admin/nhanvien/themmoi')->with('thongbao','Thêm mới nhân viên thành công !');
        }
    }

    /* Hiển thị form cập nhật nhân viên:
    #   - Vào: $id
    #   - Ra: object 'quyen', 'nhanvien'
    #   - View: admin-nhanvien-capnhat
    */
    public function getCapnhatNV($id){
        $quyen = Quyen::where('q_trangthai',1)->get();
        $nhanvien = User::join('nhanvien','users.tk_ma','nhanvien.tk_ma')->where('users.tk_ma',$id)
            ->selectRaw('nhanvien.nv_ma as nv_ma, nhanvien.nv_tennhanvien as nv_tennhanvien, nhanvien.nv_gioitinh as nv_gioitinh, 
                nhanvien.nv_diachi as nv_diachi, nhanvien.nv_dienthoai as nv_dienthoai, nhanvien.nv_email as nv_email, 
                nhanvien.nv_ngaysinh as nv_ngaysinh, nhanvien.nv_cmnd as nv_cmnd, 
                users.tk_ma as tk_ma, users.tk_tendangnhap as tk_tendangnhap, users.tk_trangthai as tk_trangthai, users.q_ma as q_ma')->first();

        return view('admin.nhanvien.capnhat',['quyen'=>$quyen, 'nhanvien'=>$nhanvien]);
    }

    /* Xử lý form cập nhật nhân viên:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao', 'loi'
    #   - View: admin-nhanvien-capnhat
    */
    public function postCapnhatNV(Request $request, $id){
        $this->validate($request,
            [
                'txtTenDangNhap'=>'required|regex:/^\S*$/u|unique:users,tk_tendangnhap,'.$id.',tk_ma',             
                'txtTenNhanVien'=>'required',
                'txtEmail'=>'required|email|unique:nhanvien,nv_email,'.$request->txtMaNhanVien.',nv_ma',
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric',
                'txtNgaySinh'=>'required'
            ],
            [
                'txtTenDangNhap.required'=>'Chưa nhập tên người dùng !',
                'txtTenDangNhap.unique'=>'Tên người dùng đã tồn tại !',
                'txtTenDangNhap.regex'=>'Tên người dùng không được có khoảng trắng !',          
                'txtTenNhanVien.required'=>'Chưa nhập tên nhân viên !',
                'txtEmail.required'=>'Chưa nhập email !',
                'txtEmail.email'=>'Email không đúng định dạng !',
                'txtEmail.unique'=>'Email đã tồn tại !',
                'txtDiaChi.required'=>'Chưa nhập địa chỉ !',
                'txtDienThoai.required'=>'Chưa nhập số điện thoại !',
                'txtDienThoai.numeric'=>'Số điện thoại chỉ bao gồm kí tự số !',
                'txtNgaySinh.required'=>'Chưa nhập ngày sinh !'
            ]
        );

        if($request->slQuyen==1){
            return redirect('admin/nhanvien/capnhat/'.$id)->with('loi',"Không thể cập nhật nhân viên với quyền 'Quản trị viên' !");
        }
        elseif($request->slQuyen==2){
            return redirect('admin/nhanvien/capnhat/'.$id)->with('loi',"Không thể cập nhật nhân viên với quyền 'Khách hàng' !");
        }
        else{
            $taikhoan = User::find($id);
            $taikhoan->tk_tendangnhap = $request->txtTenDangNhap;
            $taikhoan->tk_trangthai = $request->grpTrangThai;
            $taikhoan->q_ma = $request->slQuyen;
            if($request->changePassword == "on"){
                $this->validate($request,
                    [
                        'txtMatKhau1'=>'required|min:5',
                        'txtMatKhau2'=>'required|same:txtMatKhau1'
                    ],
                    [
                        'txtMatKhau1.required'=>'Chưa nhập mật khẩu mới !',
                        'txtMatKhau1.min'=>'Mật khẩu phải dài hơn 5 kí tự !',
                        'txtMatKhau2.required'=>'Chưa xác nhận lại mật khẩu !',
                        'txtMatKhau2.same'=>'Hai mật khẩu không giống nhau !'
                    ]
                );
                $taikhoan->tk_matkhau = md5($request->txtMatKhau1);
            }
            $taikhoan->save();

            $nhanvien = NhanVien::find($request->txtMaNhanVien);
            $nhanvien->nv_tennhanvien = $request->txtTenNhanVien;
            $nhanvien->nv_gioitinh = $request->grpGioiTinh;
            $nhanvien->nv_diachi = $request->txtDiaChi;
            $nhanvien->nv_dienthoai = $request->txtDienThoai;
            $nhanvien->nv_email = $request->txtEmail;
            $nhanvien->nv_ngaysinh = $request->txtNgaySinh;
            $nhanvien->nv_cmnd = $request->txtCMND;
            $nhanvien->save();

            return redirect('admin/nhanvien/capnhat/'.$id)->with('thongbao','Cập nhật nhân viên thành công !');
        }
    }

    /* Xử lý xóa nhân viên:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-nhanvien-danhsach
    */
    public function getXoaNV($id){
        $taikhoan = User::find($id);
        $taikhoan->tk_trangthai = 0;
        $taikhoan->save();

        return redirect('admin/nhanvien/danhsach')->with('thongbao',"Tài khoản '$taikhoan->tk_tendangnhap' đã dừng hoạt động !");
    }


    /*================================Khách hàng===========================================*/
    /* Hiển thị danh sách khách hàng:
    #   - Vào:
    #   - Ra: object 'khachhang'
    #   - View: admin-khachhang-danhsach
    */
    public function getDanhsachKH(){
        $khachhang = User::join('khachhang','users.tk_ma','khachhang.tk_ma')
            ->selectRaw('khachhang.kh_ma as kh_ma, khachhang.kh_tencongty as kh_tencongty, 
                users.tk_ma as tk_ma, users.tk_tendangnhap as tk_tendangnhap, users.tk_trangthai as tk_trangthai')->get();

        return view('admin.khachhang.danhsach',['khachhang'=>$khachhang]); 
    }

    /* Hiển thị form cập nhật khách hàng:
    #   - Vào: $id
    #   - Ra: object 'khachhang'
    #   - View: admin-khachhang-capnhat
    */
    public function getCapnhatKH($id){
        $khachhang = User::join('khachhang','users.tk_ma','khachhang.tk_ma')->where('users.tk_ma',$id)
            ->selectRaw('khachhang.kh_ma as kh_ma, khachhang.kh_tencongty as kh_tencongty, khachhang.kh_nguoidaidien as kh_nguoidaidien, khachhang.kh_diachi as kh_diachi, khachhang.kh_dienthoai as kh_dienthoai, khachhang.kh_email as kh_email, 
                users.tk_ma as tk_ma, users.tk_tendangnhap as tk_tendangnhap, users.tk_trangthai as tk_trangthai')->first();
        
        return view('admin.khachhang.capnhat',['khachhang'=>$khachhang]);
    }

    /* Xử lý form cập nhật khách hàng:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-khachhang-capnhat
    */
    public function postcapnhatKH(Request $request, $id){   
        $this->validate($request,
            [
                'txtTenDangNhap'=>'required|regex:/^\S*$/u|unique:users,tk_tendangnhap,'.$id.',tk_ma',             
                'txtTenCongTy'=>'required|unique:khachhang,kh_tencongty,'.$request->txtMaKhachHang.',kh_ma',
                'txtNguoiDaiDien'=>'required',
                'txtEmail'=>'required|email|unique:khachhang,kh_email,'.$request->txtMaKhachHang.',kh_ma',
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric'
            ],
            [
                'txtTenDangNhap.required'=>'Chưa nhập tên người dùng !',
                'txtTenDangNhap.unique'=>'Tên người dùng đã tồn tại !',
                'txtTenDangNhap.regex'=>'Tên người dùng không được có khoảng trắng !',          
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

        $taikhoan = User::find($id);
        $taikhoan->tk_tendangnhap = $request->txtTenDangNhap;
        $taikhoan->tk_trangthai = $request->grpTrangThai;
        if($request->changePassword == "on"){
            $this->validate($request,
                [
                    'txtMatKhau1'=>'required|min:5',
                    'txtMatKhau2'=>'required|same:txtMatKhau1'
                ],
                [
                    'txtMatKhau1.required'=>'Chưa nhập mật khẩu mới !',
                    'txtMatKhau1.min'=>'Mật khẩu phải dài hơn 5 kí tự !',
                    'txtMatKhau2.required'=>'Chưa xác nhận lại mật khẩu !',
                    'txtMatKhau2.same'=>'Hai mật khẩu không giống nhau !'
                ]
            );
            $taikhoan->tk_matkhau = md5($request->txtMatKhau1);
        }
        $taikhoan->save();

        $khachhang = KhachHang::find($request->txtMaKhachHang);
        $khachhang->kh_tencongty = $request->txtTenCongTy;
        $khachhang->kh_nguoidaidien = $request->txtNguoiDaiDien;
        $khachhang->kh_diachi = $request->txtDiaChi;
        $khachhang->kh_dienthoai = $request->txtDienThoai;
        $khachhang->kh_email = $request->txtEmail;
        $khachhang->save();

        return redirect('admin/khachhang/capnhat/'.$id)->with('thongbao','Cập nhật khách hàng thành công !');
    }

    /* Xử lý xóa khách hàng:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-khachhang-danhsach
    */
    public function getXoaKH($id){
        $taikhoan = User::find($id);
        $taikhoan->tk_trangthai = 0;
        $taikhoan->save();

        return redirect('admin/khachhang/danhsach')->with('thongbao',"Tài khoản '$taikhoan->tk_tendangnhap' đã dừng hoạt động !");
    }


    /*================================Đối tác===========================================*/
    /* Hiển thị danh sách đối tác:
    #   - Vào:
    #   - Ra: object 'doitac'
    #   - View: admin-doitac-danhsach
    */
    public function getDanhsachDT(){
        $doitac = DoiTac::where('dt_trangthai',1)->get();

        return view('admin.doitac.danhsach',['doitac'=>$doitac]); 
    }

    /* Hiển thị form thêm mới đối tác:
    #   - Vào:
    #   - Ra:
    #   - View: admin-doitac-themmoi
    */
    public function getThemmoiDT(){

        return view('admin.doitac.themmoi');
    }

    /* Xử lý form thêm mới đối tác:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-doitac-themmoi
    */
    public function postThemmoiDT(Request $request){   
        $this->validate($request,
            [             
                'txtTenCongTy'=>'required|unique:doitac,dt_tencongty',
                'txtNguoiDaiDien'=>'required',
                'txtEmail'=>'required|email|unique:doitac,dt_email',
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric'
            ],
            [       
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

        $doitac = new DoiTac;
        $doitac->dt_tencongty = $request->txtTenCongTy;
        $doitac->dt_nguoidaidien = $request->txtNguoiDaiDien;
        $doitac->dt_diachi = $request->txtDiaChi;
        $doitac->dt_dienthoai = $request->txtDienThoai;
        $doitac->dt_email = $request->txtEmail;
        $doitac->dt_trangthai = 1;
        $doitac->save();

        return redirect('admin/doitac/themmoi')->with('thongbao','Thêm mới đối tác thành công !');
    }

    /* Hiển thị form cập nhật đối tác:
    #   - Vào: $id
    #   - Ra: object 'doitac'
    #   - View: admin-doitac-capnhat
    */
    public function getCapnhatDT($id){
        $doitac = DoiTac::find($id);

        return view('admin.doitac.capnhat',['doitac'=>$doitac]);
    }

    /* Xử lý form cập nhật đối tác:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-doitac-capnhat
    */
    public function postcapnhatDT(Request $request, $id){   
        $this->validate($request,
            [            
                'txtTenCongTy'=>'required|unique:doitac,dt_tencongty,'.$id.',dt_ma',
                'txtNguoiDaiDien'=>'required',
                'txtEmail'=>'required|email|unique:doitac,dt_email,'.$id.',dt_ma',
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric'
            ],
            [     
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

        $doitac = DoiTac::find($id);
        $doitac->dt_tencongty = $request->txtTenCongTy;
        $doitac->dt_nguoidaidien = $request->txtNguoiDaiDien;
        $doitac->dt_diachi = $request->txtDiaChi;
        $doitac->dt_dienthoai = $request->txtDienThoai;
        $doitac->dt_email = $request->txtEmail;
        $doitac->save();

        return redirect('admin/doitac/capnhat/'.$id)->with('thongbao','Cập nhật đối tác thành công !');
    }

    /* Xử lý xóa đối tác:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-doitac-danhsach
    */
    public function getXoaDT($id){
        $doitac = DoiTac::find($id);
        $doitac->dt_trangthai = 0;
        $doitac->save();

        return redirect('admin/doitac/danhsach')->with('thongbao',"Xóa đối tác '$doitac->dt_tencongty' thành công !");
    }

    /*================================Góp ý===========================================*/
    /* Hiển thị danh sách góp ý:
    #   - Vào:
    #   - Ra: object 'gopy'
    #   - View: admin-gopy-danhsach
    */
    public function getDanhsachGY(){
        $gopy = GopY::leftJoin('users','users.tk_ma','gopy.tk_ma')
            ->selectRaw('users.tk_tendangnhap as tk_tendangnhap, 
                gopy.gy_ma as gy_ma, gopy.gy_tieude as gy_tieude, gopy.gy_noidung as gy_noidung')->get();

        return view('admin.gopy.danhsach',['gopy'=>$gopy]);
    }

    /* Xử lý xóa góp ý:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-gopy-danhsach
    */
    public function getXoaGY($id){
        $gopy = GopY::find($id);
        $gopy->delete();

        return redirect('admin/gopy/danhsach')->with('thongbao','Xóa góp ý thành công !');
    }


    /*================================Loại dự kiện===========================================*/
    /* Hiển thị danh sách loại sự kiện:
    #   - Vào:
    #   - Ra: object 'loaisukien'
    #   - View: admin-loaisukien-danhsach
    */
    public function getDanhsachLSK(){
        $loaisukien = LoaiSuKien::where('lsk_trangthai',1)->get();

        return view('admin.loaisukien.danhsach',['loaisukien'=>$loaisukien]); 
    }

    /* Hiển thị form thêm mới loại sự kiện:
    #   - Vào:
    #   - Ra:
    #   - View: admin-loaisukien-themmoi
    */
    public function getThemmoiLSK(){
        return view('admin.loaisukien.themmoi');
    }

    /* Xử lý form thêm mới loại sự kiện:
    #   - Vào: $reuqest form
    #   - Ra: errors, sesion 'thongbao'
    #   - View: admin-loaisukien-themmoi
    */
    public function postThemmoiLSK(Request $request){
        $this->validate($request,
            [
                'txtTenLoaiSuKien'=>'required|unique:loaisukien,lsk_ten'
            ],
            [
                'txtTenLoaiSuKien.required'=>'Chưa nhập tên loại sự kiện !',
                'txtTenLoaiSuKien.unique'=>'Tên loại sự kiện đã tồn tại !'
            ]
        );

        $loaisukien = new LoaiSuKien;
        $loaisukien->lsk_ten = $request->txtTenLoaiSuKien;
        $loaisukien->lsk_mota = $request->txtMoTa;
        $loaisukien->lsk_trangthai = 1;
        $loaisukien->save();

        return redirect('admin/loaisukien/themmoi')->with('thongbao','Thêm mới loại sự kiện thành công !');
    }

    /* Hiển thị form cập nhật loại sự kiện:
    #   - Vào: $id
    #   - Ra: object 'loaisukien'
    #   - View: admin-loaisukien-capnhat
    */
    public function getCapnhatLSK($id){
        $loaisukien = LoaiSuKien::find($id);

        return view('admin.loaisukien.capnhat',['loaisukien'=>$loaisukien]);
    }

    /* Xử lý form cập nhật loại sự kiện:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-loaisukien-capnhat
    */
    public function postCapnhatLSK(Request $request, $id){
        $this->validate($request,
            [
                'txtTenLoaiSuKien'=>'required|unique:loaisukien,lsk_ten,'.$id.',lsk_ma'
            ],
            [
                'txtTenLoaiSuKien.required'=>'Chưa nhập tên loại sự kiện !',
                'txtTenLoaiSuKien.unique'=>'Tên loại sự kiện đã tồn tại !'
            ]
        );

        $loaisukien = LoaiSuKien::find($id);
        $loaisukien->lsk_ten = $request->txtTenLoaiSuKien;
        $loaisukien->lsk_mota = $request->txtMoTa;
        $loaisukien->save();

        return redirect('admin/loaisukien/capnhat/'.$id)->with('thongbao','Cập nhật loại sự kiện thành công !');
    }

    /* Xử lý xóa loại sự kiện:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-loaisukien-danhsach
    */
    public function getXoaLSK($id){
        $loaisukien = LoaiSuKien::find($id);
        $loaisukien->lsk_trangthai = 0;
        $loaisukien->save();

        return redirect('admin/loaisukien/danhsach')->with('thongbao',"Xóa loại sự kiện '$loaisukien->lsk_ten' thành công !");
    }


    /*================================Công việc===========================================*/
    /* Hiển thị danh sách công việc:
    #   - Vào:
    #   - Ra: object 'congviec'
    #   - View: admin-congviec-danhsach
    */
    public function getDanhsachCV(){
        $congviec = CongViec::where('cv_trangthai',1)->get();

        return view('admin.congviec.danhsach',['congviec'=>$congviec]); 
    }

    /* Hiển thị form thêm mới công việc:
    #   - Vào:
    #   - Ra:
    #   - View: admin-congviec-themmoi
    */
    public function getThemmoiCV(){
        return view('admin.congviec.themmoi');
    }

    /* Xử lý form thêm mới công việc:
    #   - Vào: $reuqest form
    #   - Ra: errors, sesion 'thongbao'
    #   - View: admin-congviec-themmoi
    */
    public function postThemmoiCV(Request $request){
        $this->validate($request,
            [
                'txtTenCongViec'=>'required|unique:congviec,cv_ten'
            ],
            [
                'txtTenCongViec.required'=>'Chưa nhập tên công việc !',
                'txtTenCongViec.unique'=>'Tên công việc đã tồn tại !'
            ]
        );

        $congviec = new CongViec;
        $congviec->cv_ten = $request->txtTenCongViec;
        $congviec->cv_mota = $request->txtMoTa;
        $congviec->cv_trangthai = 1;
        $congviec->save();

        return redirect('admin/congviec/themmoi')->with('thongbao','Thêm mới công việc thành công !');
    }

    /* Hiển thị form cập nhật công việc:
    #   - Vào: $id
    #   - Ra: object 'congviec'
    #   - View: admin-congviec-capnhat
    */
    public function getCapnhatCV($id){
        $congviec = CongViec::find($id);

        return view('admin.congviec.capnhat',['congviec'=>$congviec]);
    }

    /* Xử lý form cập nhật công việc:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-congviec-capnhat
    */
    public function postCapnhatCV(Request $request, $id){
        $this->validate($request,
            [
                'txtTenCongViec'=>'required|unique:congviec,cv_ten,'.$id.',cv_ma'
            ],
            [
                'txtTenCongViec.required'=>'Chưa nhập tên công việc !',
                'txtTenCongViec.unique'=>'Tên công việc đã tồn tại !'
            ]
        );

        $congviec = CongViec::find($id);
        $congviec->cv_ten = $request->txtTenCongViec;
        $congviec->cv_mota = $request->txtMoTa;
        $congviec->save();

        return redirect('admin/congviec/capnhat/'.$id)->with('thongbao','Cập nhật công việc thành công !');
    }

    /* Xử lý xóa công việc:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-congviec-danhsach
    */
    public function getXoaCV($id){
        $congviec = CongViec::find($id);
        $congviec->cv_trangthai = 0;
        $congviec->save();

        return redirect('admin/congviec/danhsach')->with('thongbao',"Xóa công việc '$congviec->cv_ten' thành công !");
    }


    /*================================Sự kiện===========================================*/
    /* Hiển thị danh sách sự kiện chưa có hợp đồng:
    #   - Vào:
    #   - Ra: object 'sukien'
    #   - View: admin-sukien-dssknohd
    */
    public function getDSSKnoHD(){
        $sukien = KhachHang::join('hopdongtochucsukien','khachhang.kh_ma','hopdongtochucsukien.kh_ma')
            ->where('hopdongtochucsukien.hdtcsk_trangthai',1)
            ->join('sukien','hopdongtochucsukien.hdtcsk_sohopdong','sukien.hdtcsk_sohopdong')
            ->whereIn('sukien.sk_trangthai',[1,2,3])
            ->join('loaisukien','sukien.lsk_ma','loaisukien.lsk_ma')
            ->selectRaw('khachhang.kh_ma as kh_ma, khachhang.kh_tencongty as kh_tencongty,
                sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, sukien.sk_trangthai as sk_trangthai, 
                loaisukien.lsk_ma as lsk_ma, loaisukien.lsk_ten as lsk_ten')
            ->orderBy('hopdongtochucsukien.hdtcsk_sohopdong','asc')
            ->get();

        return view('admin/sukien/dssknohd',['sukien'=>$sukien]);
    }

    /* Hiển thị danh sách sự kiện đã có hợp đồng:
    #   - Vào:
    #   - Ra: object 'sukien'
    #   - View: admin-sukien-dsskyeshd
    */
    public function getDSSKyesHD(){
        $sukien = KhachHang::join('hopdongtochucsukien','khachhang.kh_ma','hopdongtochucsukien.kh_ma')
            ->where('hopdongtochucsukien.hdtcsk_trangthai',2)
            ->join('sukien','hopdongtochucsukien.hdtcsk_sohopdong','sukien.hdtcsk_sohopdong')
            ->where('sukien.sk_trangthai',4)
            ->selectRaw('khachhang.kh_ma as kh_ma, khachhang.kh_tencongty as kh_tencongty,
                hopdongtochucsukien.hdtcsk_sohopdong as hdtcsk_sohopdong, hopdongtochucsukien.hdtcsk_thanhtoan as hdtcsk_thanhtoan, hopdongtochucsukien.hdtcsk_ngaytaohopdong as hdtcsk_ngaytaohopdong, hopdongtochucsukien.hdtcsk_ngayxuathopdong as hdtcsk_ngayxuathopdong,
                sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, sukien.sk_hienthitrangchu as sk_hienthitrangchu')
            ->orderBy('hopdongtochucsukien.hdtcsk_sohopdong','asc')
            ->get();

        return view('admin/sukien/dsskyeshd',['sukien'=>$sukien]);
    }

    /* Hiển thị form duyệt sự kiện:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'loaisukien', 'doitac', 'congviec', 'dungcu',
    #       'sk_loaisukien', 'sk_doitac', 'sk_congviec', 'sk_dungcu'
    #   - View: admin-sukien-duyet
    */
    public function getDuyetSK($id){
        $loaisukien = LoaiSuKien::where('lsk_trangthai',1)->get();
        $doitac = DoiTac::where('dt_trangthai',1)->get();
        $congviec = CongViec::where("cv_trangthai",1)->get();
        $dungcu = DungCu::where('dc_trangthai',1)->get();

        $sukien = SuKien::find($id);
        $sk_loaisukien = SuKien::find($id)->loaisukien;

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


        return view('admin.sukien.duyet',['sukien'=>$sukien, 'loaisukien'=>$loaisukien, 'doitac'=>$doitac, 'congviec'=>$congviec, 'dungcu'=>$dungcu, 
            'sk_loaisukien'=>$sk_loaisukien, 'sk_doitac'=>$sk_doitac, 'sk_congviec'=>$sk_congviec, 'sk_dungcu'=>$sk_dungcu]);
    }

    /* Xử lý form duyệt sự kiện: send mail
    #   - Vào: $request form, $id
    #   - Ra: $errors, session 'loi', 'thongbao'
    #   - View: admin-sukien-duyet
    */
    public function postDuyetSK(Request $request, $id){
        $this->validate($request,
            [
                'txtTenSuKien'=>'required|unique:sukien,sk_ten,'.$id.',sk_ma',
                'txtDiaDiem'=>'required',
                'txtTime'=>'required',
                'txtDate'=>'required',
                'txtThoiLuong'=>'required',
                'txtKinhPhi'=>'required',
                'txtNoiDungSuKien'=>'required'
            ],
            [
                'txtTenSuKien.required'=>'Chưa nhập tên sự kiện !',
                'txtTenSuKien.unique'=>'Tên sự kiện đã tồn tại !',
                'txtDiaDiem.required'=>'Chưa nhập địa điểm !',
                'txtTime.required'=>'Chưa nhập giờ tổ chức !',
                'txtDate.required'=>'Chưa nhập ngày tổ chức !',
                'txtThoiLuong.required'=>'Chưa nhập thời lượng !',
                'txtKinhPhi.required'=>'Chưa nhập kinh phí !',
                'txtNoiDungSuKien.required'=>'Chưa nhập nội dung sự kiện !'
            ]
        );

        // Phần sự kiện
        $sukien = SuKien::find($id);
        $sukien->lsk_ma = $request->slLoaiSuKien;
        $sukien->sk_ten = $request->txtTenSuKien;
        $sukien->sk_diadiem = $request->txtDiaDiem;
        $sukien->sk_thoigianbatdaud = $request->txtDate;
        $sukien->sk_thoigianbatdaut =$request->txtTime;
        $sukien->sk_thoiluong = $request->txtThoiLuong;
        $sukien->sk_kinhphi = $request->txtKinhPhi;
        $sukien->sk_noidungsukien = $request->txtNoiDungSuKien;
        $sukien->sk_trangthai = 2;       
        $sukien->save();

        // Phần đối tác
        $sk_doitac = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_doitac','sukien.sk_ma','sukien_doitac.sk_ma')
            ->join('doitac','sukien_doitac.dt_ma','doitac.dt_ma')
            ->where('doitac.dt_trangthai',1)
            ->selectRaw('doitac.dt_ma as dt_ma')
            ->get();
        foreach ($sk_doitac as $sk_dt) {
            $check = 0;
            for($i=0; $i < count($request->slDoiTac); $i++){
                if($sk_dt->dt_ma == $request->slDoiTac[$i])
                    $check = 1;
            }
            if($check==0){
                Sukien_Doitac::where('sk_ma',$id)->where('dt_ma',$sk_dt->dt_ma)->delete();
            }
        }
        $sk_doitac = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_doitac','sukien.sk_ma','sukien_doitac.sk_ma')
            ->join('doitac','sukien_doitac.dt_ma','doitac.dt_ma')
            ->where('doitac.dt_trangthai',1)
            ->selectRaw('doitac.dt_ma as dt_ma')
            ->get();
        for($i=0; $i < count($request->slDoiTac); $i++){
            $check = 0;
            foreach ($sk_doitac as $sk_dt) {
                if($request->slDoiTac[$i] == $sk_dt->dt_ma)
                    $check = 1;
            }
            if($check==0){
                $sukien_doitac = new Sukien_Doitac;
                $sukien_doitac->sk_ma = $id;
                $sukien_doitac->dt_ma = $request->slDoiTac[$i];
                $sukien_doitac->sk_dt_thanhtoan = 0;
                $sukien_doitac->save();
            }
        }

        // Phần công việc
        $sk_congviec = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->where('congviec.cv_trangthai',1)
            ->selectRaw('congviec.cv_ma as cv_ma, 
                sukien_congviec_nhanvien.sk_cv_nv_soluongnhanvien as sk_cv_nv_soluongnhanvien')
            ->get();
        foreach ($sk_congviec as $sk_cv) {
            $check = 0;
            for($i=0; $i < count($request->congviec); $i++){
                if($sk_cv->cv_ma == $request->congviec[$i])
                    $check = 1;
            }
            if($check==0){
                Sukien_Congviec_Nhanvien::where('sk_ma',$id)->where('cv_ma',$sk_cv->cv_ma)->delete();
            }
        }
        $sk_congviec = SuKien::where('sukien.sk_ma',$id)
            ->join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->where('congviec.cv_trangthai',1)
            ->selectRaw('congviec.cv_ma as cv_ma, 
                sukien_congviec_nhanvien.sk_cv_nv_soluongnhanvien as sk_cv_nv_soluongnhanvien')
            ->get();
        for($i=0; $i < count($request->congviec); $i++){
            $check = 0;
            foreach ($sk_congviec as $sk_cv) {
                if($request->congviec[$i] == $sk_cv->cv_ma)
                    $check = 1;
            }
            if($check==0){
                $sukien_congviec_nhanvien = new Sukien_Congviec_Nhanvien;
                $sukien_congviec_nhanvien->sk_ma = $id;
                $sukien_congviec_nhanvien->cv_ma = $request->congviec[$i];
                $sukien_congviec_nhanvien->nv_ma = null;
                $sukien_congviec_nhanvien->sk_cv_nv_soluongnhanvien = $request->soluongcv[$i];
                $sukien_congviec_nhanvien->sk_cv_nv_ghichu = null;
                $sukien_congviec_nhanvien->sk_cv_nv_trangthai = 0;
                $sukien_congviec_nhanvien->save();
            }
            else{
                $sukien_congviec_nhanvien = Sukien_Congviec_Nhanvien::where('sk_ma',$id)->where('cv_ma',$request->congviec[$i])->first();
                $sukien_congviec_nhanvien->sk_cv_nv_soluongnhanvien = $request->soluongcv[$i];
                $sukien_congviec_nhanvien->save();
            }
        }

        // Phần dụng cụ
        $sk_dungcu = SuKien::where('sukien.sk_ma',$id)
            ->join('sudung','sukien.sk_ma','sudung.sk_ma')
            ->join('dungcu','sudung.dc_ma','dungcu.dc_ma')
            ->where('dungcu.dc_trangthai',1)
            ->selectRaw('dungcu.dc_ma as dc_ma, sudung.sd_soluongmuon as sd_soluongmuon')
            ->get();
        foreach ($sk_dungcu as $sk_dc) {
            $check = 0;
            for($i=0; $i < count($request->dungcu); $i++){
                if($sk_dc->dc_ma == $request->dungcu[$i])
                    $check = 1;
            }
            if($check==0){
                $soluonghuy = SuDung::where('sk_ma',$id)->where('dc_ma',$sk_dc->dc_ma)->selectRaw('sd_soluongmuon')->first();
                SuDung::where('sk_ma',$id)->where('dc_ma',$sk_dc->dc_ma)->delete();
                $dungcu = DungCu::find($sk_dc->dc_ma);
                $soluongconlai = $dungcu->dc_soluongconlai;
                $dungcu->dc_soluongconlai = $soluongconlai + $soluonghuy->sd_soluongmuon;
                $dungcu->save();
            }
        }
        $sk_dungcu = SuKien::where('sukien.sk_ma',$id)
            ->join('sudung','sukien.sk_ma','sudung.sk_ma')
            ->join('dungcu','sudung.dc_ma','dungcu.dc_ma')
            ->where('dungcu.dc_trangthai',1)
            ->selectRaw('dungcu.dc_ma as dc_ma, sudung.sd_soluongmuon as sd_soluongmuon')
            ->get();
        for($i=0; $i < count($request->dungcu); $i++){
            $check = 0;
            foreach ($sk_dungcu as $sk_dc) {
                if($request->dungcu[$i] == $sk_dc->dc_ma)
                    $check = 1;
            }
            if($check==0){
                $dungcu = DungCu::find($request->dungcu[$i]);
                if($request->soluongdc[$i] > $dungcu->dc_soluongconlai){
                    return redirect('admin/sukien/duyet/'.$id)->with('loi','Dụng cụ '.$dungcu->dc_ten.' chỉ còn lại '.$dungcu->dc_soluongconlai.', không đủ so với yêu cầu '.$request->soluongdc[$i].' của bạn !');
                }

                $sudung = new SuDung;
                $sudung->sk_ma = $id;
                $sudung->dc_ma = $request->dungcu[$i];
                $sudung->sd_soluongmuon = $request->soluongdc[$i];
                $sudung->sd_trangthai = 0;
                $sudung->save();

                $soluongconlai = $dungcu->dc_soluongconlai;
                $dungcu->dc_soluongconlai = $soluongconlai - $request->soluongdc[$i];
                $dungcu->save();
            }
            else{
                $sudung = SuDung::where('sk_ma',$id)->where('dc_ma',$request->dungcu[$i])->first();
                $sudung->sd_soluongmuon = $request->soluongdc[$i];
                $sudung->save();
            }
        }

        // Gửi mail
        $data = SuKien::where('sukien.sk_ma',$id)
            ->join('hopdongtochucsukien','sukien.hdtcsk_sohopdong','hopdongtochucsukien.hdtcsk_sohopdong')
            ->join('khachhang','hopdongtochucsukien.kh_ma','khachhang.kh_ma')
            ->selectRaw('khachhang.kh_tencongty as kh_tencongty, khachhang.kh_email as kh_email, 
                sukien.sk_ten as sk_ten')
            ->first();
        $email = $data->kh_email;

        Mail::send('admin.sukien.mailduyetsukien',array('tencongty'=>$data->kh_tencongty,'tensukien'=>$data->sk_ten), function($message) use($email){
            $message->to($email,'Guest')->subject('Mail thông báo duyệt sự kiện của công ty EventTechnologyVN');
        });

        return redirect('admin/sukien/duyet/'.$id)->with('thongbao',"Duyệt sự kiện thành công, email đã được gửi đến hộp thư của khách hàng !");
    }

    /* Hiển thị form tạo hợp đồng:
    #   - Vào: $id
    #   - Ra: các object 'sukien', 'hopdong', 'khachhang', 'loaisukien'
    #   - View: admin-sukien-taohopdong
    */
    public function getTaohopdongSK($id){
        $sukien = SuKien::find($id);
        $hopdong = SuKien::find($id)->hopdongtochucsukien;
        $khachhang = SuKien::find($id)->hopdongtochucsukien->khachhang;
        $loaisukien = SuKien::find($id)->loaisukien;

        return view('admin.sukien.taohopdong',['sukien'=>$sukien, 'hopdong'=>$hopdong, 'khachhang'=>$khachhang, 'loaisukien'=>$loaisukien]);
    }

    /* Xử lý form tạo hợp đồng:
    #   - Vào: $request form, $id
    #   - Ra: $errors, session 'thongbao'
    #   - View: admin-sukien-taohopdong
    */
    public function postTaohopdongSK(Request $request, $id){
        $this->validate($request,
            [
                'txtGiaTri'=>'required',
                'txtNoiDungHopDong'=>'required'
            ],
            [
                'txtGiaTri.required'=>'Chưa nhập giá trị hợp đồng !',
                'txtNoiDungHopDong.required'=>'Chưa nhập nội dung hợp đồng !'
            ]
        );

        $nhanvien = NhanVien::where('tk_ma',Session::get('back_login_mataikhoan'))->first();

        $hopdong = HopDongToChucSuKien::find($request->txtSoHopDong);
        $hopdong->hdtcsk_giatrihopdong = $request->txtGiaTri;
        $hopdong->hdtcsk_sotientamung = $request->txtTamUng;
        $hopdong->hdtcsk_noidunghopdong = $request->txtNoiDungHopDong;
        $hopdong->hdtcsk_ngaytaohopdong = date("Y-m-d");
        $hopdong->nv_taohopdong = $nhanvien->nv_ma;
        $hopdong->nv_chiutrachnhiem = $nhanvien->nv_ma;
        $hopdong->hdtcsk_trangthai = 2;
        $hopdong->save();

        $sukien = SuKien::find($id);
        $sukien->sk_trangthai = 4;
        $sukien->save();

        return redirect('admin/sukien/taohopdong/'.$id)->with('thongbao','Tạo hợp đồng thành công !');
    }

    /* Hiển thị trang xuất hợp đồng:
    #   - Vào: $id
    #   - Ra: object 'hopdongtochucsukien'
    #   - View: admin-sukien-hopdongtochucsukien
    */
    public function getXuathopdongSK($id){
        $hopdong = SuKien::find($id)->hopdongtochucsukien;
        $hopdong->hdtcsk_ngayxuathopdong = date("Y-m-d");
        $hopdong->save();

        $data = SuKien::join('hopdongtochucsukien','sukien.hdtcsk_sohopdong','hopdongtochucsukien.hdtcsk_sohopdong')
            ->where('sukien.sk_ma',$id)
            ->join('khachhang','hopdongtochucsukien.kh_ma','khachhang.kh_ma')
            ->selectRaw('khachhang.kh_tencongty as kh_tencongty, khachhang.kh_diachi as kh_diachi, khachhang.kh_dienthoai as kh_dienthoai, khachhang.kh_email as kh_email,
                hopdongtochucsukien.hdtcsk_sohopdong as hdtcsk_sohopdong, hopdongtochucsukien.hdtcsk_noidunghopdong as hdtcsk_noidunghopdong, 
                hopdongtochucsukien.hdtcsk_ngayxuathopdong as hdtcsk_ngayxuathopdong, hopdongtochucsukien.bm_ma as bm_ma')
            ->first();

        $ngaythang = "Cần Thơ, ngày " .date_format(date_create($data->hdtcsk_ngayxuathopdong),'d'). ", tháng " 
            .date_format(date_create($data->hdtcsk_ngayxuathopdong),'m'). ", năm " 
            .date_format(date_create($data->hdtcsk_ngayxuathopdong),'Y');

        $bieumau = BieuMau::find($data->bm_ma);

        $hopdongtochucsukien = $bieumau->bm_noidung;

        $hopdongtochucsukien = str_replace("{sohopdong}", $data->hdtcsk_sohopdong, $hopdongtochucsukien);
        $hopdongtochucsukien = str_replace("{tencongty}", $data->kh_tencongty, $hopdongtochucsukien);
        $hopdongtochucsukien = str_replace("{diachi}", $data->kh_diachi, $hopdongtochucsukien);
        $hopdongtochucsukien = str_replace("{dienthoai}", $data->kh_dienthoai, $hopdongtochucsukien);
        $hopdongtochucsukien = str_replace("{email}", $data->kh_email, $hopdongtochucsukien);
        $hopdongtochucsukien = str_replace("{noidunghopdong}", $data->hdtcsk_noidunghopdong, $hopdongtochucsukien);
        $hopdongtochucsukien = str_replace("{ngaythang}", $ngaythang, $hopdongtochucsukien);

        return view('admin.sukien.hopdongtochucsukien',['hopdongtochucsukien'=>$hopdongtochucsukien]);
    }

    /* Hiển thị trang xuất báo cáo một sự kiện:
    #   - Vào: $id
    #   - Ra: object 'baocaomotsukien'
    #   - View: admin-sukien-baocaomotsukien
    */
    public function getBaocaoMotSK($id){
        // Phan get data from db
        $hopdong = SuKien::join('hopdongtochucsukien','sukien.hdtcsk_sohopdong','hopdongtochucsukien.hdtcsk_sohopdong')
                ->where('sukien.sk_ma',$id)
                ->join('khachhang','hopdongtochucsukien.kh_ma','khachhang.kh_ma')
                ->join('nhanvien','hopdongtochucsukien.nv_chiutrachnhiem','nhanvien.nv_ma')
                ->selectRaw('hopdongtochucsukien.hdtcsk_sohopdong as hdtcsk_sohopdong, hopdongtochucsukien.hdtcsk_giatrihopdong as hdtcsk_giatrihopdong, hopdongtochucsukien.hdtcsk_ngaytaohopdong as hdtcsk_ngaytaohopdong,
                    khachhang.kh_tencongty as kh_tencongty, nhanvien.nv_tennhanvien as nv_tennhanvien')
                ->first();

        $sukien = SuKien::join('loaisukien','sukien.sk_ma','loaisukien.lsk_ma')
            ->where('sukien.sk_ma',$id)
            ->selectRaw('loaisukien.lsk_ten as lsk_ten,
                sukien.sk_ten as sk_ten, sukien.sk_diadiem as sk_diadiem, sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, sukien.sk_thoigianbatdaut as sk_thoigianbatdaut, sukien.sk_kinhphi as sk_kinhphi')
            ->first();

        $doitac = SuKien::find($id)->doitac;

        $congviec = SuKien::join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->where('sukien.sk_ma',$id)
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->join('nhanvien','sukien_congviec_nhanvien.nv_ma','nhanvien.nv_ma')
            ->selectRaw('congviec.cv_ten as cv_ten, nhanvien.nv_tennhanvien as nv_tennhanvien,
                    sukien_congviec_nhanvien.sk_cv_nv_soluongnhanvien as sk_cv_nv_soluongnhanvien, sukien_congviec_nhanvien.sk_cv_nv_trangthai as sk_cv_nv_trangthai')
            ->get();

        $dungcu = SuKien::join('sudung','sukien.sk_ma','sudung.sk_ma')
            ->where('sukien.sk_ma',$id)
            ->join('dungcu','sudung.dc_ma','dungcu.dc_ma')
            ->join('nhanvien','sudung.nv_muon','nhanvien.nv_ma')
            ->selectRaw('dungcu.dc_ten as dc_ten, sudung.sd_soluongmuon as sd_soluongmuon, nhanvien.nv_tennhanvien as nv_tennhanvien')
            ->get();

        $ngaythang = "Cần Thơ, ngày " .date('d'). ", tháng ".date('m'). ", năm ".date('Y');

        $bieumau = BieuMau::find(4);

        $baocaomotsukien = $bieumau->bm_noidung;

        // Phan combine data ra view
        $baocaomotsukien = str_replace("{sohopdong}",$hopdong->hdtcsk_sohopdong,$baocaomotsukien);
        $baocaomotsukien = str_replace("{tencongty}",$hopdong->kh_tencongty,$baocaomotsukien);
        $baocaomotsukien = str_replace("{giatrihopdong}",number_format($hopdong->hdtcsk_giatrihopdong,0,',','.')." VNĐ",$baocaomotsukien);
        $baocaomotsukien = str_replace("{ngaytaohopdong}",date_format(date_create($hopdong->hdtcsk_ngaytaohopdong),'d/m/Y'),$baocaomotsukien);
        $baocaomotsukien = str_replace("{nhanvienchiutrachnhiem}",$hopdong->nv_tennhanvien,$baocaomotsukien);

        $baocaomotsukien = str_replace("{tensukien}",$sukien->sk_ten,$baocaomotsukien);
        $baocaomotsukien = str_replace("{loaisukien}",$sukien->lsk_ten,$baocaomotsukien);
        $baocaomotsukien = str_replace("{diadiem}",$sukien->sk_diadiem,$baocaomotsukien);
        $baocaomotsukien = str_replace("{thoigianbatdau}",date_format(date_create($sukien->sk_thoigianbatdaut),'H:i:s')." - ".date_format(date_create($sukien->sk_thoigianbatdaud),'d/m/Y'),$baocaomotsukien);
        $baocaomotsukien = str_replace("{kinhphi}",$sukien->sk_kinhphi,$baocaomotsukien);

        $dt_doitac = "";
        foreach($doitac as $dt){
            $dt_doitac .= "- $dt->dt_tencongty <br>";
        }

        $baocaomotsukien = str_replace("{danhsachdoitac}",$dt_doitac,$baocaomotsukien);

        $dt_congviec = "";
        $stt=1;
        foreach($congviec as $cv){
            $dt_congviec .= "<tr>";
            $dt_congviec .= "<td class='center'>$stt</td>";
            $dt_congviec .= "<td class='left'>$cv->cv_ten</td>";
            $dt_congviec .= "<td style='text-align: right'>$cv->sk_cv_nv_soluongnhanvien</td>";
            $dt_congviec .= "<td class='left'>$cv->nv_tennhanvien</td>";
            if($cv->sk_cv_nv_trangthai==1){
                $dt_congviec .= "<td class='center'>Hoàn thành</td>";
            }
            else{
                $dt_congviec .= "<td class='center'>Chưa hoàn thành</td>";
            }
            $dt_congviec .= "</tr>";
            $stt++;
        }

        $baocaomotsukien = str_replace("{noidungcongviec}",$dt_congviec,$baocaomotsukien);

        $dt_dungcu = "";
        $stt=1;
        foreach($dungcu as $dc){
            $dt_dungcu .= "<tr>";
            $dt_dungcu .= "<td class='center'>$stt</td>";
            $dt_dungcu .= "<td class='left'>$dc->dc_ten</td>";
            $dt_dungcu .= "<td style='text-align: right'>$dc->sd_soluongmuon</td>";
            $dt_dungcu .= "<td class='left'>$dc->nv_tennhanvien</td>";
            $dt_dungcu .= "</tr>";
            $stt++;
        }

        $baocaomotsukien = str_replace("{noidungdungcu}",$dt_dungcu,$baocaomotsukien);

        $baocaomotsukien = str_replace("{ngaythang}", $ngaythang, $baocaomotsukien);

        return view('admin.sukien.baocaomotsukien',['baocaomotsukien'=>$baocaomotsukien]);
    }

    /* Hiển thị form hình ảnh sự kiện:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'hinhanh'
    #   - View: admin-sukien-hinhanh
    */
    public function getHinhanhSK($id){
        $sukien = SuKien::find($id);

        $hinhanh = HinhAnh::where('sk_ma',$id)->get();
       
        return view('admin/sukien/hinhanh',['sukien'=>$sukien,'hinhanh'=>$hinhanh]);
    }

    /* Xử lý form hình ảnh sự kiện:
    #   - Vào: $request form, $id
    #   - Ra: session 'thongbao', 'loi'
    #   - View: admin-sukien-hinhanh
    */
    public function postHinhanhSK(Request $request, $id){    

        $sukien = SuKien::find($id);
        if($request->ckHienThiTrangChu == "on"){
            $sukien->sk_hienthitrangchu = 1;
        }
        else{
            $sukien->sk_hienthitrangchu = 0;
        }
        $sukien->save();

        if($request->hasFile('fileHinhAnh')){           
            $file = $request->File('fileHinhAnh');
            $size = $file->getClientSize();
            $tail = $file->getClientOriginalExtension();

            if($tail=="jpg" || $tail=="jpeg" || $tail=="png"){
                if($size<=614400){
                    $hinhanh = new HinhAnh;
                    $hinhanh->sk_ma = $id;
                    $name = $file->getClientOriginalName();
                    $tenhinh = $id."_".str_random(2).$name;
                    while(file_exists("frontend/album/$tenhinh"))
                        $tenhinh = $id."_".str_random(2).$name;
                    $hinhanh->ha_tentaptin = $tenhinh;
                    $hinhanh->save();
                    $file->move("frontend/album",$tenhinh);
                   
                    return redirect('admin/sukien/hinhanh/'.$id)->with('thongbao','Thêm hình thành công !');                
                }   
                else
                    return redirect('admin/sukien/hinhanh/'.$id)->with('loi','Hình có kích thước vượt quá 600kb !');               
            }
            else
                return redirect('admin/sukien/hinhanh/'.$id)->with('loi','Chỉ được chọn file hình ảnh với đuôi .jpg .jpeg .png !');           
        }
        else
            return redirect('admin/sukien/hinhanh/'.$id)->with('loi','Chưa chọn hình ảnh !');  
    }

    /* Xử lý xóa hình ảnh sự kiện:
    #   - Vào: $mask, $maha
    #   - Ra: session 'thongbao'
    #   - View: admin-sukien-hinhanh
    */
    public function getXoahinhSK($mask, $maha){
        $hinhanh = HinhAnh::find($maha);
        unlink("frontend/album/".$hinhanh->ha_tentaptin);
        $hinhanh->delete();

        return redirect('admin/sukien/hinhanh/'.$mask)->with('thongbao','Xóa hình thành công !');
    }

    /* Xử lý xóa sự kiện:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-sukien-dsnosk / admin-sukien-dsyessk
    */
    public function getXoaSK($id){  

        $sukien = SuKien::find($id);
        $sukien->sk_trangthai = 0;
        $sukien->save();

        $hopdong = SuKien::find($id)->hopdongtochucsukien;
        $hopdong->hdtcsk_trangthai = 0;
        $hopdong->save();

        return redirect()->back()->with('thongbao','Xóa sự kiện thành công !');
    }

    /* Thống kê sự kiện: get
    #   - Vào:
    #   - Ra: object 'sukien'
    #   - View: admin-sukien-thongke
    */
    public function getThongkeSK(){

        $sukien = SuKien::join('loaisukien','sukien.lsk_ma','loaisukien.lsk_ma')
            ->whereIN('sk_trangthai',[3,4])
            ->selectRaw('loaisukien.lsk_ten as lsk_ten, sukien.sk_ten as sk_ten, sukien.sk_diadiem as sk_diadiem,
                    sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, sukien.sk_kinhphi as sk_kinhphi')
            ->orderBy('sukien.sk_thoigianbatdaud','ASC')
            ->get();

        return view('admin.sukien.thongke',['sukien'=>$sukien]);
    }

    /* Thống kê sự kiện: post
    #   - Vào: $request form
    #   - Ra: object 'sukien', $tungay, $denngay
    #   - View: admin-sukien-thongke
    */
    public function postThongkeSK(Request $request){
         $this->validate($request,
            [
                'txtTuNgay'=>'required',
                'txtDenNgay'=>'required'
            ],
            [
                'txtTuNgay.required'=>'Chưa nhập: từ ngày !',
                'txtDenNgay.required'=>'Chưa nhập: đến ngày !'
            ]
        );

        $sukien = SuKien::join('loaisukien','sukien.lsk_ma','loaisukien.lsk_ma')
            ->whereIN('sukien.sk_trangthai',[3,4])
            ->where('sukien.sk_thoigianbatdaud','>=',$request->txtTuNgay)
            ->where('sukien.sk_thoigianbatdaud','<=',$request->txtDenNgay)
            ->selectRaw('loaisukien.lsk_ten as lsk_ten, sukien.sk_ten as sk_ten, sukien.sk_diadiem as sk_diadiem,
                    sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, sukien.sk_kinhphi as sk_kinhphi')
            ->orderBy('sukien.sk_thoigianbatdaud','ASC')
            ->get();

        return view('admin.sukien.thongke',['sukien'=>$sukien,'tungay'=>$request->txtTuNgay,'denngay'=>$request->txtDenNgay]);
    }

    /* Xuất báo cáo:
    #   - Vào: $tungay, $denngay
    #   - Ra:
    #   - View: admin-sukien-baocaosukien
    */
    public function getXuatbaocaoSK($tungay, $denngay){

        $sukien = SuKien::join('loaisukien','sukien.lsk_ma','loaisukien.lsk_ma')
            ->whereIN('sk_trangthai',[3,4])
            ->where('sukien.sk_thoigianbatdaud','>=',$tungay)
            ->where('sukien.sk_thoigianbatdaud','<=',$denngay)
            ->selectRaw('loaisukien.lsk_ten as lsk_ten, sukien.sk_ten as sk_ten, sukien.sk_diadiem as sk_diadiem,
                    sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, sukien.sk_kinhphi as sk_kinhphi')
            ->orderBy('sukien.sk_thoigianbatdaud','ASC')
            ->get();

        $data = "";
        $stt=1;
        foreach($sukien as $sk){
            $data .= "<tr>";
            $data .= "<td class='left'>$stt</td>";
            $data .= "<td class='left'>$sk->lsk_ten</td>";
            $data .= "<td class='left'>$sk->sk_ten</td>";
            $data .= "<td>$sk->sk_diadiem</td>";
            $data .= "<td class='center'>".date_format(date_create($sk->sk_thoigianbatdaud),'d/m/Y')."</td>";
            $data .= "<td style='text-align: right'>".number_format($sk->sk_kinhphi,0,',','.')." VNĐ</td>";
            $data .= "</tr>";
            $stt++;
        }

        $ngaythang = "Cần Thơ, ngày " .date('d'). ", tháng ".date('m'). ", năm ".date('Y');

        $bieumau = BieuMau::find(3);

        $baocaosukien = $bieumau->bm_noidung;

        $baocaosukien = str_replace("{tungay}", date_format(date_create($tungay),'d/m/Y'), $baocaosukien);
        $baocaosukien = str_replace("{denngay}", date_format(date_create($denngay),'d/m/Y'), $baocaosukien);
        $baocaosukien = str_replace("{noidungbaocao}", $data, $baocaosukien);
        $baocaosukien = str_replace("{ngaythang}", $ngaythang, $baocaosukien);

        return view('admin.sukien.baocaosukien',['baocaosukien'=>$baocaosukien]);
    }


    /*================================Nhà cung cấp===========================================*/
    /* Hiển thị danh sách nhà cung cấp:
    #   - Vào:
    #   - Ra: object 'nhacungcap'
    #   - View: admin-nhacungcap-danhsach
    */
    public function getDanhsachNCC(){
        $nhacungcap = NhaCungCap::where('ncc_trangthai',1)->get();

        return view('admin.nhacungcap.danhsach',['nhacungcap'=>$nhacungcap]);
    }

    /* Hiển thị form thêm mới nhà cung cấp:
    #   - Vào:
    #   - Ra:
    #   - View: admin-nhacungcap-themmoi
    */
    public function getThemmoiNCC(){
        return view('admin.nhacungcap.themmoi');
    }

    /* Xử lý form thêm mới nhà cung cấp:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-nhacungcap-themmoi
    */
    public function postThemmoiNCC(Request $request){
        $this->validate($request,
            [
                'txtTenNhaCungCap'=>'required|unique:nhacungcap,ncc_ten',
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric',
                'txtEmail'=>'required|email|unique:nhacungcap,ncc_email'
            ],
            [
                'txtTenNhaCungCap.required'=>'Chưa nhập tên nhà cung cấp !',
                'txtTenNhaCungCap.unique'=>'Tên nhà cung cấp đã tồn tại !',
                'txtDiaChi.required'=>'Chưa nhập địa chỉ !',
                'txtDienThoai.required'=>'Chưa nhập số điện thoại !',
                'txtDienThoai.numeric'=>'Số điện thoại chỉ bao gồm kí tự số !',
                'txtEmail.required'=>'Chưa nhập email !',
                'txtEmail.email'=>'Email không đúng định dạng !',
                'txtEmail.unique'=>'Email đã tồn tại !'
            ]
        );

        $nhacungcap = new NhaCungCap;
        $nhacungcap->ncc_ten = $request->txtTenNhaCungCap;
        $nhacungcap->ncc_diachi = $request->txtDiaChi;
        $nhacungcap->ncc_dienthoai = $request->txtDienThoai;
        $nhacungcap->ncc_email = $request->txtEmail;
        $nhacungcap->ncc_trangthai = 1;
        $nhacungcap->save();

        return redirect('admin/nhacungcap/themmoi')->with('thongbao','Thêm mới nhà cung cấp thành công !');
    }

    /* Hiển thị form cập nhật nhà cung cấp:
    #   - Vào: $id
    #   - Ra: object 'nhacungcap'
    #   - View: admin-nhacungcap-capnhat
    */
    public function getCapnhatNCC($id){
        $nhacungcap = NhaCungCap::find($id);

        return view('admin.nhacungcap.capnhat',['nhacungcap'=>$nhacungcap]);
    }

    /* Xử lý form cập nhật nhà cung cấp:
    #   - Vào: $request from, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-nhacungcap-capnhat
    */
    public function postCapnhatNCC(Request $request, $id){
        $this->validate($request,
            [
                'txtTenNhaCungCap'=>'required|unique:nhacungcap,ncc_ten,'.$id.',ncc_ma',
                'txtDiaChi'=>'required',
                'txtDienThoai'=>'required|numeric',
                'txtEmail'=>'required|email|unique:nhacungcap,ncc_email,'.$id.',ncc_ma'
            ],
            [
                'txtTenNhaCungCap.required'=>'Chưa nhập tên nhà cung cấp !',
                'txtTenNhaCungCap.unique'=>'Tên nhà cung cấp đã tồn tại !',
                'txtDiaChi.required'=>'Chưa nhập địa chỉ !',
                'txtDienThoai.required'=>'Chưa nhập số điện thoại !',
                'txtDienThoai.numeric'=>'Số điện thoại chỉ bao gồm kí tự số !',
                'txtEmail.required'=>'Chưa nhập email !',
                'txtEmail.email'=>'Email không đúng định dạng !',
                'txtEmail.unique'=>'Email đã tồn tại !'
            ]
        );

        $nhacungcap = NhaCungCap::find($id);
        $nhacungcap->ncc_ten = $request->txtTenNhaCungCap;
        $nhacungcap->ncc_diachi = $request->txtDiaChi;
        $nhacungcap->ncc_dienthoai = $request->txtDienThoai;
        $nhacungcap->ncc_email = $request->txtEmail;
        $nhacungcap->save();

        return redirect('admin/nhacungcap/capnhat/'.$id)->with('thongbao','Cập nhật nhà cung cấp thành công !');
    }

    /* Xử lý xóa nhà cung cấp:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-nhacungcap-danhsach
    */
    public function getXoaNCC($id){
        $nhacungcap = NhaCungCap::find($id);
        $nhacungcap->ncc_trangthai = 0;
        $nhacungcap->save();

        return redirect('admin/nhacungcap/danhsach')->with('thongbao',"Xóa nhà cung cấp '$nhacungcap->ncc_ten' thành công !");
    }


    /*================================Dụng cụ===========================================*/
    
    /* Hiển thị form lập phiếu nhập:
    #   - Vào:
    #   - Ra: object 'nhacungcap', 'dungcu'
    #   - View: admin-dungcu-lapphieunhap
    */
    public function getLapphieunhap(){

        $nhacungcap = NhaCungCap::where('ncc_trangthai',1)->get();
        $dungcu = DungCu::where('dc_trangthai',1)->get();

        return view('admin.dungcu.lapphieunhap',['nhacungcap'=>$nhacungcap,'dungcu'=>$dungcu]);
    }

    /* Xử lý form lập phiếu nhập:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-dungcu-lapphieunhap
    */
    public function postLapphieunhap(Request $request){

        $this->validate($request,
            [
                'txtNgayNhapHang'=>'required'
            ],
            [
                'txtNgayNhapHang.required'=>'Chưa nhập ngày nhập hàng !'
            ]
        );

        $nhanvien = NhanVien::where('tk_ma',Session::get('back_login_mataikhoan'))->first();

        $maphieunhap = 'ET/PN/'.date_format(date_create($request->txtNgayNhapHang),'y').date_format(date_create($request->txtNgayNhapHang),'m')
            .date_format(date_create($request->txtNgayNhapHang),'d').'/'.date('H').date('i');

        $phieunhap = new PhieuNhap;
        $phieunhap->pn_maphieunhap = $maphieunhap;
        $phieunhap->pn_ngaynhap = $request->txtNgayNhapHang;
        $phieunhap->nv_lapphieu = $nhanvien->nv_ma;
        $phieunhap->pn_trangthai = 1;
        $phieunhap->ncc_ma = $request->slNhaCungCap;
        $phieunhap->bm_ma = 2;
        $phieunhap->save();

        for($i=0; $i < count($request->dungcu); $i++){
            $dungcu = DungCu::find($request->dungcu[$i]);
            if(empty($dungcu)){
                $newdungcu = New DungCu;
                $newdungcu->dc_ten = $request->dungcu[$i];
                $newdungcu->dc_soluongtong = $request->soluong[$i];
                $newdungcu->dc_soluongconlai = $request->soluong[$i];
                $newdungcu->dc_trangthai = 1;
                $newdungcu->save();

                $finddungcu = DungCu::where('dc_ten',$request->dungcu[$i])->first();

                $chitietphieunhap = New ChiTietPhieuNhap;
                $chitietphieunhap->pn_maphieunhap = $maphieunhap;
                $chitietphieunhap->dc_ma = $finddungcu->dc_ma;
                $chitietphieunhap->ctpn_soluong = $request->soluong[$i];
                $chitietphieunhap->ctpn_dongia = $request->dongia[$i];
                $chitietphieunhap->save();

            }
            else{
                $dungcu->dc_soluongtong = $dungcu->dc_soluongtong + $request->soluong[$i];
                $dungcu->save();

                $chitietphieunhap = New ChiTietPhieuNhap;
                $chitietphieunhap->pn_maphieunhap = $maphieunhap;
                $chitietphieunhap->dc_ma = $dungcu->dc_ma;
                $chitietphieunhap->ctpn_soluong = $request->soluong[$i];
                $chitietphieunhap->ctpn_dongia = $request->dongia[$i];
                $chitietphieunhap->save();
            }

        }

        return redirect('admin/dungcu/lapphieunhap')->with('thongbao','Lập phiếu nhập dụng cụ thành công !');
    }

    /* Hiển thị danh sách phiếu nhập dụng cụ:
    #   - Vào:
    #   - Ra: object 'phieunhap', array 'tien'
    #   - View: admin-dungcu-danhsachphieunhap
    */
    public function getDanhsachPN(){
        
        $phieunhap = PhieuNhap::join('nhacungcap','phieunhap.ncc_ma','nhacungcap.ncc_ma')
            ->where('phieunhap.pn_trangthai',1)
            ->join('nhanvien','phieunhap.nv_lapphieu','nhanvien.nv_ma')
            ->selectRaw('nhacungcap.ncc_ten as ncc_ten, 
                phieunhap.pn_maphieunhap as pn_maphieunhap, phieunhap.pn_ngaynhap as pn_ngaynhap, 
                nhanvien.nv_tennhanvien as nv_tennhanvien, phieunhap.pn_ngayxuatphieu as pn_ngayxuatphieu')
            ->get();

        $tien = [];
        $i = 0;
        foreach($phieunhap as $pn){
            $ctpn = ChiTietPhieuNhap::where('pn_maphieunhap',$pn->pn_maphieunhap)->get();
            $temp = 0;
            foreach($ctpn as $ct){
                $temp += $ct->ctpn_dongia * $ct->ctpn_soluong;
            }
            $tien[$i] = $temp;
            $i++;
        }
        
        return view('admin.dungcu.danhsachphieunhap',['phieunhap'=>$phieunhap,'tien'=>$tien]);
    }

    /* Xuất phiếu nhập dụng cụ:
    #   - Vào: $param1, $param2, $param3, $param4
    #   - Ra: object 'xuatphieunhap'
    #   - View: admin-dungcu-xuatphieunhap
    */
    public function getXuatphieunhap($param1, $param2, $param3, $param4){

        $maphieunhap = $param1."/".$param2."/".$param3."/".$param4;

        $updatepn = PhieuNhap::find($maphieunhap);
        $updatepn->pn_ngayxuatphieu = date("Y-m-d");
        $updatepn->save();

        $phieunhap = PhieuNhap::join('nhacungcap','phieunhap.ncc_ma','nhacungcap.ncc_ma')
            ->where('phieunhap.pn_maphieunhap',$maphieunhap)
            ->join('nhanvien','phieunhap.nv_lapphieu','nhanvien.nv_ma')
            ->selectRaw('nhacungcap.ncc_ten as ncc_ten, nhacungcap.ncc_dienthoai as ncc_dienthoai,
                nhanvien.nv_tennhanvien as nv_tennhanvien,
                phieunhap.pn_maphieunhap as pn_maphieunhap, phieunhap.pn_ngaynhap as pn_ngaynhap, 
                phieunhap.pn_ngayxuatphieu as pn_ngayxuatphieu, phieunhap.bm_ma as bm_ma')
            ->first();

        $tongtien = 0;
        $ctpn = ChiTietPhieuNhap::join('dungcu','chitietphieunhap.dc_ma','dungcu.dc_ma')
            ->where('pn_maphieunhap',$maphieunhap)
            ->selectRaw('dungcu.dc_ten as dc_ten, 
                chitietphieunhap.ctpn_soluong as ctpn_soluong, chitietphieunhap.ctpn_dongia as ctpn_dongia')
            ->get();
        foreach($ctpn as $ct){
            $tongtien += $ct->ctpn_dongia * $ct->ctpn_soluong;
        }

        $data = "";
        $stt=1;
        foreach($ctpn as $ct){
            $data .= "<tr>";
            $data .= "<td class='center'>$stt</td>";
            $data .= "<td class='left'>$ct->dc_ten</td>";
            $data .= "<td style='text-align:right'>$ct->ctpn_soluong</td>";
            $data .= "<td style='text-align:right'>".number_format($ct->ctpn_dongia,0,',','.')." VNĐ</td>";
            $data .= "</tr>";
            $stt++;
        }

        $ngaythang = "Cần Thơ, ngày " .date('d'). ", tháng ".date('m'). ", năm ".date('Y');

        $bieumau = BieuMau::find($phieunhap->bm_ma);

        $xuatphieunhap = $bieumau->bm_noidung;

        $xuatphieunhap = str_replace("{maphieunhap}", $maphieunhap, $xuatphieunhap);
        $xuatphieunhap = str_replace("{ngaynhap}", date_format(date_create($phieunhap->pn_ngaynhap),'d/m/Y'), $xuatphieunhap);
        $xuatphieunhap = str_replace("{tennhacungcap}", $phieunhap->ncc_ten, $xuatphieunhap);
        $xuatphieunhap = str_replace("{dtnhacungcap}", $phieunhap->ncc_dienthoai, $xuatphieunhap);
        $xuatphieunhap = str_replace("{noidungphieunhap}", $data, $xuatphieunhap);
        $xuatphieunhap = str_replace("{tongtien}", number_format($tongtien,0,',','.'), $xuatphieunhap);
        $xuatphieunhap = str_replace("{ngaythang}", $ngaythang, $xuatphieunhap);
        $xuatphieunhap = str_replace("{nhanvienlapphieu}", $phieunhap->nv_tennhanvien, $xuatphieunhap);

        return view('admin.dungcu.xuatphieunhap',['xuatphieunhap'=>$xuatphieunhap]);
    }

    /* Hiển thị danh sách dụng cụ:
    #   - Vào:
    #   - Ra: object 'dungcu', array 'nhacungcap'
    #   - View: admin-dungcu-danhsach
    */
    public function getDanhsachDC(){
        $dungcu = DungCu::where('dc_trangthai',1)->get();

        $nhacungcap = array();
        foreach ($dungcu as $dc) {
            $data = DungCu::join('chitietphieunhap','dungcu.dc_ma','=','chitietphieunhap.dc_ma')
            ->where('dungcu.dc_ma',$dc->dc_ma)
            ->join('phieunhap','phieunhap.pn_maphieunhap','=','chitietphieunhap.pn_maphieunhap')
            ->join('nhacungcap','phieunhap.ncc_ma','=','nhacungcap.ncc_ma')
            ->join('nhanvien','phieunhap.nv_lapphieu','nhanvien.nv_ma')
            ->selectRaw('nhanvien.nv_tennhanvien as nv_tennhanvien, nhacungcap.ncc_ten as ncc_ten, phieunhap.pn_maphieunhap as pn_maphieunhap, 
                chitietphieunhap.ctpn_soluong as ctpn_soluong, chitietphieunhap.ctpn_dongia as ctpn_dongia')
            ->get();
            array_push($nhacungcap,$data);
        }

        return view('admin.dungcu.danhsach',['dungcu'=>$dungcu, 'nhacungcap'=>$nhacungcap]);
    }

    /* Hiển thị from cập nhật dụng cụ:
    #   - Vào: $id
    #   - Ra: object 'dungcu'
    #   - View: admin-dungcu-capnhat
    */
    public function getCapnhatDC($id){
        $dungcu = DungCu::find($id);

        return view('admin.dungcu.capnhat',['dungcu'=>$dungcu]);
    }

    /* Xử lý from cập nhật dụng cụ:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-dungcu-capnhat
    */
    public function postCapnhatDC(Request $request, $id){
        $this->validate($request,
            [
                'txtTenDungCu'=>'required|unique:dungcu,dc_ten,'.$id.',dc_ma'
            ],
            [
                'txtTenDungCu.required'=>'Chưa nhập tên dụng cụ !',
                'txtTenDungCu.unique'=>'Tên dụng cụ đã tồn tại !'
            ]
        );

        $dungcu = DungCu::find($id);
        $dungcu->dc_ten = $request->txtTenDungCu;
        $dungcu->dc_mota = $request->txtMoTa;
        $dungcu->save();

        return redirect('admin/dungcu/capnhat/'.$id)->with('thongbao','Cập nhật dụng cụ thành công !');
    }

    /* Xử lý xóa dụng cụ:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-dungcu-danhsach
    */
    public function getXoaDC($id){
        $dungcu = DungCu::find($id);
        $dungcu->dc_trangthai = 0;
        $dungcu->save();

        return redirect('admin/dungcu/danhsach')->with('thongbao',"Xóa dụng cụ '$dungcu->dc_ten' thành công !");
    }

    /* Hiển thị danh sách yêu cầu dụng cụ:
    #   - Vào:
    #   - Ra: object 'muon', 'tra'
    #   - View: admin-dungcu-danhsachyeucau
    */
    public function getDanhsachYC(){
        
        $muon = SuKien::join('sudung','sukien.sk_ma','sudung.sk_ma')
            ->whereIn('sukien.sk_trangthai',[3,4])
            ->where('sudung.sd_trangthai',0)
            ->selectRaw('DISTINCT sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, 
                sukien.sk_thoigianbatdaud as sk_thoigianbatdaud')
            ->get();

        $tra = SuKien::join('sudung','sukien.sk_ma','sudung.sk_ma')
            ->whereIn('sukien.sk_trangthai',[3,4])
            ->where('sudung.sd_trangthai',1)
            ->selectRaw('DISTINCT sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, 
                sukien.sk_thoigianbatdaud as sk_thoigianbatdaud')
            ->get();

        return view('admin.dungcu.danhsachyeucau',['muon'=>$muon, 'tra'=>$tra]);
    }

    /* Hiển thị form duyệt mượn dụng cụ:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'nhanvien, 'dungcu'
    #   - View: admin-dungcu-duyetmuon
    */
    public function getDuyetMuon($id){
        
        $sukien = SuKien::find($id);

        $nhanvien = NhanVien::get();

        $dungcu = SuDung::join('dungcu','sudung.dc_ma','dungcu.dc_ma')
            ->where('sudung.sk_ma',$id)
            ->where('sudung.sd_trangthai',0)
            ->selectRaw('dungcu.dc_ma as dc_ma, dungcu.dc_ten as dc_ten,
                sudung.sd_soluongmuon as sd_soluongmuon')
            ->get();

        return view('admin.dungcu.duyetmuon',['sukien'=>$sukien, 'nhanvien'=>$nhanvien, 'dungcu'=>$dungcu]);
    }

    /* Xử lý form duyệt mượn dụng cụ:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-dungcu-danhsachyeucau
    */
    public function postDuyetMuon(Request $request ,$id){

        $this->validate($request,
            [
                'txtNgayMuon'=>'required'
            ],
            [
                'txtNgayMuon.required'=>'Chưa nhập ngày mượn !'
            ]
        );

        $nhanvien = NhanVien::where('tk_ma',Session::get('back_login_mataikhoan'))->first();

        $sudung = SuDung::where('sudung.sk_ma',$id)
            ->where('sudung.sd_trangthai',0)
            ->get();

        foreach($sudung as $sd ){
            DB::table('sudung')
                ->where('sk_ma',$id)
                ->where('dc_ma',$sd->dc_ma)
                ->update(['sd_ngaymuon'=>$request->txtNgayMuon,
                        'nv_muon'=>$request->slNhanVienMuon,
                        'nv_ghinhan'=>$nhanvien->nv_ma,
                        'sd_trangthai'=>1
                    ]);
        }
        
        return redirect('admin/dungcu/danhsachyeucau')->with('thongbao','Duyệt yêu cầu mượn thành công !');
    }

    /* Hiển thị form duyệt trả dụng cụ:
    #   - Vào: $id
    #   - Ra: object 'sukien', 'nhanvien, 'info', 'dungcu'
    #   - View: admin-dungcu-duyettra
    */
    public function getDuyetTra($id){
        
        $sukien = SuKien::find($id);

        $nhanvien = NhanVien::get();

        $info = SuDung::join('nhanvien','sudung.nv_muon','nhanvien.nv_ma')
            ->where('sudung.sk_ma',$id)
            ->where('sudung.sd_trangthai',1)
            ->selectRaw('nhanvien.nv_tennhanvien as nv_tennhanvien, sudung.sd_ngaymuon as sd_ngaymuon')
            ->first();

        $dungcu = SuDung::join('dungcu','sudung.dc_ma','dungcu.dc_ma')
            ->where('sudung.sk_ma',$id)
            ->where('sudung.sd_trangthai',1)
            ->selectRaw('dungcu.dc_ma as dc_ma, dungcu.dc_ten as dc_ten,
                sudung.sd_soluongmuon as sd_soluongmuon')
            ->get();

        return view('admin.dungcu.duyettra',['sukien'=>$sukien, 'nhanvien'=>$nhanvien, 'info'=>$info, 'dungcu'=>$dungcu]);
    }

    /* Xử lý form duyệt trả dụng cụ:
    #   - Vào: $request form, $id
    #   - Ra: session 'thongbao'
    #   - View: admin-dungcu-danhsachyeucau
    */
    public function postDuyetTra(Request $request ,$id){

        $this->validate($request,
            [
                'txtNgayTra'=>'required'
            ],
            [
                'txtNgayTra.required'=>'Chưa nhập ngày trả !'
            ]
        );

        $nhanvien = NhanVien::where('tk_ma',Session::get('back_login_mataikhoan'))->first();

        $sudung = SuDung::where('sudung.sk_ma',$id)
            ->where('sudung.sd_trangthai',1)
            ->get();

        $count = 0;
        foreach($sudung as $sd ){
            DB::table('sudung')
                ->where('sk_ma',$id)
                ->where('dc_ma',$sd->dc_ma)
                ->update(['sd_soluongtra'=>$request->soluongtra[$count],
                        'sd_ngaytra'=>$request->txtNgayTra,
                        'nv_tra'=>$request->slNhanVienTra,
                        'sd_ghichu'=>$request->txtGhiChu,
                        'nv_ghinhan'=>$nhanvien->nv_ma,
                        'sd_trangthai'=>2
                    ]);

            $dungcu = DungCu::find($sd->dc_ma);
            $dungcu->dc_soluongconlai = $dungcu->dc_soluongconlai + $request->soluongtra[$count];
            $dungcu->save();

            $count++;
        }
        
        return redirect('admin/dungcu/danhsachyeucau')->with('thongbao','Duyệt yêu cầu trả thành công !');
    }


    /*================================Quyền===========================================*/
    /* Hiển thị danh sách quyền:
    #   - Vào:
    #   - Ra: object 'quyen'
    #   - View: admin-quyen-danhsach
    */
    public function getDanhsachQ(){
        $quyen = Quyen::where('q_trangthai',1)->get();

        return view('admin.quyen.danhsach',['quyen'=>$quyen]);
    }

    /* Hiển thị form thêm mới quyền:
    #   - Vào:
    #   - Ra:
    #   - View: admin-quyen-themmoi
    */
    public function getThemmoiQ(){
        return view('admin.quyen.themmoi');
    }

    /* Xử lý form thêm mới quyền:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-quyen-themmoi
    */
    public function postThemmoiQ(Request $request){
        $this->validate($request,
            [
                'txtTenQuyen'=>'required|unique:quyen,q_ten'
            ],
            [
                'txtTenQuyen.required'=>'Chưa nhập tên quyền hệ thống !',
                'txtTenQuyen.unique'=>'Tên quyền hệ thống đã tồn tại !'
            ]
        );

        $quyen = new Quyen;
        $quyen->q_ten = $request->txtTenQuyen;
        $quyen->q_trangthai = 1;
        $quyen->save();

        return redirect('admin/quyen/themmoi')->with('thongbao','Thêm mới quyền hệ thống thành công !');
    }

    /* Hiển thị form phân quyền:
    #   - Vào: $id
    #   - Ra: object 'quyen', 'chucnang', 'quyen_chucnang'
    #   - View: admin-quyen-phanquyen
    */
    public function getPhanquyenQ($id){

        $quyen = Quyen::find($id);

        $chucnang = ChucNang::whereNull('cn_cha')
            ->where('cn_trangthai',1)
            ->get();

        $quyen_chucnang = Quyen::join('quyen_chucnang','quyen.q_ma','quyen_chucnang.q_ma')
            ->where('quyen.q_ma',$id)
            ->selectRaw('quyen_chucnang.q_ma as q_ma, quyen_chucnang.cn_ma as cn_ma')
            ->get();

        return view('admin.quyen.phanquyen',['quyen'=>$quyen,'chucnang'=>$chucnang,'quyen_chucnang'=>$quyen_chucnang]);
    }

    /* Xử lý form phân quyền:
    #   - Vào: $request form, $id
    #   - Ra: session 'thongbao'
    #   - View: admin-quyen-phanquyen
    */
    public function postPhanquyenQ(Request $request, $id){

        Quyen_Chucnang::where('q_ma',$id)->delete();

        for($i=0; $i < count($request->slChucNang); $i++){
            $quyen_chucnang = new Quyen_Chucnang;
            $quyen_chucnang->q_ma = $id;
            $quyen_chucnang->cn_ma = $request->slChucNang[$i];
            $quyen_chucnang->save();
        }

        return redirect('admin/quyen/phanquyen/'.$id)->with('thongbao','Phân quyền thành công !');
    }

    /* Hiển thị form cập nhật quyền:
    #   - Vào: $id
    #   - Ra: object 'quyen'
    #   - View: admin-quyen-capnhat
    */
    public function getCapnhatQ($id){
        $quyen = Quyen::find($id);

        return view('admin.quyen.capnhat',['quyen'=>$quyen]);
    }

    /* Xử lý form cập nhật quyền:
    #   - Vào: $request from, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-quyen-capnhat
    */
    public function postCapnhatQ(Request $request, $id){
        $this->validate($request,
            [
                'txtTenQuyen'=>'required|unique:quyen,q_ten,'.$id.',q_ma'
            ],
            [
                'txtTenQuyen.required'=>'Chưa nhập tên quyền hệ thống !',
                'txtTenQuyen.unique'=>'Tên quyền hệ thống đã tồn tại !'
            ]
        );

        $quyen = Quyen::find($id);
        $quyen->q_ten = $request->txtTenQuyen;
        $quyen->save();

        return redirect('admin/quyen/capnhat/'.$id)->with('thongbao','Cập nhật quyền hệ thống thành công !');
    }

    /* Xử lý xóa quyền:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-quyen-danhsach
    */
    public function getXoaQ($id){
        $quyen = Quyen::find($id);
        $quyen->q_trangthai = 0;
        $quyen->save();

        return redirect('admin/quyen/danhsach')->with('thongbao',"Xóa quyền hệ thống '$quyen->q_ten' thành công !");
    }


    /*================================Chức năng===========================================*/
    /* Hiển thị danh sách chức năng:
    #   - Vào:
    #   - Ra: object 'chucnang'
    #   - View: admin-chucnang-danhsach
    */
    public function getDanhsachCN(){

        $chucnang = ChucNang::where('cn_trangthai',1)->get();

        return view('admin.chucnang.danhsach',['chucnang'=>$chucnang]);
    }

    /* Hiển thị form thêm mới chức năng:
    #   - Vào:
    #   - Ra:
    #   - View: admin-chucnang-themmoi
    */
    public function getThemmoiCN(){
        return view('admin.chucnang.themmoi');
    }

    /* Xử lý form thêm mới chức năng:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-chucnang-themmoi
    */
    public function postThemmoiCN(Request $request){
        $this->validate($request,
            [
                'txtTenChucNang'=>'required|unique:chucnang,cn_ten',
                'txtLienKet'=>'required',
                'txtBieuTuong'=>'required',
                'txtViTri'=>'required'
            ],
            [
                'txtTenChucNang.required'=>'Chưa nhập tên chức năng !',
                'txtTenChucNang.unique'=>'Tên chức năng đã tồn tại !',
                'txtLienKet.required'=>'Chưa nhập liên kết !',
                'txtBieuTuong.required'=>'Chưa nhập biểu tượng !',
                'txtViTri.required'=>'Chưa nhập vị trí !'
            ]
        );

        $chucnang = new ChucNang;
        $chucnang->cn_ten = $request->txtTenChucNang;
        $chucnang->cn_lienket = $request->txtLienKet;
        $chucnang->cn_bieutuong = $request->txtBieuTuong;
        $chucnang->cn_vitri = $request->txtViTri;
        $chucnang->cn_cha = $request->txtCha;
        $chucnang->cn_trangthai = 1;
        $chucnang->save();

        return redirect('admin/chucnang/themmoi')->with('thongbao','Thêm mới chức năng thành công !');
    }

    /* Hiển thị form cập nhật chức năng:
    #   - Vào: $id
    #   - Ra: object 'chucnang'
    #   - View: admin-chucnang-capnhat
    */
    public function getCapnhatCN($id){

        $chucnang = ChucNang::find($id);

        return view('admin.chucnang.capnhat',['chucnang'=>$chucnang]);
    }

    /* Xử lý form cập nhật chức năng:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-chucnang-capnhat
    */
    public function postCapnhatCN(Request $request, $id){
        $this->validate($request,
            [
                'txtTenChucNang'=>'required|unique:chucnang,cn_ten,'.$id.',cn_ma',
                'txtLienKet'=>'required',
                'txtBieuTuong'=>'required',
                'txtViTri'=>'required'
            ],
            [
                'txtTenChucNang.required'=>'Chưa nhập tên chức năng !',
                'txtTenChucNang.unique'=>'Tên chức năng đã tồn tại !',
                'txtLienKet.required'=>'Chưa nhập liên kết !',
                'txtBieuTuong.required'=>'Chưa nhập biểu tượng !',
                'txtViTri.required'=>'Chưa nhập vị trí !'
            ]
        );


        $chucnang = ChucNang::find($id);
        $chucnang->cn_ten = $request->txtTenChucNang;
        $chucnang->cn_lienket = $request->txtLienKet;
        $chucnang->cn_bieutuong = $request->txtBieuTuong;
        $chucnang->cn_vitri = $request->txtViTri;
        $chucnang->cn_cha = $request->txtCha;
        $chucnang->cn_trangthai = 1;
        $chucnang->save();

        return redirect('admin/chucnang/capnhat/'.$id)->with('thongbao','Cập nhật chức năng thành công !');
    }

    /* Xử lý xóa chức năng:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-chucnang-danhsach
    */
    public function getXoaCN($id){
        $chucnang = ChucNang::find($id);
        $chucnang->cn_trangthai = 0;
        $chucnang->save();

        return redirect('admin/chucnang/danhsach')->with('thongbao',"Xóa chức năng '$chucnang->cn_ten' thành công !");
    }


    /*================================Biểu mẫu===========================================*/
    /* Hiển thị danh sách biểu mẫu:
    #   - Vào:
    #   - Ra: object 'bieumau'
    #   - View: admin-bieumau-danhsach
    */
    public function getDanhsachBM(){

        $bieumau = BieuMau::where('bm_trangthai',1)->get();

        return view('admin.bieumau.danhsach',['bieumau'=>$bieumau]);
    }    

    /* Hiển thị form thêm mới biểu mẫu:
    #   - Vào:
    #   - Ra:
    #   - View: admin-bieumau-themmoi
    */
    public function getThemmoiBM(){
        return view('admin.bieumau.themmoi');
    }

    /* Xử lý form thêm mới biểu mẫu:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-bieumau-themmoi
    */
    public function postThemmoiBM(Request $request){
        $this->validate($request,
            [
                'txtTenBieuMau'=>'required|unique:bieumau,bm_ten',
                'txtNoidung'=>'required'
            ],
            [
                'txtTenBieuMau.required'=>'Chưa nhập tên biểu mẫu !',
                'txtTenBieuMau.unique'=>'Tên biểu mẫu đã tồn tại !',
                'txtNoidung.required'=>'Chưa nhập nội dung biểu mẫu !'
            ]
        );


        $bieumau = new BieuMau;
        $bieumau->bm_ten = $request->txtTenBieuMau;
        $bieumau->bm_noidung = $request->txtNoidung;
        $bieumau->bm_trangthai = 1;
        $bieumau->save();

        return redirect('admin/bieumau/themmoi')->with('thongbao','Thêm mới biểu mẫu thành công !');
    }

    /* Hiển thị form cập nhật biểu mẫu:
    #   - Vào: $id
    #   - Ra: object 'bieumau'
    #   - View: admin-bieumau-capnhat
    */
    public function getCapnhatBM($id){

        $bieumau = BieuMau::find($id);

        return view('admin.bieumau.capnhat',['bieumau'=>$bieumau]);
    }

    /* Xử lý form cập nhật biểu mẫu:
    #   - Vào: $request form, $id
    #   - Ra: errors, session 'thongbao'
    #   - View: admin-bieumau-capnhat
    */
    public function postCapnhatBM(Request $request, $id){
        $this->validate($request,
            [
                'txtTenBieuMau'=>'required|unique:bieumau,bm_ten,'.$id.',bm_ma',
                'txtNoidung'=>'required'
            ],
            [
                'txtTenBieuMau.required'=>'Chưa nhập tên biểu mẫu !',
                'txtTenBieuMau.unique'=>'Tên biểu mẫu đã tồn tại !',
                'txtNoidung.required'=>'Chưa nhập nội dung biểu mẫu !'
            ]
        );

        $bieumau = BieuMau::find($id);
        $bieumau->bm_ten = $request->txtTenBieuMau;
        $bieumau->bm_saoluu = $bieumau->bm_noidung;
        $bieumau->bm_noidung = $request->txtNoidung;
        $bieumau->bm_trangthai = 1;
        $bieumau->save();

        return redirect('admin/bieumau/capnhat/'.$id)->with('thongbao','Cập nhật biểu mẫu thành công !');
    }

    /* Xử lý phục hồi biểu mẫu:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-bieumau-capnhat
    */
    public function getPhuchoiBM($id){
        $bieumau = BieuMau::find($id);
        $bieumau->bm_noidung = $bieumau->bm_saoluu;
        $bieumau->bm_saoluu = null;
        $bieumau->save();

        return redirect('admin/bieumau/capnhat/'.$id)->with('thongbao',"Phục hồi biểu mẫu thành công !");
    }

    /* Xử lý xóa biểu mẫu:
    #   - Vào: $id
    #   - Ra: session 'thongbao'
    #   - View: admin-bieumau-danhsach
    */
    public function getXoaBM($id){
        $bieumau = BieuMau::find($id);
        $bieumau->bm_trangthai = 0;
        $bieumau->save();

        return redirect('admin/bieumau/danhsach')->with('thongbao',"Xóa biểu mẫu '$bieumau->bm_ten' thành công !");
    }


    /*================================Thống kê thu chi===========================================*/
    /* Hiển thị biểu đồ thống kê thu chi: get
    #   - Vào:
    #   - Ra: $tungay, $denngay, $thuvao, $chira
    #   - View: admin-thongkethuchi-bieudo
    */
    public function getBieudoTKTC(){

        $tungay = '2018-01-01';
        $denngay = date("Y-m-d");

        $value_thu = HopDongToChucSuKien::whereBetween('hdtcsk_ngaytaohopdong',[$tungay,$denngay])->get();
        $thuvao = 0;
        foreach ($value_thu as $vthu) {
            $thuvao += $vthu->hdtcsk_giatrihopdong;
        }

        $value_chi = PhieuNhap::join('chitietphieunhap','phieunhap.pn_maphieunhap','chitietphieunhap.pn_maphieunhap')
            ->whereBetween('phieunhap.pn_ngaynhap',[$tungay,$denngay])
            ->selectRaw('chitietphieunhap.ctpn_soluong as ctpn_soluong, chitietphieunhap.ctpn_dongia as ctpn_dongia')
            ->get();
        $chira = 0;
        foreach ($value_chi as $vchi) {
            $chira += $vchi->ctpn_soluong * $vchi->ctpn_dongia;
        }

        return view('admin.thongkethuchi.bieudo',['tungay'=>$tungay, 'denngay'=>$denngay, 'thuvao'=>$thuvao, 'chira'=>$chira]);
    }   

    /* Hiển thị biểu đồ thống kê thu chi: post
    #   - Vào: $request form
    #   - Ra: $tungay, $denngay, $thuvao, $chira
    #   - View: admin-thongkethuchi-bieudo
    */
    public function postBieudoTKTC(Request $request){
        $this->validate($request,
            [
                'txtTuNgay'=>'required',
                'txtDenNgay'=>'required'
            ],
            [
                'txtTuNgay.required'=>'Chưa nhập: từ ngày !',
                'txtDenNgay.required'=>'Chưa nhập: đến ngày !'
            ]
        );

        $tungay = $request->txtTuNgay;
        $denngay = $request->txtDenNgay;

        $value_thu = HopDongToChucSuKien::whereBetween('hdtcsk_ngaytaohopdong',[$tungay,$denngay])->get();
        $thuvao = 0;
        foreach ($value_thu as $vthu) {
            $thuvao += $vthu->hdtcsk_giatrihopdong;
        }

        $value_chi = PhieuNhap::join('chitietphieunhap','phieunhap.pn_maphieunhap','chitietphieunhap.pn_maphieunhap')
            ->whereBetween('phieunhap.pn_ngaynhap',[$tungay,$denngay])
            ->selectRaw('chitietphieunhap.ctpn_soluong as ctpn_soluong, chitietphieunhap.ctpn_dongia as ctpn_dongia')
            ->get();
        $chira = 0;
        foreach ($value_chi as $vchi) {
            $chira += $vchi->ctpn_soluong * $vchi->ctpn_dongia;
        }

        return view('admin.thongkethuchi.bieudo',['tungay'=>$tungay, 'denngay'=>$denngay, 'thuvao'=>$thuvao, 'chira'=>$chira]);
    } 
    
}
