<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EOnOffEmptyDate extends Model
{
	use OnOffDeletes;

	public $timestamps = false;
    protected $table = 'onoff_emptydate';
	protected $fillable = ['e_name'];

	protected $deleted_at_column = false;
}
