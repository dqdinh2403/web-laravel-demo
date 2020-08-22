<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChucNang extends Model
{
    protected $table = 'chucnang';
    protected $primaryKey = 'cn_ma';
    public $timestamps = false;

    public function quyen(){
    	return $this->belongsToMany('App\Quyen','quyen_chucnang','cn_ma','q_ma');
    }
}
