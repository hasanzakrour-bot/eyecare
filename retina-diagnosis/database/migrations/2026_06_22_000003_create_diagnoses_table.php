<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('image_path');
            $table->string('predicted_class')->nullable();
            $table->decimal('confidence', 6, 5)->default(0);
            $table->enum('risk_level', ['low', 'medium', 'high', 'unknown'])->default('unknown');
            $table->json('probabilities')->nullable();
            $table->json('api_response')->nullable();
            $table->text('recommendation')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->text('doctor_decision')->nullable();
            $table->string('model_name')->nullable();
            $table->string('model_version')->nullable();
            $table->enum('status', ['completed', 'reviewed', 'failed'])->default('completed');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['risk_level', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
