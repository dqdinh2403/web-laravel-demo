<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuKien extends Model
{
    protected $table = 'sukien';
    protected $primaryKey = 'sk_ma';
    public $timestamps = false;

    public function hopdongtochucsukien(){
    	return $this->belongsTo('App\HopDongToChucSuKien','hdtcsk_sohopdong','hdtcsk_sohopdong');
    }

    public function loaisukien(){
    	return $this->belongsTo('App\LoaiSuKien','lsk_ma','lsk_ma');
    }

    public function hinhanh(){
    	return $this->hasMany('App\HinhAnh','sk_ma','sk_ma');
    }

    public function doitac(){
    	return $this->belongsToMany('App\DoiTac','sukien_doitac','sk_ma','dt_ma')
    				->withPivot('sk_dt_thanhtoan');
    }

    public function congviec(){
    	return $this->belongsToMany('App\CongViec','sukien_congviec_nhanvien','sk_ma','cv_ma')
    				->withPivot('nv_ma','sk_cv_nv_soluongnhanvien','sk_cv_nv_ghichu','sk_cv_nv_trangthai');
    }

    public function dungcu(){
    	return $this->belongsToMany('App\DungCu','sudung','sk_ma','dc_ma')
    				->withPivot('sd_soluongmuon','sd_ngaymuon','nv_muon','sd_soluongtra','sd_ngaytra','nv_tra',
    					'sd_ghichu','nv_ghinhan','sd_trangthai');
    }
}
