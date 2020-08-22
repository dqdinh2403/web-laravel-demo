<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    protected $table = 'nhacungcap';
    protected $primaryKey = 'ncc_ma';
    public $timestamps = false;

    public function phieunhap(){
    	return $this->hasMany('App\PhieuNhap','ncc_ma','ncc_ma');
    }
}
