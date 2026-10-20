{{-- Housing365 Interactive Owner Strategy Video Player --}}
{{-- Housing365 Owner Strategy Video Player component --}}

<div id="owner-video-root" class="relative w-full">
    <div id="owner-video-player-container"
         class="relative aspect-[4/3] sm:aspect-[1/1] w-full overflow-hidden rounded-3xl bg-slate-950 shadow-2xl transition-all duration-300">

        <!-- ── Background Imagery & Ken Burns Transitions ── -->
        <div class="absolute inset-0 overflow-hidden">
            <div id="bg-slide-0" class="video-bg-slide absolute inset-0 opacity-100 transition-opacity duration-700 ease-out">
                <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&auto=format&fit=crop&q=80"
                     alt="Housing365 Modern Architecture"
                     class="w-full h-full object-cover transition-transform duration-[6000ms] ease-out kenburns-0" />
            </div>

            <div id="bg-slide-1" class="video-bg-slide absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-700 ease-out">
                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&auto=format&fit=crop&q=80"
                     alt="Multi-unit building portfolio"
                     class="w-full h-full object-cover transition-transform duration-[7000ms] ease-out kenburns-1" />
            </div>

            <div id="bg-slide-2" class="video-bg-slide absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-700 ease-out">
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&auto=format&fit=crop&q=80"
                     alt="Luxury rental interior"
                     class="w-full h-full object-cover transition-transform duration-[9000ms] ease-out kenburns-2" />
            </div>

            <div id="bg-slide-3" class="video-bg-slide absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-700 ease-out">
                <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&auto=format&fit=crop&q=80"
                     alt="Modern residential building"
                     class="w-full h-full object-cover transition-transform duration-[5000ms] ease-out kenburns-3" />
            </div>

            <div id="bg-slide-4" class="video-bg-slide absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-700 ease-out">
                <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&auto=format&fit=crop&q=80"
                     alt="Housing365 Contact Consultation"
                     class="w-full h-full object-cover transition-transform duration-[4000ms] ease-out kenburns-4" />
            </div>

            <!-- Deep cinematic gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-slate-900/40"></div>
            <div class="absolute inset-0 bg-brand-navy/35 mix-blend-multiply"></div>
        </div>

        <!-- ── TOP BAR: Status & Branding ── -->
        <div class="absolute top-4 inset-x-4 z-20 flex items-center justify-between pointer-events-none">
            <div class="flex items-center gap-2 rounded-full border border-white/20 bg-slate-900/70 px-3 py-1 backdrop-blur-md">
                <span class="relative flex size-2">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                </span>
                <span id="video-chapter-label" class="text-[11px] font-bold tracking-wider text-white uppercase">
                    Featured Video
                </span>
            </div>

            <div class="flex items-center gap-1.5 rounded-full border border-white/20 bg-slate-900/70 px-3 py-1 backdrop-blur-md">
                <span class="text-[10px] font-semibold tracking-widest text-slate-300 uppercase">
                    4K &bull; 60FPS
                </span>
            </div>
        </div>

        <!-- ── INITIAL TEASER OVERLAY (BEFORE USER PRESSES PLAY) ── -->
        <div id="video-teaser-overlay"
             onclick="startOwnerVideo()"
             class="group absolute inset-0 z-30 flex cursor-pointer flex-col items-center justify-center p-6 text-center transition-all duration-300 hover:bg-black/20">
            <!-- Glowing Pulse Rings & Big Play Button -->
            <div class="relative mb-6">
                <div class="absolute -inset-4 animate-ping rounded-full bg-brand-cyan/20 opacity-75 duration-1000"></div>
                <div class="absolute -inset-8 animate-pulse rounded-full bg-brand-cyan/15 duration-1500"></div>
                <div class="relative flex size-20 sm:size-24 items-center justify-center rounded-full bg-white text-brand-cyan shadow-2xl shadow-brand-cyan/50 ring-4 ring-white/30 backdrop-blur-md transition-transform duration-300 group-hover:scale-110">
                    <i class="fa-solid fa-play ml-1 text-2xl sm:text-3xl text-brand-cyan"></i>
                </div>
            </div>

            <div class="max-w-xs space-y-1">
                <p class="font-display text-xl sm:text-2xl font-bold tracking-tight text-white drop-shadow-md">
                    Maximize Rental Value
                </p>
                <p class="text-xs text-slate-300 drop-shadow">
                    Click to play owner strategy overview (0:30)
                </p>
            </div>

            <!-- Bottom Pill Badge -->
            <div class="absolute bottom-4 left-4 flex items-center gap-3 rounded-2xl border border-white/40 bg-white/95 px-4 py-2.5 shadow-lg backdrop-blur-md transition-transform duration-200 group-hover:scale-105">
                <div class="flex size-9 items-center justify-center rounded-full bg-brand-cyan text-white shadow-sm">
                    <i class="fa-solid fa-play ml-0.5 text-xs text-white"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-bold text-brand-navy">See How We Help Owners</p>
                    <p class="text-[10px] text-slate-500">Interactive 30s video</p>
                </div>
            </div>
        </div>

        <!-- ── ACTIVE VIDEO PRESENTATION (SCENE CANVAS) ── -->
        <div id="video-active-canvas"
             onclick="toggleOwnerVideoPlay()"
             class="absolute inset-0 z-10 hidden flex-col justify-between p-6 sm:p-8 cursor-pointer select-none">
            
            <!-- Middle Content Area: Dynamically Animated Scenes -->
            <div class="my-auto flex flex-col items-center justify-center text-center w-full">

                <!-- ── SCENE 0: Intro (0s - 6s) ── -->
                <div id="video-scene-0" class="video-scene max-w-md space-y-3 transition-all duration-500 transform">
                    <div class="inline-flex items-center gap-2 rounded-full bg-brand-cyan/20 px-3 py-1 text-[11px] font-bold text-brand-cyan ring-1 ring-brand-cyan/40">
                        <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                        Housing365 Property Platform
                    </div>
                    <h3 class="font-display text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white drop-shadow-md">
                        Maximize Rental Value with a Trusted Partner
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-200 drop-shadow">
                        Precision pricing, high-speed placement, and full asset protection across North America.
                    </p>
                </div>

                <!-- ── SCENE 1: Mission (6s - 13s) ── -->
                <div id="video-scene-1" class="video-scene hidden max-w-md space-y-4 transition-all duration-500 transform">
                    <div class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400">
                        <i class="fa-solid fa-building text-sm"></i>
                        Single-Family to Multi-Unit Portfolios
                    </div>
                    <h3 class="font-display text-xl sm:text-2xl font-normal leading-snug text-white">
                        From single-family homes to multi-unit buildings, we help landlords and institutional owners{' '}
                        <span class="font-semibold text-emerald-400 underline decoration-emerald-500/40">
                            achieve higher returns with less stress.
                        </span>
                    </h3>
                    <div class="flex flex-wrap justify-center gap-2 pt-2">
                        <span class="rounded-xl border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-medium text-white backdrop-blur-sm">
                            Zero Upfront Fees
                        </span>
                        <span class="rounded-xl border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-medium text-white backdrop-blur-sm">
                            18-Day Avg Placement
                        </span>
                    </div>
                </div>

                <!-- ── SCENE 2: The 4 Pillars (13s - 22s) ── -->
                <div id="video-scene-2" class="video-scene hidden w-full max-w-sm space-y-2.5 text-left transition-all duration-500 transform">
                    <p class="text-center text-[11px] font-bold tracking-wider text-brand-cyan uppercase">
                        4 Pillars of Modern Leasing
                    </p>

                    <div class="video-pillar-card flex items-center gap-3 rounded-xl border border-white/15 bg-white/15 p-2.5 backdrop-blur-md transition-all duration-300">
                        <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-brand-cyan text-white shadow-sm">
                            <i class="fa-solid fa-check text-[11px]"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-white drop-shadow">
                            Data-driven pricing and market insights
                        </span>
                    </div>

                    <div class="video-pillar-card flex items-center gap-3 rounded-xl border border-white/15 bg-white/15 p-2.5 backdrop-blur-md transition-all duration-300">
                        <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-brand-cyan text-white shadow-sm">
                            <i class="fa-solid fa-check text-[11px]"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-white drop-shadow">
                            Professional marketing and showings
                        </span>
                    </div>

                    <div class="video-pillar-card flex items-center gap-3 rounded-xl border border-white/15 bg-white/15 p-2.5 backdrop-blur-md transition-all duration-300">
                        <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-brand-cyan text-white shadow-sm">
                            <i class="fa-solid fa-check text-[11px]"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-white drop-shadow">
                            Thorough tenant screening
                        </span>
                    </div>

                    <div class="video-pillar-card flex items-center gap-3 rounded-xl border border-white/15 bg-white/15 p-2.5 backdrop-blur-md transition-all duration-300">
                        <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-brand-cyan text-white shadow-sm">
                            <i class="fa-solid fa-check text-[11px]"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-white drop-shadow">
                            Ongoing support and transparent reporting
                        </span>
                    </div>
                </div>

                <!-- ── SCENE 3: Results (22s - 27s) ── -->
                <div id="video-scene-3" class="video-scene hidden w-full max-w-md space-y-3 transition-all duration-500 transform">
                    <p class="text-[11px] font-bold tracking-widest text-emerald-400 uppercase">
                        Proven Results Across 20+ Cities
                    </p>

                    <div class="grid grid-cols-3 gap-2 sm:gap-3">
                        <div class="rounded-2xl border border-white/15 bg-white/10 p-3 backdrop-blur-md">
                            <i class="fa-solid fa-rocket mx-auto mb-1 text-lg text-brand-cyan block"></i>
                            <p class="font-display text-xl sm:text-2xl font-bold text-white">
                                30%
                            </p>
                            <p class="text-[10px] text-slate-300">Faster Lease-Up</p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-white/10 p-3 backdrop-blur-md">
                            <i class="fa-solid fa-arrow-trend-up mx-auto mb-1 text-lg text-emerald-400 block"></i>
                            <p class="font-display text-xl sm:text-2xl font-bold text-white">
                                15%
                            </p>
                            <p class="text-[10px] text-slate-300">Higher Rental Value</p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-white/10 p-3 backdrop-blur-md">
                            <i class="fa-solid fa-award mx-auto mb-1 text-lg text-brand-cyan block"></i>
                            <p class="font-display text-xl sm:text-2xl font-bold text-white">
                                98%
                            </p>
                            <p class="text-[10px] text-slate-300">Owner Satisfaction</p>
                        </div>
                    </div>
                </div>

                <!-- ── SCENE 4: Next Steps / CTA (27s - 30s) ── -->
                <div id="video-scene-4" class="video-scene hidden max-w-md space-y-4 transition-all duration-500 transform">
                    <div class="inline-flex size-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-400 ring-1 ring-emerald-500/40">
                        <i class="fa-solid fa-shield-halved text-2xl"></i>
                    </div>

                    <div class="space-y-1">
                        <h3 class="font-display text-2xl sm:text-3xl font-bold text-white">
                            Ready to Maximize Your Return?
                        </h3>
                        <p class="text-xs text-slate-300">
                            Get your free rental evaluation within one business day.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-3 pt-2" onclick="event.stopPropagation()">
                        <a href="{{ route('contact') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-6 py-3 text-sm font-bold text-white shadow-xl shadow-brand-cyan/40 transition-all hover:scale-105 hover:bg-brand-cyan-hover">
                            <span>Talk to an Expert</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>

                        <button type="button"
                                onclick="restartOwnerVideo(event)"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-semibold text-white backdrop-blur-md transition-colors hover:bg-white/20">
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            <span>Replay</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- ── BOTTOM CONTROLS BAR ── -->
            <div onclick="event.stopPropagation()"
                 class="z-30 space-y-2 rounded-2xl border border-white/15 bg-slate-900/80 p-3 backdrop-blur-lg">
                <!-- Scrubbable Progress Bar -->
                <div id="video-scrubber-track"
                     class="group relative h-2 w-full cursor-pointer rounded-full bg-white/20"
                     onclick="handleScrubberClick(event)">
                    <div id="video-progress-bar"
                         class="h-full rounded-full bg-gradient-to-r from-brand-cyan to-emerald-400 transition-[width] duration-100"
                         style="width: 0%;"></div>
                    <div id="video-scrubber-knob"
                         class="absolute top-1/2 size-3.5 -translate-y-1/2 rounded-full border-2 border-white bg-brand-cyan opacity-0 transition-opacity group-hover:opacity-100"
                         style="left: -7px;"></div>
                </div>

                <!-- Controls Strip -->
                <div class="flex items-center justify-between text-xs text-white">
                    <div class="flex items-center gap-3">
                        <button type="button"
                                id="video-play-btn"
                                onclick="toggleOwnerVideoPlay()"
                                class="flex size-7 items-center justify-center rounded-lg bg-white/10 text-white transition hover:bg-white/20"
                                aria-label="Play">
                            <i class="fa-solid fa-play text-xs text-white" id="video-play-icon"></i>
                        </button>

                        <button type="button"
                                onclick="restartOwnerVideo(event)"
                                class="flex size-7 items-center justify-center rounded-lg bg-white/10 text-slate-300 transition hover:bg-white/20 hover:text-white"
                                aria-label="Restart video">
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                        </button>

                        <span id="video-time-display" class="font-mono text-[11px] text-slate-300">
                            0:00 / 0:30
                        </span>
                    </div>

                    <!-- Chapter indicators -->
                    <div class="hidden sm:flex items-center gap-1.5">
                        <button type="button" onclick="seekToChapter(0)" class="video-ch-dot h-1.5 w-6 rounded-full bg-brand-cyan transition-all" title="Overview"></button>
                        <button type="button" onclick="seekToChapter(1)" class="video-ch-dot h-1.5 w-2 rounded-full bg-white/30 hover:bg-white/50 transition-all" title="Mission"></button>
                        <button type="button" onclick="seekToChapter(2)" class="video-ch-dot h-1.5 w-2 rounded-full bg-white/30 hover:bg-white/50 transition-all" title="4 Pillars"></button>
                        <button type="button" onclick="seekToChapter(3)" class="video-ch-dot h-1.5 w-2 rounded-full bg-white/30 hover:bg-white/50 transition-all" title="Results"></button>
                        <button type="button" onclick="seekToChapter(4)" class="video-ch-dot h-1.5 w-2 rounded-full bg-white/30 hover:bg-white/50 transition-all" title="Next Steps"></button>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button"
                                id="video-sound-btn"
                                onclick="toggleOwnerSound()"
                                class="flex size-7 items-center justify-center rounded-lg bg-white/10 text-slate-300 transition hover:bg-white/20 hover:text-white"
                                aria-label="Mute / Unmute">
                            <i class="fa-solid fa-volume-xmark text-xs" id="video-sound-icon"></i>
                        </button>

                        <button type="button"
                                id="video-fullscreen-btn"
                                onclick="toggleOwnerFullscreen()"
                                class="flex size-7 items-center justify-center rounded-lg bg-white/10 text-slate-300 transition hover:bg-white/20 hover:text-white"
                                aria-label="Fullscreen">
                            <i class="fa-solid fa-expand text-xs" id="video-fullscreen-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Dimmed backdrop when in Theater / Fullscreen mode -->
    <div id="owner-video-backdrop"
         onclick="exitOwnerFullscreen()"
         class="fixed inset-0 z-[9998] hidden bg-black/80 backdrop-blur-md transition-opacity duration-300"></div>
</div>

<style>
    @keyframes kenburnsZoom0 {
        0% { transform: scale(1.05); }
        100% { transform: scale(1.15); }
    }
    @keyframes kenburnsZoom1 {
        0% { transform: scale(1.05); }
        100% { transform: scale(1.18); }
    }
    @keyframes kenburnsZoom2 {
        0% { transform: scale(1.08); }
        100% { transform: scale(1.16); }
    }
    @keyframes kenburnsZoom3 {
        0% { transform: scale(1.05); }
        100% { transform: scale(1.15); }
    }
    @keyframes kenburnsZoom4 {
        0% { transform: scale(1.05); }
        100% { transform: scale(1.14); }
    }
    .video-animating .kenburns-0 { animation: kenburnsZoom0 6s ease-out forwards; }
    .video-animating .kenburns-1 { animation: kenburnsZoom1 7s ease-out forwards; }
    .video-animating .kenburns-2 { animation: kenburnsZoom2 9s ease-out forwards; }
    .video-animating .kenburns-3 { animation: kenburnsZoom3 5s ease-out forwards; }
    .video-animating .kenburns-4 { animation: kenburnsZoom4 4s ease-out forwards; }
</style>

<script>
    (function() {
        const TOTAL_DURATION = 30; // 30 seconds presentation

        const CHAPTERS = [
            { id: 0, label: 'Overview', start: 0, end: 6 },
            { id: 1, label: 'Mission', start: 6, end: 13 },
            { id: 2, label: '4 Pillars', start: 13, end: 22 },
            { id: 3, label: 'Results', start: 22, end: 27 },
            { id: 4, label: 'Next Steps', start: 27, end: 30 }
        ];

        let isPlaying = false;
        let progress = 0; // 0 to 1
        let isMuted = true;
        let isFullscreen = false;
        let hasStarted = false;
        let animationFrameId = null;
        let startTime = null;
        let elapsedSeconds = 0;
        let audioCtx = null;
        let currentActiveChapter = 0;

        function playChime(freq = 440) {
            if (isMuted) return;
            try {
                if (!audioCtx) {
                    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                    if (AudioContextClass) {
                        audioCtx = new AudioContextClass();
                    }
                }
                if (!audioCtx) return;
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.8);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.8);
            } catch (err) {
                // Audio not permitted or suspended
            }
        }

        function formatTime(secs) {
            const m = Math.floor(secs / 60);
            const s = Math.floor(secs % 60);
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        }

        function updateSceneAndBg(chapterIndex) {
            currentActiveChapter = chapterIndex;
            const chapter = CHAPTERS[chapterIndex] || CHAPTERS[0];

            // Update top bar label
            const labelEl = document.getElementById('video-chapter-label');
            if (labelEl) {
                labelEl.textContent = hasStarted ? chapter.label : 'Featured Video';
            }

            // Update Background Image
            for (let i = 0; i < 5; i++) {
                const bgEl = document.getElementById(`bg-slide-${i}`);
                if (bgEl) {
                    if (i === chapterIndex) {
                        bgEl.classList.remove('opacity-0', 'pointer-events-none');
                        bgEl.classList.add('opacity-100');
                    } else {
                        bgEl.classList.add('opacity-0', 'pointer-events-none');
                        bgEl.classList.remove('opacity-100');
                    }
                }
            }

            // Update Scenes
            for (let i = 0; i < 5; i++) {
                const sceneEl = document.getElementById(`video-scene-${i}`);
                if (sceneEl) {
                    if (i === chapterIndex) {
                        sceneEl.classList.remove('hidden');
                        sceneEl.classList.add('opacity-100', 'scale-100');
                        sceneEl.classList.remove('opacity-0', 'scale-95');
                    } else {
                        sceneEl.classList.add('hidden', 'opacity-0');
                        sceneEl.classList.remove('opacity-100', 'scale-100');
                    }
                }
            }

            // Update Chapter Dots
            const dots = document.querySelectorAll('.video-ch-dot');
            dots.forEach((dot, idx) => {
                if (idx === chapterIndex) {
                    dot.classList.remove('w-2', 'bg-white/30');
                    dot.classList.add('w-6', 'bg-brand-cyan');
                } else {
                    dot.classList.add('w-2', 'bg-white/30');
                    dot.classList.remove('w-6', 'bg-brand-cyan');
                }
            });
        }

        function tick(timestamp) {
            if (!startTime) {
                startTime = timestamp - elapsedSeconds * 1000;
            }
            elapsedSeconds = (timestamp - startTime) / 1000;

            if (elapsedSeconds >= TOTAL_DURATION) {
                progress = 1;
                elapsedSeconds = TOTAL_DURATION;
                isPlaying = false;
                startTime = null;
                updateControlsUi();
                return;
            }

            progress = elapsedSeconds / TOTAL_DURATION;
            updateControlsUi();

            // Check active chapter
            const curTime = progress * TOTAL_DURATION;
            let chIdx = CHAPTERS.findIndex(c => curTime >= c.start && curTime < c.end);
            if (chIdx === -1) chIdx = CHAPTERS.length - 1;

            if (chIdx !== currentActiveChapter) {
                updateSceneAndBg(chIdx);
                playChime(440 + chIdx * 50);
            }

            if (isPlaying) {
                animationFrameId = requestAnimationFrame(tick);
            }
        }

        function updateControlsUi() {
            const curTime = progress * TOTAL_DURATION;
            const timeDisplay = document.getElementById('video-time-display');
            if (timeDisplay) {
                timeDisplay.textContent = `${formatTime(curTime)} / ${formatTime(TOTAL_DURATION)}`;
            }

            const progressBar = document.getElementById('video-progress-bar');
            if (progressBar) {
                progressBar.style.width = `${progress * 100}%`;
            }

            const knob = document.getElementById('video-scrubber-knob');
            if (knob) {
                knob.style.left = `calc(${progress * 100}% - 7px)`;
            }

            const playIcon = document.getElementById('video-play-icon');
            if (playIcon) {
                if (isPlaying) {
                    playIcon.className = 'fa-solid fa-pause text-xs text-white';
                } else {
                    playIcon.className = 'fa-solid fa-play text-xs text-white';
                }
            }

            const container = document.getElementById('owner-video-player-container');
            if (container) {
                if (isPlaying) {
                    container.classList.add('video-animating');
                } else {
                    container.classList.remove('video-animating');
                }
            }
        }

        window.startOwnerVideo = function() {
            hasStarted = true;
            const teaser = document.getElementById('video-teaser-overlay');
            const canvas = document.getElementById('video-active-canvas');
            if (teaser) teaser.classList.add('hidden');
            if (canvas) {
                canvas.classList.remove('hidden');
                canvas.classList.add('flex');
            }

            if (progress >= 1) {
                progress = 0;
                elapsedSeconds = 0;
                startTime = null;
            }

            isPlaying = true;
            startTime = null;
            updateSceneAndBg(0);
            playChime(523.25); // C5 note
            animationFrameId = requestAnimationFrame(tick);
        };

        window.toggleOwnerVideoPlay = function(e) {
            if (e) e.stopPropagation();
            if (!hasStarted) {
                startOwnerVideo();
                return;
            }

            if (isPlaying) {
                isPlaying = false;
                startTime = null;
                if (animationFrameId) cancelAnimationFrame(animationFrameId);
                updateControlsUi();
            } else {
                if (progress >= 1) {
                    progress = 0;
                    elapsedSeconds = 0;
                    startTime = null;
                    updateSceneAndBg(0);
                }
                isPlaying = true;
                startTime = null;
                playChime(587.33); // D5
                animationFrameId = requestAnimationFrame(tick);
            }
        };

        window.restartOwnerVideo = function(e) {
            if (e) e.stopPropagation();
            progress = 0;
            elapsedSeconds = 0;
            startTime = null;
            isPlaying = true;
            updateSceneAndBg(0);
            playChime(523.25);
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            animationFrameId = requestAnimationFrame(tick);
        };

        window.seekToChapter = function(chapterId) {
            const ch = CHAPTERS[chapterId];
            if (!ch) return;
            const targetProg = ch.start / TOTAL_DURATION;
            seekToProgress(targetProg);
        };

        function seekToProgress(target) {
            progress = Math.max(0, Math.min(1, target));
            elapsedSeconds = progress * TOTAL_DURATION;
            startTime = null;

            let chIdx = CHAPTERS.findIndex(c => elapsedSeconds >= c.start && elapsedSeconds < c.end);
            if (chIdx === -1) chIdx = CHAPTERS.length - 1;
            updateSceneAndBg(chIdx);
            updateControlsUi();
        }

        window.handleScrubberClick = function(e) {
            const rect = e.currentTarget.getBoundingClientRect();
            const pos = (e.clientX - rect.left) / rect.width;
            seekToProgress(pos);
        };

        window.toggleOwnerSound = function() {
            isMuted = !isMuted;
            const icon = document.getElementById('video-sound-icon');
            if (icon) {
                if (isMuted) {
                    icon.className = 'fa-solid fa-volume-xmark text-xs';
                } else {
                    icon.className = 'fa-solid fa-volume-high text-xs text-brand-cyan';
                    playChime(440);
                }
            }
        };

        window.toggleOwnerFullscreen = function() {
            isFullscreen = !isFullscreen;
            const container = document.getElementById('owner-video-player-container');
            const backdrop = document.getElementById('owner-video-backdrop');
            const icon = document.getElementById('video-fullscreen-icon');

            if (!container || !backdrop || !icon) return;

            if (isFullscreen) {
                container.classList.add('fixed', 'inset-4', 'sm:inset-10', 'z-[9999]', 'aspect-auto', 'max-w-none', 'rounded-2xl');
                container.classList.remove('aspect-[4/3]', 'sm:aspect-[1/1]');
                backdrop.classList.remove('hidden');
                icon.className = 'fa-solid fa-compress text-xs';
            } else {
                container.classList.remove('fixed', 'inset-4', 'sm:inset-10', 'z-[9999]', 'aspect-auto', 'max-w-none', 'rounded-2xl');
                container.classList.add('aspect-[4/3]', 'sm:aspect-[1/1]');
                backdrop.classList.add('hidden');
                icon.className = 'fa-solid fa-expand text-xs';
            }
        };

        window.exitOwnerFullscreen = function() {
            if (isFullscreen) {
                toggleOwnerFullscreen();
            }
        };

        document.addEventListener('keydown', function(e) {
            if (!hasStarted) return;
            if (e.code === 'Space' && e.target === document.body) {
                e.preventDefault();
                toggleOwnerVideoPlay();
            } else if (e.code === 'Escape' && isFullscreen) {
                exitOwnerFullscreen();
            }
        });
    })();
</script>
