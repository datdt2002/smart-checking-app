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
        Schema::create('genders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10);
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('firstname', 30);
            $table->string('lastname', 30);
            $table->boolean('active')->default(false);
            $table->string('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('gender_id')->nullable();
            $table->string('indentity', 20)->unique()->nullable();
            $table->string('mobile', 10)->unique()->nullable();
            $table->string('address', 500)->nullable();
            $table->string('origin_place', 500)->nullable();
            $table->integer('department_id')->nullable()->nullable();
            $table->integer('status_id')->nullable();
            $table->timestamp('contract_started')->nullable();
            $table->timestamp('contract_finished')->nullable();
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('gender_id')->references('id')->on('genders')->onDelete('cascade');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign('departments_manager_id_foreign');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_department_id_foreign');
            $table->dropForeign('users_status_id_foreign');
            $table->dropForeign('users_gender_id_foreign');
        });
        Schema::dropIfExists('users');
        Schema::dropIfExists('genders');
    }
};
