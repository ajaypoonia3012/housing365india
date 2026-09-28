<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('user_type')->default('tenant')->after('phone'); // tenant, buyer, owner
            $table->string('city')->nullable()->after('user_type');
            $table->boolean('is_prime')->default(false)->after('city');
            $table->string('prime_plan')->nullable()->after('is_prime');
            $table->integer('prime_contacts_limit')->default(0)->after('prime_plan');
            $table->integer('unlocked_contacts_count')->default(0)->after('prime_contacts_limit');
            $table->timestamp('prime_expires_at')->nullable()->after('unlocked_contacts_count');
            $table->string('preferred_localities')->nullable()->after('prime_expires_at');
            $table->string('preferred_bhk')->nullable()->after('preferred_localities');
            $table->string('preferred_budget')->nullable()->after('preferred_bhk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'user_type',
                'city',
                'is_prime',
                'prime_plan',
                'prime_contacts_limit',
                'unlocked_contacts_count',
                'prime_expires_at',
                'preferred_localities',
                'preferred_bhk',
                'preferred_budget',
            ]);
        });
    }
};
