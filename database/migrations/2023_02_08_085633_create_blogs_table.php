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
        Schema::create("blogs", function(Blueprint $table){
           $table->bigIncrements('id');
           $table->string('author', 255);
           $table->bigInteger('author_id');
           $table->text('image');
           $table->string('title', 255);
           $table->text('content');
           $table->timestamp('created_at')->nullable()->default(null);
           $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};
