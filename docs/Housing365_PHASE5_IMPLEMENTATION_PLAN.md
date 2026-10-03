# Housing365 India — Phase 5 Implementation Plan
**Document Version:** 1.0.0  
**Phase:** Phase 5 — Tenant Lifecycle, Verification, Rent Collection & Inventory Booking  
**Target Environment:** Laravel 12.x / PHP 8.2+ / Filament 5.8 / SQLite (Production-Ready)  
**Brand Identity:** Housing365 India ("Everything Property. One Platform.")  

---

## 1. Existing Architecture Audit
The platform currently operates on Laravel 12.x with Filament 5.8, utilizing a non-destructive SQLite database architecture.
- **Brand Palette:** Strictly Navy (`#03234B`) and Cyan (`#00AEEF`) with Plus Jakarta Sans & DM Sans typography.
- **Phase 1–4 Base:** Complete multi-role identity, programmatic locations, 9 marketplace purposes (Rent, Sale, Commercial Rent, Commercial Sale, Land/Plot, PG, Co-Living, Coworking, Builder Projects), Contact Access Paywall, Housing365 Care, and Optional Property Management.

---

## 2. Existing Relevant Tables
The database contains 14 executed migrations across 12 batches:
1. `users`: Multi-role persona user accounts.
2. `properties`: Unified marketplace properties with categories, purposes, verification badges, and `is_managed` flag.
3. `inquiries`: Direct property inquiries linked via `property_id`.
4. `saved_properties`: User favorited properties.
5. `contact_access_packages`, `contact_access_purchases`, `contact_unlocks`: Native Contact Access paywall and unlock system.
6. `roles`, `user_roles`, `organizations`, `organization_members`: Role authorization engine.
7. `care_service_categories`, `vendor_profiles`, `service_requests`, `service_quotes`, `work_orders`: Housing365 Care and vendor management.
8. `management_plans`, `management_enrollments`: Optional Property Management.
9. `service_audit_logs`: Polymorphic system audit log.

---

## 3. Existing Models
- `User`, `Property`, `Inquiry`, `SavedProperty`, `ContactAccessPackage`, `ContactAccessPurchase`, `ContactUnlock`, `Role`, `Organization`, `OrganizationMember`, `CareServiceCategory`, `VendorProfile`, `ServiceRequest`, `ServiceQuote`, `WorkOrder`, `ManagementPlan`, `ManagementEnrollment`, `ServiceAuditLog`.

---

## 4. Existing Services
- `Housing365CareService`: Full lifecycle of service requests, quote triage, work orders, and QC reviews.
- `PropertyManagementService`: Manages optional property management enrollment, agreement presentation, legal acceptance with IP logging, and cancellation.
- `ContactAccessService`: Atomically deducts credits for owner contact unlocks.

---

## 5. Existing Routes
- Public marketplace routes: `/properties`, `/properties/{slug}`, `/rent/{state?}/{city?}/{locality?}`, `/sale/...`, `/commercial/...`, `/land/...`, `/pg/...`, `/projects/...`.
- Care routes: `/care`, `/care/{slug}`, `/care/request`, `/care/requests/{id}`, `/care/quotes/{id}/approve`.
- Property management routes: `/properties/{id}/management/explore`, `/properties/{id}/management/request`, `/management/agreements/{id}`, `/management/agreements/{id}/accept`, `/management/agreements/{id}/cancel`.
- Role workspaces: `/dashboard/owner`, `/dashboard/tenant`, `/dashboard/vendor`.

---

## 6. Existing Payment Architecture
- Contract: `App\Contracts\PaymentGatewayInterface`.
- Implementation: `App\Services\Payment\SandboxPaymentGateway`.
- Order creation, signature verification, server-side confirmation, and idempotency protection established in Phase 1 for Contact Access packages.

---

## 7. Existing Housing365 Care Architecture
- Decoupled from Property Management. Self-managed owners and tenants can book repairs on-demand.
- Vendors only receive job context needed for execution; zero owner personal phone or email is ever leaked to vendors.

---

## 8. Existing Property Management Architecture
- **Path A (Leasing Only):** ₹0 free listing, free direct inquiries, self-managed by owner. Zero management fees, zero agreements required.
- **Path B (Leasing + Property Management):** Explicit owner opt-in, clear pricing display, separate agreement acceptance with IP and timestamp logging. 30-day cancellation reverting to Leasing Only.

---

## 9. Reusable Components
- Blade layout `layouts.app` with responsive header, footer, mobile drawer, and notification alerts.
- Form inputs, buttons, badges, modals, and card components matching Navy/Cyan aesthetics.
- `ServiceAuditLog` for recording tenant lifecycle, verification events, and rent payments.

---

## 10. New Tables Required (Additive Migration #15)
To support Phase 5 without touching existing schema:
1. `tenant_applications`: Rental applications submitted by prospective tenants for properties.
2. `tenant_screenings`: Consent-first background screening records with progression statuses.
3. `verification_documents`: Private, access-controlled government ID and income proofs with OCR extraction metadata and masked identifiers.
4. `rent_accounts`: Tenancy rent account configuration linking property, owner, and tenant.
5. `rent_ledgers`: Monthly billing periods, due dates, amounts due/paid, and late flags.
6. `rent_payments`: Individual payment transactions with gateway order/payment references, idempotency keys, and statuses.
7. `pg_rooms`: Room-level inventory under PG properties (room number, occupancy, gender preference, rent/deposit).
8. `pg_beds`: Bed-level inventory (bed code, availability state: `AVAILABLE`, `HELD`, `RESERVED`, `OCCUPIED`, `MAINTENANCE`, `BLOCKED`).
9. `pg_bookings`: Bed reservation bookings with transactional double-booking locks.
10. `coworking_inventories`: Desk/seat/cabin/meeting room inventory under coworking spaces.
11. `coworking_bookings`: Coworking reservations with conflict-prevention locking.

---

## 11. New Models Required
- `TenantApplication`
- `TenantScreening`
- `VerificationDocument`
- `RentAccount`
- `RentLedger`
- `RentPayment`
- `PgRoom`
- `PgBed`
- `PgBooking`
- `CoworkingInventory`
- `CoworkingBooking`

---

## 12. New Services & Interfaces Required
- `App\Contracts\IdentityVerificationProviderInterface`
- `App\Contracts\DocumentVerificationProviderInterface`
- `App\Contracts\RentPaymentGatewayInterface`
- `App\Services\Verification\SandboxIdentityVerificationProvider`
- `App\Services\Verification\SandboxDocumentVerificationProvider`
- `App\Services\Payment\SandboxRentPaymentGateway`
- `App\Services\TenantScreeningService`
- `App\Services\RentPaymentService`
- `App\Services\PgBookingService`
- `App\Services\CoworkingBookingService`

---

## 13. New Controllers Required
- `App\Http\Controllers\TenantScreeningController`: Tenant applications, screening consent, document uploads, owner applicant review.
- `App\Http\Controllers\RentPaymentController`: Rent ledger view, payment initiation, payment callback/webhook, owner rent overview.
- `App\Http\Controllers\InventoryBookingController`: PG bed selection & booking, Coworking desk selection & booking.

---

## 14. New Filament Resources Required
Navigation Group: `'Tenant Lifecycle & Operations'`
- `TenantScreeningResource`: Admin screening review queue, verification status escalation, document review.
- `RentLedgerResource`: Monthly rent ledgers, payment tracking, reconciliation.
- `PgInventoryResource`: PG rooms and beds inventory oversight.
- `CoworkingInventoryResource`: Coworking desk and cabin inventory oversight.

---

## 15. Security Boundaries
1. **Tenant Privacy:** Tenant screening records, income details, and government documents are visible ONLY to the applicant tenant, the authorized owner of the property applied to, and system admins. Unrelated owners, vendors, brokers, or guests receive HTTP 403.
2. **Owner Privacy & Contact Access Paywall:** All Phase 5 views (applications, rent payment, PG booking, coworking booking) preserve the Contact Access paywall; direct owner phone/email is never exposed.
3. **Document Security:** Verification documents stored on private disk (`storage/app/verification_documents`). Access restricted to authorized controller download endpoint. Zero unmasked IDs in URLs, logs, or views.
4. **Payment Verification:** Server-side confirmation only. No trust in frontend status. Idempotency key prevents duplicate charges.
5. **Inventory Double-Booking:** Database transactions with `lockForUpdate` prevent concurrent double-booking of PG beds and Coworking seats.

---

## 16. Migration Strategy
- Migration: `2026_10_02_170000_create_phase5_tenant_lifecycle_and_inventory_tables.php`.
- 100% additive; uses `Schema::hasTable` guards.
- Foreign keys properly indexed with `cascadeOnDelete` or `nullOnDelete`.

---

## 17. Test Strategy
- Dedicated feature test class `tests/Feature/Phase5TenantLifecycleTest.php`.
- Test suites covering:
  - Tenant screening lifecycle and consent gating.
  - Government ID / OCR document verification with privacy masking.
  - Rent payment workflow, idempotency, duplicate callback protection, and ledger balance reconciliation.
  - Optional Property Management isolation (rent payment does NOT imply management).
  - PG bed-level booking and transactional double-booking prevention.
  - Coworking inventory booking and conflict prevention.
  - Cross-user security isolation and zero owner contact leakage.
  - Full application regression (`php artisan test`, Pint, Vite build, optimize:clear).
