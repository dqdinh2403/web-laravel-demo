<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhieuNhap extends Model
{
    protected $table = 'phieunhap';
    protected $primaryKey = 'pn_maphieunhap';
    public $timestamps = false;
    public $incrementing = false;

    public function nhanvien(){
    	return $this->belongsTo('App\NhanVien','nv_lapphieu','nv_ma');
    }

    public function nhacungcap(){
    	return $this->belongsTo('App\NhaCungCap','ncc_ma','ncc_ma');
    }

    public function bieumau(){
    	return $this->belongsTo('App\BieuMau','bm_ma','bm_ma');
    }

    public function dungcu(){
    	return $this->belongsToMany('App\DungCu','chitietphieunhap','pn_maphieunhap','dc_ma')
    				->withPivot('ctpn_soluong','ctpn_dongia');
    }
}
