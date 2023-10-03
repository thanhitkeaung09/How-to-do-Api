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
        Schema::create('post_shares', function (Blueprint $table) {
            $table->id();
//            $table->unsignedBigInteger('user_id')->unsigned();
//            $table->foreign("user_id")->references("id")->on("users");
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
//            $table->unsignedBigInteger("post_id")->unsigned();
//            $table->foreign("post_id")->references("id")->on("posts");
            $table->foreignId('post_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean("isShare")->default(true);
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
        Schema::dropIfExists('post_shares');
    }
};
