<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quyen_Chucnang extends Model
{
    protected $table = 'quyen_chucnang';
    protected $primaryKey = ['q_ma','cn_ma'];
    public $timestamps = false;
    public $incrementing = false;
}
