<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GopY extends Model
{
    protected $table = 'gopy';
    protected $primaryKey = 'gy_ma';
    public $timestamps = false;

    public function users(){
    	return $this->belongsTo('App\User','tk_ma','tk_ma');
    }
}
