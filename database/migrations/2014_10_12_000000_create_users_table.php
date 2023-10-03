<?php

use App\Enums\Language;
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
        Schema::create('users', function (Blueprint $table) {
            //            \Illuminate\Support\Facades\DB::statement('SET SESSION sql_require_primary_key=0');
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('social_id')->unique()->nullable();
            $table->string('social_type')->nullable();
            $table->string('profile_image')->nullable();
            $table->enum('language', Language::values())->default(Language::EN->value)->nullable();
            $table->string('device_token')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
