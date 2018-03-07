<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EOnOffStandart extends Model
{
	use SoftDeletes;

	public $timestamps = false;
    protected $table = 'onoff_standart';
	protected $fillable = ['e_name'];

}
