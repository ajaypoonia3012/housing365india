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
        // 1. Add additive referral and UPI fields to users table
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 50)->nullable()->unique()->after('remember_token');
            }
            if (! Schema::hasColumn('users', 'upi_id')) {
                $table->string('upi_id', 100)->nullable()->after('referral_code');
            }
            if (! Schema::hasColumn('users', 'upi_id_encrypted')) {
                $table->text('upi_id_encrypted')->nullable()->after('upi_id');
            }
        });

        // 2. Referrals table
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('referral_code', 50)->index();
            $table->string('submission_method', 30)->default('refer_owner'); // refer_owner, property_board_photo
            $table->string('owner_name', 150);
            $table->string('owner_phone', 50);
            $table->string('normalized_owner_phone', 50)->index();
            $table->string('owner_email', 150)->nullable();
            $table->string('normalized_owner_email', 150)->nullable()->index();
            $table->string('state', 100)->nullable();
            $table->string('city', 100);
            $table->string('locality', 150);
            $table->string('property_type', 100);
            $table->string('listing_intent', 50)->default('rent'); // rent, sale, pg, coliving, commercial, land
            $table->text('property_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('referrer_relationship', 100)->nullable();
            $table->string('photo_path', 255)->nullable(); // Private storage path
            $table->string('photo_mime', 50)->nullable();
            $table->unsignedInteger('photo_size')->nullable();
            $table->string('status', 50)->default('SUBMITTED')->index(); // SUBMITTED, UNDER_REVIEW, CONTACTED, QUALIFIED, PROPERTY_LISTED, REWARD_PENDING, REWARD_APPROVED, PAYOUT_PENDING, PAID, REJECTED, CANCELLED
            $table->string('qualification_status', 50)->default('PENDING');
            $table->boolean('duplicate_flag')->default(false)->index();
            $table->boolean('fraud_flag')->default(false)->index();
            $table->json('fraud_reasons')->nullable();
            $table->foreignId('existing_property_id')->nullable()->constrained('properties')->nullOnDelete();
            $table->foreignId('qualified_property_id')->nullable()->constrained('properties')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('qualified_at')->nullable();
            $table->timestamp('property_listed_at')->nullable();
            $table->timestamp('reward_eligible_at')->nullable();
            $table->timestamps();
        });

        // 3. Referral Payouts table
        Schema::create('referral_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_number', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('INR');
            $table->string('payout_method', 50)->default('UPI');
            $table->string('payout_destination_masked', 100);
            $table->text('payout_destination_encrypted')->nullable();
            $table->string('status', 50)->default('PENDING')->index(); // PENDING, APPROVED, PROCESSING, PAID, FAILED, ON_HOLD
            $table->string('provider', 50)->default('ADMIN_MANUAL');
            $table->string('provider_reference', 100)->nullable();
            $table->string('transaction_reference', 100)->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // 4. Referral Rewards ledger table (Immutable financial ledger)
        Schema::create('referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_id')->constrained('referrals')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->default(100.00);
            $table->string('currency', 10)->default('INR');
            $table->string('status', 50)->default('PENDING')->index(); // PENDING, APPROVED, ON_HOLD, PAID, CANCELLED
            $table->string('reward_type', 50)->default('PROPERTY_PUBLISHED');
            $table->string('reason', 255)->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('payout_id')->nullable()->constrained('referral_payouts')->nullOnDelete();
            $table->timestamps();

            // Enforce strictly 1 reward per referral and reward_type
            $table->unique(['referral_id', 'reward_type'], 'uniq_referral_reward_type');
        });

        // 5. Referral Audit Logs table
        Schema::create('referral_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_id')->constrained('referrals')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event', 100);
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50)->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_audit_logs');
        Schema::dropIfExists('referral_rewards');
        Schema::dropIfExists('referral_payouts');
        Schema::dropIfExists('referrals');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'upi_id', 'upi_id_encrypted']);
        });
    }
};
