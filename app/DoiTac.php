<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoiTac extends Model
{
    protected $table = 'doitac';
    protected $primaryKey = 'dt_ma';
    public $timestamps = false;

    public function sukien(){
    	return $this->belongsToMany('App\SuKien','sukien_doitac','dt_ma','sk_ma')
    				->withPivot('sk_dt_thanhtoan');
    }
}
