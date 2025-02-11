<?php

use App\Models\Event;
use App\Models\User;
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
        Schema::create('talks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignIdFor(Event::class)->nullable();
            $table->foreignIdFor(User::class)->nullable();
            $table->string('banner_image')->nullable();
            $table->json('images')->nullable();
            // $table->json('tags')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('order_column')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talks');
    }
};
