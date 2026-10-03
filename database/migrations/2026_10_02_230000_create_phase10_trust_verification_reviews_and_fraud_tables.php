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
        // 1. Verification Types (Configurable catalogue of verification rules)
        if (! Schema::hasTable('verification_types')) {
            Schema::create('verification_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique(); // e.g. OWNER_IDENTITY, PROPERTY_OWNERSHIP, etc.
                $table->string('subject_type')->default('ALL'); // USER, PROPERTY, VENDOR, BROKER, BUILDER, ALL
                $table->text('description')->nullable();
                $table->json('required_documents')->nullable();
                $table->unsignedInteger('validity_days')->default(365);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Verifications (Core polymorphic verification registry)
        if (! Schema::hasTable('verifications')) {
            Schema::create('verifications', function (Blueprint $table) {
                $table->id();
                $table->string('subject_type');
                $table->unsignedBigInteger('subject_id');
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->foreignId('verification_type_id')->constrained('verification_types')->cascadeOnDelete();
                $table->string('verification_type_code');
                $table->string('status')->default('PENDING'); // NOT_SUBMITTED, PENDING, UNDER_REVIEW, VERIFIED, REJECTED, EXPIRED, SUSPENDED
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('expires_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->text('internal_notes')->nullable();
                $table->json('evidence_references')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['subject_type', 'subject_id']);
                $table->index(['verification_type_code', 'status']);
                $table->index('status');
            });
        }

        // 3. Extend verification_documents table (Add verification_id, file_hash, retention_until, anonymized_at)
        if (Schema::hasTable('verification_documents')) {
            Schema::table('verification_documents', function (Blueprint $table) {
                if (! Schema::hasColumn('verification_documents', 'verification_id')) {
                    $table->foreignId('verification_id')->nullable()->after('tenant_screening_id')->constrained('verifications')->cascadeOnDelete();
                }
                if (! Schema::hasColumn('verification_documents', 'file_hash')) {
                    $table->string('file_hash')->nullable()->after('file_path');
                }
                if (! Schema::hasColumn('verification_documents', 'retention_until')) {
                    $table->timestamp('retention_until')->nullable()->after('verified_at');
                }
                if (! Schema::hasColumn('verification_documents', 'anonymized_at')) {
                    $table->timestamp('anonymized_at')->nullable()->after('retention_until');
                }
            });
        }

        // 4. Verification Events (Audit trail for verification transitions)
        if (! Schema::hasTable('verification_events')) {
            Schema::create('verification_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('verification_id')->constrained('verifications')->cascadeOnDelete();
                $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('from_status')->nullable();
                $table->string('to_status');
                $table->string('action');
                $table->text('reason')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 5. Trust Badges Catalogue
        if (! Schema::hasTable('trust_badges')) {
            Schema::create('trust_badges', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique(); // IDENTITY_VERIFIED, PROPERTY_VERIFIED, etc.
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->text('criteria')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 6. Subject Badges (Issued active badges tied to verified subjects)
        if (! Schema::hasTable('subject_badges')) {
            Schema::create('subject_badges', function (Blueprint $table) {
                $table->id();
                $table->string('subject_type');
                $table->unsignedBigInteger('subject_id');
                $table->foreignId('trust_badge_id')->constrained('trust_badges')->cascadeOnDelete();
                $table->foreignId('verification_id')->nullable()->constrained('verifications')->nullOnDelete();
                $table->boolean('is_active')->default(true);
                $table->timestamp('assigned_at')->useCurrent();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('revoked_at')->nullable();
                $table->string('revocation_reason')->nullable();
                $table->timestamps();

                $table->index(['subject_type', 'subject_id', 'is_active']);
            });
        }

        // 7. Reviews (Polymorphic, interaction-verified reviews)
        if (! Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->string('subject_type');
                $table->unsignedBigInteger('subject_id');
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('interaction_type')->nullable(); // viewing, rental, booking, service_order, care_work_order, coworking_booking
                $table->unsignedBigInteger('interaction_id')->nullable();
                $table->unsignedTinyInteger('rating'); // 1 to 5
                $table->string('title');
                $table->text('body');
                $table->string('status')->default('PENDING'); // PENDING, PUBLISHED, REJECTED, HIDDEN, FLAGGED
                $table->timestamp('submitted_at')->useCurrent();
                $table->timestamp('published_at')->nullable();
                $table->timestamp('moderated_at')->nullable();
                $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('moderation_reason')->nullable();
                $table->timestamps();

                $table->index(['subject_type', 'subject_id', 'status']);
                $table->index(['user_id', 'interaction_type', 'interaction_id']);
            });
        }

        // 8. Review Responses (Owner/Operator official responses)
        if (! Schema::hasTable('review_responses')) {
            Schema::create('review_responses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
                $table->foreignId('responder_id')->constrained('users')->cascadeOnDelete();
                $table->text('body');
                $table->string('status')->default('PUBLISHED'); // PENDING, PUBLISHED, HIDDEN
                $table->timestamp('moderated_at')->nullable();
                $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // 9. Review Reports (Reporting abusive/spam reviews)
        if (! Schema::hasTable('review_reports')) {
            Schema::create('review_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
                $table->foreignId('reported_by_user_id')->constrained('users')->cascadeOnDelete();
                $table->string('reason'); // spam, harassment, false_information, privacy_violation, irrelevant, conflict_of_interest, suspected_fraud, duplicate, other
                $table->text('details')->nullable();
                $table->string('status')->default('PENDING'); // PENDING, REVIEWED, DISMISSED, ACTION_TAKEN
                $table->string('action_taken')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
            });
        }

        // 10. Review Moderation Actions (Audit trail of review moderation)
        if (! Schema::hasTable('review_moderation_actions')) {
            Schema::create('review_moderation_actions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
                $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
                $table->string('action'); // approved, rejected, hidden, flagged, restored
                $table->string('reason')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 11. Fraud Rules (Configurable risk and fraud detection rules)
        if (! Schema::hasTable('fraud_rules')) {
            Schema::create('fraud_rules', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('rule_type');
                $table->unsignedInteger('threshold')->default(1);
                $table->string('action')->default('MONITOR'); // NONE, MONITOR, REQUIRE_VERIFICATION, LIMIT_ACTION, SEND_TO_REVIEW, SUSPEND_LISTING, SUSPEND_ACCOUNT
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 12. Risk Flags (Active risk flags on subjects or users)
        if (! Schema::hasTable('risk_flags')) {
            Schema::create('risk_flags', function (Blueprint $table) {
                $table->id();
                $table->string('subject_type');
                $table->unsignedBigInteger('subject_id');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('flag_type'); // duplicate_phone, duplicate_email, repeated_property_submissions, suspicious_referrals, excessive_lead_creation, etc.
                $table->string('severity')->default('MEDIUM'); // LOW, MEDIUM, HIGH, CRITICAL
                $table->string('status')->default('ACTIVE'); // ACTIVE, RESOLVED, DISMISSED
                $table->json('details')->nullable();
                $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('resolved_at')->nullable();
                $table->text('resolution_notes')->nullable();
                $table->timestamps();

                $table->index(['subject_type', 'subject_id']);
                $table->index(['status', 'severity']);
            });
        }

        // 13. Risk Events (Immutable telemetry of evaluated risk activities)
        if (! Schema::hasTable('risk_events')) {
            Schema::create('risk_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('event_type');
                $table->decimal('risk_score', 8, 2)->default(0.00);
                $table->json('payload')->nullable();
                $table->string('ip_hash')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index('event_type');
            });
        }

        // 14. Fraud Reviews (Queue of subjects flagged for manual compliance review)
        if (! Schema::hasTable('fraud_reviews')) {
            Schema::create('fraud_reviews', function (Blueprint $table) {
                $table->id();
                $table->string('subject_type');
                $table->unsignedBigInteger('subject_id');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('trigger_reason');
                $table->string('status')->default('PENDING'); // PENDING, UNDER_REVIEW, CLEARED, ACTION_TAKEN
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->text('findings')->nullable();
                $table->string('action_taken')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'trigger_reason']);
            });
        }

        // 15. Account Suspensions (Reversible account restriction records, user data never deleted)
        if (! Schema::hasTable('account_suspensions')) {
            Schema::create('account_suspensions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('suspension_type'); // TEMPORARY, PERMANENT
                $table->text('reason');
                $table->timestamp('starts_at');
                $table->timestamp('ends_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('appeal_status')->default('NONE'); // NONE, APPEALED, REJECTED, OVERTURNED
                $table->timestamps();

                $table->index(['user_id', 'is_active']);
            });
        }

        // 16. Appeals (User appeals for rejected verification, suspensions, or reviews)
        if (! Schema::hasTable('appeals')) {
            Schema::create('appeals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('appealable_type');
                $table->unsignedBigInteger('appealable_id');
                $table->text('reason');
                $table->json('evidence_data')->nullable();
                $table->string('status')->default('SUBMITTED'); // SUBMITTED, UNDER_REVIEW, APPROVED, REJECTED, CLOSED
                $table->text('admin_response')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->index(['appealable_type', 'appealable_id']);
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appeals');
        Schema::dropIfExists('account_suspensions');
        Schema::dropIfExists('fraud_reviews');
        Schema::dropIfExists('risk_events');
        Schema::dropIfExists('risk_flags');
        Schema::dropIfExists('fraud_rules');
        Schema::dropIfExists('review_moderation_actions');
        Schema::dropIfExists('review_reports');
        Schema::dropIfExists('review_responses');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('subject_badges');
        Schema::dropIfExists('trust_badges');
        Schema::dropIfExists('verification_events');

        if (Schema::hasTable('verification_documents')) {
            Schema::table('verification_documents', function (Blueprint $table) {
                if (Schema::hasColumn('verification_documents', 'anonymized_at')) {
                    $table->dropColumn('anonymized_at');
                }
                if (Schema::hasColumn('verification_documents', 'retention_until')) {
                    $table->dropColumn('retention_until');
                }
                if (Schema::hasColumn('verification_documents', 'file_hash')) {
                    $table->dropColumn('file_hash');
                }
                if (Schema::hasColumn('verification_documents', 'verification_id')) {
                    $table->dropForeign(['verification_id']);
                    $table->dropColumn('verification_id');
                }
            });
        }

        Schema::dropIfExists('verifications');
        Schema::dropIfExists('verification_types');
    }
};
