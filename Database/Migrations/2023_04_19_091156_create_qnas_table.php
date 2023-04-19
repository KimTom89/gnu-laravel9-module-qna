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
        Schema::create('qnas', function (Blueprint $table) {
            $table->comment('qa_content => qnas');
            $table->id();
            $table->integer('related_qna_id')->nullable()->default(0);
            $table->integer('qna_category_id')->nullable()->default(0);
            $table->string('mb_id', 60)->default('');
            $table->string('user_name')->nullable()->default('');
            $table->string('user_email')->nullable()->default('');
            $table->string('user_hp')->default('');
            $table->tinyInteger('is_receive_email')->default(0);
            $table->tinyInteger('is_receive_sms')->default(0);
            $table->tinyInteger('html')->default(0);
            $table->string('subject')->default('');
            $table->text('content');
            $table->tinyInteger('status')->default(0);
            $table->string('ip')->default('');
            $table->string('answer_subject')->nullable();
            $table->text('answer_content')->nullable();
            $table->dateTime('answer_date')->nullable();
            $table->string('extra_1')->default('');
            $table->string('extra_2')->default('');
            $table->string('extra_3')->default('');
            $table->string('extra_4')->default('');
            $table->string('extra_5')->default('');
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
        Schema::dropIfExists('qnas');
    }
};
