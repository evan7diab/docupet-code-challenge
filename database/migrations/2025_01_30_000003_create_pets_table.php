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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sex', 10); // male | female
            $table->foreignId('breed_id')->nullable()->constrained()->nullOnDelete();
            $table->string('breed_text')->nullable();
            $table->boolean('breed_unknown')->default(false);
            $table->date('dob')->nullable();
            $table->unsignedTinyInteger('approx_age_years')->nullable(); // 1..20
            $table->boolean('is_dangerous')->default(false); // snapshot at save time
            $table->timestamps();

            $table->index(['type_id', 'breed_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
