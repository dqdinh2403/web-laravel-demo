<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuNhap extends Model
{
    protected $table = 'chitietphieunhap';
    protected $primaryKey = ['pn_maphieunhap','dc_ma'];
    public $timestamps = false;
    public $incrementing = false;
}
