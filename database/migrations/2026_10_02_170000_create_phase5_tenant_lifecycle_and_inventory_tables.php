<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Additive tables for Phase 5: Tenant Lifecycle, Verification, Rent Collection, PG & Coworking Inventory.
     */
    public function up(): void
    {
        // 1. Tenant Applications
        if (! Schema::hasTable('tenant_applications')) {
            Schema::create('tenant_applications', function (Blueprint $table) {
                $table->id();
                $table->string('application_number')->unique();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
                $table->decimal('monthly_income', 12, 2)->nullable();
                $table->string('occupation')->nullable();
                $table->string('employer_name')->nullable();
                $table->date('proposed_move_in')->nullable();
                $table->unsignedInteger('tenancy_months')->default(11);
                $table->string('status')->default('SUBMITTED'); // DRAFT, SUBMITTED, UNDER_REVIEW, APPROVED, REJECTED, WITHDRAWN
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['property_id', 'status']);
                $table->index(['tenant_id', 'status']);
            });
        }

        // 2. Tenant Screenings (Consent-first)
        if (! Schema::hasTable('tenant_screenings')) {
            Schema::create('tenant_screenings', function (Blueprint $table) {
                $table->id();
                $table->string('screening_number')->unique();
                $table->foreignId('tenant_application_id')->nullable()->constrained('tenant_applications')->nullOnDelete();
                $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->string('status')->default('CONSENT_REQUIRED'); // DRAFT, SUBMITTED, CONSENT_REQUIRED, CONSENTED, DOCUMENTS_REQUIRED, DOCUMENTS_SUBMITTED, UNDER_REVIEW, VERIFICATION_IN_PROGRESS, VERIFIED, PARTIALLY_VERIFIED, FAILED, EXPIRED, CANCELLED
                $table->timestamp('consent_granted_at')->nullable();
                $table->string('consent_ip_address')->nullable();
                $table->string('consent_purpose')->nullable();
                $table->string('employment_status')->nullable();
                $table->decimal('monthly_income_verified', 12, 2)->nullable();
                $table->string('id_verification_status')->default('PENDING'); // PENDING, IN_PROGRESS, VERIFIED, FAILED
                $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('reviewer_notes')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->index(['tenant_id', 'status']);
                $table->index(['property_id', 'status']);
            });
        }

        // 3. Verification Documents (Private Storage & OCR Metadata)
        if (! Schema::hasTable('verification_documents')) {
            Schema::create('verification_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_screening_id')->nullable()->constrained('tenant_screenings')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('document_type'); // aadhaar, pan, passport, driving_licence, voter_id, salary_slip, bank_statement
                $table->string('document_number_masked')->nullable(); // e.g. XXXX XXXX 1234
                $table->string('file_path'); // stored on private non-public disk
                $table->string('original_filename')->nullable();
                $table->string('ocr_status')->default('PENDING'); // PENDING, PROCESSING, SUCCESS, FAILED, SKIPPED
                $table->json('ocr_extracted_data')->nullable();
                $table->string('verification_status')->default('PENDING'); // PENDING, VERIFIED, REJECTED
                $table->string('rejection_reason')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->timestamps();

                $table->index(['tenant_screening_id', 'document_type']);
                $table->index(['user_id', 'verification_status']);
            });
        }

        // 4. Rent Accounts (Tenancy Agreement & Recurring Rent Terms)
        if (! Schema::hasTable('rent_accounts')) {
            Schema::create('rent_accounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
                $table->date('lease_start_date')->nullable();
                $table->date('lease_end_date')->nullable();
                $table->decimal('monthly_rent', 10, 2);
                $table->decimal('security_deposit', 10, 2)->nullable();
                $table->unsignedSmallInteger('due_day_of_month')->default(5);
                $table->string('status')->default('ACTIVE'); // ACTIVE, PAUSED, TERMINATED
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['property_id', 'status']);
                $table->index(['tenant_id', 'status']);
                $table->index(['owner_id', 'status']);
            });
        }

        // 5. Rent Ledgers (Monthly Billing Periods)
        if (! Schema::hasTable('rent_ledgers')) {
            Schema::create('rent_ledgers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('rent_account_id')->constrained('rent_accounts')->cascadeOnDelete();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
                $table->string('billing_period'); // e.g. "2026-10"
                $table->date('due_date');
                $table->decimal('amount_due', 10, 2);
                $table->decimal('amount_paid', 10, 2)->default(0.00);
                $table->decimal('outstanding_balance', 10, 2);
                $table->string('status')->default('DUE'); // DUE, PARTIALLY_PAID, PAID, OVERDUE
                $table->boolean('is_late')->default(false);
                $table->decimal('late_fee', 10, 2)->default(0.00);
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->unique(['rent_account_id', 'billing_period']);
                $table->index(['tenant_id', 'status']);
                $table->index(['owner_id', 'status']);
            });
        }

        // 6. Rent Payments (Idempotent Transaction Records)
        if (! Schema::hasTable('rent_payments')) {
            Schema::create('rent_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('rent_ledger_id')->constrained('rent_ledgers')->cascadeOnDelete();
                $table->foreignId('rent_account_id')->constrained('rent_accounts')->cascadeOnDelete();
                $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
                $table->decimal('amount', 10, 2);
                $table->string('payment_method')->default('upi');
                $table->string('gateway_order_id')->nullable();
                $table->string('gateway_payment_id')->nullable();
                $table->string('gateway_signature')->nullable();
                $table->string('idempotency_key')->unique();
                $table->string('status')->default('INITIATED'); // PENDING, INITIATED, AUTHORIZED, PAID, FAILED, CANCELLED, REFUNDED, PARTIALLY_REFUNDED, RECONCILIATION_REQUIRED
                $table->timestamp('paid_at')->nullable();
                $table->string('failure_reason')->nullable();
                $table->timestamps();

                $table->index(['rent_ledger_id', 'status']);
                $table->index(['tenant_id', 'status']);
            });
        }

        // 7. PG Rooms
        if (! Schema::hasTable('pg_rooms')) {
            Schema::create('pg_rooms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->string('room_number');
                $table->string('floor')->nullable();
                $table->string('room_type')->default('double'); // single, double, triple, four_sharing
                $table->string('gender_preference')->default('unisex'); // male, female, unisex
                $table->decimal('monthly_rent_per_bed', 10, 2);
                $table->decimal('deposit_per_bed', 10, 2)->nullable();
                $table->json('amenities')->nullable();
                $table->string('status')->default('active'); // active, maintenance
                $table->timestamps();

                $table->index(['property_id', 'status']);
            });
        }

        // 8. PG Beds
        if (! Schema::hasTable('pg_beds')) {
            Schema::create('pg_beds', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pg_room_id')->constrained('pg_rooms')->cascadeOnDelete();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->string('bed_code'); // e.g. "R101-A"
                $table->string('status')->default('AVAILABLE'); // AVAILABLE, HELD, RESERVED, OCCUPIED, NOTICE_PERIOD, MAINTENANCE, BLOCKED
                $table->timestamp('held_until')->nullable();
                $table->foreignId('held_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique(['pg_room_id', 'bed_code']);
                $table->index(['property_id', 'status']);
            });
        }

        // 9. PG Bookings
        if (! Schema::hasTable('pg_bookings')) {
            Schema::create('pg_bookings', function (Blueprint $table) {
                $table->id();
                $table->string('booking_number')->unique();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->foreignId('pg_room_id')->constrained('pg_rooms')->cascadeOnDelete();
                $table->foreignId('pg_bed_id')->constrained('pg_beds')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->date('check_in_date');
                $table->decimal('monthly_rent', 10, 2);
                $table->decimal('deposit_amount', 10, 2)->default(0.00);
                $table->string('status')->default('CONFIRMED'); // HELD, CONFIRMED, CANCELLED, COMPLETED
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['property_id', 'status']);
                $table->index(['user_id', 'status']);
            });
        }

        // 10. Coworking Inventories (Desks, Cabins, Meeting Rooms)
        if (! Schema::hasTable('coworking_inventories')) {
            Schema::create('coworking_inventories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->string('inventory_code'); // e.g. "DESK-01", "CABIN-A"
                $table->string('inventory_type'); // hot_desk, dedicated_desk, private_cabin, meeting_room
                $table->unsignedInteger('capacity')->default(1);
                $table->decimal('hourly_rate', 10, 2)->nullable();
                $table->decimal('daily_rate', 10, 2)->nullable();
                $table->decimal('monthly_rate', 10, 2)->nullable();
                $table->json('amenities')->nullable();
                $table->string('status')->default('AVAILABLE'); // AVAILABLE, MAINTENANCE, BLOCKED
                $table->timestamps();

                $table->unique(['property_id', 'inventory_code']);
                $table->index(['property_id', 'inventory_type', 'status'], 'cw_inv_prop_type_stat_idx');
            });
        }

        // 11. Coworking Bookings
        if (! Schema::hasTable('coworking_bookings')) {
            Schema::create('coworking_bookings', function (Blueprint $table) {
                $table->id();
                $table->string('booking_number')->unique();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->foreignId('coworking_inventory_id')->constrained('coworking_inventories')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('booking_type'); // hourly, daily, monthly
                $table->dateTime('start_datetime');
                $table->dateTime('end_datetime');
                $table->unsignedInteger('duration_units')->default(1);
                $table->decimal('total_amount', 10, 2);
                $table->string('status')->default('CONFIRMED'); // HELD, RESERVED, CONFIRMED, ACTIVE, COMPLETED, CANCELLED, BLOCKED
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['coworking_inventory_id', 'start_datetime', 'end_datetime'], 'cw_bk_inv_dates_idx');
                $table->index(['property_id', 'status']);
                $table->index(['user_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coworking_bookings');
        Schema::dropIfExists('coworking_inventories');
        Schema::dropIfExists('pg_bookings');
        Schema::dropIfExists('pg_beds');
        Schema::dropIfExists('pg_rooms');
        Schema::dropIfExists('rent_payments');
        Schema::dropIfExists('rent_ledgers');
        Schema::dropIfExists('rent_accounts');
        Schema::dropIfExists('verification_documents');
        Schema::dropIfExists('tenant_screenings');
        Schema::dropIfExists('tenant_applications');
    }
};
