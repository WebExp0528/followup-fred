<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FollowupLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('followup_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('followup_id')->nullable();
			$table->string('email',100)->nullable();
			$table->string('to_emails',100)->nullable();
			$table->string('sent_dateTime', 100)->nullable();
      $table->string('subject', 200)->nullable();
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
		Schema::drop('followup_logs');
    }
}
