@extends('layouts.app')

@section('title', 'Refer & Earn ₹100 | Housing365 India')
@section('meta_description', 'Refer property owners to Housing365 and earn ₹100 when your eligible referral successfully lists a property. Web-based, instant UPI rewards, zero app required.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-brand-navy via-brand-navy-light to-brand-navy-dark text-white py-16 sm:py-24 overflow-hidden border-b border-white/10">
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-brand-cyan/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-brand-cyan/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-cyan/20 border border-brand-cyan/40 text-brand-cyan text-xs font-black tracking-wider uppercase">
                    <i class="fa-solid fa-gift"></i>
                    <span>HOUSING365 REFERRAL ACQUISITION PROGRAM</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-display font-black tracking-tight leading-tight">
                    Refer a Property.<br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-cyan via-sky-300 to-white">
                        Earn ₹{{ $rewardAmount }}.
                    </span>
                </h1>

                <p class="text-base sm:text-lg text-slate-200/90 max-w-2xl mx-auto lg:mx-0 font-normal leading-relaxed">
                    Know someone looking to rent out or sell their property? Refer them to Housing365 and earn ₹{{ $rewardAmount }} direct to your UPI account when your referral successfully qualifies and lists live.
                </p>

                <!-- Action CTAs -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="{{ auth()->check() ? route('dashboard.referrals.create') : route('login', ['redirect' => route('dashboard.referrals.create')]) }}"
                       class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-black text-sm tracking-wide shadow-xl shadow-brand-cyan/25 transition duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Refer a Property Now</span>
                    </a>

                    <a href="{{ auth()->check() ? route('dashboard.referrals') : route('login', ['redirect' => route('dashboard.referrals')]) }}"
                       class="w-full sm:w-auto px-7 py-4 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm border border-white/20 transition duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-list-check text-xs"></i>
                        <span>My Referrals &amp; Rewards</span>
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-xs text-slate-300/80 font-medium">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        <span>100% Web-Based (No App Required)</span>
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-brand-cyan"></i>
                        <span>Direct UPI Payout</span>
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-brand-cyan"></i>
                        <span>Verified &amp; Fraud-Protected</span>
                    </span>
                </div>
            </div>

            <!-- Interactive Share & Refer Box -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 sm:p-8 text-brand-navy shadow-2xl border border-slate-100 relative">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-brand-cyan">Your Unique Referral Link</span>
                            <h3 class="text-lg font-bold text-brand-navy mt-0.5">Share with Property Owners</h3>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center">
                            <i class="fa-solid fa-share-nodes text-base"></i>
                        </div>
                    </div>

                    @if(auth()->check())
                        @php
                            $shareUrl = route('refer-and-earn', ['ref' => $userCode]);
                            $shareText = "List your property on Housing365 India for ₹0 Free Leasing! Use my referral link: {$shareUrl}";
                        @endphp

                        <div class="mt-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Your Referral Code</label>
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    <span class="font-mono font-black text-lg text-brand-navy tracking-widest">{{ $userCode }}</span>
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                                        Active
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Direct Referral URL</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" id="referralUrlInput" readonly value="{{ $shareUrl }}"
                                           class="flex-1 px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-700 truncate">
                                    <button type="button" onclick="copyReferralLink()" id="copyBtn"
                                            class="px-4 py-2.5 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs transition flex items-center gap-1.5">
                                        <i class="fa-regular fa-copy"></i>
                                        <span>Copy</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Social Web Share Buttons -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Instant Web Share</label>
                                <div class="grid grid-cols-4 gap-2">
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode($shareText) }}" target="_blank" rel="noopener noreferrer"
                                       class="p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs flex flex-col items-center justify-center gap-1 transition">
                                        <i class="fa-brands fa-whatsapp text-lg"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer"
                                       class="p-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs flex flex-col items-center justify-center gap-1 transition">
                                        <i class="fa-brands fa-facebook text-lg"></i>
                                        <span>Facebook</span>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}" target="_blank" rel="noopener noreferrer"
                                       class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs flex flex-col items-center justify-center gap-1 transition">
                                        <i class="fa-brands fa-x-twitter text-lg"></i>
                                        <span>X</span>
                                    </a>
                                    <button type="button" onclick="triggerWebShare('{{ $shareUrl }}', '{{ addslashes($shareText) }}')"
                                            class="p-3 rounded-xl bg-brand-cyan/10 hover:bg-brand-cyan/20 text-brand-navy font-bold text-xs flex flex-col items-center justify-center gap-1 transition">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-lg text-brand-cyan"></i>
                                        <span>Share</span>
                                    </button>
                                </div>
                            </div>

                            <div class="pt-2 text-center">
                                <a href="{{ route('dashboard.referrals.create') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center justify-center gap-1">
                                    <span>Or submit owner details directly</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 text-center space-y-4 py-4">
                            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-lock text-brand-cyan"></i>
                            </div>
                            <h4 class="text-base font-bold text-brand-navy">Sign In to Get Your Referral Link</h4>
                            <p class="text-xs text-slate-500 max-w-xs mx-auto">
                                Log in to generate your unique referral code, track your submitted referrals, and withdraw ₹{{ $rewardAmount }} rewards via UPI.
                            </p>
                            <div class="flex items-center justify-center gap-3 pt-2">
                                <a href="{{ route('login', ['redirect' => route('refer-and-earn')]) }}" class="px-5 py-2.5 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white text-xs font-bold transition">
                                    Log In
                                </a>
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-brand-navy text-xs font-bold transition">
                                    Create Free Account
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section (Original 4 Steps) -->
<section class="py-16 sm:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-black uppercase tracking-widest text-brand-cyan">Simple &amp; Transparent</span>
            <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-brand-navy mt-1">
                How Refer &amp; Earn Works
            </h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2">
                Four clear steps to earning ₹{{ $rewardAmount }} per property referral. No gimmicks, no complex rules.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 relative hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan font-black text-lg flex items-center justify-center mb-5">
                    1
                </div>
                <h3 class="text-base font-bold text-brand-navy mb-2">Submit Referral</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Provide the owner's name and contact number (Method A) or snap a photo of a genuine "To Let" / "For Sale" board (Method B).
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 relative hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-brand-navy/10 text-brand-navy font-black text-lg flex items-center justify-center mb-5">
                    2
                </div>
                <h3 class="text-base font-bold text-brand-navy mb-2">Verification &amp; Check</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Housing365 operations checks for duplicates, verifies genuine ownership, and reaches out to the owner to assist with listing.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 relative hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 font-black text-lg flex items-center justify-center mb-5">
                    3
                </div>
                <h3 class="text-base font-bold text-brand-navy mb-2">Property Goes Live</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    The owner completes their ₹0 Free listing on Housing365 India. Once published, your referral is automatically marked qualified!
                </p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 relative hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 font-black text-lg flex items-center justify-center mb-5">
                    4
                </div>
                <h3 class="text-base font-bold text-brand-navy mb-2">Earn ₹{{ $rewardAmount }} Direct UPI</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Your ₹{{ $rewardAmount }} reward is approved and disbursed straight to your verified UPI ID. Fast, secure, and hassle-free.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Submission Methods Breakdown -->
<section class="py-16 sm:py-20 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <!-- Method A Card -->
            <div class="rounded-3xl p-8 bg-gradient-to-br from-slate-50 to-white border border-slate-200 flex flex-col justify-between">
                <div>
                    <span class="px-3 py-1 rounded-full bg-brand-cyan/15 text-brand-cyan text-xs font-black uppercase tracking-wider">Method A</span>
                    <h3 class="text-2xl font-bold text-brand-navy mt-4 mb-3">Refer an Owner Directly</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                        Best when you personally know a landlord, flat owner, or commercial property holder looking to rent or sell. Simply enter their city, phone number, and property type.
                    </p>
                    <ul class="space-y-2.5 text-xs text-slate-600 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-600"></i> Owner Name &amp; 10-Digit Mobile</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-600"></i> Locality, City &amp; Listing Intent (Rent / Sale / PG)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-600"></i> Safe &amp; private — contact is never made public</li>
                    </ul>
                </div>
                <a href="{{ auth()->check() ? route('dashboard.referrals.create', ['method' => 'refer_owner']) : route('login') }}"
                   class="px-6 py-3.5 rounded-xl bg-brand-navy text-white text-xs font-bold text-center hover:bg-brand-navy-light transition">
                    Submit Owner Details &rarr;
                </a>
            </div>

            <!-- Method B Card -->
            <div class="rounded-3xl p-8 bg-gradient-to-br from-slate-50 to-white border border-slate-200 flex flex-col justify-between">
                <div>
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-black uppercase tracking-wider">Method B</span>
                    <h3 class="text-2xl font-bold text-brand-navy mt-4 mb-3">Upload Property Board Photo</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                        Spot a "To Let" or "For Sale" sign outside a building or flat? Take a clear photo showing the contact number and upload it directly.
                    </p>
                    <ul class="space-y-2.5 text-xs text-slate-600 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-600"></i> Upload board photo (JPG, PNG, WebP)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-600"></i> Encrypted private storage — not accessible to public</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-600"></i> Our operations team calls and assists the owner</li>
                    </ul>
                </div>
                <a href="{{ auth()->check() ? route('dashboard.referrals.create', ['method' => 'property_board_photo']) : route('login') }}"
                   class="px-6 py-3.5 rounded-xl bg-brand-cyan text-brand-navy text-xs font-black text-center hover:bg-brand-cyan-hover transition">
                    Upload Board Photo &rarr;
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQs Section -->
<section class="py-16 sm:py-20 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-black uppercase tracking-wider text-brand-cyan">Got Questions?</span>
            <h2 class="text-3xl font-display font-extrabold text-brand-navy mt-1">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h4 class="text-sm font-bold text-brand-navy mb-1.5">How much can I earn per referral?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    You earn exactly ₹{{ $rewardAmount }} for every successful eligible referral that gets verified and successfully listed on Housing365 India.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h4 class="text-sm font-bold text-brand-navy mb-1.5">When do I receive the ₹{{ $rewardAmount }} reward?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Reward eligibility triggers once the referred owner's property is published live. After admin verification, your reward is approved and you can request a direct UPI disbursement.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h4 class="text-sm font-bold text-brand-navy mb-1.5">Do I need a mobile app to participate?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    No! Housing365 Refer &amp; Earn is 100% web-based. You can submit referrals, view real-time qualification status, and withdraw rewards through any modern browser on your phone, tablet, or laptop.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h4 class="text-sm font-bold text-brand-navy mb-1.5">What happens if two people refer the same owner or property?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Housing365 follows a strict first-valid attribution policy. The first user to submit the genuine, qualifying referral gets the ₹{{ $rewardAmount }} reward. Duplicate submissions are flagged and rejected.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h4 class="text-sm font-bold text-brand-navy mb-1.5">Can I refer brokers or real estate agents?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    No. The Refer &amp; Earn program is specifically designed to onboard genuine direct property owners. Commercial brokers, builder sales reps, and professional intermediaries are flagged and ineligible for referral rewards.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h4 class="text-sm font-bold text-brand-navy mb-1.5">Where can I read the full terms?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    You can review the complete program terms, eligibility definitions, and anti-fraud guidelines on our <a href="{{ route('refer-and-earn.terms') }}" class="text-brand-cyan font-bold hover:underline">Referral Program Terms</a> page.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Copy Link Script -->
<script>
function copyReferralLink() {
    const input = document.getElementById('referralUrlInput');
    const btn = document.getElementById('copyBtn');
    if (!input || !btn) return;

    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(() => {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Copied!</span>';
        btn.classList.remove('bg-brand-navy');
        btn.classList.add('bg-emerald-600');
        setTimeout(() => {
            btn.innerHTML = '<i class="fa-regular fa-copy"></i> <span>Copy</span>';
            btn.classList.remove('bg-emerald-600');
            btn.classList.add('bg-brand-navy');
        }, 2000);
    });
}

function triggerWebShare(url, text) {
    if (navigator.share) {
        navigator.share({
            title: 'Refer & Earn ₹100 | Housing365 India',
            text: text,
            url: url
        }).catch(() => {});
    } else {
        copyReferralLink();
    }
}
</script>
@endsection
