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
        Schema::create('m_d_r_users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('username', 255);
            $table->string('password', 255);
            $table->char('userType', 1);
            $table->char('isLock', 1);
            $table->string('imgURL', 255);
            $table->string('occupationStatus', 100);
            $table->string('occupationDetails', 255);
            $table->string('occupationPositionWork', 100);
            $table->string('nameofschool', 100);
            $table->string('degree', 100);
            $table->string('address', 100);
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
        Schema::dropIfExists('m_d_r_users');
    }
};
