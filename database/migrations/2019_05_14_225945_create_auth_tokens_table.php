<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('auth_tokens', function(Blueprint $table)
		{
			$table->integer('id', true)->autoIncrement();
			$table->string('service')->nullable();
			$table->string('access_token', 555)->nullable();
			$table->string('refresh_token', 555)->nullable();
			$table->integer('user_id')->nullable();
			$table->string('email',100)->unique();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('auth_tokens');
	}

}
