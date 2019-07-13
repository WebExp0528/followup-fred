<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emails', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('from_email')->nullable();
			$table->string('from')->nullable();
			$table->string('to')->nullable();
			$table->string('cc')->nullable();
			$table->string('bcc')->nullable();
			$table->string('reply_id')->nullable();
			$table->string('message_id')->nullable();
			$table->string('thread_id')->nullable();
			$table->string('subject')->nullable();
			$table->text('body', 16777215)->nullable();
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
		Schema::drop('emails');
	}

}
