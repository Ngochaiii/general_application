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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->string('username')->unique();
            $table->string('refresh_token')->nullable();
            $table->string('password')->nullable();
            $table->string('password_hash')->nullable();
            $table->string('email')->unique();
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email_send')->nullable();
            $table->string('email_reply')->nullable();
            $table->longText('email_footer')->nullable();
            $table->string('cccd')->unique();
            $table->integer('created_by')->nullable();
            $table->boolean('active')->nullable();
            $table->boolean('is_root')->default(0);
            $table->integer('role_id')->nullable();
            $table->string('avatar')->nullable();
            $table->char('department_id', 50)->nullable();
            $table->string('user_uid')->nullable();
            $table->char('position_id', 50)->nullable();
            $table->longText('mail_footer')->nullable();
            $table->text('role_id')->nullable()->change();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
