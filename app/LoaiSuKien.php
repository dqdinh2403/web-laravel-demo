<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiSuKien extends Model
{
    protected $table = 'loaisukien';
    protected $primaryKey = 'lsk_ma';
    public $timestamps = false;

    public function sukien(){
    	return $this->hasMany('App\SuKien','lsk_ma','lsk_ma');
    }
}
