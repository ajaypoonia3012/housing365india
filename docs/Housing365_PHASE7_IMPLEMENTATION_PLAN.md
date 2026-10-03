# Housing365 Phase 7 Implementation Plan
**Refer & Earn ₹100 Referral Acquisition Program**

---

## 1. Executive Summary & Core Objectives

The goal of Phase 7 is to implement the complete, web-based **Housing365 Refer & Earn ₹100 Program**.
Housing365 users across any persona (Owners, Tenants, Brokers, Builders, PG Operators, Co-living Operators, Coworking Operators, Vendors, Buyers, Sellers) can refer genuine property owners and earn a **₹100 Reward** for each successful, eligible referral.

### Architectural Locks & Non-Negotiables:
1. **Zero Android App Dependency:** The entire referral experience is 100% web-based (`/refer-and-earn`, `/dashboard/referrals`, `/dashboard/rewards`, web share APIs, QR code). No mobile app is required.
2. **Fixed ₹100 Reward:** ₹100 reward per eligible referral upon successful qualification (default trigger: `PROPERTY_PUBLISHED`). Raw lead submission does NOT automatically pay rewards.
3. **Property Management Remains Strictly Optional:** A referred owner listing a property is default Path A (Leasing Only, ₹0 standard listing). The referral never activates Property Management or creates a `ManagementEnrollment`.
4. **Contact Access Paywall is Immutable:** Referred owner phone and email are private and never exposed to unauthorized users, guests, or in public pages, JSON, meta tags, or HTML attributes.
5. **Housing365 Care Independence:** Submitting or qualifying a referral never creates a Housing365 Care request.
6. **Multi-Role Compatibility:** Referral capability belongs to the user account, regardless of current active role.
7. **Brand & UI Lock:** 100% adherence to Housing365 Navy (`#03234B`), Cyan (`#00AEEF`), Plus Jakarta Sans, DM Sans, existing button and card designs. Main navigation is NOT cluttered with "Buy/Sell/Refer & Earn".

---

## 2. Referral Lifecycle & Server-Side State Machine

```
SUBMITTED
   ↓
UNDER_REVIEW
   ↓
CONTACTED
   ↓
QUALIFIED
   ↓
PROPERTY_LISTED
   ↓
REWARD_PENDING
   ↓
REWARD_APPROVED
   ↓
PAYOUT_PENDING
   ↓
PAID
```
*(Alternative terminal/exception states: `REJECTED`, `CANCELLED`, `ON_HOLD`)*

- State transitions are strictly validated in `ReferralService`. Frontend requests cannot directly force state updates.
- Rejected referrals mandate an explicit audit reason (e.g. `DUPLICATE_OWNER`, `PROPERTY_ALREADY_EXISTS`, `SELF_REFERRAL`, `BROKER_CONTACT`, `INVALID_CONTACT`).

---

## 3. Duplicate Detection & Anti-Abuse Rules

1. **Normalized Contact Matching:**
   - Phone numbers are normalized (strip non-digits, remove leading `0`, `+91`, `91`, keeping last 10 digits).
   - Emails are lowercased and trimmed.
2. **First-Valid Attribution Rule:**
   - If User A refers Owner X and User B refers Owner X, User A's submission is given attribution. User B's submission is flagged as `DUPLICATE_OWNER` and rejected.
3. **Existing Property Check:**
   - Referrals matching an existing property address, locality/unit, or existing listing contact are flagged as `PROPERTY_ALREADY_EXISTS`.
4. **Self-Referral Prevention:**
   - Users cannot refer their own phone number, email address, or user ID. Flagged as `SELF_REFERRAL`.
5. **Broker / Professional Protection:**
   - Contacts matching registered brokers, builders, or professional accounts are flagged for admin manual review (`BROKER_CONTACT_FLAG`).
6. **Private Photo Board Security:**
   - Method B uploads (To-Let / For-Sale board photos) are validated (MIME: jpg, jpeg, png, webp; max size 5MB), given randomized UUID filenames, and stored on private disk storage with authorized download routes only.

---

## 4. Reward & Payout Financial Ledger

1. **`ReferralReward`:**
   - Dedicated ledger model with unique constraint on `['referral_id', 'reward_type']`.
   - Prevents duplicate reward generation.
   - Statuses: `PENDING`, `APPROVED`, `ON_HOLD`, `PAID`, `CANCELLED`.
   - Default amount: `100.00` INR (using `decimal:2` precision).
2. **`ReferralPayout`:**
   - Payout requests / batches with unique `payout_number`.
   - Admin-managed UPI payout mode: Admin records transaction reference and settles payout safely without claiming fake automated bank transfers.
   - User UPI ID is stored with encryption and displayed masked (e.g. `a***@upi`).
3. **Gateway Abstraction:**
   - `ReferralPayoutGatewayInterface`
   - `AdminManualReferralPayoutGateway`

---

## 5. User Experience & Public Touchpoints

- **Landing Page:** `GET /refer-and-earn`
  - Hero: "Refer a Property. Earn ₹100."
  - 4-Step Explanation: 1. Refer Owner/Board -> 2. Housing365 Verifies -> 3. Property Goes Live -> 4. Earn ₹100.
  - Interactive Referral Link Generator with Copy, WhatsApp, Facebook, X, Email, and Web Share API integrations.
  - QR Code generation for in-person sharing.
  - Original FAQs and link to Terms.
- **Terms Page:** `GET /refer-and-earn/terms`
- **User Dashboard:**
  - `GET /dashboard/referrals`: Referral statistics (Total, Successful, Pending, Rejected), referral history table.
  - `GET /dashboard/referrals/create`: Multi-tab submission form (Method A: Refer Owner, Method B: Property Board Photo).
  - `POST /dashboard/referrals`: Form submission with rate limiting and CSRF protection.
  - `GET /dashboard/rewards`: Financial rewards ledger, earnings summary, and UPI payout configuration.
  - `POST /dashboard/payout-method`: Save UPI ID securely.

---

## 6. Filament Admin Suite

- **`ReferralResource`:** List and filter referrals by status, city, property type, duplicate flag, fraud flag; admin actions for Review, Mark Contacted, Qualify, Reject, Mark Property Listed.
- **`ReferralRewardResource`:** Reward ledger review, approve, put on hold.
- **`ReferralPayoutResource`:** Payout queue, mark paid with transaction reference, mark failed.

---

## 7. Testing Strategy (`tests/Feature/Phase7ReferralProgramTest.php`)

Comprehensive 34+ test cases covering:
1. Referral code generation and link attribution.
2. Method A & B submission.
3. Phone normalization and duplicate prevention.
4. Existing property conflict detection.
5. Broker contact flag.
6. Self-referral prevention.
7. Server-side finite state transitions.
8. Property publication triggering ₹100 reward.
9. Financial ledger uniqueness (no duplicate reward).
10. Reward approval and payout lifecycle.
11. Payout amount validation.
12. UPI masking and private data protection.
13. Contact Access Paywall non-leakage.
14. Optional Property Management invariant.
15. Care service isolation.
16. Multi-role user accessibility.
17. Malicious photo upload blocking.
18. Full suite regression with zero breaking changes.
