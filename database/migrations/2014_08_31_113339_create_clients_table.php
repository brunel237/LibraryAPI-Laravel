<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->enum("sex", ["male", "female"])->nullable(false);
            $table->date("date_of_birth")->nullable(false);
            $table->string('email')->nullable(false)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->string('address')->nullable(false);
            $table->string('profession')->nullable(false);

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
        Schema::dropIfExists('clients');
    }
}
