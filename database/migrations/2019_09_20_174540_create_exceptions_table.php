<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExceptionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('exceptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('method');
            $table->text('message')->nullable();
            $table->unsignedInteger('code')->default(0);
            $table->string('developer_message')->nullable();
            $table->unsignedTinyInteger('risk')->default(1);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('exceptions');
    }
}
