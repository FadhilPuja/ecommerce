<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('midtrans_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('status');
            $table->json('payload');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('midtrans_histories');
    }
};
