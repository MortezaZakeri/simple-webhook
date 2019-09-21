<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebhooksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('token');
            $table->text('url');
            $table->string('verb', 10)->default('POST');
            $table->uuid('user_id');
//            $table->unique(['user_id', 'url', 'method']); //each user can register in one webhook by one method
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('webhooks');
    }
}
