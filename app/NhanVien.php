<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    protected $table = 'nhanvien';
    protected $primaryKey = 'nv_ma';
    public $timestamps = false;

    public function users(){
    	return $this->belongsTo('App\User','tk_ma','tk_ma');
    }

    public function hopdongtochucsukien_nvtaohopdong(){
    	return $this->hasMany('App\HopDongToChucSuKien','nv_taohopdong','nv_ma');
    }

    public function hopdongtochucsukien_nvchiutrachnhiem(){
    	return $this->hasMany('App\HopDongToChucSuKien','nv_chiutrachnhiem','nv_ma');
    }

    public function phieunhap(){
    	return $this->hasMany('App\PhieuNhap','nv_lapphieu','nv_ma');
    }

    public function sukien_congviec_nhanvien(){
    	return $this->hasMany('App\Sukien_Congviec_Nhanvien','nv_ma','nv_ma');
    }

    public function sudung_nvmuon(){
    	return $this->hasMany('App\SuDung','nv_muon','nv_ma');
    }

    public function sudung_nvtra(){
    	return $this->hasMany('App\SuDung','nv_tra','nv_ma');
    }

    public function sudung_nvghinhan(){
    	return $this->hasMany('App\SuDung','nv_ghinhan','nv_ma');
    }
}
