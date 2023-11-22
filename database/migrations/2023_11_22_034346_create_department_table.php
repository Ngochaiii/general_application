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
        Schema::create('department', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->integer('parent_id')->nullable();
            $table->integer('manager_by')->nullable();
            $table->integer('manager_name')->nullable();
            $table->string('level')->nullable();
            $table->string('contact')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->boolean('active')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department');
    }
};
