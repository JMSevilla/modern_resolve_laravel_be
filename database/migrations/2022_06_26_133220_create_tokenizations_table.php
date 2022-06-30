<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokenizations', function (Blueprint $table) {
            $table->increments('tokenID');
            $table->integer('userID');
            $table->string('token', 255);
            $table->string('lastRoute', 100);
            $table->char('isDestroyed', 1);
            $table->char('isvalid', 1);
            $table->timestamp('createdAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokenizations');
    }
};
