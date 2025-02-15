<?php

use App\Models\EventType;
use App\Models\Location;
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
            $table->string('banner_image')->nullable()->after('is_published');
            $table->string('images')->nullable()->after('banner_image');
            $table->foreignIdFor(EventType::class)->nullable()->after('images');
            $table->foreignIdFor(User::class)->nullable()->after('event_type_id');
            $table->foreignIdFor(Location::class)->nullable()->after('user_id');
            $table->string('location')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations, only for test/dev purposes. Delete before sending to production.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('banner_image');
            $table->dropColumn('event_type_id');
            $table->dropColumn('user_id');
            $table->string('location')->change();

        });
    }
};
