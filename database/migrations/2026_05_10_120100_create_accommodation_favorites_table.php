<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $connection = config('database.default');

        Schema::connection($connection)->create('accommodation_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('accommodation_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'accommodation_id']);
        });
    }

    public function down(): void
    {
        Schema::connection(config('database.default'))->dropIfExists('accommodation_favorites');
    }
};
