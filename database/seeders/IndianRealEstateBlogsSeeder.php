<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IndianRealEstateBlogsSeeder extends Seeder
{
    public function run(): void
    {
        $posts = $this->getBlogPosts();

        foreach ($posts as $postData) {
            BlogPost::updateOrCreate(
                ['slug' => $postData['slug']],
                [
                    'title' => $postData['title'],
                    'category' => $postData['category'],
                    'excerpt' => $postData['excerpt'],
                    'content' => $postData['content'],
                    'image' => $postData['image'],
                    'author_name' => $postData['author_name'],
                    'read_time' => $postData['read_time'],
                    'is_published' => true,
                    'published_at' => $postData['published_at'],
                ]
            );
        }
    }

    private function getBlogPosts(): array
    {
        return [
            // ==========================================
            // GROUP 1: RERA & BUILDER LEGALITIES (1-16)
            // ==========================================
            [
                'title' => 'Understanding RERA Act 2016: How It Protects Indian Homebuyers From Builder Malpractices',
                'slug' => 'understanding-rera-act-2016-homebuyer-protection',
                'category' => 'RERA Compliance',
                'read_time' => '7 min read',
                'author_name' => 'Housing365 Legal & Advisory',
                'published_at' => now()->subDays(115),
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'The Real Estate (Regulation and Development) Act, 2016 fundamentally transformed Indian real estate. Learn how RERA enforces financial discipline, transparency, and timely delivery.',
                'content' => '<h2>The Historic Shift in Indian Real Estate Regulation</h2>
<p>Prior to the enactment of the Real Estate (Regulation and Development) Act, 2016 (RERA), the Indian residential housing market was heavily titled in favor of developers. Buyers routinely faced indefinite possession delays, one-sided builder-buyer agreements, sudden price escalation clauses, and diversion of funds to other speculative projects.</p>

<p>RERA established a dedicated regulatory watchdog in every state and Union Territory to protect consumers, enforce financial transparency, and bring standardized operational guidelines to real estate transactions.</p>

<div class="my-6 p-5 bg-sky-50 border-l-4 border-brand-cyan rounded-r-2xl">
    <h4 class="text-brand-navy font-bold text-base mb-1">Key Legislative Landmark</h4>
    <p class="text-sm text-slate-700">Under Section 3 of RERA, no promoter can advertise, market, book, sell, or offer for sale any plot, apartment, or commercial building without first registering the project with the state Real Estate Regulatory Authority.</p>
</div>

<h3>Core Pillars of Homebuyer Protection Under RERA</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Standardized Carpet Area:</strong> Builders can only quote and sell properties based on actual usable carpet area, eliminating misleading "super built-up area" loadings.</li>
    <li><strong>70% Escrow Account Mandate:</strong> Developers must deposit 70% of all customer collections into a separate scheduled bank escrow account dedicated strictly to construction and land acquisition costs of that specific project.</li>
    <li><strong>5-Year Structural Defect Guarantee:</strong> Developers are legally liable to repair structural, design, and workmanship defects at zero cost within 30 days of notification for 5 years after possession.</li>
    <li><strong>Equal Penalties for Delays:</strong> Under Section 18, builders must pay the same interest rate on delayed possession (typically SBI MCLR + 2%) as they charge buyers for late installment payments.</li>
</ul>

<h3>How RERA Regulates Fast-Track Dispute Resolution</h3>
<p>Unlike civil courts that took decades to adjudicate property disputes, RERA authorities are mandated to dispose of complaints within 60 days. Buyers can file online complaints for delays, defective construction, or unapproved plan alterations directly on their state portal (such as MahaRERA, UP RERA, HRERA, or Karnataka RERA).</p>',
            ],
            [
                'title' => 'RERA Carpet Area vs Built-Up vs Super Built-Up: Practical Measurement Guide',
                'slug' => 'rera-carpet-area-vs-built-up-vs-super-built-up',
                'category' => 'RERA Compliance',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Research Desk',
                'published_at' => now()->subDays(112),
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Confused by real estate floor area terminology? Discover the exact legal formula for RERA carpet area and how to identify inflated super built-up loading in builder brochures.',
                'content' => '<h2>Why Area Measurement Matters When Buying Property</h2>
<p>One of the most widespread malpractices in the pre-RERA era was the inflation of usable area using obscure definitions of "super built-up area." Buyers frequently paid for 1,500 sq. ft. but received an apartment with barely 950 sq. ft. of usable floor space—a 35% to 40% "loading" charge.</p>

<p>RERA put an end to this ambiguity by defining a uniform, legally binding formula for <strong>Carpet Area</strong> across all states in India.</p>

<h3>Understanding the Three Key Area Classifications</h3>
<div class="space-y-4 my-6">
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">1. RERA Carpet Area</h4>
        <p class="text-sm text-slate-600 mt-1">The net usable floor area of an apartment, excluding external walls, service shafts, and exclusive balcony or verandah area, but including internal partition walls of the apartment.</p>
    </div>
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">2. Built-Up Area</h4>
        <p class="text-sm text-slate-600 mt-1">The carpet area plus the thickness of outer perimeter walls, balcony spaces, and utility dry balconies attached to the unit.</p>
    </div>
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">3. Super Built-Up Area</h4>
        <p class="text-sm text-slate-600 mt-1">The built-up area plus a proportionate share of common areas like corridors, staircases, lift lobbies, clubhouse, and security cabins. While commonly quoted in resale, RERA mandates sales contracts to specify net carpet area.</p>
    </div>
</div>

<h3>The Exact RERA Carpet Area Formula</h3>
<p class="font-mono bg-slate-900 text-sky-400 p-4 rounded-xl text-sm leading-relaxed">
RERA Carpet Area = Usable Internal Floor Area + Internal Partition Walls<br>
(Excludes: External Walls, Balconies, Verandahs, Private Terraces, Service Shafts)
</p>

<h3>Checklist for Homebuyers Before Signing</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li>Always insist on the exact RERA Carpet Area in the registered Agreement for Sale.</li>
    <li>Calculate the effective per-sq-ft rate using carpet area rather than super area.</li>
    <li>Verify that private balconies and terraces are priced separately as line items rather than blended into carpet area.</li>
</ul>',
            ],
            [
                'title' => 'How to Verify Builder RERA Registration Number Online on State Portals',
                'slug' => 'how-to-verify-builder-rera-registration-number-online',
                'category' => 'RERA Compliance',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Advisory',
                'published_at' => now()->subDays(108),
                'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Step-by-step tutorial for searching and verifying any residential project on state RERA portals like MahaRERA, UP RERA, HRERA, and K-RERA before paying a token deposit.',
                'content' => '<h2>Never Pay a Token Without Verifying RERA Details</h2>
<p>Every legitimate real estate project in India with land size exceeding 500 sq. meters or more than 8 apartments must possess a valid RERA Registration Number. Promoters are legally required to display this number and their official state RERA portal link in every advertisement, hoarding, brochure, and newspaper release.</p>

<h3>What Information Can You Find on the RERA Website?</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Sanctioned Building Plans:</strong> Verify how many floors, towers, and units were actually approved by the municipal corporation.</li>
    <li><strong>Declared Completion Date:</strong> Check the developer\'s committed possession date declared under oath to the authority (often later than sales pitch promises).</li>
    <li><strong>Quarterly Progress Reports (QPR):</strong> Review updated photos of construction milestones, percentage completion, and financial expenditures.</li>
    <li><strong>Financial Encumbrances:</strong> See which banks or NBFCs have taken a mortgage on the land or project to fund construction.</li>
    <li><strong>Litigation History:</strong> Review past and pending legal disputes filed by other buyers or landowners against the promoter.</li>
</ul>

<h3>State-Wise Verification Links &amp; Steps</h3>
<div class="overflow-x-auto my-6">
    <table class="w-full text-left text-sm border border-slate-200">
        <thead class="bg-slate-100 text-brand-navy font-bold">
            <tr>
                <th class="p-3 border-b">State Authority</th>
                <th class="p-3 border-b">Official Website</th>
                <th class="p-3 border-b">Search Option</th>
            </tr>
        </thead>
        <tbody class="divide-y text-slate-700">
            <tr>
                <td class="p-3 font-semibold">Maharashtra (MahaRERA)</td>
                <td class="p-3">maharera.mahaonline.gov.in</td>
                <td class="p-3">Search Project by Name or Reg No.</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Uttar Pradesh (UP RERA)</td>
                <td class="p-3">up-rera.in</td>
                <td class="p-3">Project Search (Noida/Lucknow)</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Haryana (HRERA)</td>
                <td class="p-3">haryanarera.gov.in</td>
                <td class="p-3">Gurugram / Panchkula Authority search</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Karnataka (K-RERA)</td>
                <td class="p-3">rera.karnataka.gov.in</td>
                <td class="p-3">Approved Projects / Registered Promoters</td>
            </tr>
        </tbody>
    </table>
</div>',
            ],
            [
                'title' => 'The 5-Year Structural Defect Liability Clause Under Section 14(3) of RERA',
                'slug' => '5-year-structural-defect-liability-clause-rera-section-14',
                'category' => 'RERA Compliance',
                'read_time' => '5 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(105),
                'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb18615f8?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Did you know your builder is legally required to repair structural cracks, leakages, and workmanship faults for five full years after handover? Learn how Section 14(3) works.',
                'content' => '<h2>Free Defect Rectification for 5 Years</h2>
<p>One of the most powerful consumer protections in RERA is <strong>Section 14(3)</strong>. Prior to RERA, builders offered a nominal one-year warranty on fixtures or simply walked away once the final installment was paid, leaving Resident Welfare Associations (RWAs) to spend millions fixing premature water seepage, foundation cracking, or faulty lifts.</p>

<p>Section 14(3) places statutory liability on the developer for five full years from the date of handing over possession.</p>

<h3>What Qualifies as a Structural or Quality Defect?</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Structural Defects:</strong> Beam cracks, pillar degradation, slab seepage, foundation shifting, or masonry destabilization.</li>
    <li><strong>Workmanship Defects:</strong> Substandard tiling, peeling plaster, improper window framing causing rain infiltration, or poor electrical conduit fitting.</li>
    <li><strong>Service &amp; Equipment Failures:</strong> Defective transformer installations, failing elevator hydraulics, and faulty sewage treatment plants (STPs).</li>
</ul>

<div class="my-6 p-5 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl">
    <h4 class="text-brand-navy font-bold text-base mb-1">Mandatory 30-Day Resolution Timeline</h4>
    <p class="text-sm text-slate-700">Once an allottee or society notifies the promoter in writing of a defect within five years, the promoter is legally required to rectify the defect within 30 days without charging the allottee. If the developer fails, the aggrieved buyer is entitled to comprehensive financial compensation.</p>
</div>',
            ],
            [
                'title' => 'RERA 70% Escrow Account Mandate: How It Prevents Fund Diversion by Builders',
                'slug' => 'rera-70-percent-escrow-account-mandate-fund-diversion',
                'category' => 'RERA Compliance',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Financial Desk',
                'published_at' => now()->subDays(101),
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Explore the financial mechanics of Section 4(2)(l)(D): why builders can no longer use money collected from your building to purchase land for another venture.',
                'content' => '<h2>The Root Cause of Stalled Housing Projects</h2>
<p>The primary reason thousands of housing projects across Delhi NCR, Mumbai, and Bengaluru were stalled between 2010 and 2017 was unconstrained fund diversion. Developers collected 50% to 70% of apartment values from buyers and used that liquidity to bid on new land parcels or service high-interest shadow-bank loans rather than completing the towers under construction.</p>

<h3>Section 4(2)(l)(D): Ring-Fencing Project Capital</h3>
<p>To eliminate this systemic risk, RERA introduced a strict escrow account requirement:</p>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>70% Direct Deposit:</strong> At least 70% of every rupee realized from allottees must be deposited into a dedicated project escrow account in a scheduled bank.</li>
    <li><strong>Strictly Restricted Withdrawals:</strong> Funds can only be withdrawn to pay for actual land acquisition costs and verified construction expenditures for that exact project.</li>
    <li><strong>Triple Certification Requirement:</strong> Before each withdrawal, the promoter must submit three independent certificates:
        <ul class="list-circle pl-6 mt-1 space-y-1 text-sm text-slate-600">
            <li>Certificate from the project Engineer confirming completed physical milestones.</li>
            <li>Certificate from the project Architect verifying structural specifications.</li>
            <li>Certificate from a practicing Chartered Accountant confirming the withdrawal proportion matches the percentage of completion.</li>
        </ul>
    </li>
</ul>',
            ],
            [
                'title' => 'Possession Delay Penalties: How to Claim Monthly Interest from Builders Under Section 18',
                'slug' => 'possession-delay-penalties-monthly-interest-section-18',
                'category' => 'RERA Compliance',
                'read_time' => '7 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(98),
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'When your builder misses the committed delivery date, Section 18 of RERA gives you two powerful legal rights: full refund with interest or monthly compensation until handover.',
                'content' => '<h2>Your Statutory Rights When Possession Is Delayed</h2>
<p>Missing possession dates used to be treated casually by promoters who offered nominal compensation clauses in builder contracts—often a meager ₹5 per square foot per month while charging buyers 18% per annum for delayed installment payments.</p>

<p>RERA abolished this asymmetry through <strong>Section 18</strong>, which mandates that buyers have statutory rights that override any unfair contractual clauses.</p>

<h3>The Two Options Available to Buyers Under Section 18</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-6">
    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
        <h4 class="font-bold text-brand-navy text-base mb-2">Option A: Complete Withdrawal from Project</h4>
        <p class="text-sm text-slate-600">If the allottee chooses to exit the project, the promoter must return the entire principal amount paid to date, along with compensatory interest calculated from the date of each payment up to the refund date, plus compensation within 45 to 60 days.</p>
    </div>
    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
        <h4 class="font-bold text-brand-navy text-base mb-2">Option B: Continue in Project with Monthly Interest</h4>
        <p class="text-sm text-slate-600">If the buyer prefers to retain the property, the promoter must pay monthly interest for every month of delay until the Occupancy Certificate is obtained and legal possession is handed over.</p>
    </div>
</div>

<h3>Standard Interest Rate Formula</h3>
<p class="font-mono bg-slate-900 text-sky-400 p-4 rounded-xl text-sm">
Prescribed Rate = State Bank of India Highest Marginal Cost of Funds Based Lending Rate (MCLR) + 2% per annum.
</p>',
            ],
            [
                'title' => 'MahaRERA Conciliation Forum vs Formal Complaint: Step-by-Step Guide',
                'slug' => 'maharera-conciliation-forum-vs-formal-complaint-guide',
                'category' => 'RERA Compliance',
                'read_time' => '6 min read',
                'author_name' => 'Priya Venkatesh',
                'published_at' => now()->subDays(95),
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Navigating real estate disputes in Mumbai, Pune, Thane, or Navi Mumbai? Compare the cost, timeline, and enforceability of MahaRERA Conciliation benches against formal complaints.',
                'content' => '<h2>MahaRERA: India\'s Most Active Real Estate Regulatory Authority</h2>
<p>Maharashtra has been at the forefront of RERA implementation since 2017. While filing a formal complaint under Section 31 before the MahaRERA Authority is the traditional route, MahaRERA also pioneered the <strong>MahaRERA Conciliation and Dispute Resolution Forum</strong>.</p>

<h3>What Is the Conciliation Forum?</h3>
<p>The conciliation forum pairs one representative from consumer organizations (like Mumbai Grahak Panchayat) with one representative from builder associations (like CREDAI-MCHI or NAREDCO). Together, they act as neutral conciliators to help the developer and allottee reach an amicable, legally binding settlement without protracted hearings.</p>

<h3>Comparison: Conciliation vs Formal Complaint</h3>
<div class="overflow-x-auto my-6">
    <table class="w-full text-left text-sm border border-slate-200">
        <thead class="bg-slate-100 text-brand-navy font-bold">
            <tr>
                <th class="p-3 border-b">Feature</th>
                <th class="p-3 border-b">Conciliation Forum</th>
                <th class="p-3 border-b">Formal Complaint (Form M)</th>
            </tr>
        </thead>
        <tbody class="divide-y text-slate-700">
            <tr>
                <td class="p-3 font-semibold">Filing Fee</td>
                <td class="p-3">₹1,000 + GST</td>
                <td class="p-3">₹5,000</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Average Timeline</td>
                <td class="p-3">30 to 45 Days</td>
                <td class="p-3">6 to 18 Months</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Nature of Proceeding</td>
                <td class="p-3">Collaborative, informal discussion</td>
                <td class="p-3">Adjudicatory, quasi-judicial hearing</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Enforceability</td>
                <td class="p-3">Equal to a civil court consent decree</td>
                <td class="p-3">Enforceable regulatory order</td>
            </tr>
        </tbody>
    </table>
</div>',
            ],
            [
                'title' => 'UP RERA Recovery Certificates (RCs): How Buyers Recover Principal and Interest',
                'slug' => 'up-rera-recovery-certificates-rc-guide',
                'category' => 'RERA Compliance',
                'read_time' => '6 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(91),
                'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'When a developer ignores a UP RERA refund decree in Noida or Lucknow, a Recovery Certificate is issued. Understand how the District Administration attaches assets to recover funds.',
                'content' => '<h2>Enforcing RERA Orders When Promoters Refuse to Pay</h2>
<p>One of the recurring challenges faced by homebuyers in Noida, Greater Noida, and Ghaziabad has been the execution of refund and interest orders. While UP RERA has ruled decisively in favor of consumers in thousands of cases, some insolvent or stubborn promoters failed to deposit the awarded amounts.</p>

<h3>How the Recovery Certificate (RC) Mechanism Works</h3>
<p>Under <strong>Section 40(1)</strong> of RERA read with the UP RERA Rules, any amount due from a promoter can be recovered as an arrear of land revenue:</p>

<ol class="list-decimal pl-6 space-y-3 text-slate-700 my-4">
    <li><strong>Order &amp; Default:</strong> UP RERA issues a refund/interest order. The builder is given 45 days to comply.</li>
    <li><strong>Execution Application:</strong> If the builder defaults, the buyer files an online execution application on the UP RERA portal.</li>
    <li><strong>Issuance of RC:</strong> The Authority issues a Recovery Certificate directed to the District Magistrate (DM) of the district where the builder\'s assets or registered office are located.</li>
    <li><strong>Tehsildar Action:</strong> The District Administration treats the decree as land revenue arrears, empowering Tehsildars to freeze bank accounts, seal project offices, and auction immovable assets.</li>
</ol>',
            ],
            [
                'title' => 'Haryana RERA: Key Differences Between Gurugram and Panchkula Benches',
                'slug' => 'haryana-rera-gurugram-vs-panchkula-rules',
                'category' => 'RERA Compliance',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Legal & Advisory',
                'published_at' => now()->subDays(87),
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Haryana is unique in having two distinct RERA authorities. Understand which bench governs your property, key regulations on Occupation Certificates, and delayed charges.',
                'content' => '<h2>Two Benches, One State: Why HRERA Is Bifurcated</h2>
<p>Because Gurugram alone represents one of the largest and most dense high-rise residential corridors in Asia, the state of Haryana established two separate RERA bodies:</p>

<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>HRERA Gurugram Bench:</strong> Holds exclusive territorial jurisdiction over all projects, promoters, and real estate agents operating within the District of Gurugram.</li>
    <li><strong>HRERA Panchkula Bench:</strong> Governs all other remaining 21 districts of Haryana, including Faridabad, Sonipat, Karnal, Rohtak, and Panchkula.</li>
</ul>

<h3>Key Landmark HRERA Rules for NCR Buyers</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Strict Ban on Pre-Launches:</strong> Marketing or collecting token money in "expression of interest" (EOI) or soft-launch schemes without active registration attracts severe penalties up to 10% of total project cost.</li>
    <li><strong>Broker Accountability:</strong> Real estate brokers and channel partners must hold valid HRERA agent licenses. Recommending an unregistered project makes the broker jointly liable for buyer restitution.</li>
</ul>',
            ],
            [
                'title' => 'Karnataka RERA (K-RERA): Protecting Bengaluru IT Corridor Homebuyers',
                'slug' => 'karnataka-rera-k-rera-bengaluru-homebuyer-guide',
                'category' => 'RERA Compliance',
                'read_time' => '6 min read',
                'author_name' => 'Priya Venkatesh',
                'published_at' => now()->subDays(84),
                'image' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'A guide for Bengaluru tech professionals buying apartments in Whitefield, Sarjapur, or Electronic City. How K-RERA audits plan sanctions and protects buyers from delayed projects.',
                'content' => '<h2>Navigating Bengaluru\'s High-Growth Real Estate Market</h2>
<p>Bengaluru\'s tech corridors have witnessed unprecedented residential expansion. However, issues such as unapproved layout extensions, water clearance hurdles, and lake buffer zone violations have historically created severe complications for homebuyers.</p>

<p>Karnataka RERA (K-RERA) provides an essential layer of verification before committing capital to a Bengaluru property.</p>

<h3>Key K-RERA Checkpoints Before Signing in Bengaluru</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Check BDA / BMRDA Sanction:</strong> Ensure the layout approval on the K-RERA portal matches the municipal body (BBMP, BDA, or BMRDA) layout plans.</li>
    <li><strong>Verify Environmental &amp; Pollution Clearances:</strong> High-density projects in Bengaluru must upload KSPCB (Karnataka State Pollution Control Board) and SEIAA approvals.</li>
    <li><strong>Monitor Tower-Wise Handover Timelines:</strong> In large phased townships, verify whether the promoter registered the entire master development or individual phases.</li>
</ul>',
            ],
            [
                'title' => 'Section 11 Promoter Responsibilities: Formation of RWA and Conveyance Deeds',
                'slug' => 'section-11-promoter-responsibilities-rwa-conveyance',
                'category' => 'RERA Compliance',
                'read_time' => '5 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(81),
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'When must a builder hand over society management to the residents? Explore statutory timelines under Section 11 of RERA for forming an RWA and transferring title.',
                'content' => '<h2>Why Societies Struggle to Gain Control from Developers</h2>
<p>In many housing societies, builders retain control of maintenance, clubhouse rentals, and parking long after residents move in, using facility management subsidiaries to generate recurring profits. Section 11 of RERA establishes explicit timelines to protect resident autonomy.</p>

<h3>Statutory Mandates Under Section 11(4)</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Formation of RWA / Society:</strong> The promoter must facilitate the formation of an association, society, or cooperative body of allottees within three months of the majority of allottees booking their apartments.</li>
    <li><strong>Execution of Conveyance Deed:</strong> The promoter must execute a registered conveyance deed transferring ownership of the underlying land and common areas to the association of allottees within three months of obtaining the Occupancy Certificate.</li>
    <li><strong>Transfer of Maintenance Corpus:</strong> All unspent maintenance funds, advance sink funds, and interest earned must be fully audited and transferred to the registered RWA bank account.</li>
</ul>',
            ],
            [
                'title' => 'Can a Builder Alter Sanctioned Layouts or Amenities Without Buyer Consent? Section 14 Explored',
                'slug' => 'can-builder-change-layout-plans-without-buyer-consent-section-14',
                'category' => 'RERA Compliance',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Legal & Advisory',
                'published_at' => now()->subDays(78),
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Did your builder shrink the central green park to add an extra residential tower? Discover how Section 14 mandates two-thirds allottee consent for any major plan modifications.',
                'content' => '<h2>The Era of Bait-and-Switch Floor Plans Is Over</h2>
<p>A frequent pre-RERA grievance was purchasing an apartment with promises of sprawling golf views, only to find the developer later constructed another 30-story high-rise on the amenity space. <strong>Section 14(2)</strong> of RERA guarantees that promoters cannot unilaterally modify approved project blueprints.</p>

<h3>The Two-Thirds (2/3rd) Consent Rule</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Individual Apartment Changes:</strong> No minor additions or alterations can be made to an individual apartment without the written consent of that specific allottee.</li>
    <li><strong>Common Amenities &amp; Master Layouts:</strong> Any major alteration to common areas, sanctioned layout plans, building heights, or promised common facilities requires prior written consent from at least <strong>two-thirds (66.6%) of all allottees</strong> who have booked units in the project.</li>
</ul>',
            ],
            [
                'title' => 'RERA Appellate Tribunal (REAT): Appeal Timelines and The 30% Pre-Deposit Rule',
                'slug' => 'rera-appellate-tribunal-reat-appeals-pre-deposit-rule',
                'category' => 'RERA Compliance',
                'read_time' => '6 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(74),
                'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Understanding the judicial hierarchy above state RERA bodies: how the Real Estate Appellate Tribunal functions and why the Supreme Court upheld the mandatory pre-deposit rule.',
                'content' => '<h2>The Appellate Tier in Real Estate Litigation</h2>
<p>If either the homebuyer or the promoter is aggrieved by a ruling delivered by the RERA Authority or an Adjudicating Officer, the law provides a direct statutory appeal mechanism before the <strong>Real Estate Appellate Tribunal (REAT)</strong>.</p>

<h3>Key Procedural Rules for Filing Appeals</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>60-Day Appeal Window:</strong> Any appeal before the Tribunal must be filed within 60 days from the date of receipt of the original RERA order.</li>
    <li><strong>The Pre-Deposit Mandate for Builders:</strong> Under Section 43(5), if a developer files an appeal against an order directing refund, compensation, or penalty, the appeal cannot be entertained unless the developer deposits at least <strong>30% of the penalty amount or the entire compensatory sum</strong> with the Tribunal.</li>
    <li><strong>Supreme Court Affirmation:</strong> The Supreme Court of India upheld this pre-deposit mandate as a necessary protection to deter promoters from filing frivolous, time-wasting appeals to stall payments to buyers.</li>
</ul>',
            ],
            [
                'title' => 'RERA for Commercial Real Estate: Offices, Retail Shops and Co-Working Spaces',
                'slug' => 'rera-for-commercial-properties-offices-retail-shops',
                'category' => 'RERA Compliance',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Research Desk',
                'published_at' => now()->subDays(70),
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Does RERA apply to office buildings, shopping malls, and fractional commercial units? Understand how RERA protects investors buying commercial real estate in India.',
                'content' => '<h2>Commercial Real Estate Falls Fully Under RERA Scope</h2>
<p>A common misconception among investors is that RERA applies solely to residential housing. In reality, Section 2(e) and Section 3 explicitly apply to <strong>both residential and commercial real estate</strong> developments exceeding 500 sq. meters or 8 units.</p>

<h3>Key Protections for Commercial Unit Buyers</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Retail Shops in Shopping Complexes:</strong> Builders must register retail developments and specify exact carpet area rather than ambiguous gross leasable area (GLA).</li>
    <li><strong>Virtual Office Spaces &amp; Fractional Investments:</strong> Projects offering "assured return" schemes on unpartitioned office floor plates must adhere to RERA disclosures and project timelines.</li>
    <li><strong>Timely Delivery of Power Infrastructure:</strong> Heavy-load electrical substations, HVAC chillers, and fire suppression systems promised in commercial specifications are enforceable under Section 14.</li>
</ul>',
            ],
            [
                'title' => 'Harmonizing IBC vs RERA: Homebuyers as Financial Creditors in Builder Insolvency',
                'slug' => 'harmonizing-ibc-vs-rera-homebuyers-as-financial-creditors',
                'category' => 'RERA Compliance',
                'read_time' => '7 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(66),
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'What happens when a developer is dragged into the National Company Law Tribunal (NCLT)? How the Insolvency and Bankruptcy Code (IBC) recognizes allottees as financial creditors.',
                'content' => '<h2>When Insolvency Clashes with RERA Proceedings</h2>
<p>When financial creditors or vendors initiate corporate insolvency resolution against a defaulting developer under the Insolvency and Bankruptcy Code (IBC), a moratorium under Section 14 of IBC is imposed. Historically, this halted ongoing RERA recovery proceedings.</p>

<h3>Homebuyers as Recognized Financial Creditors</h3>
<p>Through historic legislative amendments upheld by the Supreme Court of India in the landmark <em>Pioneer Urban Land and Infrastructure</em> case, allottees of real estate projects are recognized as <strong>Financial Creditors</strong> under the IBC:</p>

<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Voting Rights in Committee of Creditors (CoC):</strong> Homebuyers have proportionate representation on the CoC, voting on resolution plans to choose capable replacement builders who can finish the towers.</li>
    <li><strong>Project-Wise Insolvency:</strong> NCLAT has progressively favored "reverse corporate insolvency," restricting insolvency proceedings strictly to the stalled project rather than liquidating the promoter\'s entire company across unrelated sites.</li>
</ul>',
            ],
            [
                'title' => 'What Documents Must a Promoter Upload on RERA Portal Before Launching Sales?',
                'slug' => 'mandatory-documents-promoter-must-upload-on-rera-portal',
                'category' => 'RERA Compliance',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Advisory',
                'published_at' => now()->subDays(62),
                'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Audit your builder\'s compliance dossier: from title deed searches and sanctioned architectural plans to encumbrance certificates and chartered accountant project estimates.',
                'content' => '<h2>Transparency by Design: The Mandatory RERA Disclosure Vault</h2>
<p>Before any registration certificate is granted, the promoter must submit an exhaustive legal and financial dossier to the state authority. This information is published on the public portal for prospective buyers to scrutinize.</p>

<h3>The Essential 7 Documents to Verify</h3>
<ol class="list-decimal pl-6 space-y-2 text-slate-700">
    <li><strong>Authenticated Copy of Land Title:</strong> Proves clean, marketable title or a valid Joint Development Agreement (JDA) with registered landowners.</li>
    <li><strong>Commencement Certificate (CC):</strong> Issued by the local planning authority permitting construction up to specified floors.</li>
    <li><strong>Sanctioned Floor &amp; Elevation Plans:</strong> Architectural layouts approved by municipal fire, structural, and civil engineering bodies.</li>
    <li><strong>Pro-Forma Agreement for Sale:</strong> Must conform to RERA\'s statutory model agreement rather than one-sided builder contracts.</li>
    <li><strong>Bank Escrow Account Details:</strong> Official letter from a scheduled bank certifying the creation of the dedicated 70% project account.</li>
    <li><strong>CA-Audited Cost Estimates:</strong> Breakup of total estimated land cost and actual structural development cost.</li>
    <li><strong>List of Past 5-Year Projects:</strong> Track record of completed, delayed, or litigated projects delivered by the promoter group.</li>
</ol>',
            ],

            // ==========================================
            // GROUP 2: RENTING & TENANCY LEGALITIES (17-33)
            // ==========================================
            [
                'title' => 'Model Tenancy Act (MTA) 2021: Complete Landlord & Tenant Rights Breakdown',
                'slug' => 'model-tenancy-act-2021-landlord-tenant-rights-breakdown',
                'category' => 'Rental Legalities',
                'read_time' => '8 min read',
                'author_name' => 'Housing365 Editorial Desk',
                'published_at' => now()->subDays(59),
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'How the Union Cabinet\'s Model Tenancy Act balances property rights, security deposits, eviction rules, and digital tenancy registration across Indian states.',
                'content' => '<h2>A Modern Framework for India\'s Rental Housing Ecosystem</h2>
<p>For over half a century, urban rental housing in India was governed by antiquated Rent Control Acts enacted in the post-WWII era. These laws created artificial rent ceilings, discouraged landlords from renting vacant houses, and trapped property owners in multi-decade eviction lawsuits.</p>

<p>The <strong>Model Tenancy Act (MTA), 2021</strong> was framed by the Ministry of Housing and Urban Affairs to replace these archaic statutes with a transparent, equitable, and market-driven tenancy framework.</p>

<h3>Core Highlights of the Model Tenancy Act</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Mandatory Written Tenancy Agreements:</strong> Oral tenancy arrangements are illegal. Every rental agreement must be submitted digitally to the local Rent Authority within two months of execution.</li>
    <li><strong>Strict Security Deposit Limits:</strong>
        <ul class="list-circle pl-6 mt-1 space-y-1 text-sm text-slate-600">
            <li>Maximum <strong>two months\' rent</strong> for residential properties.</li>
            <li>Maximum <strong>six months\' rent</strong> for commercial properties.</li>
        </ul>
    </li>
    <li><strong>24-Hour Prior Notice for Entry:</strong> Landlords cannot enter premises without providing at least 24 hours\' advance notice via electronic message or written letter, respecting tenant privacy.</li>
    <li><strong>Specialized Fast-Track Rent Courts:</strong> Disposes of rent revision and eviction disputes within 60 days, bypassing civil court backlogs entirely.</li>
</ul>',
            ],
            [
                'title' => 'Security Deposit Limit in India: Why the 2-Month Cap Is Changing Rental Markets',
                'slug' => 'security-deposit-limit-india-two-month-cap-rules',
                'category' => 'Rental Legalities',
                'read_time' => '5 min read',
                'author_name' => 'Priya Venkatesh',
                'published_at' => now()->subDays(56),
                'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Say goodbye to 10-month advance deposits in Bengaluru and Mumbai. Learn how state tenancy laws and the Model Tenancy Act are capping deposits at two months.',
                'content' => '<h2>The Era of 10-Month Upfront Deposits Is Ending</h2>
<p>For decades, moving into a rented flat in Bengaluru, Mumbai, or Hyderabad required a massive capital outlay. Landlords frequently demanded 10 to 12 months of rent as an upfront interest-free security deposit—locking up ₹3 Lakhs to ₹6 Lakhs for a standard 2 BHK or 3 BHK apartment.</p>

<h3>The National Two-Month Statutory Standard</h3>
<p>Under Section 11 of the Model Tenancy Act, security deposits are capped at a maximum of two months\' rent for residential tenancies. States like Andhra Pradesh, Tamil Nadu, and Uttar Pradesh have enacted versions of this cap.</p>

<h3>When Must the Landlord Refund the Deposit?</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li>The security deposit must be refunded to the tenant on the date of vacation and physical handover of vacant possession.</li>
    <li>Only legitimate deductions for unpaid electricity/water bills and demonstrable physical damage beyond normal wear and tear are permitted.</li>
    <li>Unfairly withholding deposits can be challenged before the Rent Authority with interest penalties against the landlord.</li>
</ul>',
            ],
            [
                'title' => 'Registered Rent Agreement vs Notarized Agreement: Legal Admissibility & Stamp Duty',
                'slug' => 'registered-rent-agreement-vs-notarized-stamp-duty-guide',
                'category' => 'Rental Legalities',
                'read_time' => '6 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(53),
                'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Is a ₹100 stamp paper notarized agreement valid in court? Discover the crucial legal distinctions under Section 17 of the Registration Act 1908.',
                'content' => '<h2>The Common Fallacy of the Notarized Rent Agreement</h2>
<p>Across urban India, thousands of landlords and tenants execute an 11-month agreement on ₹100 stamp paper attested by a local public notary, assuming it provides complete legal protection. In legal reality, notarization merely verifies the identity of the signatories—it does <strong>not</strong> validate the legal enforceability of the tenancy terms.</p>

<h3>Comparison: Notarized vs Registered Tenancy Agreement</h3>
<div class="overflow-x-auto my-6">
    <table class="w-full text-left text-sm border border-slate-200">
        <thead class="bg-slate-100 text-brand-navy font-bold">
            <tr>
                <th class="p-3 border-b">Parameter</th>
                <th class="p-3 border-b">Notarized Agreement</th>
                <th class="p-3 border-b">Registered / e-Stamped Lease</th>
            </tr>
        </thead>
        <tbody class="divide-y text-slate-700">
            <tr>
                <td class="p-3 font-semibold">Legal Registration</td>
                <td class="p-3">Not registered with Sub-Registrar</td>
                <td class="p-3">Registered under Registration Act 1908</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Court Admissibility</td>
                <td class="p-3">Inadmissible as primary evidence if unstamped</td>
                <td class="p-3">Primary admissible evidence in any court</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Official Address Proof</td>
                <td class="p-3">Rejected for Passports &amp; Bank KYC</td>
                <td class="p-3">Accepted by Passport Seva &amp; UIDAI</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Stamp Duty Payment</td>
                <td class="p-3">Often under-stamped (₹50/₹100)</td>
                <td class="p-3">Paid per state government stamp schedule</td>
            </tr>
        </tbody>
    </table>
</div>',
            ],
            [
                'title' => 'Police Tenant Verification: Online Process, Documents & Penalties for Non-Compliance',
                'slug' => 'police-tenant-verification-online-process-documents-penalties',
                'category' => 'Rental Legalities',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Tenancy Advisory',
                'published_at' => now()->subDays(49),
                'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Skipping tenant police verification can land landlords in jail under Section 188 of the Indian Penal Code. Step-by-step digital filing guide for Delhi, Mumbai, Bengaluru, and Gurugram.',
                'content' => '<h2>Why Tenant Police Verification Is Legally Mandatory</h2>
<p>Police verification is not an optional courtesy; it is a mandatory legal directive issued by city Police Commissioners under Section 144 of the Code of Criminal Procedure (CrPC). Landlords who fail to register tenant details can be booked under <strong>Section 188 of the Indian Penal Code (IPC)</strong> for disobeying orders promulgated by public servants.</p>

<h3>Documents Required for Online Verification</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li>Government Photo ID of Tenant &amp; Landlord (Aadhaar, Passport, or Voter ID).</li>
    <li>Passport-sized photographs of all adult occupants.</li>
    <li>Proof of Employment or Student Enrollment (Company ID, Offer Letter, or College ID).</li>
    <li>Permanent address proof of the tenant with reference contact numbers.</li>
    <li>Executed and signed copy of the Rent Agreement.</li>
</ul>

<h3>Online Portals by City</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Delhi:</strong> Delhi Police Citizen Portal (delhipolice.gov.in).</li>
    <li><strong>Mumbai:</strong> Mumbai Police Tenant Verification Portal (mumbaipolice.gov.in).</li>
    <li><strong>Bengaluru:</strong> Karnataka Police Seva Sindhu / Local Station Verification.</li>
    <li><strong>Gurugram / Faridabad:</strong> HarSamay Haryana Police Citizen Portal.</li>
</ul>',
            ],
            [
                'title' => 'Landlord vs Tenant Maintenance: Who Pays for Plumbing, Electrical & Painting?',
                'slug' => 'landlord-vs-tenant-maintenance-repair-responsibility-guide',
                'category' => 'Rental Legalities',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Care Desk',
                'published_at' => now()->subDays(46),
                'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Stop arguing over repair bills. Clear demarcation under the Model Tenancy Act specifying which repairs belong to the landlord and which are the tenant\'s duty.',
                'content' => '<h2>Demystifying Property Maintenance Responsibilities</h2>
<p>Disputes over who pays for a malfunctioning geyser, external water seepage, or routine plumbing repairs are the number one cause of friction between landlords and tenants. The Second Schedule of the Model Tenancy Act codifies these responsibilities with precision.</p>

<h3>Repairs Borne Exclusively by the Landlord</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Structural Integrity:</strong> Major wall plaster repairs, terrace waterproofing, structural beam maintenance, and foundation issues.</li>
    <li><strong>Electrical Infrastructure:</strong> Replacement of concealed internal wiring, main circuit breaker (MCB) panels, and earthing setups.</li>
    <li><strong>External Painting:</strong> Periodic repainting of external building facades and common hallways.</li>
    <li><strong>Sanitary Pipe Replacements:</strong> Major drainage pipe leakages and concealed wall plumbing line bursts.</li>
</ul>

<h3>Repairs Borne by the Tenant (Wear &amp; Tear)</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Consumable Electricals:</strong> Changing light bulbs, tube lights, switch covers, and internal socket plugs.</li>
    <li><strong>Minor Plumbing:</strong> Replacement of tap washers, shower heads, geyser thermostats, and drain clearing.</li>
    <li><strong>Kitchen Fixtures:</strong> Gas pipe servicing, chimney cleaning, and minor carpentry on cupboard hinges.</li>
    <li><strong>Internal Cleanliness:</strong> Daily upkeep, pest control, and returning the property in clean condition upon move-out.</li>
</ul>',
            ],
            [
                'title' => 'Society NOC for Bachelors, Pet Owners, and Single Women: Supreme Court Precedents',
                'slug' => 'society-noc-bachelors-pets-single-women-rules',
                'category' => 'Rental Legalities',
                'read_time' => '6 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(42),
                'image' => 'https://images.unsplash.com/photo-1545239351-ef35f43d514b?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Can a Cooperative Housing Society ban bachelor tenants or pet dogs? The law is clear: bye-laws cannot violate constitutional fundamental rights.',
                'content' => '<h2>Arbitrary Society Bye-Laws Under the Legal Microscope</h2>
<p>Across major Indian metros, Resident Welfare Associations (RWAs) and Cooperative Housing Societies (CHS) routinely formulate arbitrary internal resolutions refusing entry to single bachelors, unmarried professionals, single women, non-vegetarians, or pet owners.</p>

<h3>What Does the Law Say?</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Ownership Rights:</strong> The registered owner of a flat has full constitutional rights under Article 300A to lease their private property to any law-abiding citizen.</li>
    <li><strong>Fundamental Rights:</strong> Article 19(1)(e) guarantees all Indian citizens the right to reside in any part of India. An RWA bye-law cannot supersede statutory and constitutional provisions.</li>
    <li><strong>Supreme Court on Pets:</strong> The Supreme Court of India in the landmark <em>Animal Welfare Board of India</em> guidelines held that housing societies cannot ban domestic pets, restrict pet usage of lifts, or impose discriminatory pet fees.</li>
</ul>

<div class="my-6 p-5 bg-sky-50 border-l-4 border-brand-cyan rounded-r-2xl">
    <h4 class="text-brand-navy font-bold text-base mb-1">Police &amp; Registrar Redressal</h4>
    <p class="text-sm text-slate-700">Societies attempting to physically block a verified tenant from moving in can be reported to the Registrar of Cooperative Societies and the local police station for illegal restraint under Section 341 of the IPC.</p>
</div>',
            ],
            [
                'title' => 'How to Legally Evict a Non-Paying Tenant Under State Tenancy Laws',
                'slug' => 'how-to-legally-evict-non-paying-tenant-india',
                'category' => 'Rental Legalities',
                'read_time' => '7 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(39),
                'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Never change the locks or disconnect utilities. Follow the legal due process for non-payment eviction through formal legal notice and Rent Court orders.',
                'content' => '<h2>Never Resort to Self-Help Eviction Tactics</h2>
<p>When a tenant stops paying rent or refuses to vacate upon lease expiry, frustrated landlords sometimes resort to shutting off electricity, cutting water connections, or locking the premises. <strong>This is illegal.</strong> Under Indian law, disconnecting essential services or forceful dispossession can lead to criminal charges against the landlord under the Tenancy Acts.</p>

<h3>Lawful Eviction Procedure Under the Model Tenancy Act</h3>
<ol class="list-decimal pl-6 space-y-3 text-slate-700 my-4">
    <li><strong>Valid Default Ground:</strong> Non-payment of rent for two consecutive months constitutes a valid statutory ground for eviction under Section 21.</li>
    <li><strong>Serve Formal Legal Notice:</strong> Issue an advocate-vetted legal notice via registered post granting the tenant 15 to 30 days to clear all outstanding rent arrears or vacate.</li>
    <li><strong>Petition to the Rent Court:</strong> If the tenant fails to comply, file an eviction petition before the designated Rent Court / Rent Authority.</li>
    <li><strong>Double / Quadruple Rent Penalty:</strong> Under Section 23 of the Model Tenancy Act, a tenant who overstays past lease termination without court permission is liable to pay <strong>double the monthly rent for the first two months</strong> and <strong>four times the monthly rent</strong> thereafter until physical possession is handed back.</li>
</ol>',
            ],
            [
                'title' => 'TDS on Rent Under Section 194-IB: What Tenants Paying Over ₹50,000/Month Must Know',
                'slug' => 'tds-on-rent-section-194-ib-income-tax-guide',
                'category' => 'Rental Legalities',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Financial Desk',
                'published_at' => now()->subDays(35),
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Renting a premium apartment in India? If your monthly rent exceeds ₹50,000, you are legally required to deduct 5% TDS. Step-by-step Form 26QC compliance guide.',
                'content' => '<h2>Section 194-IB: The Tax Rule Every Luxury Tenant Must Follow</h2>
<p>Introduced in the Finance Act 2017, <strong>Section 194-IB</strong> of the Income Tax Act places a statutory obligation on individuals and Hindu Undivided Families (HUFs) paying rent exceeding <strong>₹50,000 per month</strong> (or part of a month) to deduct Tax Deducted at Source (TDS) at the rate of 5%.</p>

<h3>Key Compliance Checkpoints</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>No TAN Required:</strong> Unlike businesses, individual salaried tenants do not need a Tax Deduction Account Number (TAN). The deduction is executed simply using the tenant\'s PAN and landlord\'s PAN.</li>
    <li><strong>Timing of Deduction:</strong> TDS must be deducted either in the last month of the financial year (March) or in the final month of the tenancy if the lease ends earlier.</li>
    <li><strong>Filing Form 26QC:</strong> The deducted amount must be deposited online via Challan-cum-statement Form 26QC within 30 days from the end of the month in which tax was deducted.</li>
    <li><strong>Issuing Form 16C:</strong> The tenant must download and issue TDS Certificate Form 16C from the TRACES portal to the landlord within 15 days of filing Form 26QC.</li>
</ul>',
            ],
            [
                'title' => 'Rent Receipts & Rent Agreement Checklist for Claiming Maximum HRA Exemption',
                'slug' => 'rent-receipts-agreement-checklist-hra-tax-exemption',
                'category' => 'Rental Legalities',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Financial Desk',
                'published_at' => now()->subDays(31),
                'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Maximize your take-home salary legally under Section 10(13A). Checklist for landlord PAN declaration, monthly revenue stamps, and valid lease formats.',
                'content' => '<h2>Unlocking Legitimate Tax Savings on House Rent Allowance</h2>
<p>House Rent Allowance (HRA) is one of the most substantial tax deductions available to salaried employees in India under Section 10(13A) of the Income Tax Act, 1961. However, claims are frequently rejected or audited due to incomplete paperwork.</p>

<h3>The Golden Rules for Valid HRA Documentation</h3>
<ol class="list-decimal pl-6 space-y-2 text-slate-700">
    <li><strong>Active Registered / Stamped Lease:</strong> Ensure you have a signed agreement covering the exact financial year with clear rental clauses.</li>
    <li><strong>Landlord PAN Mandatory Above ₹1,00,000/year:</strong> If your total rent paid exceeds ₹8,333 per month (₹1 Lakh annually), reporting the landlord\'s PAN to your employer is legally mandatory.</li>
    <li><strong>Revenue Stamps for Cash Payments:</strong> If paying cash over ₹5,000 per month, each monthly receipt must bear a ₹1 revenue stamp signed across by the landlord. Digital bank transfers (NEFT/UPI) are strongly recommended as supporting evidence.</li>
    <li><strong>Co-Habitation with Parents:</strong> If paying rent to parents, ensure proper rent agreements and bank transfers exist, and your parents declare this rental income on their respective ITR filings.</li>
</ol>',
            ],
            [
                'title' => 'Subletting & Airbnb Hosting: Can a Tenant Sublet Without Landlord Consent?',
                'slug' => 'subletting-airbnb-hosting-laws-india-tenant-guide',
                'category' => 'Rental Legalities',
                'read_time' => '5 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(28),
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Thinking of listing a spare bedroom on Airbnb or subleasing your rented flat while traveling? Discover why subletting without written permission invites immediate lease termination.',
                'content' => '<h2>The Legal Reality of Subletting in India</h2>
<p>With the boom in home-sharing platforms like Airbnb and short-term co-living, many tenants consider subletting an empty room or listing their entire rented apartment during holiday periods. However, doing so without express contractual authorization constitutes a direct breach of contract.</p>

<h3>Statutory Ban on Unapproved Subletting</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Model Tenancy Act Section 7:</strong> Subletting in whole or part is strictly prohibited unless authorized through a supplementary agreement executed between the landlord and tenant.</li>
    <li><strong>Immediate Eviction Grounds:</strong> Unapproved subletting provides the landlord an unassailable ground for immediate lease cancellation, forfeiture of deposit, and expedited eviction.</li>
    <li><strong>Commercial vs Residential Usage:</strong> Running a commercial bed-and-breakfast or hostel from a residential flat violates local municipal zoning laws and housing society bye-laws.</li>
</ul>',
            ],
            [
                'title' => 'Lock-In Period vs Notice Period: Resolving Early Lease Termination Disputes',
                'slug' => 'lock-in-period-vs-notice-period-early-termination',
                'category' => 'Rental Legalities',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Tenancy Advisory',
                'published_at' => now()->subDays(24),
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'What happens if you have to relocate for work during a 6-month lock-in period? Learn how Indian courts interpret lock-in clauses and liquidated damages.',
                'content' => '<h2>Understanding the Difference Between Lock-In and Notice Period</h2>
<p>Almost every urban tenancy agreement contains both a "lock-in period" and a "notice period," yet their legal implications are frequently conflated when job relocations or life changes demand early exit.</p>

<h3>Definitions &amp; Legal Enforceability</h3>
<div class="space-y-4 my-6">
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">Lock-In Period (Typically 1 to 6 Months)</h4>
        <p class="text-sm text-slate-600 mt-1">A period during which neither party can terminate the tenancy without penalty. If a tenant leaves during the lock-in, contracts often specify that the remaining months\' rent is forfeited.</p>
    </div>
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">Notice Period (Typically 30 to 60 Days)</h4>
        <p class="text-sm text-slate-600 mt-1">The advance written warning either party must provide after the lock-in period ends to terminate the lease smoothly and arrange for security deposit reconciliation.</p>
    </div>
</div>

<h3>Supreme Court View on Lock-In Penalties</h3>
<p>Under Section 74 of the Indian Contract Act, a landlord cannot mechanically pocket six months of rent as a "penalty" unless they demonstrate actual financial loss. The landlord has a legal duty to mitigate damages by attempting to re-rent the unit. Once a replacement tenant moves in, the previous tenant cannot be held liable for overlapping rent.</p>',
            ],
            [
                'title' => 'Rent Escalation Clauses: Standard 5% to 10% Annual Increments Explained',
                'slug' => 'rent-escalation-clauses-standard-annual-increments',
                'category' => 'Rental Legalities',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Advisory Desk',
                'published_at' => now()->subDays(21),
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'How to negotiate fair, transparent rent escalation clauses. Why the standard 5% to 10% annual increase should be linked to prevailing market yields.',
                'content' => '<h2>The Standard 11-Month Renewal Mechanism</h2>
<p>In residential tenancies across India, agreements are standardly signed for 11 months to avoid mandatory Sub-Registrar lease registration under Section 17 of the Registration Act. Upon renewal, a standard <strong>Rent Escalation Clause</strong> dictates the revised asking rent.</p>

<h3>Market Benchmarks for Rent Escalation</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Residential Tenancies:</strong> Standard annual escalation rates range between <strong>5% and 8%</strong> in mature metropolitan areas, occasionally touching 10% in high-demand IT hubs like Bengaluru and Hyderabad.</li>
    <li><strong>Commercial Leases:</strong> Standard commercial escalation is structured as <strong>15% every three years</strong> or 5% annually, linked to Consumer Price Index (CPI) movements.</li>
    <li><strong>Model Tenancy Act Provisions:</strong> Landlords must provide at least three months\' written notice before revising rent, and tenants have 30 days to accept or issue notice to vacate.</li>
</ul>',
            ],
            [
                'title' => 'PG vs Co-Living vs Independent Rental: Financial & Lifestyle Comparison',
                'slug' => 'pg-vs-coliving-vs-independent-rental-comparison',
                'category' => 'Rental Legalities',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Research Desk',
                'published_at' => now()->subDays(18),
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Moving to a new city? Compare monthly budgets, privacy, deposit requirements, and amenities between traditional PGs, managed co-living spaces, and full flats.',
                'content' => '<h2>Choosing the Right Accommodation for Your Career Phase</h2>
<p>India\'s urban workforce mobility has created three distinct rental models. Each serves different lifestyles, budgets, and commitment levels.</p>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">Paying Guest (PG)</h4>
        <p class="text-xs text-slate-500 mt-1 mb-2">Budget: ₹7,000 - ₹14,000/mo</p>
        <ul class="text-xs text-slate-600 space-y-1">
            <li>• Meals and laundry included</li>
            <li>• Shared rooms (2-3 sharing)</li>
            <li>• Strict house rules &amp; curfews</li>
            <li>• Minimal security deposit (1 mo)</li>
        </ul>
    </div>
    <div class="p-4 bg-sky-50 border border-brand-cyan/30 rounded-xl">
        <h4 class="font-bold text-brand-navy">Managed Co-Living</h4>
        <p class="text-xs text-brand-cyan mt-1 mb-2">Budget: ₹15,000 - ₹30,000/mo</p>
        <ul class="text-xs text-slate-600 space-y-1">
            <li>• High-speed Wi-Fi &amp; gaming zones</li>
            <li>• Housekeeping &amp; gym access</li>
            <li>• No intrusive curfews</li>
            <li>• 1 to 2 months deposit</li>
        </ul>
    </div>
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">Independent Flat</h4>
        <p class="text-xs text-slate-500 mt-1 mb-2">Budget: ₹25,000 - ₹60,000/mo</p>
        <ul class="text-xs text-slate-600 space-y-1">
            <li>• Total privacy &amp; freedom</li>
            <li>• Full control over furnishing</li>
            <li>• You pay utilities &amp; maintenance</li>
            <li>• Higher deposit (2-5 months)</li>
        </ul>
    </div>
</div>',
            ],
            [
                'title' => 'Move-In Inspection Checklist: Protecting Your Security Deposit from Day One',
                'slug' => 'move-in-inspection-checklist-protect-security-deposit',
                'category' => 'Rental Legalities',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Care Desk',
                'published_at' => now()->subDays(15),
                'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Never move into a rented house without an executed fixture and condition report. Complete photo audit checklist to avoid unfair deductions at move-out.',
                'content' => '<h2>Document Every Existing Flaw Before You Unpack</h2>
<p>The simplest way to ensure 100% of your security deposit is returned is to create an irrefutable paper trail on the day you collect keys. Landlords rarely intend to deduct money unfairly, but after an 11-month tenancy, pre-existing chipped tiles or stained walls are frequently attributed to the outgoing tenant.</p>

<h3>The 6-Point Move-In Audit Protocol</h3>
<ol class="list-decimal pl-6 space-y-2 text-slate-700">
    <li><strong>Take High-Resolution Time-Stamped Photos:</strong> Photograph every wall, kitchen countertop, bathroom mirror, and wooden door.</li>
    <li><strong>Record Utility Meter Readings:</strong> Document the electricity meter (KWh units), gas meter, and water sub-meter readings alongside the owner.</li>
    <li><strong>Test Every Electrical Point:</strong> Plug a phone charger into every wall socket, test ceiling fans, geyser thermostats, and air conditioners.</li>
    <li><strong>Inspect Plumbing Lines:</strong> Run all taps and flushes simultaneously to check water pressure, drainage speed, and sink pipe leakage.</li>
    <li><strong>Compile an Inventory Checklist:</strong> List every piece of furniture, key set, remote control, and electronic appliance.</li>
    <li><strong>Exchange Counter-Signed Email:</strong> Send the photo archive and signed inventory document to the landlord via email within 48 hours of move-in.</li>
</ol>',
            ],
            [
                'title' => 'NRI Landlord Renting Guide: Power of Attorney, NRO Accounts & Section 195 TDS',
                'slug' => 'nri-landlord-renting-guide-poa-nro-accounts-tds-195',
                'category' => 'Rental Legalities',
                'read_time' => '7 min read',
                'author_name' => 'Housing365 Legal & Advisory',
                'published_at' => now()->subDays(12),
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Managing rental properties from the US, UK, or Gulf? Critical tax compliance rules under Section 195 (31.2% TDS), FEMA repatriation, and Special Power of Attorney.',
                'content' => '<h2>Managing Indian Real Estate from Overseas</h2>
<p>Non-Resident Indians (NRIs) own a substantial portion of premium residential housing in Mumbai, Gurugram, Bengaluru, and Hyderabad. While rental yields provide steady passive income, non-compliance with tax withholding and banking rules can trigger severe penalties.</p>

<h3>Key Regulatory Mandates for NRI Landlords</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Section 195 TDS (30% + Surcharges):</strong> Tenants renting from an NRI landlord are legally obligated to deduct tax under Section 195 at the flat rate of 31.2% unless the landlord obtains a Lower TDS Certificate (Form 13) from the Assessing Officer.</li>
    <li><strong>NRO Bank Account Mandate:</strong> Under Foreign Exchange Management Act (FEMA) guidelines, rental proceeds must be deposited into a Non-Resident Ordinary (NRO) account, not a regular resident savings account.</li>
    <li><strong>Repatriation via Form 15CA &amp; 15CB:</strong> Funds up to USD 1 Million per financial year can be repatriated overseas from NRO accounts after filing an online Form 15CA and CA certification on Form 15CB.</li>
</ul>',
            ],
            [
                'title' => 'Renting Commercial Properties in India: Lock-in, Fit-Outs, GST and Stamp Duty',
                'slug' => 'renting-commercial-properties-india-lockin-fitout-gst',
                'category' => 'Rental Legalities',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Research Desk',
                'published_at' => now()->subDays(9),
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Leasing retail shops, showrooms, or corporate offices? Comprehensive guide to rent-free fit-out periods, Common Area Maintenance (CAM), and 18% GST rules.',
                'content' => '<h2>Commercial vs Residential Leasing Dynamics</h2>
<p>Commercial leases are far more complex than residential agreements. They typically involve multi-year commitments (e.g., 3+3+3 or 5+5 lease models) and substantial upfront capital investment in interiors and fit-outs.</p>

<h3>Key Commercial Lease Terms Explained</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Rent-Free Fit-Out Period:</strong> Landlords typically grant 30 to 90 days of rent-free access for the tenant to complete civil interiors, HVAC ducting, and cabling before the rent clock starts ticking.</li>
    <li><strong>GST on Commercial Rent (18%):</strong> Commercial rentals attract 18% GST under the forward charge mechanism if the property owner is GST-registered, or via reverse charge if the tenant is registered.</li>
    <li><strong>Common Area Maintenance (CAM):</strong> Understand whether CAM charges are calculated on usable carpet area or gross building area, and whether power backup diesel costs are billed at actuals.</li>
</ul>',
            ],
            [
                'title' => 'What to Do If Your Landlord Withholds Your Security Deposit Unfairly',
                'slug' => 'what-to-do-if-landlord-withholds-security-deposit-unfairly',
                'category' => 'Rental Legalities',
                'read_time' => '6 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(6),
                'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Did your landlord invent arbitrary deductions of ₹50,000 upon moving out? Step-by-step legal roadmap to recover your deposit through Consumer Forum and Rent Authority.',
                'content' => '<h2>Your Legal Recourse Against Deposit Theft</h2>
<p>Unfair retention of security deposits is the most prevalent consumer grievance in urban rentals. Landlords often cite routine paint fading, minor scuffs, or non-existent damages to withhold large sums of tenant money.</p>

<h3>Step-by-Step Recovery Action Plan</h3>
<ol class="list-decimal pl-6 space-y-3 text-slate-700 my-4">
    <li><strong>Collate Objective Evidence:</strong> Gather your move-in condition photos, counter-signed inventory, and exit handover photos proving the premises were returned in good order.</li>
    <li><strong>Send an Advocate Legal Notice:</strong> Have an advocate issue a formal demand notice granting 15 days to refund the balance along with 18% interest per annum. In 70% of cases, receipt of a formal legal notice prompts settlement.</li>
    <li><strong>Approach District Consumer Disputes Commission:</strong> Tenants qualify as consumers under the Consumer Protection Act, 2019. You can file an online consumer complaint on the e-Daakhil portal for deficiency of service and unfair trade practice, seeking refund plus mental agony damages.</li>
</ol>',
            ],

            // ==========================================
            // GROUP 3: BUYING PROPERTY & TAXATION (34-50)
            // ==========================================
            [
                'title' => 'The Ultimate Legal Due Diligence Checklist for Buying Under-Construction Flats',
                'slug' => 'legal-due-diligence-checklist-buying-under-construction-flats',
                'category' => 'Home Buying Guide',
                'read_time' => '8 min read',
                'author_name' => 'Housing365 Legal & Advisory',
                'published_at' => now()->subDays(60),
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Never rely solely on bank approvals. Complete 12-point legal due diligence checklist covering title searches, environmental NOCs, and sanctioned plans before buying.',
                'content' => '<h2>Why Homebuyers Must Conduct Independent Due Diligence</h2>
<p>Many buyers falsely assume that because a leading national bank has "pre-approved" a builder project for home loans, the project is 100% legally watertight. Banks primarily assess financial feasibility and their own mortgage risk—they do not guarantee absence of consumer litigation or construction quality.</p>

<h3>The Comprehensive 12-Point Pre-Purchase Verification Checklist</h3>
<ol class="list-decimal pl-6 space-y-2 text-slate-700">
    <li><strong>RERA Registration Active:</strong> Verify on the state portal that the project registration is live and not expired or blacklisted.</li>
    <li><strong>Title Deed &amp; 30-Year Title Search:</strong> Verify an unbroken chain of ownership showing the promoter or landowner has marketable title.</li>
    <li><strong>Commencement Certificate (CC):</strong> Ensure the local municipal authority has issued a CC for your specific tower and floor level.</li>
    <li><strong>Height &amp; Airport Authority NOC:</strong> Mandatory for high-rises situated within civil aviation radar corridors.</li>
    <li><strong>Pollution &amp; Environmental Clearance (SEIAA):</strong> Projects exceeding 20,000 sq. meters must hold state environmental impact clearance.</li>
    <li><strong>Fire Safety NOC:</strong> Provisional fire department clearance for structural evacuation stairs and hydrants.</li>
    <li><strong>Non-Agricultural (NA) Land Order:</strong> Confirms the underlying agricultural land was officially converted for residential development.</li>
</ol>',
            ],
            [
                'title' => 'Title Search & 30-Year Title Deed Verification: Ensuring Marketable Ownership',
                'slug' => 'title-search-30-year-title-deed-verification-guide',
                'category' => 'Home Buying Guide',
                'read_time' => '7 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(57),
                'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'What is a mother deed? Why do Indian property lawyers search the Sub-Registrar\'s records for thirty years? How to uncover secret mortgages and family disputes.',
                'content' => '<h2>The Foundation of Real Estate Ownership: Title Integrity</h2>
<p>Unlike countries with centralized Torrens land registry guarantees, India operates on a system of presumptive property title through registered deeds. Establishing that the seller actually has unencumbered, marketable title requires tracing the property\'s ownership lineage back at least 30 years.</p>

<h3>What Is a "Mother Deed"?</h3>
<p>The Mother Deed (also known as the parent document) is the earliest traceable registered deed that establishes the original allotment, sale, or partition of the land parcel. Every subsequent sale deed, gift deed, or release deed branches from this root document.</p>

<h3>Key Warning Signs During Title Search</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Unbroken Chain of Conveyance:</strong> If Property moved from Party A to Party C without a recorded registered deed from Party B, the title chain is broken and suspect.</li>
    <li><strong>Undivided Hindu Family (HUF) Claims:</strong> Coparcenary rights of minor children or married daughters must be expressly released through registered Release Deeds.</li>
    <li><strong>Pledged Title Deeds:</strong> If the original paper deeds are not in physical possession of the seller, they may be mortgaged with a financial institution.</li>
</ul>',
            ],
            [
                'title' => 'Encumbrance Certificate (EC): How to Check Property Liens and Mortgages Online',
                'slug' => 'encumbrance-certificate-ec-check-property-liens-online',
                'category' => 'Home Buying Guide',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Advisory',
                'published_at' => now()->subDays(54),
                'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Learn the difference between Form 15 and Form 16 Encumbrance Certificates. How to search state registration portals (like IGR Maharashtra and Kaveri Online) to verify clean title.',
                'content' => '<h2>What Is an Encumbrance Certificate?</h2>
<p>An Encumbrance Certificate (EC) is an official government extract issued by the Sub-Registrar of Assurances documenting all registered transactions, mortgages, charges, or court attachments registered against a specific survey number or flat for a defined period (typically 15 to 30 years).</p>

<h3>Form 15 vs Form 16: Understanding Your EC Results</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-6">
    <div class="p-4 bg-emerald-50 border border-emerald-300 rounded-xl">
        <h4 class="font-bold text-brand-navy">Form 16 (Nil Encumbrance)</h4>
        <p class="text-sm text-slate-600 mt-1">Issued when no registered mortgage, lien, or third-party claim exists on the property during the searched timeframe. This is what buyers and home loan lenders demand.</p>
    </div>
    <div class="p-4 bg-amber-50 border border-amber-300 rounded-xl">
        <h4 class="font-bold text-brand-navy">Form 15 (Encumbrance Registered)</h4>
        <p class="text-sm text-slate-600 mt-1">Issued when mortgages, sales, court attachments, or partition deeds are on record. The buyer must verify that all previous mortgages were officially cleared via registered Release Deeds.</p>
    </div>
</div>',
            ],
            [
                'title' => 'Occupancy Certificate (OC) vs Completion Certificate (CC): Buying Without OC Risks',
                'slug' => 'occupancy-certificate-oc-vs-completion-certificate-cc-risks',
                'category' => 'Home Buying Guide',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Research Desk',
                'published_at' => now()->subDays(50),
                'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb18615f8?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Never move into or register a flat without an Occupancy Certificate. Explore water and power disconnection risks, municipal penalties, and loan ineligibility.',
                'content' => '<h2>The Critical Distinction Between CC and OC</h2>
<p>Many homebuyers confuse a Completion Certificate (CC) with an Occupancy Certificate (OC). While a CC confirms that the building was erected according to the sanctioned architectural height and setback norms, only an <strong>Occupancy Certificate</strong> certifies that the building is safe, habitable, and legally connected to civic utilities.</p>

<h3>What Happens If You Move In Without an OC?</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Commercial Utility Rates:</strong> Without an OC, municipal water and electricity boards classify connections as provisional/commercial, charging up to 3x higher utility tariffs.</li>
    <li><strong>Demolition / Sealing Risk:</strong> Buildings without an OC are classified as unauthorized structures, exposing residents to municipal sealing or demolition notices.</li>
    <li><strong>Severe Resale Disadvantage:</strong> Major national banks refuse home loans on properties lacking an OC, severely crushing your future resale liquidity.</li>
</ul>',
            ],
            [
                'title' => 'Understanding Khata in Karnataka: Difference Between A Khata, B Khata, and E-Khata',
                'slug' => 'understanding-khata-karnataka-a-khata-b-khata-e-khata',
                'category' => 'Home Buying Guide',
                'read_time' => '6 min read',
                'author_name' => 'Priya Venkatesh',
                'published_at' => now()->subDays(47),
                'image' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Buying an apartment or plot in Bengaluru? Discover what BBMP A Khata means, why B Khata properties are risky, and the new digital E-Khata system.',
                'content' => '<h2>What Is a Khata in Karnataka Property Law?</h2>
<p>In Karnataka, a "Khata" is an account maintained by the municipal corporation (Bruhat Bengaluru Mahanagara Palike - BBMP) identifying the person legally responsible for paying property taxes for a specific parcel. While a Khata is an assessment register document rather than proof of legal ownership title, it is mandatory for bank loans and building plan sanctions.</p>

<h3>A Khata vs B Khata Explained</h3>
<div class="space-y-4 my-6">
    <div class="p-4 bg-emerald-50 border border-emerald-300 rounded-xl">
        <h4 class="font-bold text-brand-navy">A Khata (Full Civic Compliance)</h4>
        <p class="text-sm text-slate-600 mt-1">Properties that comply with all building bye-laws, possess approved plan sanctions, land conversion orders, and completion certificates. A Khata properties enjoy easy home loan approval and seamless transferability.</p>
    </div>
    <div class="p-4 bg-amber-50 border border-amber-300 rounded-xl">
        <h4 class="font-bold text-brand-navy">B Khata (Conditional Tax Register)</h4>
        <p class="text-sm text-slate-600 mt-1">Maintained under Section 108A of the KMC Act to collect property taxes from properties with layout violations, unapproved land use, or missing completion certificates. B Khata is not an official legal title and most nationalized banks will not fund B Khata units.</p>
    </div>
</div>',
            ],
            [
                'title' => 'State-Wise Stamp Duty & Registration Charges: Maharashtra, Karnataka, Delhi, UP & Haryana',
                'slug' => 'state-wise-stamp-duty-registration-charges-india',
                'category' => 'Tax & Stamp Duty',
                'read_time' => '7 min read',
                'author_name' => 'Housing365 Financial Desk',
                'published_at' => now()->subDays(44),
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Calculate your total closing costs: detailed stamp duty, registration fee, metro cess, and local body surcharges across India\'s top residential property markets.',
                'content' => '<h2>Closing Costs: The 6% to 8% Overhead on Home Buying</h2>
<p>When budgeting for a home purchase in India, buyers must account for state stamp duty and registration fees. These levies are non-negotiable statutory payments due to the State Inspector General of Registration (IGR) upon executing the Sale Deed.</p>

<h3>State-Wise Rates Overview (2026 Benchmarks)</h3>
<div class="overflow-x-auto my-6">
    <table class="w-full text-left text-sm border border-slate-200">
        <thead class="bg-slate-100 text-brand-navy font-bold">
            <tr>
                <th class="p-3 border-b">State / Union Territory</th>
                <th class="p-3 border-b">Standard Stamp Duty</th>
                <th class="p-3 border-b">Concession for Women</th>
                <th class="p-3 border-b">Registration Fee</th>
            </tr>
        </thead>
        <tbody class="divide-y text-slate-700">
            <tr>
                <td class="p-3 font-semibold">Maharashtra (Mumbai/Pune)</td>
                <td class="p-3">5% - 6% (incl. 1% Metro Cess)</td>
                <td class="p-3">1% Concession (4% - 5%)</td>
                <td class="p-3">1% (capped at ₹30,000 for >₹30L)</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Karnataka (Bengaluru)</td>
                <td class="p-3">5% + Surcharges (total ~5.6%)</td>
                <td class="p-3">Concessions on lower ticket sizes</td>
                <td class="p-3">2% of property value</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Delhi NCT</td>
                <td class="p-3">6% (Male)</td>
                <td class="p-3">4% (Female) / 5% (Joint)</td>
                <td class="p-3">1% of transaction value</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Haryana (Gurugram)</td>
                <td class="p-3">7% (Male in municipal limits)</td>
                <td class="p-3">5% (Female) / 6% (Joint)</td>
                <td class="p-3">Slab-based (₹10,000 - ₹50,000)</td>
            </tr>
            <tr>
                <td class="p-3 font-semibold">Uttar Pradesh (Noida/Ghaziabad)</td>
                <td class="p-3">7% of property value</td>
                <td class="p-3">₹10,000 rebate for female buyers</td>
                <td class="p-3">1% (capped at ₹20,000)</td>
            </tr>
        </tbody>
    </table>
</div>',
            ],
            [
                'title' => 'Section 194-IA: Mandatory 1% TDS on Property Purchases Above ₹50 Lakhs',
                'slug' => 'section-194-ia-mandatory-1-percent-tds-property-purchase',
                'category' => 'Tax & Stamp Duty',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Financial Desk',
                'published_at' => now()->subDays(40),
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Buying a property priced at ₹50 Lakhs or more? You, as the buyer, must deduct 1% TDS before paying the seller. Challan 26QB filing process and interest penalties.',
                'content' => '<h2>The Buyer\'s Statutory Duty to Deduct Tax</h2>
<p>Under <strong>Section 194-IA</strong> of the Income Tax Act, 1961, any buyer purchasing immovable property (other than agricultural land) where the total consideration or circle rate exceeds <strong>₹50 Lakhs</strong> is legally required to deduct 1% TDS from the amount payable to the seller.</p>

<h3>Critical Rules You Must Follow</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Calculation Basis:</strong> 1% TDS applies to the higher of the actual consideration amount or the stamp duty circle rate value.</li>
    <li><strong>Include Ancillary Charges:</strong> Under amended provisions, parking charges, club membership, electricity meters, and advance maintenance must be included in the ₹50 Lakh calculation threshold.</li>
    <li><strong>Challan 26QB Submission:</strong> The buyer must remit the deducted tax online via Form 26QB within 30 days from the end of the month in which deduction was made.</li>
    <li><strong>Issue Form 16B:</strong> The buyer must download Certificate Form 16B from TRACES and furnish it to the seller so the seller can claim tax credit on their ITR.</li>
</ul>',
            ],
            [
                'title' => 'Home Loan Tax Deductions: Optimizing Sections 80C, 24(b), and 80EEA Under Income Tax',
                'slug' => 'home-loan-tax-deductions-sections-80c-24b-80eea',
                'category' => 'Tax & Stamp Duty',
                'read_time' => '7 min read',
                'author_name' => 'Housing365 Financial Desk',
                'published_at' => now()->subDays(37),
                'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Unlock up to ₹5 Lakhs in annual tax deductions across home loan principal and interest repayments. Detailed breakdown under Old vs New Tax Regimes.',
                'content' => '<h2>Tax Benefits That Lower the Effective Cost of Your Home Loan</h2>
<p>Equated Monthly Installments (EMIs) consist of two distinct components: principal repayment and interest payment. Under the Old Tax Regime, the Income Tax Act provides robust incentives for homeowners.</p>

<h3>Summary of Home Loan Deductions</h3>
<div class="space-y-4 my-6">
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">Section 80C: Principal Repayment</h4>
        <p class="text-sm text-slate-600 mt-1">Deduct up to <strong>₹1,50,000 per year</strong> towards the principal portion of your EMI, plus stamp duty and registration charges paid in the year of purchase.</p>
    </div>
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">Section 24(b): Interest on Housing Loan</h4>
        <p class="text-sm text-slate-600 mt-1">Deduct up to <strong>₹2,00,000 per year</strong> for a self-occupied property. For let-out (rented) properties, there is no upper cap on interest deduction, though set-off against other income heads is capped at ₹2 Lakhs per annum.</p>
    </div>
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
        <h4 class="font-bold text-brand-navy">Joint Ownership Advantage</h4>
        <p class="text-sm text-slate-600 mt-1">If husband and wife co-own the property and are co-borrowers on the loan, <strong>both can claim full individual deductions</strong> (up to ₹3 Lakh under 80C and ₹4 Lakh under 24(b) combined).</p>
    </div>
</div>',
            ],
            [
                'title' => 'Buying Resale Property vs New Builder Launch: Cost, Documentation & Hidden Expenses',
                'slug' => 'buying-resale-property-vs-new-builder-launch-comparison',
                'category' => 'Home Buying Guide',
                'read_time' => '7 min read',
                'author_name' => 'Housing365 Research Desk',
                'published_at' => now()->subDays(34),
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Weighing ready-to-move resale flats against under-construction builder projects? Comprehensive comparison of GST savings, society transfer fees, and possession certainty.',
                'content' => '<h2>Evaluating the Resale vs Under-Construction Dilemma</h2>
<p>Every prospective homebuyer faces the fundamental choice between booking an under-construction unit from a developer or purchasing a pre-owned flat in a completed society.</p>

<h3>Key Trade-Offs</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>GST Advantage:</strong> Ready-to-move-in properties with an Occupancy Certificate attract <strong>0% GST</strong>, saving 5% compared to under-construction projects.</li>
    <li><strong>Possession Certainty:</strong> Resale properties eliminate construction delay risks and double financial burdens (paying rent while servicing pre-EMIs).</li>
    <li><strong>Society Transfer Charges:</strong> In resale transactions in Maharashtra and Delhi, factor in society transfer fees (capped at ₹25,000 by Maharashtra CHS bye-laws, though private builders in NCR often demand higher transfer fees).</li>
    <li><strong>Modern Amenities vs Established Neighborhoods:</strong> New launches often offer superior clubhouses and EV charging infrastructure, whereas resale flats enjoy mature road connectivity and established schools.</li>
</ul>',
            ],
            [
                'title' => 'Joint Property Ownership in India: Tax Benefits, Tenancy in Common & Succession',
                'slug' => 'joint-property-ownership-india-tax-benefits-succession',
                'category' => 'Home Buying Guide',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Financial Desk',
                'published_at' => now()->subDays(30),
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Purchasing real estate jointly with your spouse or parents? How to draft clear ownership percentage clauses and avoid bitter succession battles.',
                'content' => '<h2>The Strategic Benefits of Joint Ownership</h2>
<p>Purchasing residential property jointly with a spouse, sibling, or parent is increasingly popular in urban India. Beyond emotional security, joint ownership offers clear financial and legal advantages.</p>

<h3>Key Advantages</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Higher Loan Eligibility:</strong> Combining household incomes increases loan sanction limits from lending banks.</li>
    <li><strong>Double Tax Relief:</strong> Both co-owners can claim separate deductions under Section 80C (up to ₹1.5L each) and Section 24(b) (up to ₹2L each) in proportion to their ownership share.</li>
    <li><strong>Stamp Duty Rebates:</strong> Many states (like Delhi, Haryana, and Punjab) offer 1% to 2% lower stamp duty rates when a woman is a sole or joint owner.</li>
</ul>

<h3>Joint Tenancy vs Tenancy in Common</h3>
<p>Ensure the Sale Deed specifies whether the co-owners hold the property as "Joint Tenants with Right of Survivorship" (where the deceased owner\'s share passes automatically to the surviving co-owner) or as "Tenants in Common" (where each owner\'s share passes to their respective legal heirs according to succession laws).</p>',
            ],
            [
                'title' => 'Power of Attorney (GPA) Property Sales: Supreme Court Ruling and Severe Legal Risks',
                'slug' => 'power-of-attorney-gpa-property-sales-supreme-court-risks',
                'category' => 'Home Buying Guide',
                'read_time' => '6 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(27),
                'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Never buy property solely on General Power of Attorney and Agreement to Sell. The landmark Suraj Lamp ruling and why GPA does not transfer ownership title.',
                'content' => '<h2>The Illusory Security of "GPA Sales"</h2>
<p>In various parts of Delhi, Uttar Pradesh, and unauthorized colonies across India, properties are frequently traded through a cocktail of informal documents: General Power of Attorney (GPA), Agreement to Sell, Affidavit, and a Will. Buyers mistakenly believe this constitutes full legal ownership.</p>

<h3>The Landmark Suraj Lamp Supreme Court Judgment</h3>
<p>In the historic judgment of <em>Suraj Lamp &amp; Industries Pvt. Ltd. vs. State of Haryana (2011)</em>, the Supreme Court of India conclusively settled the law:</p>

<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>No Transfer of Title:</strong> A General Power of Attorney is an agency document authorizing an agent to perform acts on behalf of the principal; it does <strong>not</strong> confer ownership rights or title in immovable property.</li>
    <li><strong>Section 54 Transfer of Property Act:</strong> Immovable property valued over ₹100 can only be legally transferred via a registered <strong>Deed of Conveyance / Sale Deed</strong> executed on appropriate stamp duty.</li>
    <li><strong>Zero Loan Eligibility:</strong> No regulated bank or housing finance company will sanction a mortgage on GPA transactions.</li>
</ul>',
            ],
            [
                'title' => 'Agricultural Land Conversion to Non-Agricultural (NA / CLU): Essential Guide for Plot Buyers',
                'slug' => 'agricultural-land-conversion-na-clu-plot-buyers-guide',
                'category' => 'Home Buying Guide',
                'read_time' => '7 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(23),
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Buying a plotted layout or farmhouse land? What is Change of Land Use (CLU)? How to verify agricultural land conversion orders with the District Revenue Department.',
                'content' => '<h2>Why You Cannot Simply Build on Farmland</h2>
<p>In all Indian states, agricultural land is strictly zoned for farming. Constructing a residential villa, commercial godown, or plotting scheme on un-converted agricultural land is illegal and invites demolition notices under state Land Revenue Codes.</p>

<h3>The Non-Agricultural (NA) Conversion Procedure</h3>
<ol class="list-decimal pl-6 space-y-2 text-slate-700">
    <li><strong>Master Plan Alignment:</strong> The land parcel must fall within the designated residential or mixed-use zone of the regional master plan.</li>
    <li><strong>Revenue Collector / SDO Application:</strong> The owner applies to the District Collector or Sub-Divisional Magistrate (SDM) for Change of Land Use (CLU) / NA conversion.</li>
    <li><strong>Clearance of Dues &amp; Conversion Premium:</strong> Payment of the prescribed state conversion fee and verification that no land ceiling or tenancy restrictions (e.g., Section 79A/B in Karnataka or Section 43 in Maharashtra) are violated.</li>
    <li><strong>Official NA Sanction Order:</strong> The Revenue Department issues an official NA Conversion Order, updating revenue records (7/12 extract or Patta/Chitta).</li>
</ol>',
            ],
            [
                'title' => 'Hidden Costs of Home Buying: GST, Parking, Clubhouse, Franking & Advance Maintenance',
                'slug' => 'hidden-costs-of-home-buying-gst-parking-clubhouse-charges',
                'category' => 'Home Buying Guide',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Financial Desk',
                'published_at' => now()->subDays(20),
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'A flat advertised at ₹1 Crore often ends up costing ₹1.22 Crores on paper. Complete breakdown of 15% to 22% ancillary charges beyond Base Selling Price (BSP).',
                'content' => '<h2>The Gap Between Base Price and Actual All-Inclusive Outflow</h2>
<p>Developer brochures and property portals prominently market properties using Base Selling Price (BSP). However, when signing the allotment sheet, buyers discover substantial mandatory add-ons that inflate the final purchase budget by 15% to 22%.</p>

<h3>The 8 Hidden Closing Costs Checklist</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Stamp Duty &amp; Registration (5% to 8%):</strong> Paid directly to the state government.</li>
    <li><strong>GST on Under-Construction (5%):</strong> Standard 5% GST on non-affordable housing (1% on affordable housing).</li>
    <li><strong>Car Parking Allocation (₹2L - ₹7L):</strong> Stilt, covered, or basement parking allocation fees.</li>
    <li><strong>Clubhouse &amp; Infrastructure Development (₹2L - ₹5L):</strong> Electrification, water infrastructure, sewage treatment plant (STP), and lifestyle club membership.</li>
    <li><strong>Preferential Location Charges (PLC):</strong> 5% to 15% surcharge for corner flats, park-facing units, or higher floor levels.</li>
    <li><strong>Advance Maintenance Corpus:</strong> Typically 12 to 24 months of advance society maintenance deposited into escrow.</li>
</ul>',
            ],
            [
                'title' => 'Property Tax Assessment & Online Payment Verification Before Purchase',
                'slug' => 'property-tax-assessment-online-payment-verification-guide',
                'category' => 'Home Buying Guide',
                'read_time' => '5 min read',
                'author_name' => 'Housing365 Advisory',
                'published_at' => now()->subDays(17),
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Never inherit a previous owner\'s unpaid municipal taxes. How to verify property tax receipts, obtain a Nil Dues certificate, and transfer the municipal tax ledger.',
                'content' => '<h2>Don\'t Inherit the Seller\'s Municipal Tax Arrears</h2>
<p>When purchasing a resale property, unpaid municipal property taxes (also called holding tax or house tax) constitute a statutory charge on the property itself. Municipal corporations hold the legal authority to seal the property or disconnect water connections, regardless of whether the liability was incurred by the previous owner.</p>

<h3>Verification Protocol for Buyers</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Examine 3 Years of Tax Paid Receipts:</strong> Request official counterfoil receipts or digital payment transaction acknowledgments for the past three financial years.</li>
    <li><strong>Verify on Municipal Portal:</strong> Enter the property\'s unique Assessment Identification Number (PID / SAS / Property ID) directly into the municipal portal (e.g., BBMP, BMC, MCD, or GHMC).</li>
    <li><strong>Obtain a "No Dues Certificate":</strong> Insist on a formal certificate from the seller certifying zero outstanding civic property taxes up to the date of execution of the sale deed.</li>
</ul>',
            ],
            [
                'title' => 'NRI Guide to Buying Real Estate in India: FEMA Guidelines, Permitted Properties & Repatriation',
                'slug' => 'nri-guide-buying-real-estate-india-fema-rules',
                'category' => 'NRI Property Guide',
                'read_time' => '8 min read',
                'author_name' => 'Housing365 Legal & Advisory',
                'published_at' => now()->subDays(13),
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Complete legal manual for Non-Resident Indians (NRIs) and Overseas Citizens of India (OCIs) investing in Indian property. FEMA guidelines, banking channels, and repatriation limits.',
                'content' => '<h2>Liberalized Investment Regime for NRIs and OCIs</h2>
<p>The Reserve Bank of India (RBI) under the Foreign Exchange Management Act (FEMA) permits Non-Resident Indians (NRIs) and Overseas Citizens of India (OCIs) to purchase residential and commercial real estate in India under a general permission regime without requiring prior central bank approval.</p>

<h3>What Can NRIs Buy in India?</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Permitted Properties:</strong> Any number of residential apartments, independent villas, or commercial office and retail spaces.</li>
    <li><strong>Strictly Prohibited:</strong> NRIs and OCIs <strong>cannot</strong> purchase agricultural land, plantation properties, or farmhouses under any circumstances, unless inherited from a resident Indian.</li>
</ul>

<h3>Permitted Inward Remittance Channels</h3>
<p>All purchase payments must be channeled through normal banking routes via inward remittances from abroad or debited from the buyer\'s NRE, NRO, or FCNR(B) bank accounts. Payments in foreign currency cash or traveler\'s cheques are strictly prohibited.</p>',
            ],
            [
                'title' => 'Buying Plots in Gated Communities: Verifying BDA, DTCP, HMDA & RERA Layout Approvals',
                'slug' => 'buying-plots-in-gated-communities-bda-dtcp-hmda-rera-approvals',
                'category' => 'Home Buying Guide',
                'read_time' => '6 min read',
                'author_name' => 'Housing365 Research Desk',
                'published_at' => now()->subDays(10),
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Purchasing a residential plot? How to verify master plan zoning, park reservations, civil infrastructure handover to local panchayats, and RERA plotted registrations.',
                'content' => '<h2>The Traps of Unapproved Plotted Developments</h2>
<p>Plotted land investments offer high capital appreciation, but they also carry significant regulatory risks. Developers frequently carve out illegal layouts on revenue land without widening roads, leaving green park space, or constructing proper drainage infrastructure.</p>

<h3>What Approved Layouts Must Hold</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Town Planning Approval:</strong> Approved layout sanctions from competent urban planning bodies: BDA / BMRDA (Bengaluru), DTCP (Tamil Nadu/Haryana), HMDA (Hyderabad), or PMRDA (Pune).</li>
    <li><strong>RERA Plotted Registration:</strong> Plotted developments exceeding 500 sq. meters must be registered with RERA. The promoter cannot sell plots until the layout infrastructure is fully laid.</li>
    <li><strong>Relinquishment Deed to Municipality:</strong> The developer must execute a registered relinquishment deed transferring roads, civic amenity (CA) sites, and parks to the local municipal council.</li>
</ul>',
            ],
            [
                'title' => 'Property Mutation (Dakhil Kharij): Why Registration Alone Is Not Enough to Transfer Records',
                'slug' => 'property-mutation-dakhil-kharij-municipal-revenue-records',
                'category' => 'Home Buying Guide',
                'read_time' => '6 min read',
                'author_name' => 'Adv. Rohit Malhotra',
                'published_at' => now()->subDays(5),
                'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1200&q=80',
                'excerpt' => 'Think your property purchase is finished after registering the Sale Deed? Discover why filing for Property Mutation (Dakhil Kharij) in municipal revenue records is critical.',
                'content' => '<h2>Sale Deed vs Mutation: The Essential Distinction</h2>
<p>Many new homeowners celebrate after executing their Sale Deed at the Sub-Registrar\'s office and store the registered deed in a bank locker, believing their job is done. Months later, they receive property tax bills still issued in the previous owner\'s name.</p>

<p>Executing a Sale Deed transfers legal title between the buyer and seller. However, updating the <strong>municipal and revenue records</strong> to recognize the new owner for municipal tax and civic liability requires a separate legal procedure known as <strong>Property Mutation (Dakhil Kharij)</strong>.</p>

<h3>Why Mutation Is Indispensable</h3>
<ul class="list-disc pl-6 space-y-2 text-slate-700">
    <li><strong>Tax Billing in Your Name:</strong> Ensures property tax assessment bills are officially issued in your name, preventing the seller from fraudulently claiming the property.</li>
    <li><strong>Utility Meter Transfers:</strong> Water and electricity connection transfers require the certified copy of the mutation extract.</li>
    <li><strong>Resale &amp; Inheritance:</strong> When you eventually sell or bequeath the property, prospective buyers and title verification advocates will demand both the registered Sale Deed and the updated Mutation Certificate.</li>
</ul>',
            ],
        ];
    }
}
