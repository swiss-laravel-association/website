<?php

use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talk_speaker', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Talk::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Speaker::class)->constrained();
            $table->timestamps();
        });
    }
};
