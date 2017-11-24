<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('segment', 200)->comment('摘抄');
            $table->string('author', 20)->comment('作者');
            $table->string('article', 2000)->comment('原文')->nullable();
            $table->string('description', 2000)->comment('解析')->nullable();
            $table->string('thoughts', 2000)->comment('感想')->nullable();
            $table->string('setting', 500)->comment('设置')->nullable();
            $table->string('tag', 500)->comment('标签')->nullable();
            $table->tinyInteger('cate')->comment('分类');
            $table->timestamps();
            $table->index('author');
            $table->index('tag');
            $table->index('cate');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segments');
    }
}
