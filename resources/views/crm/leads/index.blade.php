@extends('layouts.app')

@section('title', 'CRM Leads Pipeline — Housing365 India')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-6 border-b border-gray-200">
            <div>
                <span class="text-xs font-bold tracking-wider uppercase text-[#00AEEF]">Unified Lead Management</span>
                <h1 class="text-2xl font-bold text-[#03234B] mt-1">Leads & Conversion Pipeline</h1>
                <p class="text-sm text-gray-500 mt-1">Multi-channel verified inquiries, assignments, and follow-ups.</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-3">
                <a href="{{ route('monetization.plans') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-[#00AEEF] hover:bg-sky-500 shadow-sm transition">
                    Promote Listings
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mt-6">
            <form method="GET" action="{{ route('crm.leads.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status</label>
                    <select name="status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-[#00AEEF] focus:border-[#00AEEF]" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach(['NEW', 'ASSIGNED', 'CONTACTED', 'QUALIFIED', 'VIEWING_REQUESTED', 'VIEWING_SCHEDULED', 'VIEWING_COMPLETED', 'APPLICATION_STARTED', 'CONVERTED', 'LOST', 'CLOSED'] as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Lead Type</label>
                    <select name="lead_type" class="w-full text-sm border-gray-300 rounded-lg focus:ring-[#00AEEF] focus:border-[#00AEEF]" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach(['residential_rental', 'property_sale', 'land', 'commercial', 'pg', 'coliving', 'coworking', 'builder_project', 'care_service', 'property_management'] as $lt)
                            <option value="{{ $lt }}" {{ request('lead_type') === $lt ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $lt)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('crm.leads.index') }}" class="w-full text-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                        Reset Filters
                    </a>
                </div>
            </form>
        </div>

        {{-- Leads Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
            @if($leads->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No leads found</h3>
                    <p class="mt-1 text-sm text-gray-500">Inquiries and viewing requests will appear here in real-time.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lead</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Property / Target</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">SLA Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Assigned To</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($leads as $lead)
                                <tr class="hover:bg-sky-50/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-[#03234B]">{{ $lead->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $lead->created_at->format('d M Y, h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium truncate max-w-xs">
                                            {{ $lead->property ? $lead->property->title : 'General Enquiry' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $lead->preferred_city ?? ($lead->property?->city ?? 'National') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            {{ ucwords(str_replace('_', ' ', $lead->lead_type)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold 
                                            @if($lead->status === 'NEW') bg-blue-100 text-blue-800 
                                            @elseif(in_array($lead->status, ['ASSIGNED', 'CONTACTED', 'QUALIFIED'])) bg-amber-100 text-amber-800
                                            @elseif(str_starts_with($lead->status, 'VIEWING')) bg-purple-100 text-purple-800
                                            @elseif($lead->status === 'CONVERTED') bg-emerald-100 text-emerald-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $lead->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($lead->sla_status === 'breached')
                                            <span class="text-xs font-bold text-red-600 flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-red-600 mr-1.5 animate-pulse"></span>
                                                Breached
                                            </span>
                                        @elseif($lead->sla_status === 'achieved')
                                            <span class="text-xs font-semibold text-emerald-600 flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                                                Responded
                                            </span>
                                        @else
                                            <span class="text-xs text-amber-600 flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-amber-400 mr-1.5"></span>
                                                Due: {{ $lead->sla_deadline_at ? $lead->sla_deadline_at->diffForHumans() : 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $lead->assignedUser ? $lead->assignedUser->name : 'Unassigned' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('crm.leads.show', $lead->id) }}" class="text-[#00AEEF] hover:text-[#03234B] font-semibold transition">
                                            View & Route &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
