<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HopDongToChucSuKien extends Model
{
    protected $table = 'hopdongtochucsukien';
    protected $primaryKey = 'hdtcsk_sohopdong';
    public $timestamps = false;
    public $incrementing = false;

    public function khachhang(){
    	return $this->belongsTo('App\KhachHang','kh_ma','kh_ma');
    }

    public function nhanvien_nvtaohopdong(){
    	return $this->belongsTo('App\NhanVien','nv_taohopdong','nv_ma');
    }

    public function nhanvien_nvchiutrachnhiem(){
    	return $this->belongsTo('App\NhanVien','nv_chiutrachnhiem','nv_ma');
    }

    public function bieumau(){
    	return $this->belongsTo('App\BieuMau','bm_ma','bm_ma');
    }

    public function sukien(){
    	return $this->hasMany('App\SuKien','hdtcsk_sohopdong','hdtcsk_sohopdong');
    }
}
