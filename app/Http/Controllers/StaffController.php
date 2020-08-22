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

/* Phần xử lý chức năng của nhân viên*/
class StaffController extends Controller
{
    
    /* Hiển thị form cập nhật thông tin cá nhân:
    #   - Vào:
    #   - Ra: object 'nhanvien'
    #   - View: staff-ttnhanvien
    */
    public function getThongtinNV(){
        $nhanvien = User::join('nhanvien','users.tk_ma','nhanvien.tk_ma')->where('users.tk_ma',Session::get('front_login_mataikhoan'))
            ->selectRaw('nhanvien.nv_ma as nv_ma, nhanvien.nv_tennhanvien as nv_tennhanvien, nhanvien.nv_gioitinh as nv_gioitinh, 
            	nhanvien.nv_diachi as nv_diachi, nhanvien.nv_dienthoai as nv_dienthoai, nhanvien.nv_email as nv_email, 
            	nhanvien.nv_ngaysinh as nv_ngaysinh, nhanvien.nv_cmnd as nv_cmnd, 
            	users.tk_ma as tk_ma, users.tk_tendangnhap as tk_tendangnhap')->first();
      
        return view('staff.ttnhanvien',['nhanvien'=>$nhanvien]);
    }

    /* Xử lý form cập nhật thông tin cá nhân:
    #   - Vào: $request form
    #   - Ra: errors, session 'thongbao', 'loi'
    #   - View: staff-ttnhanvien
    */
    public function postThongtinNV(Request $request){
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

            $taikhoan = User::find(Session::get('front_login_mataikhoan'));
            if($taikhoan->tk_matkhau == md5($request->txtMatKhauOld)){
            	$taikhoan->tk_matkhau = md5($request->txtMatKhau1);
            	$taikhoan->save();
            }
            else{
            	return redirect('staff/ttnhanvien')->with('loi','Mật khẩu cũ không chính xác !');
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

        return redirect('staff/ttnhanvien')->with('thongbao','Cập nhật thông tin cá nhân thành công !');
    }

    /* Hiển thị bảng tham gia sự kiện:
    #   - Vào:
    #   - Ra: object 'sukien'
    #   - View: staff-thamgiasukien
    */
    public function getThamgiasukien(){
        $sukien = SuKien::join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->where('sukien.sk_thoigianbatdaud','>',date("Y-m-d"))
            ->whereIn('sukien.sk_trangthai',[3,4])
            ->whereNull('sukien_congviec_nhanvien.nv_ma')
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->where('congviec.cv_trangthai',1)
            ->selectRaw('sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, sukien.sk_diadiem as sk_diadiem, sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, 
                    sukien_congviec_nhanvien.sk_cv_nv_soluongnhanvien as sk_cv_nv_soluongnhanvien,
                    congviec.cv_ma as cv_ma, congviec.cv_ten as cv_ten')
            ->get();
            
        return view('staff.thamgiasukien',['sukien'=>$sukien]);
    }

    /* Xử lý form tham gia sự kiện:
    #   - Vào: $skma, $cvma
    #   - Ra: session 'thongbao', 'loi'
    #   - View: staff-thamgiasukien
    */
    public function getXLthamgiasukien($skma, $cvma){

        $nhanvien = NhanVien::where('tk_ma',Session::get('front_login_mataikhoan'))->first();

        $check = Sukien_Congviec_Nhanvien::where('sk_ma',$skma)->where('cv_ma',$cvma)->first();

        if(!is_null($check->nv_ma)){
            return redirect('staff/thamgiasukien')->with('loi','Công việc của sự kiện này đã có người tham gia !');
        }
        else{
            DB::table('sukien_congviec_nhanvien')
                ->where('sk_ma',$skma)
                ->where('cv_ma',$cvma)
                ->update(['nv_ma'=>$nhanvien->nv_ma]);

            return redirect('staff/thamgiasukien')->with('thongbao','Đăng ký tham gia sự kiện thành công !');
        }
    }

    /* Hiển thị form ghi chú sự kiện:
    #   - Vào: $skma, $cvma
    #   - Ra: object 'sukien'
    #   - View: staff-ghichusukien
    */
    public function getGhichuSK($skma, $cvma){
        $nhanvien = NhanVien::where('tk_ma',Session::get('front_login_mataikhoan'))->first();
        
        $sukien = Sukien::join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->where('sukien_congviec_nhanvien.sk_ma',$skma)
            ->where('sukien_congviec_nhanvien.cv_ma',$cvma)
            ->where('sukien_congviec_nhanvien.nv_ma',$nhanvien->nv_ma)
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->selectRaw('sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, 
                congviec.cv_ma as cv_ma, congviec.cv_ten as cv_ten')
            ->first();

        return view('staff.ghichusukien',['sukien'=>$sukien]);
    }

    /* Xử lý form ghi chú sự kiện:
    #   - Vào: $request form, $skma, $cvma
    #   - Ra: session 'thongbao'
    #   - View: staff-lichsuthamgia
    */
    public function postGhichuSK(Request $request, $skma, $cvma){
        $nhanvien = NhanVien::where('tk_ma',Session::get('front_login_mataikhoan'))->first();

        DB::table('sukien_congviec_nhanvien')
                ->where('sk_ma',$skma)
                ->where('cv_ma',$cvma)
                ->where('nv_ma',$nhanvien->nv_ma)
                ->update(['sk_cv_nv_ghichu'=>$request->txtGhiChu, 'sk_cv_nv_trangthai'=>1]);

        return redirect('staff/lichsuthamgia')->with('thongbao','Thêm ghi chú sự kiện thành công !');
    }

    /* Hiển thị lịch sử tham gia sự kiện:
    #   - Vào:
    #   - Ra: object 'sukien'
    #   - View: staff-lichsuthamgia
    */
    public function getLichsuthamgia(){
        $nhanvien = NhanVien::where('tk_ma',Session::get('front_login_mataikhoan'))->first();
        
        $sukien = Sukien::join('sukien_congviec_nhanvien','sukien.sk_ma','sukien_congviec_nhanvien.sk_ma')
            ->where('sukien_congviec_nhanvien.nv_ma',$nhanvien->nv_ma)
            ->join('congviec','sukien_congviec_nhanvien.cv_ma','congviec.cv_ma')
            ->selectRaw('sukien.sk_ma as sk_ma, sukien.sk_ten as sk_ten, sukien.sk_thoigianbatdaud as sk_thoigianbatdaud, 
                congviec.cv_ma as cv_ma, congviec.cv_ten as cv_ten, sukien_congviec_nhanvien.sk_cv_nv_trangthai as sk_cv_nv_trangthai')
            ->get();

        return view('staff.lichsuthamgia',['sukien'=>$sukien]);
    }

}
