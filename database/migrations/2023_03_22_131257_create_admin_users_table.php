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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
//            $table->unsignedBigInteger('user_id')->unsigned();
//            $table->foreign('user_id')->references('id')->on('users');
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
//            $table->unsignedBigInteger('admin_id')->unsigned();
//            $table->foreign('admin_id')->references('id')->on('admins');
            $table->foreignId('admin_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_follow')->default(true);
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
        Schema::dropIfExists('admin_users');
    }
};
