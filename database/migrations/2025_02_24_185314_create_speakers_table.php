<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speakers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->string('website')->nullable();
            $table->string('github_profile')->nullable();
            $table->string('x_profile')->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->string('bluesky_profile')->nullable();
            $table->string('youtube_profile')->nullable();
            $table->timestamps();
        });
    }
};
