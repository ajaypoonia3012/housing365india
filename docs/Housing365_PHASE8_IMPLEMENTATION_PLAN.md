# Housing365 India — Phase 8 Implementation Plan
## National Marketplace Discovery, SEO & Location Architecture

**Target Environment:** Laravel 12.x / PHP 8.2+ / Filament 5.8 / SQLite (Dev)  
**Brand Identity:** Housing365 India ("Everything Property. One Platform.")  
**Scope Boundary:** Phase 8 ONLY. Do NOT start Phase 9.

---

## 1. Executive Summary & Core Mandate

Phase 8 elevates Housing365 India into a scalable, nationwide property discovery platform and local-search engine. It enables organic, inventory-driven discovery across all 28 Indian States and 8 Union Territories down to Cities and Localities, spanning all primary inventory categories:
- **Rent:** Residential Apartments, Independent Houses, Villas, Builder Floors, Studio Apartments, Rooms
- **PG:** Boys PG, Girls PG, Student PG, Working Professional PG, PG Rooms, PG Beds
- **Co-living:** Managed Co-living, Private & Shared Rooms
- **Coworking:** Flex desks, Private offices, Meeting rooms
- **Commercial:** Office spaces, Retail/Shops, Warehouses, Industrial units
- **Buy / Sell:** Residential Sales, Commercial Sales, Land, Plots
- **Builders:** Builder Projects, New Developments, RERA verified projects
- **Services:** Housing365 Care (plumbing, electrical, painting, cleaning, AC service), Tenant Verification, Rent Agreements

### Absolute Invariants & Boundaries
1. **Property Management Invariant:** Property Management remains strictly **OPTIONAL**. Public listing and discovery pages always present standard leasing/listing without mandatory management. No automated enrollments or agreement triggers occur from browsing.
2. **Contact Access Paywall Invariant:** Zero contact leakage. Seller phone numbers, owner emails, and personal details remain masked and strictly gated behind the Contact Access paywall across all public pages, HTML, JSON-LD schema, meta tags, and OpenGraph headers.
3. **Housing365 Care Invariant:** Housing365 Care remains independent and does not activate Property Management or trigger service requests on discovery views.
4. **Refer & Earn Invariant:** Refer & Earn remains ₹100 per successful eligible referral. Private referral data, owner phone/email, UPI IDs, and payout logs are strictly excluded from all public discovery pages, robots, and sitemaps.
5. **Brand & UI Lock:** Zero redesign. Preserve the approved Housing365 navy (`#03234B`) and cyan (`#00AEEF`) palette, typography (Plus Jakarta Sans & DM Sans), rounded-card styling, buttons, shadows, and Filament admin theme. Header top navigation remains unchanged.
6. **No Fake SEO:** No doorway pages, no thin computer-generated low-quality pages, no fake ratings/reviews, and no fabricated availability. Indexing is strictly inventory-driven or driven by reviewed location guide content.

---

## 2. Location Architecture & Database Evolution

### 2.1 Database Schema (Batch 16 Migration)
Create `2026_10_02_200000_create_phase8_national_location_and_seo_tables.php`:
1. **`states` table**:
   - `id`, `name`, `slug` (unique), `code` (e.g., 'RJ', 'MH', 'DL', 'KA'), `type` ('state', 'union_territory'), `region` ('Northern', 'Western', 'Southern', 'Eastern', 'Central', 'North-Eastern'), `capital`
   - `tenancy_act_title`, `police_verification_portal`, `stamp_duty_summary`
   - `meta_title`, `meta_description`, `seo_h1`, `seo_content`
   - `is_active` (boolean, default true), `is_indexable` (boolean, default true), `order` (integer)
   - Timestamps

2. **`cities` table** (Refined database model or safe bridge with `city_locations`):
   - Enhance `city_locations` with additive columns:
     - `state_id` (foreignId to states.id, nullable)
     - `state_slug` (string, nullable)
     - `state_name` (string, nullable)
     - `meta_title`, `meta_description`, `seo_h1`, `seo_intro`, `seo_content`
     - `is_active` (boolean, default true), `is_indexable` (boolean, default true)
     - `latitude`, `longitude` (decimal, nullable)
     - `order` (integer, default 0)

3. **`localities` table**:
   - `id`, `city_location_id` (FK to city_locations.id), `state_id` (FK to states.id, nullable)
   - `name`, `slug`, `pincode` (nullable)
   - `latitude`, `longitude` (decimal, nullable)
   - `landmark_summary`, `metro_connectivity`, `society_clusters` (json/text)
   - `meta_title`, `meta_description`, `seo_intro`, `seo_content`
   - `is_active` (boolean, default true), `is_indexable` (boolean, default false - indexable only if inventory exists or admin explicitly flags it)
   - Unique constraint: `['city_location_id', 'slug']`
   - Timestamps

4. **`seo_meta_overrides` table** (Centralized SEO CMS):
   - `id`, `path` (unique string, e.g. `/rent/rajasthan/jaipur/`), `route_name` (nullable)
   - `meta_title`, `meta_description`, `canonical_url` (nullable)
   - `h1`, `intro_text`, `body_html` (nullable)
   - `is_indexable` (boolean, default true), `schema_type` (nullable), `schema_json` (nullable)
   - `faqs` (json, nullable)
   - Timestamps

5. **Indexes on `properties`**:
   - Add database indexes for `['status', 'purpose', 'city']`, `['status', 'city', 'locality']` to ensure sub-millisecond querying without N+1 bottlenecks.

---

## 3. SEO & Indexability Architecture

### 3.1 Centralized SEO & Indexability Service (`SeoService` & `SeoIndexabilityService`)
- Responsibilities:
  - `generateTitle(array $context): string`
  - `generateDescription(array $context): string`
  - `generateCanonical(string $path, array $queryParams = []): string`
  - `generateBreadcrumbs(array $crumbs): array`
  - `generateBreadcrumbsSchema(array $crumbs): array`
  - `generatePropertySchema(Property $property): array` (strictly without seller phone/email)
  - `generateLocalBusinessSchema(CityLocation $city): array`
  - `shouldIndex(string $pageType, array $context): bool`
    - Evaluates genuine published inventory counts (`status = 'Available'`).
    - If a locality or city has zero inventory AND zero verified editorial guide content, sets `noindex, follow`.
    - Strips tracking/referral parameters (`ref`, `utm_*`, `bhk`, `sort`, `page`) from canonical URLs.

### 3.2 Canonical URL Standardization
- Enforces single canonical URL format:
  - Purpose root: `/rent`, `/sale`, `/commercial`, `/pg`, `/co-living`, `/coworking`, `/land`, `/projects`
  - State level: `/{purpose}/{state-slug}`
  - City level: `/{purpose}/{state-slug}/{city-slug}`
  - Locality level: `/{purpose}/{state-slug}/{city-slug}/{locality-slug}`
- Service locations:
  - `/housing365-care`
  - `/housing365-care/{state-slug}/{city-slug}`
  - `/housing365-care/{state-slug}/{city-slug}/{service-slug}`
  - `/property-management`
  - `/property-management/{state-slug}/{city-slug}`

---

## 4. XML Sitemaps & Robots Architecture

### 4.1 Scalable XML Sitemap Engine
- **Sitemap Index:** `/sitemap.xml`
- **Child Sitemaps:**
  1. `/sitemaps/properties.xml` — All public, available properties (`status = 'Available'`)
  2. `/sitemaps/locations.xml` — Active indexable States, Cities, and Localities with inventory
  3. `/sitemaps/categories.xml` — Clean category marketplace hubs (`/rent`, `/sale`, `/commercial`, `/pg`, `/co-living`, `/coworking`, `/land`, `/projects`)
  4. `/sitemaps/services.xml` — Housing365 Care, Property Management, Rent Agreements, Tenant Verification
  5. `/sitemaps/cms.xml` — Public static pages (About, Pricing, Contact, Blog Posts)
- **Sitemap Invariants:**
  - Zero private URLs (`/dashboard/*`, `/admin/*`, `/login`, `/referrals/*`, `/rewards/*`, `/payouts/*`).
  - Zero noindex URLs.
  - Zero draft or rented properties.

### 4.2 Production Robots.txt
- Allow search engines to index public property, location, and service routes.
- Disallow private paths (`/dashboard/`, `/admin/`, `/referrals/`, `/storage/private/`, `/api/`).
- Declare `Sitemap: https://housing365.in/sitemap.xml`.

---

## 5. Filament Admin Management

- **Location Management:**
  - `StateResource`: View/edit Indian States & Union Territories, active toggles, tenancy acts, and SEO content.
  - `CityLocationResource`: Extended with state relationships, locality counts, inventory metrics, and SEO metadata.
  - `LocalityResource`: View/edit Localities grouped by City and State, with active/indexable toggles and inventory counts.
- **SEO & CMS Management:**
  - `SeoMetaOverrideResource`: Manage custom SEO titles, meta descriptions, H1s, intros, FAQs, and canonicals for any public URL path.

---

## 6. Testing & Quality Verification Plan

### Test Suite: `tests/Feature/Phase8NationalSeoTest.php`
1. Country & State hierarchy functions correctly.
2. City & Locality relationships enforce parent constraints.
3. Unique slugs enforced across states, cities, and localities within a city.
4. City marketplace renders cleanly with genuine inventory and dynamic localities.
5. Locality marketplace renders with contextual breadcrumbs and no leaked contacts.
6. All primary discovery routes render with valid HTTP 200:
   - `/rent/{state}/{city}`
   - `/sale/{state}/{city}`
   - `/pg/{state}/{city}`
   - `/co-living/{state}/{city}`
   - `/coworking/{state}/{city}`
   - `/commercial/{state}/{city}`
   - `/land/{state}/{city}`
   - `/projects/{state}/{city}`
   - `/housing365-care/{state}/{city}`
   - `/property-management/{state}/{city}`
7. Empty localities without content return `noindex, follow` directive.
8. Unpublished or rented properties are excluded from discovery listings.
9. Canonical URLs strip query filters (`?sort=price&bhk=2`) and point to clean base paths.
10. XML Sitemap index `/sitemap.xml` generates valid XML with child sitemaps.
11. Child sitemaps contain only available properties and active indexable locations.
12. Private pages (`/dashboard/*`, `/referrals/*`, `/admin/*`) are strictly excluded from sitemaps and blocked in `robots.txt`.
13. Defense-in-depth: Owner phone, email, referral details, and UPI information never leak into HTML or schema JSON.
14. Contact Access paywall remains 100% intact.
15. Property Management remains optional on all location and discovery pages.
16. Housing365 Care remains independent and isolated.
17. Refer & Earn ₹100 remains untouched.
18. Multi-role authorization and existing Phase 1–7 tests continue to pass 100% green.
