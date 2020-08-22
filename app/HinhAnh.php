<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HinhAnh extends Model
{
    protected $table = 'hinhanh';
    protected $primaryKey = 'ha_ma';
    public $timestamps = false;

    public function sukien(){
    	return $this->belongsTo('App\SuKien','sk_ma','sk_ma');
    }
}
