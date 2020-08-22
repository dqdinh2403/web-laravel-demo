<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BieuMau extends Model
{
    protected $table = 'bieumau';
    protected $primaryKey = 'bm_ma';
    public $timestamps = false;

    public function hopdongtochucsukien(){
    	return $this->hasMany('App\HopDongToChucSuKien','bm_ma','bm_ma');
    }

    public function phieunhap(){
    	return $this->hasMany('App\PhieuNhap','bm_ma','bm_ma');
    }
}
