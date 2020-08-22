<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quyen extends Model
{
    protected $table = 'quyen';
    protected $primaryKey = 'q_ma';
    public $timestamps = false;

    public function users(){
    	return $this->hasMany('App\User','q_ma','q_ma');
    }

    public function chucnang(){
    	return $this->belongsToMany('App\ChucNang','quyen_chucnang','q_ma','cn_ma');
    }
}
