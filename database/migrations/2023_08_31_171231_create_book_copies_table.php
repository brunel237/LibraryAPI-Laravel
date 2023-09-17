<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id')->unique()->nullable(false);
            $table->unsignedInteger('stock_quantity')->nullable(false);
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
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
        Schema::dropIfExists('book_copies');
    }
}
