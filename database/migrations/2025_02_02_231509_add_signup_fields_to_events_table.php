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
        Schema::table('events', function (Blueprint $table) {
                /** TODO: add all the other fields and relation */
            $table->string('api_code', 500)->nullable(); // has to be long enough for the encrypted value
            $table->dateTime('sign_in_start')->nullable();
            $table->dateTime('sign_in_end')->nullable();
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
        Schema::dropColumns('events', ['api_code', 'sign_in_start', 'sign_in_end']);
    }
};
