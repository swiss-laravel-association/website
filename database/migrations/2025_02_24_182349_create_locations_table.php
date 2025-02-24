<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('zip_code');
            $table->string('city');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->timestamps();
        });
    }
};
