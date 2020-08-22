<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sukien_Doitac extends Model
{
    protected $table = 'sukien_doitac';
    protected $primaryKey = ['sk_ma','dt_ma'];
    public $timestamps = false;
    public $incrementing = false;
}
