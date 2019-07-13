<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('timezone');
			$table->string('email',100)->unique();
			$table->string('password')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->string('phone_number', 20)->nullable();
			$table->integer('plan_id')->nullable();
			$table->string('stripe_id')->nullable();
			$table->string('card_brand')->nullable();
			$table->string('card_last_four')->nullable();
			$table->dateTime('trial_ends_at')->nullable();
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
		Schema::drop('users');
	}

}
