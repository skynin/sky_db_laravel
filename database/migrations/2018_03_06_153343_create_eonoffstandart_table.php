<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEonoffstandartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
	{
		Schema::create('onoff_standart', function (Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('e_onoff')->default(64);
			$table->string('e_name');
			$table->softDeletes();
		});
	}

	/**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('onoff_standart');
    }
}
