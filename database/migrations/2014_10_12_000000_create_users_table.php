<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable(false);
            $table->string('password')->nullable(false);
            $table->enum("role", ["user", "admin"])->nullable()->default("user");
            $table->boolean("isEligible")->default(true);
            $table->unsignedBigInteger('client_id')->nullable(false);

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
