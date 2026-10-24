<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('certifications')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->integer('service_radius_km')->default(10);
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->json('available_days')->nullable();
            $table->time('available_from')->nullable();
            $table->time('available_until')->nullable();
            $table->timestamps();
        });
    }
};
