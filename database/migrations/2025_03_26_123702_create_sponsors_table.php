<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 50);
            $table->string('name');
            $table->string('website');
            $table->unsignedSmallInteger('order')->default(999);
            $table->timestamps();
        });
    }
};
