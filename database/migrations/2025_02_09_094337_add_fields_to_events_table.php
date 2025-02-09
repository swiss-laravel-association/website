<?php

use App\Models\User;
use App\Models\Location;
use App\Models\EventType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('is_published');
            $table->foreignIdFor(EventType::class)->nullable()->after('banner_image');
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
