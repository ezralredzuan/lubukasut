<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('EventID')->unique();
            $table->longText('html');
            $table->longText('css');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_contents');
    }
};