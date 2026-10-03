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
        Schema::create('contact_access_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('credits')->default(1);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('INR');
            $table->unsignedInteger('validity_days')->default(90);
            $table->string('badge_text')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('contact_access_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_access_package_id')->nullable()->constrained('contact_access_packages')->nullOnDelete();
            $table->string('order_reference')->unique();
            $table->unsignedInteger('credits_granted')->default(0);
            $table->unsignedInteger('credits_remaining')->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('INR');
            $table->string('payment_gateway')->default('manual_gateway');
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('gateway_order_id')->nullable();
            $table->string('gateway_payment_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_access_purchase_id')->nullable()->constrained('contact_access_purchases')->nullOnDelete();
            $table->timestamp('unlocked_at')->useCurrent();
            $table->string('status', 30)->default('active');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_unlocks');
        Schema::dropIfExists('contact_access_purchases');
        Schema::dropIfExists('contact_access_packages');
    }
};
