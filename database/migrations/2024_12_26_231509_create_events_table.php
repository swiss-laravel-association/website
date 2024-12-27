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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
                /** TODO: add all the other fields and relation */
            $table->string('name')->nullable();
            $table->string('api_code', 200)->nullable();
            $table->dateTime('sign_in_start')->nullable();
            $table->dateTime('sign_in_end')->nullable();
            $table->timestamps();
        });

        Schema::create('event_has_users', function (Blueprint $table) {
            $table->foreignIdFor(Event::class);
            $table->foreignIdFor(User::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_has_users');
        Schema::dropIfExists('events');
    }
};
