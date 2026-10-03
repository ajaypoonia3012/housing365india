<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CareServiceController;
use App\Http\Controllers\ContactAccessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\InventoryBookingController;
use App\Http\Controllers\LeadManagementController;
use App\Http\Controllers\LocationMarketplaceController;
use App\Http\Controllers\MonetizationController;
use App\Http\Controllers\PostPropertyController;
use App\Http\Controllers\PropertyListingController;
use App\Http\Controllers\PropertyManagementController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ReferralDashboardController;
use App\Http\Controllers\RentPaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TenantScreeningController;
use App\Http\Controllers\TrustVerificationController;
use App\Http\Controllers\VendorPortalController;
use Illuminate\Support\Facades\Route;

// Public Dynamic Frontend Routes
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/services', [FrontendController::class, 'services'])->name('services');
Route::get('/care', [CareServiceController::class, 'index'])->name('care.index');
Route::get('/housing365-care', [CareServiceController::class, 'index'])->name('care');
Route::get('/services/care', [CareServiceController::class, 'index'])->name('services.care');
Route::get('/care/{slug}', [CareServiceController::class, 'category'])->name('care.category')->where('slug', '^(?!request$)[a-z0-9\-]+$');
Route::get('/services/{slug}', [FrontendController::class, 'serviceDetail'])->name('services.show');
Route::get('/locations', [FrontendController::class, 'locations'])->name('locations');
Route::get('/locations/{city}', [FrontendController::class, 'locationDetail'])->name('locations.show');
Route::get('/pricing', [FrontendController::class, 'pricing'])->name('pricing');
Route::get('/resources', [FrontendController::class, 'resources'])->name('resources');
Route::get('/franchising', [FrontendController::class, 'franchising'])->name('franchising');
Route::get('/reviews', [FrontendController::class, 'reviews'])->name('reviews');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/inquiry', [FrontendController::class, 'storeInquiry'])->name('inquiry.store')->middleware('throttle:5,1');

// Authentication
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Multi-Role Workspaces & Role Switching
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/switch/{role}', [DashboardController::class, 'switchRole'])->name('dashboard.switch');

    Route::get('/dashboard/owner', [DashboardController::class, 'owner'])->middleware('role:owner')->name('dashboard.owner');
    Route::get('/dashboard/tenant', [DashboardController::class, 'tenant'])->middleware('role:tenant')->name('dashboard.tenant');
    Route::get('/dashboard/broker', [DashboardController::class, 'broker'])->middleware('role:broker')->name('dashboard.broker');
    Route::get('/dashboard/builder', [DashboardController::class, 'builder'])->middleware('role:builder')->name('dashboard.builder');
    Route::get('/dashboard/pg-operator', [DashboardController::class, 'pgOperator'])->middleware('role:pg_operator')->name('dashboard.pg-operator');
    Route::get('/dashboard/coliving-operator', [DashboardController::class, 'colivingOperator'])->middleware('role:coliving_operator')->name('dashboard.coliving-operator');
    Route::get('/dashboard/coworking-operator', [DashboardController::class, 'coworkingOperator'])->middleware('role:coworking_operator')->name('dashboard.coworking-operator');
    Route::get('/dashboard/vendor', [DashboardController::class, 'vendor'])->middleware('role:vendor')->name('dashboard.vendor');
    Route::get('/dashboard/buyer', [DashboardController::class, 'buyer'])->middleware('role:buyer')->name('dashboard.buyer');
    Route::get('/dashboard/seller', [DashboardController::class, 'seller'])->middleware('role:seller')->name('dashboard.seller');

    // Housing365 Care Customer Service Requests, Quotes & Invoices
    Route::get('/care/request', [CareServiceController::class, 'createForm'])->name('care.request');
    Route::post('/care/request', [CareServiceController::class, 'store'])->name('care.request.store');
    Route::get('/care/requests/{id}', [CareServiceController::class, 'show'])->name('care.requests.show');
    Route::post('/care/quotes/{id}/approve', [CareServiceController::class, 'approveQuote'])->name('care.quotes.approve');
    Route::post('/care/quotes/{id}/decline', [CareServiceController::class, 'declineQuote'])->name('care.quotes.decline');
    Route::post('/care/requests/{id}/qc', [CareServiceController::class, 'reviewQc'])->name('care.requests.qc');
    Route::post('/care/requests/{id}/confirm', [CareServiceController::class, 'confirmResolution'])->name('care.requests.confirm');
    Route::get('/care/invoices/{id}', [CareServiceController::class, 'showInvoice'])->name('care.invoices.show');
    Route::post('/care/invoices/{id}/pay', [CareServiceController::class, 'payInvoice'])->name('care.invoices.pay');

    // Optional Property Management (Paths A & B)
    Route::get('/properties/{id}/management/explore', [PropertyManagementController::class, 'explore'])->name('management.explore');
    Route::post('/properties/{id}/management/request', [PropertyManagementController::class, 'requestEnrollment'])->name('management.request');
    Route::get('/management/agreements/{id}', [PropertyManagementController::class, 'showAgreement'])->name('management.agreement');
    Route::post('/management/agreements/{id}/accept', [PropertyManagementController::class, 'acceptAgreement'])->name('management.agreement.accept');
    Route::post('/management/agreements/{id}/cancel', [PropertyManagementController::class, 'cancelEnrollment'])->name('management.agreement.cancel');

    // Vendor Portal Jobs, Offers, Work Orders & Payouts
    Route::get('/dashboard/vendor/jobs', [VendorPortalController::class, 'jobs'])->name('vendor.jobs');
    Route::get('/dashboard/vendor/jobs/{id}', [VendorPortalController::class, 'showJob'])->name('vendor.jobs.show');
    Route::post('/dashboard/vendor/requests/{id}/accept', [VendorPortalController::class, 'acceptOffer'])->name('vendor.requests.accept');
    Route::post('/dashboard/vendor/requests/{id}/reject', [VendorPortalController::class, 'rejectOffer'])->name('vendor.requests.reject');
    Route::post('/dashboard/vendor/jobs/{id}/quote', [VendorPortalController::class, 'submitQuote'])->name('vendor.jobs.quote');
    Route::post('/dashboard/vendor/work-orders/{id}/start', [VendorPortalController::class, 'startWork'])->name('vendor.work-orders.start');
    Route::post('/dashboard/vendor/work-orders/{id}/status', [VendorPortalController::class, 'updateStatus'])->name('vendor.work-orders.status');
    Route::post('/dashboard/vendor/work-orders/{id}/complete', [VendorPortalController::class, 'completeWork'])->name('vendor.work-orders.complete');
    Route::get('/dashboard/vendor/payouts', [VendorPortalController::class, 'payouts'])->name('vendor.payouts');

    // Phase 5: Tenant Screening & Verification
    Route::post('/properties/{id}/apply', [TenantScreeningController::class, 'apply'])->name('tenant.apply');
    Route::get('/screenings/{id}', [TenantScreeningController::class, 'showScreening'])->name('tenant.screenings.show');
    Route::post('/screenings/{id}/consent', [TenantScreeningController::class, 'grantConsent'])->name('tenant.screenings.consent');
    Route::post('/screenings/{id}/document', [TenantScreeningController::class, 'uploadDocument'])->name('tenant.screenings.document');
    Route::get('/verification/documents/{id}/download', [TenantScreeningController::class, 'downloadDocument'])->name('tenant.documents.download');
    Route::get('/properties/{id}/applicants', [TenantScreeningController::class, 'ownerApplicants'])->name('owner.properties.applicants');

    // Phase 5: Rent Payment & Collections
    Route::get('/rent/invoices', [RentPaymentController::class, 'tenantLedgers'])->name('rent.invoices');
    Route::get('/rent/ledgers/{id}', [RentPaymentController::class, 'showLedger'])->name('rent.ledgers.show');
    Route::post('/rent/ledgers/{id}/pay', [RentPaymentController::class, 'pay'])->name('rent.ledgers.pay');
    Route::post('/rent/payments/{id}/confirm', [RentPaymentController::class, 'confirm'])->name('rent.payments.confirm');
    Route::get('/rent/collections', [RentPaymentController::class, 'ownerRentOverview'])->name('rent.collections');

    // Phase 5: PG & Coworking Booking Actions
    Route::post('/pg/beds/{id}/hold', [InventoryBookingController::class, 'holdPgBed'])->name('pg.beds.hold');
    Route::post('/pg/beds/{id}/book', [InventoryBookingController::class, 'bookPgBed'])->name('pg.beds.book');
    Route::post('/pg/bookings/{id}/cancel', [InventoryBookingController::class, 'cancelPgBooking'])->name('pg.bookings.cancel');
    Route::post('/coworking/{id}/book', [InventoryBookingController::class, 'bookCoworking'])->name('coworking.book');
    Route::post('/coworking/bookings/{id}/cancel', [InventoryBookingController::class, 'cancelCoworkingBooking'])->name('coworking.bookings.cancel');
});

// Phase 5: Public PG & Coworking Inventory Views & Webhooks
Route::get('/properties/{id}/pg/inventory', [InventoryBookingController::class, 'showPgProperty'])->name('pg.inventory');
Route::get('/properties/{id}/coworking/inventory', [InventoryBookingController::class, 'showCoworkingProperty'])->name('coworking.inventory');
Route::post('/rent/webhook', [RentPaymentController::class, 'webhook'])->name('rent.webhook');

// Housing365 Native Contact Access System (Paywall & Unlock)
Route::post('/properties/{id}/unlock-contact', [ContactAccessController::class, 'unlockContact'])->name('properties.unlock-contact');
Route::get('/contact-access/packages', [ContactAccessController::class, 'packages'])->name('contact-access.packages');
Route::post('/contact-access/purchase', [ContactAccessController::class, 'purchase'])->name('contact-access.purchase');
Route::post('/contact-access/verify', [ContactAccessController::class, 'verify'])->name('contact-access.verify');

// Role-Based Silo Hubs
Route::get('/owners', [FrontendController::class, 'owners'])->name('owners');
Route::get('/tenants', [FrontendController::class, 'tenants'])->name('tenants');
Route::get('/property-managers', [FrontendController::class, 'propertyManagers'])->name('property-managers');
Route::get('/portal-and-technology', [FrontendController::class, 'portalTechnology'])->name('portal-and-technology');

// Programmatic SEO Indian State & City Directory
Route::get('/india', [FrontendController::class, 'indiaIndex'])->name('india.index');
Route::get('/india/{state}', [FrontendController::class, 'indiaState'])->name('india.state');
Route::get('/india/{state}/{city}', [FrontendController::class, 'indiaCity'])->name('india.city');

// Backwards-compatible legacy redirects (Prime & International)
Route::redirect('/prime', '/pricing', 301)->name('prime');
Route::redirect('/my-prime', '/properties', 301)->name('my.prime');
Route::redirect('/mbprime', '/pricing', 301);
Route::redirect('/mbprime/primePackages', '/pricing', 301);
Route::redirect('/prime-packages', '/pricing', 301);
Route::redirect('/us', '/india', 301);
Route::redirect('/us/{any}', '/india', 301)->where('any', '.*');

// Properties & Free Listing Marketplace
Route::get('/properties', [PropertyListingController::class, 'index'])->name('properties.index');
Route::get('/post-property', [PostPropertyController::class, 'create'])->name('properties.post');
Route::post('/post-property', [PostPropertyController::class, 'store'])->name('properties.store');
Route::post('/post-property/assistance', [PostPropertyController::class, 'requestAssistance'])->name('properties.assistance');

// Saved Properties & Viewings
Route::middleware(['auth'])->group(function () {
    Route::get('/saved-properties', [PropertyListingController::class, 'savedProperties'])->name('properties.saved');
    Route::post('/properties/{id}/save', [PropertyListingController::class, 'toggleSave'])->name('properties.save');
});
Route::post('/properties/{id}/viewing', [PropertyListingController::class, 'requestViewing'])->name('properties.viewing');

// Unified Clean Marketplace Search Routes (e.g. /rent/rajasthan/jaipur/mansarovar/)
Route::get('/rent/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'rent', $state, $city, $locality);
})->name('marketplace.rent');

Route::get('/sale/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'sale', $state, $city, $locality);
})->name('marketplace.sale');

Route::get('/commercial/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'commercial', $state, $city, $locality);
})->name('marketplace.commercial');

Route::get('/land/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'land_sale', $state, $city, $locality);
})->name('marketplace.land');

Route::get('/pg/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'pg', $state, $city, $locality);
})->name('marketplace.pg');

Route::get('/co-living/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'coliving', $state, $city, $locality);
})->name('marketplace.coliving');

Route::get('/coworking/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'coworking', $state, $city, $locality);
})->name('marketplace.coworking');

Route::get('/projects/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'project', $state, $city, $locality);
})->name('marketplace.projects');

// Property Detail Page (slug-based)
Route::get('/properties/{slug}', [PropertyListingController::class, 'show'])->name('properties.show');

// Blog & Guides
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Phase 7: Housing365 Refer & Earn ₹100 Acquisition Program
Route::get('/refer-and-earn', [ReferralController::class, 'landing'])->name('refer-and-earn');
Route::get('/refer-and-earn/terms', [ReferralController::class, 'terms'])->name('refer-and-earn.terms');
Route::get('/r/{code}', [ReferralController::class, 'redirectShortLink'])->name('referral.short-link');

Route::middleware(['auth'])->group(function () {
    Route::get('/referrals/{id}/photo', [ReferralController::class, 'downloadPhoto'])->name('referrals.photo.download');
    Route::get('/dashboard/referrals', [ReferralDashboardController::class, 'index'])->name('dashboard.referrals');
    Route::get('/dashboard/referrals/create', [ReferralDashboardController::class, 'create'])->name('dashboard.referrals.create');
    Route::post('/dashboard/referrals', [ReferralDashboardController::class, 'store'])->name('dashboard.referrals.store')->middleware('throttle:15,1');
    Route::get('/dashboard/referrals/{id}', [ReferralDashboardController::class, 'show'])->name('dashboard.referrals.show');
    Route::get('/dashboard/rewards', [ReferralDashboardController::class, 'rewards'])->name('dashboard.rewards');
    Route::post('/dashboard/payout-method', [ReferralDashboardController::class, 'savePayoutMethod'])->name('dashboard.payout-method');
    Route::post('/dashboard/rewards/request-payout', [ReferralDashboardController::class, 'requestPayout'])->name('dashboard.rewards.request-payout');
});

// Phase 8: National Marketplace Discovery & Category Aliases
Route::get('/buy/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'sale', $state, $city, $locality);
})->name('marketplace.buy');

Route::get('/sell/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'sale', $state, $city, $locality);
})->name('marketplace.sell');

Route::get('/builders/{state?}/{city?}/{locality?}', function ($state = null, $city = null, $locality = null) {
    return app(PropertyListingController::class)->index(request(), 'project', $state, $city, $locality);
})->name('marketplace.builders');

// Phase 8: Location Marketplace Landing Pages
Route::get('/locations/{state}/{city}', [LocationMarketplaceController::class, 'city'])->name('locations.city');
Route::get('/locations/{state}/{city}/{locality}', [LocationMarketplaceController::class, 'locality'])->name('locations.locality');

// Phase 8: Care & Property Management Location Hubs
Route::get('/housing365-care/{state}/{city}/{service?}', [LocationMarketplaceController::class, 'careLocation'])->name('care.location');
Route::get('/property-management/{state}/{city}', [LocationMarketplaceController::class, 'managementLocation'])->name('management.location');

// Phase 8: XML Sitemaps
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemaps/properties.xml', [SitemapController::class, 'properties'])->name('sitemap.properties');
Route::get('/sitemaps/locations.xml', [SitemapController::class, 'locations'])->name('sitemap.locations');
Route::get('/sitemaps/categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemaps/services.xml', [SitemapController::class, 'services'])->name('sitemap.services');
Route::get('/sitemaps/cms.xml', [SitemapController::class, 'cms'])->name('sitemap.cms');

// Phase 9: Monetization, Plans & Invoices
Route::get('/monetization/plans', [MonetizationController::class, 'index'])->name('monetization.plans');
Route::post('/monetization/validate-coupon', [MonetizationController::class, 'validateCoupon'])->name('monetization.validate-coupon');

Route::middleware(['auth'])->group(function () {
    Route::post('/monetization/checkout', [MonetizationController::class, 'checkout'])->name('monetization.checkout');
    Route::post('/monetization/verify-payment', [MonetizationController::class, 'verifyPayment'])->name('monetization.verify-payment');
    Route::get('/invoices/{invoice}', [MonetizationController::class, 'showInvoice'])->name('monetization.invoice.show');

    // Phase 9: Unified Lead Management Platform
    Route::get('/crm/leads', [LeadManagementController::class, 'index'])->name('crm.leads.index');
    Route::get('/crm/leads/{lead}', [LeadManagementController::class, 'show'])->name('crm.leads.show');
    Route::post('/crm/leads/{lead}/status', [LeadManagementController::class, 'updateStatus'])->name('crm.leads.status');
    Route::post('/crm/leads/{lead}/assign', [LeadManagementController::class, 'assign'])->name('crm.leads.assign');
    Route::post('/crm/leads/{lead}/notes', [LeadManagementController::class, 'addNote'])->name('crm.leads.notes');
    Route::post('/crm/leads/{lead}/tasks', [LeadManagementController::class, 'addTask'])->name('crm.leads.tasks');

    // Phase 10: Trust & Verification Hub, Private Document Retrieval, Appeals
    Route::get('/dashboard/trust', [TrustVerificationController::class, 'index'])->name('trust.dashboard');
    Route::post('/dashboard/trust/submit', [TrustVerificationController::class, 'submit'])->name('trust.submit');
    Route::get('/dashboard/trust/documents/{document}/download', [TrustVerificationController::class, 'downloadDocument'])->name('trust.document.download');
    Route::post('/dashboard/trust/appeal', [TrustVerificationController::class, 'submitAppeal'])->name('trust.appeal');

    // Phase 10: Review Submission, Responses & Reporting
    Route::get('/properties/{property}/reviews/check-eligibility', [ReviewController::class, 'checkEligibility'])->name('reviews.check');
    Route::post('/properties/{property}/reviews', [ReviewController::class, 'storePropertyReview'])->name('reviews.property.store');
    Route::post('/reviews/{review}/response', [ReviewController::class, 'storeResponse'])->name('reviews.response');
    Route::post('/reviews/{review}/report', [ReviewController::class, 'report'])->name('reviews.report');
});
