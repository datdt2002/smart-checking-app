<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checking_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10);
        });

        Schema::create('check_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->timestamp('datetime');
            $table->integer('checking_type_id');
            $table->integer('shift_id');
            $table->integer('status_id');
        });

        Schema::table('check_logs', function (Blueprint $table) {
            $table->foreign('checking_type_id')->references('id')->on('checking_types')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_logs', function (Blueprint $table) {
            $table->dropForeign('check_logs_checking_type_id_foreign');
            $table->dropForeign('check_logs_shift_id_foreign');
            $table->dropForeign('check_logs_user_id_foreign');
            $table->dropForeign('check_logs_status_id_foreign');
        });
        Schema::dropIfExists('check_logs');
        Schema::dropIfExists('checking_types');
    }
};
