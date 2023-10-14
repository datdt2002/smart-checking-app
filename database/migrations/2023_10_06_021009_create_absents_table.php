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
        Schema::create('absents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->timestamp('started_time');
            $table->timestamp('finished_time');
            $table->integer('shift_id');
            $table->string('reason');
            $table->integer('approver');
            $table->integer('status_id');
        });
        Schema::table('absents', function (Blueprint $table) {
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approver')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absents', function (Blueprint $table) {
            $table->dropForeign('absents_shift_id_foreign');
            $table->dropForeign('absents_user_id_foreign');
            $table->dropForeign('absents_approver_foreign');
            $table->dropForeign('absents_status_id_foreign');
        });
        Schema::dropIfExists('absents');
    }
};
