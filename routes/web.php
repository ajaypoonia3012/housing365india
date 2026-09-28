<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PropertyListingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostPropertyController;
use App\Http\Controllers\AuthController;

// Public Dynamic Frontend Routes
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/services', [FrontendController::class, 'services'])->name('services');
Route::get('/services/{slug}', [FrontendController::class, 'serviceDetail'])->name('services.show');
Route::get('/locations', [FrontendController::class, 'locations'])->name('locations');
Route::get('/locations/{city}', [FrontendController::class, 'locationDetail'])->name('locations.show');
Route::get('/pricing', [FrontendController::class, 'pricing'])->name('pricing');
Route::get('/reviews', [FrontendController::class, 'reviews'])->name('reviews');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/inquiry', [FrontendController::class, 'storeInquiry'])->name('inquiry.store')->middleware('throttle:5,1');

// Authentication & Housing365 Prime Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Prime Activation, Member Dashboard & Contact Unlocking
Route::post('/prime/activate', [AuthController::class, 'activatePrime'])->name('prime.activate');
Route::get('/my-prime', [AuthController::class, 'myPrime'])->name('my.prime')->middleware('auth');
Route::post('/properties/{id}/unlock-contact', [AuthController::class, 'unlockOwnerContact'])->name('properties.unlock-contact');

// Role-Based Silo Hubs
Route::get('/owners', [FrontendController::class, 'owners'])->name('owners');
Route::get('/tenants', [FrontendController::class, 'tenants'])->name('tenants');
Route::get('/property-managers', [FrontendController::class, 'propertyManagers'])->name('property-managers');
Route::get('/portal-and-technology', [FrontendController::class, 'portalTechnology'])->name('portal-and-technology');
Route::get('/prime', [FrontendController::class, 'prime'])->name('prime');
Route::get('/mbprime/primePackages', [FrontendController::class, 'prime']);
Route::redirect('/mbprime', '/prime', 301);
Route::redirect('/prime-packages', '/prime', 301);

// Programmatic SEO Indian State & City Directory
Route::get('/india', [FrontendController::class, 'indiaIndex'])->name('india.index');
Route::get('/india/{state}', [FrontendController::class, 'indiaState'])->name('india.state');
Route::get('/india/{state}/{city}', [FrontendController::class, 'indiaCity'])->name('india.city');

// Backwards-compatible legacy redirects
Route::redirect('/us', '/india', 301);
Route::redirect('/us/{any}', '/india', 301)->where('any', '.*');

// Properties & Free Listing Marketplace
Route::get('/properties', [PropertyListingController::class, 'index'])->name('properties.index');
Route::get('/post-property', [PostPropertyController::class, 'create'])->name('properties.post');
Route::post('/post-property', [PostPropertyController::class, 'store'])->name('properties.store');
Route::post('/post-property/assistance', [PostPropertyController::class, 'requestAssistance'])->name('properties.assistance');
Route::get('/properties/{slug}', [PropertyListingController::class, 'show'])->name('properties.show');

// Blog & Guides
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
