<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EOnOffWithDate extends Model
{
	use OnOffDeletes;

	public $timestamps = false;
    protected $table = 'onoff_standart';
	protected $fillable = ['e_name'];
}
