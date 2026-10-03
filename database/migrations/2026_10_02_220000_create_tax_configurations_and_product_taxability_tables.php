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
        // 1. Tax Configurations table (Admin-controlled GST/Tax rules)
        if (! Schema::hasTable('tax_configurations')) {
            Schema::create('tax_configurations', function (Blueprint $table) {
                $table->id();
                $table->boolean('tax_enabled')->default(true);
                $table->string('tax_name')->default('GST');
                $table->string('tax_type')->default('GST'); // GST, OTHER, NONE
                $table->decimal('tax_rate', 5, 2)->default(18.00);
                $table->boolean('cgst_enabled')->default(true);
                $table->boolean('sgst_enabled')->default(true);
                $table->boolean('igst_enabled')->default(false);
                $table->decimal('cgst_rate', 5, 2)->default(9.00);
                $table->decimal('sgst_rate', 5, 2)->default(9.00);
                $table->decimal('igst_rate', 5, 2)->default(18.00);
                $table->dateTime('effective_from')->nullable();
                $table->dateTime('effective_until')->nullable();
                $table->json('applicable_to')->nullable(); // product or category codes, null = global
                $table->text('notes')->nullable();
                $table->boolean('active')->default(true);
                $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['active', 'effective_from']);
            });
        }

        // 2. Add is_taxable to monetization_products if not present
        if (Schema::hasTable('monetization_products')) {
            Schema::table('monetization_products', function (Blueprint $table) {
                if (! Schema::hasColumn('monetization_products', 'is_taxable')) {
                    $table->boolean('is_taxable')->default(true)->after('description');
                }
            });
        }

        // 3. Add tax snapshot fields to monetization_transactions if not present
        if (Schema::hasTable('monetization_transactions')) {
            Schema::table('monetization_transactions', function (Blueprint $table) {
                if (! Schema::hasColumn('monetization_transactions', 'tax_enabled')) {
                    $table->boolean('tax_enabled')->default(true)->after('tax_amount');
                }
                if (! Schema::hasColumn('monetization_transactions', 'tax_name')) {
                    $table->string('tax_name')->nullable()->after('tax_enabled');
                }
                if (! Schema::hasColumn('monetization_transactions', 'tax_rate')) {
                    $table->decimal('tax_rate', 5, 2)->default(0.00)->after('tax_name');
                }
                if (! Schema::hasColumn('monetization_transactions', 'tax_details')) {
                    $table->json('tax_details')->nullable()->after('tax_rate');
                }
            });
        }

        // 4. Add tax snapshot fields to invoices if not present
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (! Schema::hasColumn('invoices', 'tax_enabled')) {
                    $table->boolean('tax_enabled')->default(true)->after('tax');
                }
                if (! Schema::hasColumn('invoices', 'tax_name')) {
                    $table->string('tax_name')->nullable()->after('tax_enabled');
                }
                if (! Schema::hasColumn('invoices', 'tax_rate')) {
                    $table->decimal('tax_rate', 5, 2)->default(0.00)->after('tax_name');
                }
                if (! Schema::hasColumn('invoices', 'cgst_amount')) {
                    $table->decimal('cgst_amount', 10, 2)->default(0.00)->after('tax_rate');
                }
                if (! Schema::hasColumn('invoices', 'sgst_amount')) {
                    $table->decimal('sgst_amount', 10, 2)->default(0.00)->after('cgst_amount');
                }
                if (! Schema::hasColumn('invoices', 'igst_amount')) {
                    $table->decimal('igst_amount', 10, 2)->default(0.00)->after('sgst_amount');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_configurations');

        if (Schema::hasTable('monetization_products')) {
            Schema::table('monetization_products', function (Blueprint $table) {
                if (Schema::hasColumn('monetization_products', 'is_taxable')) {
                    $table->dropColumn('is_taxable');
                }
            });
        }

        if (Schema::hasTable('monetization_transactions')) {
            Schema::table('monetization_transactions', function (Blueprint $table) {
                $cols = ['tax_enabled', 'tax_name', 'tax_rate', 'tax_details'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('monetization_transactions', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $cols = ['tax_enabled', 'tax_name', 'tax_rate', 'cgst_amount', 'sgst_amount', 'igst_amount'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('invoices', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
