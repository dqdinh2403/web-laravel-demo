<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DungCu extends Model
{
    protected $table = 'dungcu';
    protected $primaryKey = 'dc_ma';
    public $timestamps = false;

    public function phieunhap(){
    	return $this->belongsToMany('App\PhieuNhap','chitietphieunhap','dc_ma','pn_maphieunhap')
    				->withPivot('ctpn_soluong','ctpn_dongia');
    }

    public function sukien(){
    	return $this->belongsToMany('App\SuKien','sudung','dc_ma','sk_ma')
    				->withPivot('sd_soluongmuon','sd_ngaymuon','nv_muon','sd_soluongtra','sd_ngaytra','nv_tra',
    					'sd_ghichu','nv_ghinhan','sd_trangthai');
    }
}
