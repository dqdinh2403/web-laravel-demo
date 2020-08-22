<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sukien_Congviec_Nhanvien extends Model
{
    protected $table = 'sukien_congviec_nhanvien';
    protected $primaryKey = ['sk_ma','cv_ma'];
    public $timestamps = false;
    public $incrementing = false;

    public function nhanvien(){
    	return $this->belongsTo('App\NhanVien','nv_ma','nv_ma');
    }
}
