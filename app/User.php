<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'tk_ma';
    public $timestamps = false;

    public function quyen(){
        return $this->belongsTo('App\Quyen','q_ma','q_ma');
    }

    public function nhanvien(){
        return $this->hasOne('App\NhanVien','tk_ma','tk_ma');
    }

    public function khachhang(){
        return $this->hasOne('App\KhachHang','tk_ma','tk_ma');
    }

    public function gopy(){
        return $this->hasMany('App\GopY','tk_ma','tk_ma');
    }
}
