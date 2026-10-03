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
        // 1. Care Service Categories
        if (! Schema::hasTable('care_service_categories')) {
            Schema::create('care_service_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('icon')->default('fa-solid fa-wrench');
                $table->boolean('is_emergency_available')->default(false);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->decimal('base_price', 10, 2)->nullable();
                $table->string('pricing_type')->default('quote_based');
                $table->timestamps();
            });
        }

        // 2. Vendor Profiles
        if (! Schema::hasTable('vendor_profiles')) {
            Schema::create('vendor_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('business_name')->nullable();
                $table->json('service_categories')->nullable();
                $table->json('service_areas')->nullable();
                $table->string('verification_status')->default('unverified');
                $table->boolean('is_available')->default(true);
                $table->decimal('rating', 3, 2)->default(5.00);
                $table->integer('jobs_completed')->default(0);
                $table->timestamps();
            });
        }

        // 3. Service Requests
        if (! Schema::hasTable('service_requests')) {
            Schema::create('service_requests', function (Blueprint $table) {
                $table->id();
                $table->string('request_number')->unique();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->foreignId('service_category_id')->constrained('care_service_categories')->cascadeOnDelete();
                $table->foreignId('assigned_vendor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->text('description');
                $table->string('priority')->default('normal'); // low, normal, high, emergency
                $table->string('status')->default('SUBMITTED');
                $table->date('preferred_date')->nullable();
                $table->string('preferred_time_window')->nullable();
                $table->string('contact_preference')->nullable();
                $table->json('photos')->nullable();
                $table->string('video_url')->nullable();
                $table->text('admin_notes')->nullable(); // Strictly internal
                $table->text('qc_notes')->nullable();
                $table->integer('rating')->nullable();
                $table->text('review')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('triaged_at')->nullable();
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->timestamps();
            });
        }

        // 4. Service Quotes
        if (! Schema::hasTable('service_quotes')) {
            Schema::create('service_quotes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
                $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
                $table->string('quote_number')->unique();
                $table->decimal('labour_amount', 10, 2)->default(0);
                $table->decimal('materials_amount', 10, 2)->default(0);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('other_charges', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2);
                $table->string('estimated_duration')->nullable();
                $table->text('notes')->nullable();
                $table->date('valid_until')->nullable();
                $table->string('status')->default('PENDING'); // PENDING, APPROVED, DECLINED, SUPERSEDED
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->decimal('approved_amount', 10, 2)->nullable();
                $table->timestamps();
            });
        }

        // 5. Work Orders
        if (! Schema::hasTable('work_orders')) {
            Schema::create('work_orders', function (Blueprint $table) {
                $table->id();
                $table->string('work_order_number')->unique();
                $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
                $table->foreignId('service_quote_id')->nullable()->constrained('service_quotes')->nullOnDelete();
                $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->string('status')->default('SCHEDULED'); // SCHEDULED, IN_PROGRESS, COMPLETED, QC_PENDING, QC_COMPLETED
                $table->timestamp('scheduled_at')->nullable();
                $table->text('work_notes')->nullable();
                $table->json('before_photos')->nullable();
                $table->json('after_photos')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('qc_approved_at')->nullable();
                $table->timestamps();
            });
        }

        // 6. Management Plans
        if (! Schema::hasTable('management_plans')) {
            Schema::create('management_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('pricing_model')->default('percentage'); // percentage, fixed
                $table->decimal('fee_percentage', 5, 2)->nullable();
                $table->decimal('fixed_fee', 10, 2)->nullable();
                $table->string('billing_frequency')->default('monthly');
                $table->json('included_services')->nullable();
                $table->json('sla_details')->nullable();
                $table->text('terms_content')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 7. Management Enrollments
        if (! Schema::hasTable('management_enrollments')) {
            Schema::create('management_enrollments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->foreignId('management_plan_id')->constrained('management_plans')->cascadeOnDelete();
                $table->string('status')->default('REQUESTED'); // REQUESTED, UNDER_REVIEW, AGREEMENT_PENDING, AWAITING_OWNER_ACCEPTANCE, ACTIVE, PAUSED, CANCELLED, EXPIRED
                $table->string('agreement_reference')->nullable();
                $table->json('pricing_snapshot')->nullable();
                $table->timestamp('agreement_accepted_at')->nullable();
                $table->string('agreement_accepted_ip')->nullable();
                $table->timestamp('requested_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('activated_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'property_id']);
            });
        }

        // 8. Service Audit Logs
        if (! Schema::hasTable('service_audit_logs')) {
            Schema::create('service_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->string('auditable_type');
                $table->unsignedBigInteger('auditable_id');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('event');
                $table->json('details')->nullable();
                $table->timestamps();

                $table->index(['auditable_type', 'auditable_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_audit_logs');
        Schema::dropIfExists('management_enrollments');
        Schema::dropIfExists('management_plans');
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('service_quotes');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('vendor_profiles');
        Schema::dropIfExists('care_service_categories');
    }
};
