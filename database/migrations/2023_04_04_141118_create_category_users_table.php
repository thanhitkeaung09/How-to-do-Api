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
        Schema::create('category_users', function (Blueprint $table) {
            $table->id();
//            $table->unsignedBigInteger('user_id')->unsigned();
//            $table->foreign("user_id")->references("id")->on("users");
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
//            $table->unsignedBigInteger("category_id")->unsigned();
//            $table->foreign("category_id")->references("id")->on("categories");
            $table->foreignId('category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('category_users');
    }
};
