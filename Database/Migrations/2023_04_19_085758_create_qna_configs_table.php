<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qna_configs', function (Blueprint $table) {
            $table->comment('g5_qa_config => qna_configs');
            $table->string('title')->default('');
            $table->string('category')->default('');
            $table->integer('use_category_depth')->default(1);
            $table->string('skin')->default('');
            $table->string('mobile_skin')->default('');
            $table->tinyInteger('use_email')->default(0);
            $table->tinyInteger('req_email')->default(0);
            $table->tinyInteger('use_hp')->default(0);
            $table->tinyInteger('req_hp')->default(0);
            $table->tinyInteger('use_sms')->default(0);
            $table->string('send_number')->nullable()->default('0');
            $table->string('admin_hp')->nullable()->default('');
            $table->string('admin_email')->nullable()->default('');
            $table->tinyInteger('use_editor')->default(0);
            $table->integer('subject_length')->default(0);
            $table->integer('mobile_subject_length')->default(0);
            $table->integer('page_rows')->default(0);
            $table->integer('mobile_page_rows')->default(0);
            $table->integer('image_width')->default(0);
            $table->integer('upload_file_size')->default(0);
            $table->integer('upload_file_count')->default(0);
            $table->text('insert_content')->nullable();
            $table->string('include_head')->nullable()->default('');
            $table->string('include_tail')->nullable()->default('');
            $table->text('content_head')->nullable();
            $table->text('content_tail')->nullable();
            $table->text('mobile_content_head')->nullable();
            $table->text('mobile_content_tail')->nullable();
            $table->string('extra_1_subj')->nullable()->default('');
            $table->string('extra_2_subj')->nullable()->default('');
            $table->string('extra_3_subj')->nullable()->default('');
            $table->string('extra_4_subj')->nullable()->default('');
            $table->string('extra_5_subj')->nullable()->default('');
            $table->string('extra_1')->nullable()->default('');
            $table->string('extra_2')->nullable()->default('');
            $table->string('extra_3')->nullable()->default('');
            $table->string('extra_4')->nullable()->default('');
            $table->string('extra_5')->nullable()->default('');
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
        Schema::dropIfExists('qna_configs');
    }
};
