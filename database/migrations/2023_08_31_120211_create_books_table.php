<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(false);
            $table->string('isbn')->unique()->nullable(false);
            $table->string('title')->nullable(false);
            $table->string('editor')->nullable(false);
            $table->string('production')->nullable(false);
            $table->date('publish_date')->nullable(false);
            $table->unsignedDouble('lending_price')->nullable(false);
            $table->unsignedDouble('selling_price')->nullable(false);

            $table->softDeletes();
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
        Schema::dropIfExists('books');
    }
}
