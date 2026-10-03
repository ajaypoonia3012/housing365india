# Housing365 India — Phase 11 User Acceptance Testing (UAT) Report

**Date:** October 3, 2026  
**Phase:** 11 — Production Readiness, UAT, Security, Performance & SQL Production Hardening  
**Status:** ALL ACCEPTANCE SCENARIOS PASSED (100%)  

---

## 1. Overview & Methodology

User Acceptance Testing (UAT) validates that Housing365 India meets the end-to-end operational requirements of all stakeholders: Property Owners, Tenants, Corporate Clients, Service Vendors, and Platform Administrators.

All test scenarios were executed against the hardened MySQL InnoDB database using authenticated personas and role-based session states.

---

## 2. UAT Journey Verification Matrix

### Journey 1: Tenant Search, Privacy Enforcement & Contact Unlock
* **Actor:** Tenant (`tenant_p11@housing365.test`)
* **Objective:** Discover rental property in Bengaluru, verify contact privacy protection, purchase contact package, and unlock owner contact.
* **Steps Executed:**
  1. Access `/properties?city=Bengaluru&purpose=rent`.
  2. Verified property card displays verified badge; `seller_phone` and `seller_email` are hidden.
  3. POST `/properties/{id}/unlock-contact` without credits -> Received HTTP 402 with `require_credits: true`.
  4. Purchase "Starter Contact Pass" (3 credits, ₹299).
  5. POST `/properties/{id}/unlock-contact` -> Success (`credits_remaining: 2`); owner phone & email displayed.
  6. Repeated unlock on same property -> Instant access with zero additional credit deduction.
* **Result:** **PASSED**

---

### Journey 2: Free Self-Managed Owner Listing (₹0 Fee Invariant)
* **Actor:** Property Owner (`owner_p11@housing365.test`)
* **Objective:** Publish residential rental property without fees or mandatory property management.
* **Steps Executed:**
  1. Access `/post-property`.
  2. Fill property details: 2 BHK in Pune, rent ₹20,000/month.
  3. Selected "Self-Managed (₹0 Standard Listing)".
  4. Verified no payment is demanded; property created with `is_managed = false`.
  5. Verified `activeManagementEnrollment` is `null`.
* **Result:** **PASSED**

---

### Journey 3: Housing365 Care End-to-End Service Execution
* **Actors:** Owner, Vendor (`P11 Solutions`), Admin
* **Objective:** Complete full service lifecycle from request creation to GST-compliant invoice and payment.
* **Steps Executed:**
  1. Owner submits plumbing service request (`urgent`, Bengaluru).
  2. Admin triages request and assigns vendor `P11 Solutions`.
  3. Vendor accepts assignment and submits quote (₹1,000 labor + ₹180 GST @ 18%).
  4. Owner approves quote -> Work Order automatically created in `SCHEDULED` status.
  5. Vendor starts work order, uploads after-service photo, and marks completed.
  6. Owner/Admin approves QC review -> Care invoice generated for ₹1,180.
  7. Owner executes server-side verified payment with idempotency key.
  8. Invoice marked `PAID`; vendor payout record initialized for ₹1,000.
* **Result:** **PASSED**

---

### Journey 4: PG Bed & Coworking Inventory Concurrency
* **Actors:** Multiple Concurrent Tenants
* **Objective:** Reserve PG bed and coworking desk slots under high contention without double-booking.
* **Steps Executed:**
  1. Tenant A places 15-minute transactional hold on Bed #101-A.
  2. Tenant B simultaneously attempts to hold Bed #101-A -> Exception thrown (`Bed not available`).
  3. Tenant A confirms booking -> Bed status updated to `OCCUPIED`.
  4. Tenant C attempts to book Coworking Desk with capacity = 1 during same slot -> Blocked with capacity limit exception.
* **Result:** **PASSED**

---

### Journey 5: Refer & Earn Program Flow
* **Actors:** Referrer, Referred Owner
* **Objective:** Attain unique referral code, submit referral, verify listing match, and credit ₹100 reward.
* **Steps Executed:**
  1. User requests referral code -> Generated unique code `H365-XXXXXX`.
  2. Referral submitted for owner in Koramangala.
  3. Phone normalized (+91, 0 prefixes stripped to standard 10-digit).
  4. Owner lists property with matching phone -> Automated qualification triggers.
  5. ₹100 referral reward generated and queued for UPI payout.
* **Result:** **PASSED**

---

### Journey 6: Trust & Verification Document Security
* **Actors:** Owner, Compliance Officer, Malicious Actor
* **Objective:** Submit KYC verification, verify private storage isolation, and check IDOR protection.
* **Steps Executed:**
  1. Owner uploads Aadhaar card (`.pdf`).
  2. File stored on `private` disk (not in public webroot).
  3. Malicious user attempts GET `/verifications/documents/{id}/download` -> Received HTTP 403 Forbidden.
  4. Owner and Admin access download route -> Valid PDF stream delivered.
* **Result:** **PASSED**

---

## 3. UAT Summary Conclusion

All 6 core business flows function accurately in accordance with specification guidelines. Zero regressions across earlier phases were observed.
