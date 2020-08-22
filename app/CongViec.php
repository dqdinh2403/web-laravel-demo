<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CongViec extends Model
{
    protected $table = 'congviec';
    protected $primaryKey = 'cv_ma';
    public $timestamps = false;

    public function sukien(){
    	return $this->belongsToMany('App\SuKien','sukien_congviec_nhanvien','cv_ma','sk_ma')
    				->withPivot('nv_ma','sk_cv_nv_soluongnhanvien','sk_cv_nv_ghichu','sk_cv_nv_trangthai');
    }
}
