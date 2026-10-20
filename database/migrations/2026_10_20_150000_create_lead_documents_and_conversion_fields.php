<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add Conversion & Pipeline Fields to `leads` table if missing
        Schema::table('leads', function (Blueprint $table) {
            if (! Schema::hasColumn('leads', 'converted_deal_value')) {
                $table->decimal('converted_deal_value', 14, 2)->nullable()->after('budget_max');
            }
            if (! Schema::hasColumn('leads', 'converted_deal_type')) {
                $table->string('converted_deal_type')->nullable()->after('converted_deal_value');
            }
            if (! Schema::hasColumn('leads', 'conversion_notes')) {
                $table->text('conversion_notes')->nullable()->after('converted_deal_type');
            }
            if (! Schema::hasColumn('leads', 'converted_at')) {
                $table->timestamp('converted_at')->nullable()->after('conversion_notes');
            }
            if (! Schema::hasColumn('leads', 'last_contacted_at')) {
                $table->timestamp('last_contacted_at')->nullable()->after('first_response_at');
            }
        });

        // 2. Create `lead_documents` table for Agreements, KYC, Police Verification, etc.
        if (! Schema::hasTable('lead_documents')) {
            Schema::create('lead_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->string('document_type')->default('other'); // agreement_draft, signed_agreement, tenant_kyc, owner_title_deed, police_verification, inspection_report, other
                $table->string('file_path');
                $table->string('file_name')->nullable();
                $table->unsignedBigInteger('file_size')->nullable(); // in bytes
                $table->string('mime_type')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['lead_id', 'document_type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_documents');

        Schema::table('leads', function (Blueprint $table) {
            $columns = ['converted_deal_value', 'converted_deal_type', 'conversion_notes', 'converted_at', 'last_contacted_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('leads', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
