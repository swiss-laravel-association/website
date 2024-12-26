<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid()
                ->default(Str::uuid())
                ->after('id');
            $table->string('pepper', 64)
                ->default('') // new Expression('(CONCAT(MD5(RAND()), MD5(RAND())))')
                ->after('password');
        });

            // spice all existing users!
        User::upsert(
            User::all()
                ->map(fn(User $user) => [
                    ...$user->only(['id', 'name', 'email', 'password']),
                    'pepper' => Str::random(64)
                ])->toArray(),
            'id'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pepper');
            $table->dropColumn('uuid');
        });
    }
};
