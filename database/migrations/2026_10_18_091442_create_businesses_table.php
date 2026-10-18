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
        Schema::create('businesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('business_category_id')->constrained('business_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug_store')->unique();
            $table->text('description')->nullable();
            $table->string('phone_number');
            $table->string('country_code');
            $table->string('logo')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('order_method')->default('whatsapp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
