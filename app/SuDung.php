<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuDung extends Model
{
    protected $table = 'sudung';
    protected $primaryKey = ['sk_ma','dc_ma'];
    public $timestamps = false;
    public $incrementing = false;

    public function nhanvien_nvmuon(){
    	return $this->belongsTo('App\NhanVien','nv_muon','nv_ma');
    }

    public function nhanvien_nvtra(){
    	return $this->belongsTo('App\NhanVien','nv_tra','nv_ma');
    }

    public function nhanvien_nvghinhan(){
    	return $this->belongsTo('App\NhanVien','nv_ghinhan','nv_ma');
    }
}
