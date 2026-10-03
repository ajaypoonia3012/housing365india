@extends('layouts.app')

@section('title', 'Lead Details & Timeline — Housing365 India')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('crm.leads.index') }}" class="text-sm font-semibold text-gray-500 hover:text-[#03234B] flex items-center transition">
                &larr; Back to Leads Pipeline
            </a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#03234B] text-white">
                Lead #{{ $lead->id }}
            </span>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left 2 cols: Details & Timeline --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Lead Profile Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs uppercase font-bold tracking-wider text-[#00AEEF]">{{ ucwords(str_replace('_', ' ', $lead->lead_type)) }}</span>
                            <h2 class="text-2xl font-bold text-[#03234B] mt-1">{{ $lead->name }}</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Created on {{ $lead->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                            @if($lead->status === 'NEW') bg-blue-100 text-blue-800 
                            @elseif(in_array($lead->status, ['ASSIGNED', 'CONTACTED', 'QUALIFIED'])) bg-amber-100 text-amber-800
                            @elseif(str_starts_with($lead->status, 'VIEWING')) bg-purple-100 text-purple-800
                            @elseif($lead->status === 'CONVERTED') bg-emerald-100 text-emerald-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $lead->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100 text-sm">
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase">Phone</span>
                            <div class="font-medium text-gray-800 mt-0.5">{{ $lead->phone ?? 'Not provided' }}</div>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase">Email</span>
                            <div class="font-medium text-gray-800 mt-0.5">{{ $lead->email ?? 'Not provided' }}</div>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase">Preferred Location</span>
                            <div class="font-medium text-gray-800 mt-0.5">{{ $lead->preferred_city ?? 'Anywhere' }} {{ $lead->preferred_locality ? "({$lead->preferred_locality})" : '' }}</div>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase">Source / Channel</span>
                            <div class="font-medium text-gray-800 mt-0.5">{{ ucwords(str_replace('_', ' ', $lead->source)) }} ({{ $lead->source_channel }})</div>
                        </div>
                    </div>

                    @if($lead->property)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <span class="text-xs font-semibold text-gray-400 uppercase">Associated Property</span>
                            <div class="mt-2 p-3 bg-slate-50 rounded-lg flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-bold text-[#03234B]">{{ $lead->property->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $lead->property->locality }}, {{ $lead->property->city }}</div>
                                </div>
                                <a href="{{ route('properties.show', $lead->property->slug) }}" target="_blank" class="text-xs font-semibold text-[#00AEEF] hover:underline">
                                    View Listing &rarr;
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Activity Timeline --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-[#03234B] mb-4">Immutable Activity & Status Timeline</h3>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @forelse($lead->activities->sortByDesc('created_at') as $act)
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-sky-100 text-[#00AEEF] flex items-center justify-center ring-8 ring-white text-xs font-bold">
                                                    {{ strtoupper(substr($act->activity_type, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-800">{{ $act->title }}</p>
                                                    @if($act->description)
                                                        <p class="text-xs text-gray-600 mt-0.5">{{ $act->description }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-xs whitespace-nowrap text-gray-400">
                                                    <time>{{ $act->created_at->diffForHumans() }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-xs text-gray-400 italic">No activity recorded yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Notes Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-bold text-[#03234B] mb-4">Internal CRM Notes</h3>
                    
                    <form method="POST" action="{{ route('crm.leads.notes', $lead->id) }}" class="mb-6">
                        @csrf
                        <textarea name="content" rows="3" required placeholder="Add private consultation note or update..." class="w-full text-sm border-gray-300 rounded-lg focus:ring-[#00AEEF] focus:border-[#00AEEF]"></textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <label class="inline-flex items-center text-xs text-gray-600">
                                <input type="checkbox" name="is_private" value="1" class="rounded border-gray-300 text-[#00AEEF] focus:ring-[#00AEEF]">
                                <span class="ml-2">Private note (visible only to managers)</span>
                            </label>
                            <button type="submit" class="px-4 py-2 bg-[#03234B] text-white text-xs font-bold rounded-lg hover:bg-slate-800 transition">
                                Save Note
                            </button>
                        </div>
                    </form>

                    <div class="space-y-3">
                        @forelse($lead->notes->sortByDesc('created_at') as $note)
                            <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                    <span class="font-bold text-gray-700">{{ $note->user->name }}</span>
                                    <span>{{ $note->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                <p class="text-sm text-gray-800">{{ $note->content }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">No notes added yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right 1 col: Actions & Routing & Tasks --}}
            <div class="space-y-6">
                {{-- Status Transition Form --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-[#03234B] uppercase tracking-wider mb-4">Update Lifecycle Status</h3>
                    <form method="POST" action="{{ route('crm.leads.status', $lead->id) }}">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Target Status</label>
                                <select name="status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-[#00AEEF] focus:border-[#00AEEF]">
                                    @foreach(['NEW', 'ASSIGNED', 'CONTACTED', 'QUALIFIED', 'VIEWING_REQUESTED', 'VIEWING_SCHEDULED', 'VIEWING_COMPLETED', 'APPLICATION_STARTED', 'CONVERTED', 'LOST', 'CLOSED'] as $st)
                                        <option value="{{ $st }}" {{ $lead->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Reason / Notes</label>
                                <input type="text" name="reason" placeholder="Optional rationale for transition" class="w-full text-sm border-gray-300 rounded-lg focus:ring-[#00AEEF] focus:border-[#00AEEF]">
                            </div>
                            <button type="submit" class="w-full py-2 px-4 bg-[#00AEEF] text-white text-xs font-bold rounded-lg hover:bg-sky-500 transition">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Assignment Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-[#03234B] uppercase tracking-wider mb-4">Assignment & Ownership</h3>
                    <div class="text-sm mb-4">
                        <span class="text-xs text-gray-500">Currently Assigned To:</span>
                        <div class="font-bold text-gray-800 text-base mt-0.5">
                            {{ $lead->assignedUser ? $lead->assignedUser->name : 'Unassigned (Queue)' }}
                        </div>
                    </div>

                    <form method="POST" action="{{ route('crm.leads.assign', $lead->id) }}">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Reassign To User ID</label>
                                <input type="number" name="assigned_user_id" placeholder="User ID" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-[#00AEEF] focus:border-[#00AEEF]">
                            </div>
                            <button type="submit" class="w-full py-2 px-4 bg-slate-800 text-white text-xs font-bold rounded-lg hover:bg-slate-700 transition">
                                Reassign Lead
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Task / Follow-up Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-[#03234B] uppercase tracking-wider mb-4">Schedule Follow-up Task</h3>
                    <form method="POST" action="{{ route('crm.leads.tasks', $lead->id) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Task Title</label>
                            <input type="text" name="title" required placeholder="e.g. Follow up on property visit" class="w-full text-sm border-gray-300 rounded-lg">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Type</label>
                                <select name="task_type" class="w-full text-xs border-gray-300 rounded-lg">
                                    <option value="call">Call</option>
                                    <option value="visit">Visit</option>
                                    <option value="email">Email</option>
                                    <option value="general">General</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Priority</label>
                                <select name="priority" class="w-full text-xs border-gray-300 rounded-lg">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Due Date</label>
                            <input type="datetime-local" name="due_at" class="w-full text-xs border-gray-300 rounded-lg">
                        </div>
                        <button type="submit" class="w-full py-2 px-4 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-500 transition">
                            Create Task
                        </button>
                    </form>

                    @if($lead->tasks->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                            <span class="text-xs font-bold text-gray-500 uppercase">Upcoming Tasks</span>
                            @foreach($lead->tasks as $tsk)
                                <div class="p-2 bg-slate-50 rounded text-xs flex justify-between items-center">
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $tsk->title }}</div>
                                        <div class="text-gray-400">{{ $tsk->due_at?->format('d M Y') }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">{{ $tsk->status }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
