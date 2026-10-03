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
        // Additive columns for service_requests
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('assignment_status')->nullable()->after('assigned_vendor_id'); // OFFERED, ACCEPTED, REJECTED
            $table->text('assignment_rejected_reason')->nullable()->after('assignment_status');
            $table->text('rework_notes')->nullable()->after('qc_notes');
            $table->integer('rework_count')->default(0)->after('rework_notes');
            $table->text('service_location')->nullable()->after('contact_preference');
            $table->text('access_instructions')->nullable()->after('service_location');
            $table->foreignId('pg_room_id')->nullable()->after('property_id')->constrained('pg_rooms')->nullOnDelete();
            $table->foreignId('pg_bed_id')->nullable()->after('pg_room_id')->constrained('pg_beds')->nullOnDelete();
            $table->foreignId('coworking_inventory_id')->nullable()->after('pg_bed_id')->constrained('coworking_inventories')->nullOnDelete();
        });

        // Additive columns for service_quotes
        Schema::table('service_quotes', function (Blueprint $table) {
            $table->json('line_items')->nullable()->after('notes');
            $table->text('rejection_reason')->nullable()->after('status');
        });

        // Additive columns for work_orders
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('qc_status')->nullable()->default('PENDING')->after('status'); // PENDING, APPROVED, REWORK_REQUIRED, REJECTED
            $table->text('qc_notes')->nullable()->after('qc_status');
            $table->integer('rework_count')->default(0)->after('qc_notes');
            $table->text('materials_used')->nullable()->after('work_notes');
            $table->text('arrival_notes')->nullable()->after('materials_used');
        });

        // Housing365 Care Invoices
        Schema::create('care_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // customer/payer
            $table->foreignId('vendor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_status')->default('PENDING'); // PENDING, PAID, FAILED, CANCELLED
            $table->string('idempotency_key')->nullable()->index();
            $table->string('payment_reference')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Housing365 Vendor Payout Ledger
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_number')->unique();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('care_invoice_id')->nullable()->constrained('care_invoices')->nullOnDelete();
            $table->decimal('gross_amount', 10, 2)->default(0);
            $table->decimal('platform_fee', 10, 2)->default(0); // Housing365 commission / margin
            $table->decimal('tax_deduction', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->string('status')->default('PENDING'); // PENDING, APPROVED, PROCESSING, PAID, FAILED, ON_HOLD
            $table->string('payout_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('failure_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payouts');
        Schema::dropIfExists('care_invoices');

        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['qc_status', 'qc_notes', 'rework_count', 'materials_used', 'arrival_notes']);
        });

        Schema::table('service_quotes', function (Blueprint $table) {
            $table->dropColumn(['line_items', 'rejection_reason']);
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['pg_room_id']);
            $table->dropForeign(['pg_bed_id']);
            $table->dropForeign(['coworking_inventory_id']);
            $table->dropColumn([
                'assignment_status',
                'assignment_rejected_reason',
                'rework_notes',
                'rework_count',
                'service_location',
                'access_instructions',
                'pg_room_id',
                'pg_bed_id',
                'coworking_inventory_id',
            ]);
        });
    }
};
