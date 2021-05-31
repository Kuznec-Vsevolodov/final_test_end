<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChatTeamUsersMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_teams', function (Blueprint $table) {
            $table->id();
            $table->text('main_user')->default('');
            $table->text('secondary_users')->default('');
            $table->text('messages')->default('');
            $table->integer('price')->default(0);
            $table->integer('round')->default(0);
            $table->integer('tries')->default(0);
            $table->integer('negative-status')->default(0);
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
        //
    }
}
