@extends('layouts.app')

@section('title', 'Refer & Earn Terms & Conditions | Housing365 India')
@section('meta_description', 'Official terms and conditions for Housing365 India Refer & Earn ₹100 Referral Acquisition Program.')

@section('content')
<section class="py-12 sm:py-16 bg-slate-50 min-h-[80vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-slate-200">
            <div class="border-b border-slate-100 pb-6 mb-8">
                <span class="text-xs font-black uppercase tracking-wider text-brand-cyan">Official Policy</span>
                <h1 class="text-3xl sm:text-4xl font-display font-extrabold text-brand-navy mt-1">
                    Housing365 Refer &amp; Earn Program Terms
                </h1>
                <p class="text-xs text-slate-500 mt-2">
                    Last Updated: {{ date('F j, Y') }} • Effective across all Indian States &amp; Union Territories
                </p>
            </div>

            <div class="prose prose-sm max-w-none text-slate-700 space-y-6 text-xs sm:text-sm leading-relaxed">
                <div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">1. Program Overview &amp; Eligibility</h3>
                    <p>
                        The Housing365 Refer &amp; Earn program allows any registered, authenticated user on Housing365 India (regardless of user persona — owner, tenant, broker, builder, or vendor) to refer genuine property owners and earn a fixed reward of ₹{{ $rewardAmount }} per qualifying referral.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">2. Definition of an Eligible Referral</h3>
                    <p>A referral is recognized as eligible and qualified for reward payout if and only if:</p>
                    <ul class="list-disc pl-5 space-y-1.5 mt-2">
                        <li>The referred property owner is genuine, directly owns or controls the property, and is not an existing registered lister on Housing365.</li>
                        <li>The property does not already exist on Housing365 India's live marketplace.</li>
                        <li>The referred owner successfully publishes a verified listing for rent, sale, PG, co-living, or commercial use on Housing365 India.</li>
                        <li>The referral passes all anti-fraud, duplicate contact, and ownership verification criteria.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">3. First-Valid Referral Attribution &amp; Duplicate Policy</h3>
                    <p>
                        If multiple users refer the same owner or property, Housing365 operates strictly on a first-valid submission basis. The first user whose referral submission satisfies qualification criteria receives the ₹{{ $rewardAmount }} reward. All subsequent submissions for the same normalized contact number or property address are marked as duplicates and rejected.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">4. Self-Referrals &amp; Broker Protection</h3>
                    <p>
                        Self-referrals (referring one's own contact number, email, or properties) are strictly prohibited. Referrals identified as commercial real estate brokers, builder sales representatives, or professional lead brokers will be flagged and disqualified from receiving owner referral rewards.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">5. Reward Calculation &amp; Payout Mechanics</h3>
                    <p>
                        The reward for each eligible referral is ₹{{ $rewardAmount }}. Rewards are credited to the referring user's reward ledger upon property publication and administrator qualification. Approved rewards are disbursed via UPI to the verified UPI ID provided by the user. Payout requests are processed by Housing365 accounts and finance.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">6. Privacy &amp; Data Security</h3>
                    <p>
                        Referred owner information is strictly protected under Housing365 privacy principles. Contact information is used solely for the legitimate purpose of listing assistance. Referral data is never sold, shared, or made publicly available on search engines, public HTML, or open APIs. Participation in the referral program does not grant direct Contact Access to private seller details.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">7. Property Management Independence</h3>
                    <p>
                        Submitting a referral does not automatically enroll the owner into paid Property Management. All properties referred are onboarded under Path A (Leasing Only, ₹0 free listing). Property Management remains strictly optional and requires explicit digital agreement by the owner.
                    </p>
                </div>

                <div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">8. Program Modification &amp; Termination</h3>
                    <p>
                        Housing365 India reserves the right to amend, pause, or terminate the Refer &amp; Earn program, modify reward criteria, or revoke rewards obtained through fraudulent, automated, or abusive mechanisms at its sole discretion.
                    </p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('refer-and-earn') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to Refer &amp; Earn</span>
                </a>
                <a href="{{ route('contact') }}" class="text-xs font-bold text-slate-500 hover:text-brand-navy transition">
                    Contact Compliance Support &rarr;
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
