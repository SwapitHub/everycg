<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailerContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailer_contact', function (Blueprint $table) {
            $table->id();
			$table->string('name', 100);
            $table->string('Company', 60)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('email', 255)->unique();
            $table->string('remember_token', 100)->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('mailer_contact');
    }
}
