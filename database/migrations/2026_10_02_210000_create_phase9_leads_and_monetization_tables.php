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
        // 1. Leads
        if (! Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inquiry_id')->nullable()->constrained('inquiries')->nullOnDelete();
                $table->string('lead_type')->default('general'); // rental, sale, commercial, land, pg, coliving, coworking, builder_project, broker_listing, care_service, property_management, tenant_service, general
                $table->string('source')->default('web_listing'); // web_listing, instant_showing, viewing_modal, tour_booking, contact_access, care_request, direct_search, campaign, referral
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
                $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('assigned_organization_id')->nullable()->constrained('organizations')->nullOnDelete();
                $table->string('contact_name');
                $table->string('contact_email');
                $table->string('contact_phone')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('status')->default('NEW'); // NEW, ASSIGNED, CONTACTED, QUALIFIED, VIEWING_REQUESTED, VIEWING_SCHEDULED, VIEWING_COMPLETED, APPLICATION_STARTED, APPLICATION_SUBMITTED, CONVERTED, LOST, CLOSED
                $table->string('priority')->default('medium'); // low, medium, high, urgent
                $table->decimal('budget_min', 12, 2)->nullable();
                $table->decimal('budget_max', 12, 2)->nullable();
                $table->string('city')->nullable();
                $table->string('locality')->nullable();
                $table->json('requirements')->nullable();
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->timestamp('first_response_at')->nullable();
                $table->timestamp('sla_deadline_at')->nullable();
                $table->string('sla_status')->default('pending'); // pending, within_sla, breached
                $table->timestamp('sla_breached_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'priority']);
                $table->index(['assigned_user_id', 'status']);
                $table->index(['property_id']);
                $table->index(['lead_type', 'city']);
            });
        }

        // 2. Lead Assignments
        if (! Schema::hasTable('lead_assignments')) {
            Schema::create('lead_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('assigned_to_organization_id')->nullable()->constrained('organizations')->nullOnDelete();
                $table->foreignId('assigned_by_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('assigned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('assignment_type')->default('manual'); // manual, automatic, round_robin, fallback, auto_route
                $table->text('notes')->nullable();
                $table->text('reason')->nullable();
                $table->timestamps();

                $table->index(['lead_id']);
                $table->index(['assigned_user_id']);
                $table->index(['assigned_to_user_id']);
            });
        }

        // 3. Lead Status Histories
        if (! Schema::hasTable('lead_status_histories')) {
            Schema::create('lead_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->string('from_status')->nullable();
                $table->string('to_status');
                $table->foreignId('changed_by_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('reason')->nullable();
                $table->timestamps();

                $table->index(['lead_id']);
            });
        }

        // 4. Lead Activities
        if (! Schema::hasTable('lead_activities')) {
            Schema::create('lead_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('activity_type'); // status_change, assignment, note, call, email, whatsapp, viewing_requested, viewing_scheduled, viewing_completed, application_event, payment_event, followup, system
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['lead_id', 'activity_type']);
            });
        }

        // 5. Lead Notes
        if (! Schema::hasTable('lead_notes')) {
            Schema::create('lead_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->text('content');
                $table->boolean('is_private')->default(false);
                $table->timestamps();

                $table->index(['lead_id']);
            });
        }

        // 6. Lead Tasks / Followups
        if (! Schema::hasTable('lead_tasks')) {
            Schema::create('lead_tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->string('task_type')->default('general'); // call, email, visit, verification, document_collection, general
                $table->timestamp('due_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->string('status')->default('pending'); // pending, completed, cancelled, overdue
                $table->string('priority')->default('medium'); // low, medium, high, urgent
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['lead_id']);
                $table->index(['assigned_to_user_id', 'status']);
            });
        }

        // 7. Lead Routing Rules
        if (! Schema::hasTable('lead_routing_rules')) {
            Schema::create('lead_routing_rules', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('locality')->nullable();
                $table->string('property_type')->nullable();
                $table->string('category')->nullable();
                $table->string('lead_type')->nullable();
                $table->string('routing_method')->default('listing_owner'); // listing_owner, direct_user, round_robin, broker, builder, vendor, fallback
                $table->foreignId('assign_to_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('assign_to_role')->nullable();
                $table->integer('priority')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 8. SLA Rules
        if (! Schema::hasTable('sla_rules')) {
            Schema::create('sla_rules', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('lead_type')->nullable();
                $table->string('priority')->nullable();
                $table->integer('response_time_minutes')->default(60);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 9. Monetization Products
        if (! Schema::hasTable('monetization_products')) {
            Schema::create('monetization_products', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique(); // FEATURED_LISTING, BOOSTED_LISTING, PREMIUM_MARKETING, PROPERTY_PHOTOGRAPHY, VIRTUAL_TOUR, CONTACT_ACCESS, BROKER_SUBSCRIPTION, BUILDER_PLAN, PG_PLAN, COWORKING_PLAN, VENDOR_PLAN, TENANT_SERVICE, CARE_SERVICE, PROPERTY_MANAGEMENT
                $table->string('name');
                $table->string('target_role')->nullable();
                $table->string('target_category')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 10. Monetization Plans
        if (! Schema::hasTable('monetization_plans')) {
            Schema::create('monetization_plans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('monetization_product_id')->constrained('monetization_products')->cascadeOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->decimal('price', 10, 2);
                $table->string('currency')->default('INR');
                $table->decimal('tax_percentage', 5, 2)->default(18.00);
                $table->integer('validity_days')->nullable();
                $table->json('features')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 11. Promotions (Featured / Boosted listings)
        if (! Schema::hasTable('promotions')) {
            Schema::create('promotions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('monetization_plan_id')->nullable()->constrained('monetization_plans')->nullOnDelete();
                $table->foreignId('plan_id')->nullable()->constrained('monetization_plans')->nullOnDelete();
                $table->string('promotion_type')->default('featured'); // featured, boosted, premium
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->decimal('amount_paid', 10, 2)->default(0);
                $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
                $table->string('status')->default('active'); // active, expired, cancelled
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('transaction_id')->nullable();
                $table->timestamps();

                $table->index(['property_id', 'status']);
                $table->index(['user_id']);
            });
        }

        // 12. Coupons
        if (! Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('discount_type')->default('percentage'); // percentage, fixed
                $table->decimal('discount_value', 10, 2);
                $table->decimal('min_spend', 10, 2)->default(0);
                $table->decimal('max_discount', 10, 2)->nullable();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->integer('usage_limit')->nullable();
                $table->integer('usage_count')->default(0);
                $table->integer('per_user_limit')->default(1);
                $table->json('applicable_product_codes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 13. Monetization Transactions
        if (! Schema::hasTable('monetization_transactions')) {
            Schema::create('monetization_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_number')->unique();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
                $table->foreignId('monetization_plan_id')->nullable()->constrained('monetization_plans')->nullOnDelete();
                $table->foreignId('plan_id')->nullable()->constrained('monetization_plans')->nullOnDelete();
                $table->foreignId('product_id')->nullable()->constrained('monetization_products')->nullOnDelete();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
                $table->nullableMorphs('payable');
                $table->string('gateway')->default('sandbox');
                $table->string('gateway_order_id')->nullable();
                $table->string('gateway_payment_id')->nullable();
                $table->string('gateway_signature')->nullable();
                $table->json('gateway_payload')->nullable();
                $table->string('failure_reason')->nullable();
                $table->decimal('amount', 10, 2);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('discount_amount', 10, 2)->default(0);
                $table->decimal('net_amount', 10, 2);
                $table->string('currency')->default('INR');
                $table->string('status')->default('pending'); // pending, paid, failed, refunded
                $table->string('idempotency_key')->nullable()->unique();
                $table->json('metadata')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
            });
        }

        // 14. Invoices
        if (! Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
                $table->foreignId('transaction_id')->nullable()->constrained('monetization_transactions')->nullOnDelete();
                $table->decimal('subtotal', 10, 2);
                $table->decimal('discount_amount', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->json('tax_details')->nullable();
                $table->decimal('total', 10, 2);
                $table->string('currency')->default('INR');
                $table->string('payment_status')->default('pending'); // pending, paid, cancelled, refunded
                $table->string('billing_name');
                $table->text('billing_address')->nullable();
                $table->string('billing_gstin')->nullable();
                $table->string('customer_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_phone')->nullable();
                $table->timestamp('issued_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'payment_status']);
            });
        }

        // 15. Invoice Items
        if (! Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
                $table->string('description');
                $table->string('item_description')->nullable();
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->decimal('tax_rate', 5, 2)->default(18.00);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('total', 10, 2);
                $table->timestamps();
            });
        }

        // 16. Coupon Redemptions
        if (! Schema::hasTable('coupon_redemptions')) {
            Schema::create('coupon_redemptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('transaction_id')->nullable()->constrained('monetization_transactions')->nullOnDelete();
                $table->decimal('discount_applied', 10, 2);
                $table->timestamps();

                $table->index(['coupon_id', 'user_id']);
            });
        }

        // 17. CRM Notifications
        if (! Schema::hasTable('crm_notifications')) {
            Schema::create('crm_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->string('channel')->default('in_app'); // in_app, email, sms, whatsapp
                $table->string('status')->default('delivered'); // pending, sent, delivered, failed
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'read_at']);
            });
        }

        // 18. Notification Preferences
        if (! Schema::hasTable('notification_preferences')) {
            Schema::create('notification_preferences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('notification_type');
                $table->boolean('email_enabled')->default(true);
                $table->boolean('sms_enabled')->default(true);
                $table->boolean('whatsapp_enabled')->default(true);
                $table->boolean('in_app_enabled')->default(true);
                $table->timestamps();

                $table->unique(['user_id', 'notification_type']);
            });
        }

        // 19. Analytics Events
        if (! Schema::hasTable('analytics_events')) {
            Schema::create('analytics_events', function (Blueprint $table) {
                $table->id();
                $table->string('event_name');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
                $table->string('source')->nullable();
                $table->string('ip_hash')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['event_name', 'created_at']);
                $table->index(['property_id', 'event_name']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('crm_notifications');
        Schema::dropIfExists('coupon_redemptions');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('monetization_transactions');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('monetization_plans');
        Schema::dropIfExists('monetization_products');
        Schema::dropIfExists('sla_rules');
        Schema::dropIfExists('lead_routing_rules');
        Schema::dropIfExists('lead_tasks');
        Schema::dropIfExists('lead_notes');
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('lead_status_histories');
        Schema::dropIfExists('lead_assignments');
        Schema::dropIfExists('leads');
    }
};
