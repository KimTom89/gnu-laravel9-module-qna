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
        Schema::create('qna_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qna_id')->default(0)->comment('QnA id');
            $table->string('path')->default('');
            $table->string('file_name')->default('');
            $table->integer('file_size')->default(0)->comment('byte 단위');
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
        Schema::dropIfExists('qna_files');
    }
};
