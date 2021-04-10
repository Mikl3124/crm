<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuotesTable extends Migration
{

	public function up()
	{
		Schema::create('quotes', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('project_id')->unsigned();
			$table->string('filename');
			$table->string('url');
			$table->string('token');
			$table->integer('amount');
			$table->boolean('accepted')->default(false);
		});
	}

	public function down()
	{
		Schema::drop('quotes');
	}
}