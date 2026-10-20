<x-filament-panels::page>
    <form wire:submit="save" style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- TOP BANNER: Brand Overview -->
        <div style="background: linear-gradient(135deg, #03234B 0%, #0D3261 100%); border-radius: 1rem; padding: 1.5rem 1.75rem; color: #FFFFFF; box-shadow: 0 4px 20px rgba(3, 35, 75, 0.15); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0, 174, 239, 0.2); border: 1px solid rgba(0, 174, 239, 0.4); border-radius: 9999px; padding: 0.25rem 0.75rem; margin-bottom: 0.5rem;">
                    <span style="width: 6px; height: 6px; border-radius: 9999px; background: #00AEEF;"></span>
                    <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #00AEEF;">
                        HOUSING365 ANALYTICS &amp; SEO DESK
                    </span>
                </div>
                <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0; color: #FFFFFF;">
                    Google Tracking &amp; Site Verification
                </h2>
                <p style="font-size: 0.825rem; color: #94A3B8; margin: 0.35rem 0 0 0; max-width: 650px; line-height: 1.4;">
                    Configure your live Google Analytics 4 Measurement ID and Google Search Console meta tag. Changes take effect instantly across all public website pages.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <a href="{{ url('/') }}" target="_blank" 
                   style="background: rgba(255,255,255,0.1); color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; border: 1px solid rgba(255,255,255,0.15); display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span>View Public Site</span>
                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                </a>
            </div>
        </div>

        <!-- CARD 1: Google Analytics 4 -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; margin-bottom: 1.25rem;">
                <div style="width: 44px; height: 44px; min-width: 44px; min-height: 44px; max-width: 44px; max-height: 44px; border-radius: 12px; background: rgba(0, 174, 239, 0.1); color: #00AEEF; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-chart-simple" style="font-size: 20px;"></i>
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <h3 style="font-size: 1rem; font-weight: 700; color: #03234B; margin: 0;">
                            Google Analytics 4 (GA4)
                        </h3>
                        @if(!empty($google_analytics_id))
                            <span style="background: #DCFCE7; color: #15803D; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px; text-transform: uppercase;">
                                Active &middot; {{ $google_analytics_id }}
                            </span>
                        @else
                            <span style="background: #FEF3C7; color: #B45309; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px; text-transform: uppercase;">
                                Setup Pending
                            </span>
                        @endif
                    </div>
                    <p style="font-size: 0.775rem; color: #64748B; margin: 0.2rem 0 0 0;">
                        Tracks real-time visitor traffic, inbound rental inquiry submissions, and referral channels.
                    </p>
                </div>
            </div>

            <div>
                <label for="ga_id" style="display: block; font-size: 0.825rem; font-weight: 700; color: #1E293B; margin-bottom: 0.4rem;">
                    Measurement ID (Format: G-XXXXXXXXXX)
                </label>
                <input type="text" 
                       id="ga_id" 
                       wire:model="google_analytics_id" 
                       placeholder="e.g. G-ABC123XYZ4"
                       style="width: 100%; border: 1px solid #CBD5E1; border-radius: 0.625rem; padding: 0.65rem 0.85rem; font-size: 0.85rem; color: #0F172A; outline: none; transition: border-color 0.15s; font-family: monospace;"
                       onfocus="this.style.borderColor='#00AEEF'" 
                       onblur="this.style.borderColor='#CBD5E1'">
                <p style="font-size: 0.75rem; color: #64748B; margin: 0.4rem 0 0 0;">
                    When filled, Housing365 automatically renders the official Google <code style="background: #F1F5F9; color: #00AEEF; padding: 0.1rem 0.3rem; border-radius: 4px; font-size: 0.75rem;">gtag.js</code> tracking snippet on all pages.
                </p>
            </div>
        </div>

        <!-- CARD 2: Google Search Console -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; margin-bottom: 1.25rem;">
                <div style="width: 44px; height: 44px; min-width: 44px; min-height: 44px; max-width: 44px; max-height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10B981; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-magnifying-glass-location" style="font-size: 20px;"></i>
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <h3 style="font-size: 1rem; font-weight: 700; color: #03234B; margin: 0;">
                            Google Search Console Verification
                        </h3>
                        @if(!empty($google_search_console_code))
                            <span style="background: #DCFCE7; color: #15803D; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px; text-transform: uppercase;">
                                Tag Installed
                            </span>
                        @else
                            <span style="background: #FEF3C7; color: #B45309; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px; text-transform: uppercase;">
                                Verification Needed
                            </span>
                        @endif
                    </div>
                    <p style="font-size: 0.775rem; color: #64748B; margin: 0.2rem 0 0 0;">
                        Proves website ownership to index all 28 Indian states, top metro cities, and verified property landing pages across Google search.
                    </p>
                </div>
            </div>

            <div>
                <label for="gsc_code" style="display: block; font-size: 0.825rem; font-weight: 700; color: #1E293B; margin-bottom: 0.4rem;">
                    Verification Code or Full &lt;meta&gt; Tag
                </label>
                <input type="text" 
                       id="gsc_code" 
                       wire:model="google_search_console_code" 
                       placeholder="e.g. 5xX3F9k7... or <meta name='google-site-verification' content='...' />"
                       style="width: 100%; border: 1px solid #CBD5E1; border-radius: 0.625rem; padding: 0.65rem 0.85rem; font-size: 0.85rem; color: #0F172A; outline: none; transition: border-color 0.15s; font-family: monospace;"
                       onfocus="this.style.borderColor='#10B981'" 
                       onblur="this.style.borderColor='#CBD5E1'">
                <p style="font-size: 0.75rem; color: #64748B; margin: 0.4rem 0 0 0;">
                    Accepts either the raw verification token or the complete HTML tag copied from Google Search Console.
                </p>
            </div>
        </div>

        <!-- CARD 3: WhatsApp Concierge & Live Chat Box -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; margin-bottom: 1.25rem;">
                <div style="width: 44px; height: 44px; min-width: 44px; min-height: 44px; max-width: 44px; max-height: 44px; border-radius: 12px; background: rgba(37, 211, 102, 0.12); color: #25D366; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-brands fa-whatsapp" style="font-size: 24px;"></i>
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <h3 style="font-size: 1rem; font-weight: 700; color: #03234B; margin: 0;">
                            WhatsApp Concierge &amp; Floating Chat Box
                        </h3>
                        @if(!empty($whatsapp_number))
                            <span style="background: #DCFCE7; color: #15803D; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px; text-transform: uppercase;">
                                Active &middot; {{ $whatsapp_number }}
                            </span>
                        @else
                            <span style="background: #FEF3C7; color: #B45309; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px; text-transform: uppercase;">
                                Number Needed
                            </span>
                        @endif
                    </div>
                    <p style="font-size: 0.775rem; color: #64748B; margin: 0.2rem 0 0 0;">
                        Controls the floating WhatsApp widget on all public website pages and instant client lead routing.
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <div>
                    <label for="wa_num" style="display: block; font-size: 0.825rem; font-weight: 700; color: #1E293B; margin-bottom: 0.4rem;">
                        Support WhatsApp Number (with Country Code)
                    </label>
                    <input type="text"
                           id="wa_num"
                           wire:model="whatsapp_number"
                           placeholder="e.g. +91 63761 63263 or 916376163263"
                           style="width: 100%; border: 1px solid #CBD5E1; border-radius: 0.625rem; padding: 0.65rem 0.85rem; font-size: 0.85rem; color: #0F172A; outline: none; transition: border-color 0.15s; font-family: monospace;"
                           onfocus="this.style.borderColor='#25D366'"
                           onblur="this.style.borderColor='#CBD5E1'">
                    <p style="font-size: 0.75rem; color: #64748B; margin: 0.4rem 0 0 0;">
                        Indian mobile format: +91 XXXXX XXXXX. Users clicking the floating widget will be routed to this number.
                    </p>
                </div>

                <div>
                    <label for="wa_msg" style="display: block; font-size: 0.825rem; font-weight: 700; color: #1E293B; margin-bottom: 0.4rem;">
                        Default Greeting / Inbound Prompt
                    </label>
                    <input type="text"
                           id="wa_msg"
                           wire:model="whatsapp_default_message"
                           placeholder="e.g. Hi Housing365 India, I am interested in property services."
                           style="width: 100%; border: 1px solid #CBD5E1; border-radius: 0.625rem; padding: 0.65rem 0.85rem; font-size: 0.85rem; color: #0F172A; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='#25D366'"
                           onblur="this.style.borderColor='#CBD5E1'">
                    <p style="font-size: 0.75rem; color: #64748B; margin: 0.4rem 0 0 0;">
                        Pre-filled starter message when visitors initiate chat on WhatsApp.
                    </p>
                </div>
            </div>
        </div>

        <!-- CARD 4: Additional Custom Header Script -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; margin-bottom: 1.25rem;">
                <div style="width: 44px; height: 44px; min-width: 44px; min-height: 44px; max-width: 44px; max-height: 44px; border-radius: 12px; background: rgba(212, 168, 83, 0.15); color: #B45309; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-code" style="font-size: 18px;"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #03234B; margin: 0;">
                        Custom Header Tracking Script (Optional)
                    </h3>
                    <p style="font-size: 0.775rem; color: #64748B; margin: 0.2rem 0 0 0;">
                        Optional Google Tag Manager container snippet, Meta Pixel, or LinkedIn Insights tag.
                    </p>
                </div>
            </div>

            <div>
                <label for="ga_script" style="display: block; font-size: 0.825rem; font-weight: 700; color: #1E293B; margin-bottom: 0.4rem;">
                    Raw Script / HTML Code
                </label>
                <textarea id="ga_script" 
                          wire:model="google_analytics_script" 
                          rows="3" 
                          placeholder="<!-- Paste optional tag snippet here -->"
                          style="width: 100%; border: 1px solid #CBD5E1; border-radius: 0.625rem; padding: 0.65rem 0.85rem; font-size: 0.8rem; font-family: monospace; color: #0F172A; outline: none; transition: border-color 0.15s;"
                          onfocus="this.style.borderColor='#00AEEF'" 
                          onblur="this.style.borderColor='#CBD5E1'"></textarea>
            </div>
        </div>

        <!-- SAVE BUTTON BAR -->
        <div style="display: flex; align-items: center; justify-content: flex-end; padding-top: 0.5rem;">
            <button type="submit" 
                    style="background: #00AEEF; color: #FFFFFF; font-weight: 700; font-size: 0.875rem; padding: 0.75rem 1.75rem; border-radius: 0.625rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 14px rgba(0, 174, 239, 0.35); transition: all 0.2s;"
                    onmouseover="this.style.background='#0096C7'; this.style.transform='translateY(-1px)';" 
                    onmouseout="this.style.background='#00AEEF'; this.style.transform='none';">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save Analytics, SEO &amp; WhatsApp Settings</span>
            </button>
        </div>

        <!-- STEP-BY-STEP INSTRUCTIONS -->
        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 1rem; padding: 1.5rem; margin-top: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                <i class="fa-solid fa-circle-question" style="color: #00AEEF; font-size: 16px;"></i>
                <h4 style="font-size: 0.875rem; font-weight: 700; color: #03234B; margin: 0;">
                    Step-by-Step Setup Guide
                </h4>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 0.75rem; padding: 1rem; font-size: 0.775rem; color: #475569; line-height: 1.5;">
                    <div style="display: flex; align-items: center; gap: 0.4rem; font-weight: 700; color: #03234B; margin-bottom: 0.4rem;">
                        <i class="fa-solid fa-chart-line text-brand-cyan" style="color: #00AEEF;"></i>
                        <span>Where to get your GA4 Measurement ID:</span>
                    </div>
                    <ol style="margin: 0; padding-left: 1.25rem;">
                        <li>Open <a href="https://analytics.google.com" target="_blank" style="color: #00AEEF; text-decoration: underline;">analytics.google.com</a></li>
                        <li>Click <strong>Admin</strong> (gear icon bottom left) &rarr; <strong>Data Streams</strong></li>
                        <li>Select your web stream and copy the <strong>Measurement ID</strong> (starts with <code>G-</code>)</li>
                        <li>Paste it above and click <strong>Save Settings</strong>.</li>
                    </ol>
                </div>

                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 0.75rem; padding: 1rem; font-size: 0.775rem; color: #475569; line-height: 1.5;">
                    <div style="display: flex; align-items: center; gap: 0.4rem; font-weight: 700; color: #03234B; margin-bottom: 0.4rem;">
                        <i class="fa-solid fa-shield-halved" style="color: #10B981;"></i>
                        <span>Where to get your Search Console code:</span>
                    </div>
                    <ol style="margin: 0; padding-left: 1.25rem;">
                        <li>Open <a href="https://search.google.com/search-console" target="_blank" style="color: #00AEEF; text-decoration: underline;">search.google.com/search-console</a></li>
                        <li>Add your domain/URL (e.g. <code>https://housing365.in</code>)</li>
                        <li>Select <strong>HTML tag</strong> as the verification method</li>
                        <li>Copy the tag, paste it above, click <strong>Save</strong>, then click <strong>Verify</strong> in Google.</li>
                    </ol>
                </div>
            </div>
        </div>
    </form>
</x-filament-panels::page>
