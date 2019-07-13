<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFollowupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('followups', function(Blueprint $table)
		{
			$table->integer('id', true );
			$table->string('sender_email',100)->nullable();
			$table->string('to_emails',500)->nullable();
			$table->integer('send_timestamp')->default(0);
			$table->integer('email_id')->default(0);
			$table->string('send_dateTime')->nullable();
			$table->integer('followup_type');
			$table->string('thread_id',100);
			$table->string('message_id',100);
			$table->integer('sent_count')->default(0);
			$table->integer('status')->default(0);
			$table->tinyInteger('responded')->default(0);
			$table->dateTime('completed_at')->nullable();
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
		Schema::drop('followups');
	}

}
