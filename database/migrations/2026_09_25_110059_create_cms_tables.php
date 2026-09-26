<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Settings Table (For full dynamic site customization)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('text'); // text, textarea, richtext, image, boolean, number
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // 2. Dynamic Pages (Home, About, Pricing, Contact, Silos, etc.)
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_link')->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('sections_data')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // 3. Services (Tenant, Owner, Corporate, Institutional)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('all');
            $table->string('icon')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->json('features')->nullable();
            $table->string('pricing_info')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Properties (Rentals, listings, suites)
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('address')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country')->default('CA');
            $table->string('postal_code')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('price_frequency')->default('month');
            $table->integer('bedrooms')->default(1);
            $table->decimal('bathrooms', 3, 1)->default(1.0);
            $table->integer('sqft')->nullable();
            $table->string('property_type')->default('Apartment'); // Condo, House, Apartment, Suite
            $table->string('status')->default('Available'); // Available, Rented, Pending
            $table->boolean('featured')->default(false);
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('amenities')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        // 5. City Locations (Toronto, Vancouver, Ottawa, New York, etc.)
        Schema::create('city_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('province_or_state');
            $table->string('country')->default('CA'); // CA, US
            $table->integer('tier')->default(1);
            $table->string('avg_rent')->nullable();
            $table->integer('available_units_count')->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // 6. Testimonials & Reviews
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->default('Verified Resident');
            $table->string('location')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('content');
            $table->boolean('is_featured')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 7. Team Members
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->nullable();
            $table->string('linkedin')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 8. Blog Posts & Articles
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Rental Guide');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('author_name')->default('Housing365 Editorial');
            $table->string('read_time')->default('4 min read');
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 9. FAQs
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('General');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 10. Inquiries & Contact Submissions (Admin can view and manage)
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('service_type')->default('General');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status')->default('new'); // new, contact_made, resolved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('city_locations');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('services');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('settings');
    }
};
