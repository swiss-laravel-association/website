<?php

use App\Models\Tag;
use App\Models\Talk;
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
        Schema::create('talk_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId(Talk::class);
            $table->foreignId(Tag::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talk_tags');
    }
};
