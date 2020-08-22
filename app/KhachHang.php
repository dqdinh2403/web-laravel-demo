<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    protected $table = 'khachhang';
    protected $primaryKey = 'kh_ma';
    public $timestamps = false;

    public function users(){
    	return $this->belongsTo('App\User','tk_ma','tk_ma');
    }

    public function hopdongtochucsukien(){
    	return $this->hasMany('App\HopDongToChucSuKien','kh_ma','kh_ma');
    }
}
