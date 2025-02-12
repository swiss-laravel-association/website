<?php

use App\Models\Country;
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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('complement')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('postal_code');
            $table->string('city');
            $table->string('canton')->nullable();
            $table->foreignIdFor(Country::class);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->integer('capacity')->unsigned()->nullable();
            $table->string('main_image')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. Only for test/dev purposes. Delete before sending to production.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
