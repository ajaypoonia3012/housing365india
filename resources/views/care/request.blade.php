@extends('layouts.app')

@section('title', 'Book a Housing365 Care Service Request | India')
@section('meta_description', 'Submit a Housing365 Care service request. Professional property maintenance, repairs, and deep cleaning across India.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('care.index') }}" class="hover:text-brand-cyan transition font-medium">Housing365 Care</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">New Service Request</span>
    </div>
</div>

<section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-8">
        
        <div class="mb-8 text-center sm:text-left">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">HOUSING365 CARE DISPATCH</span>
            <h1 class="text-3xl font-display font-extrabold text-brand-navy mt-1">Book a Service Request</h1>
            <p class="text-sm text-slate-500 mt-1">
                Tell us what needs attention. Our operations team will assign a verified partner technician and provide an upfront quote.
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-xl">
            <form action="{{ route('care.request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Service Category -->
                <div>
                    <label for="service_category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Service Category <span class="text-rose-500">*</span>
                    </label>
                    <select id="service_category_id" name="service_category_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan/20 text-sm font-medium text-slate-800 bg-white">
                        <option value="">-- Select a Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (old('service_category_id') == $cat->id || $selectedCategorySlug == $cat->slug) ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->price_display }})
                            </option>
                        @endforeach
                    </select>
                    @error('service_category_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Association -->
                <div>
                    <label for="property_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Associated Property (Optional)
                    </label>
                    <select id="property_id" name="property_id" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan/20 text-sm font-medium text-slate-800 bg-white">
                        <option value="">-- General Request / Not Listed Property --</option>
                        @foreach($properties as $prop)
                            <option value="{{ $prop->id }}" {{ (old('property_id') == $prop->id || $selectedPropertyId == $prop->id) ? 'selected' : '' }}>
                                {{ $prop->title }} ({{ $prop->city ?? 'India' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">If this service is for one of your listed properties, select it to track records automatically.</p>
                    @error('property_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Issue Title -->
                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Short Issue Title (Optional)
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Master bathroom tap leaking continuously" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan/20 text-sm font-medium text-slate-800">
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Issue Description -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Detailed Description of Issue <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="4" required placeholder="Please describe the issue, brand/model of appliance if applicable, and any specific access instructions..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan/20 text-sm font-medium text-slate-800">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority & Scheduling -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="priority" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Priority
                        </label>
                        <select id="priority" name="priority" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan/20 text-sm font-medium text-slate-800 bg-white">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low (Routine)</option>
                            <option value="normal" {{ (old('priority', request('priority')) == 'normal' || (!old('priority') && request('priority') != 'emergency')) ? 'selected' : '' }}>Normal (Standard)</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High (Urgent)</option>
                            <option value="emergency" {{ (old('priority') == 'emergency' || request('priority') == 'emergency') ? 'selected' : '' }}>Emergency (Immediate 24/7)</option>
                        </select>
                    </div>

                    <div>
                        <label for="preferred_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Preferred Date
                        </label>
                        <input type="date" id="preferred_date" name="preferred_date" value="{{ old('preferred_date', now()->addDay()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan/20 text-sm font-medium text-slate-800">
                    </div>

                    <div>
                        <label for="preferred_time_window" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Time Window
                        </label>
                        <select id="preferred_time_window" name="preferred_time_window" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan/20 text-sm font-medium text-slate-800 bg-white">
                            <option value="morning" {{ old('preferred_time_window') == 'morning' ? 'selected' : '' }}>Morning (9:00 AM - 12:00 PM)</option>
                            <option value="afternoon" {{ old('preferred_time_window') == 'afternoon' ? 'selected' : '' }}>Afternoon (12:00 PM - 4:00 PM)</option>
                            <option value="evening" {{ old('preferred_time_window') == 'evening' ? 'selected' : '' }}>Evening (4:00 PM - 8:00 PM)</option>
                            <option value="anytime" {{ old('preferred_time_window') == 'anytime' ? 'selected' : '' }}>Anytime</option>
                        </select>
                    </div>
                </div>

                <!-- Photos Upload -->
                <div>
                    <label for="photos" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Attach Photos of Issue (Optional, Max 5 images)
                    </label>
                    <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-brand-cyan hover:file:text-brand-navy">
                    <p class="text-[11px] text-slate-400 mt-1">Photos help our technician bring the right tools and replacement parts.</p>
                </div>

                <!-- Terms & Transparency Notice -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 leading-relaxed">
                    <div class="font-bold text-brand-navy mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-brand-cyan"></i>
                        <span>Housing365 Care Customer Protection</span>
                    </div>
                    <span>
                        Submitting this request does not commit you to any charges. A verified technician will inspect or quote the work. Work orders are only generated after you explicitly approve the quote.
                    </span>
                </div>

                <!-- Submit Button -->
                <div class="pt-2 flex items-center justify-between">
                    <a href="{{ route('care.index') }}" class="text-xs font-bold text-slate-500 hover:text-brand-navy transition">
                        &larr; Back to Care Overview
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-sm uppercase tracking-wider transition shadow-lg shadow-brand-cyan/20 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Submit Care Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
