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
        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->string('short_url', 255)->unique(); // Maximum length of 255 characters and unique
            $table->string('long_url', 2048); // Maximum length of 2048 characters
            $table->unsignedBigInteger('user_id')->nullable(); // Nullable user_id field
            $table->integer('total_visits')->default(0); // Default value of 0
            $table->dateTime('expiry_date')->nullable(); // Nullable expiry date
            $table->enum('status', ['active', 'expired', 'deleted'])->default('active'); // Status field with default 'active'
            $table->timestamps(); // This will add `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
