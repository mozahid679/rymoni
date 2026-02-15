<!DOCTYPE html>
<html lang="en" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    navOpen: false,
    selectedUnit: null,
    filter: 'all',
    searchQuery: '',
    units: [
        { id: 1, no: 'A-101', type: 'shop', price: 15000, size: '200 sqf', floor: 'Ground', status: 'Available', bedrooms: 0, bathrooms: 1, features: ['Street facing', 'AC ready'] },
        { id: 2, no: 'A-102', type: 'shop', price: 12000, size: '180 sqf', floor: 'Ground', status: 'Occupied', bedrooms: 0, bathrooms: 1, features: ['Corner shop', 'Storage'] },
        { id: 3, no: 'B-201', type: 'house', price: 9000, size: '600 sqf', floor: '2nd', status: 'Available', bedrooms: 2, bathrooms: 1, features: ['Balcony', 'Semi-furnished'] },
        { id: 4, no: 'B-202', type: 'house', price: 9500, size: '650 sqf', floor: '2nd', status: 'Available', bedrooms: 2, bathrooms: 2, features: ['West facing', 'Park view'] },
        { id: 5, no: 'C-301', type: 'house', price: 12500, size: '850 sqf', floor: '3rd', status: 'Available', bedrooms: 3, bathrooms: 2, features: ['Master bedroom', 'Store room'] },
        { id: 6, no: 'S-001', type: 'shop', price: 22000, size: '320 sqf', floor: 'Ground', status: 'Available', bedrooms: 0, bathrooms: 2, features: ['Double door', 'Showcase'] }
    ],
    waNumber: '8801700000000',
    emailInput: '',
    showToast: false,
    toastMessage: '',
    selectedFeature: 'all',
    faqOpen: null
}" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'));
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => { if (!localStorage.getItem('theme')) darkMode = e.matches; });">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UrbanSpace · modern living & commerce</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
        }

        .smooth-scroll {
            scroll-behavior: smooth;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body
    class="font-smooth smooth-scroll bg-white text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">

    <!-- === NAVIGATION (glassmorphic + interactive) === -->
    <nav
        class="fixed top-0 z-[100] w-full border-b border-slate-200/60 bg-white/80 backdrop-blur-xl dark:border-slate-800/60 dark:bg-slate-950/80">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 lg:px-6">
            <div class="text-2xl font-extrabold tracking-tighter">URBAN<span class="text-indigo-600">SPACE</span></div>

            <!-- desktop menu -->
            <div class="hidden items-center gap-8 text-sm font-semibold md:flex">
                <a class="transition hover:text-indigo-600" href="#features">Features</a>
                <a class="transition hover:text-indigo-600" href="#inventory">Inventory</a>
                <a class="transition hover:text-indigo-600" href="#reviews">Reviews</a>
                <a class="transition hover:text-indigo-600" href="#faq">FAQ</a>
                <a class="transition hover:text-indigo-600" href="#contact">Contact</a>
            </div>

            <div class="flex items-center gap-3">
                <button
                    class="rounded-full bg-slate-100 p-2.5 text-slate-600 transition-all hover:scale-110 dark:bg-slate-800 dark:text-slate-300"
                    @click="darkMode = !darkMode"
                    :aria-label="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
                    <template x-if="!darkMode"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                        </svg></template>
                    <template x-if="darkMode"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41" />
                        </svg></template>
                </button>
                <a class="hidden rounded-full border border-slate-200 px-5 py-2.5 text-sm font-bold transition hover:bg-slate-50 sm:block dark:border-slate-700 dark:hover:bg-slate-900"
                    href="#login"
                    @click.prevent="toastMessage='🔐 demo – login modal'; showToast=true; setTimeout(()=>showToast=false,3000)">Login</a>
                <a class="rounded-full bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-500/30 transition-transform hover:scale-105"
                    href="#inventory">Rent now</a>

                <!-- mobile toggle -->
                <button
                    class="relative flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 md:hidden dark:bg-slate-800"
                    @click="navOpen = !navOpen">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25" x-show="!navOpen" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" x-show="navOpen"
                            x-cloak />
                    </svg>
                </button>
            </div>
        </div>
        <!-- mobile menu panel -->
        <div class="absolute w-full bg-white/95 backdrop-blur-lg md:hidden dark:bg-slate-950/95" x-show="navOpen"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100" x-cloak>
            <div class="flex flex-col space-y-4 p-6 text-lg font-semibold">
                <a href="#features" @click="navOpen=false">Features</a>
                <a href="#inventory" @click="navOpen=false">Inventory</a>
                <a href="#reviews" @click="navOpen=false">Reviews</a>
                <a href="#faq" @click="navOpen=false">FAQ</a>
                <a href="#contact" @click="navOpen=false">Contact</a>
            </div>
        </div>
    </nav>

    <!-- === HERO (with live counter) === -->
    <header class="relative overflow-hidden px-6 pb-32 pt-48">
        <div class="relative z-10 mx-auto max-w-7xl text-center">
            <div
                class="mb-8 inline-flex animate-pulse items-center gap-2 rounded-full bg-indigo-50 px-4 py-2 text-xs font-bold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <span class="relative flex h-2 w-2"><span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-indigo-400 opacity-75"></span><span
                        class="relative inline-flex h-2 w-2 rounded-full bg-indigo-500"></span></span>
                🔥 <span x-text="units.filter(u => u.status === 'Available').length"></span> UNITS READY IN DHAKA
            </div>
            <h1 class="mb-8 text-6xl font-black leading-[0.9] tracking-tight md:text-8xl">Elevate your <br> <span
                    class="text-indigo-600">lifestyle & business.</span></h1>
            <p class="mx-auto mb-12 max-w-xl text-lg font-medium leading-relaxed text-slate-500 dark:text-slate-400">
                Modern personal project offering premium commercial shops and residential rooms designed for the future.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a class="rounded-3xl bg-indigo-600 px-10 py-5 text-lg font-black text-white shadow-2xl shadow-indigo-500/40 transition hover:scale-105 hover:bg-indigo-700"
                    href="#inventory">Explore units</a>
                <a class="rounded-3xl border border-slate-200 bg-white px-10 py-5 text-lg font-black transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800"
                    href="#features">360° virtual tour</a>
            </div>
        </div>
        <div
            class="absolute left-1/2 top-0 -z-10 h-full w-full -translate-x-1/2 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-indigo-100/40 via-transparent to-transparent dark:from-indigo-900/10">
        </div>
    </header>

    <!-- === FEATURES (expanded, interactive icons) === -->
    <section class="bg-slate-50 py-32 transition-colors dark:bg-slate-900/30" id="features">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div>
                    <h2 class="mb-6 text-4xl font-black tracking-tighter">Built with precision, <br>managed with <span
                            class="text-indigo-600">care.</span></h2>
                    <p class="mb-10 text-lg text-slate-500 dark:text-slate-400">Three core pillars: security,
                        accessibility, modern aesthetics. Hover any card.</p>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div
                            class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:scale-105 hover:border-indigo-300 dark:border-slate-800 dark:bg-slate-950">
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200 transition group-hover:rotate-3 dark:shadow-none">
                                🔒</div>
                            <h4 class="text-lg font-bold">Elite Security</h4>
                            <p class="text-sm text-slate-500">24/7 CCTV + guards + smart locks.</p>
                        </div>
                        <div
                            class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:scale-105 hover:border-indigo-300 dark:border-slate-800 dark:bg-slate-950">
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg transition group-hover:rotate-3">
                                📍</div>
                            <h4 class="text-lg font-bold">Prime spot</h4>
                            <p class="text-sm text-slate-500">Main road, 2min from metro.</p>
                        </div>
                        <div
                            class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:scale-105 hover:border-indigo-300 dark:border-slate-800 dark:bg-slate-950">
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg transition group-hover:rotate-3">
                                ⚡</div>
                            <h4 class="text-lg font-bold">Backup power</h4>
                            <p class="text-sm text-slate-500">Generator & solar hybrid.</p>
                        </div>
                        <div
                            class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:scale-105 hover:border-indigo-300 dark:border-slate-800 dark:bg-slate-950">
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg transition group-hover:rotate-3">
                                ♿</div>
                            <h4 class="text-lg font-bold">Accessibility</h4>
                            <p class="text-sm text-slate-500">Elevator + ramp + wide doors.</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="h-64 rounded-[3rem] bg-indigo-200 bg-cover bg-center dark:bg-indigo-900/40"
                        style="background-image: url('https://placehold.co/600x400/indigo/white?text=modern+facade');">
                    </div>
                    <div class="mt-12 h-64 rounded-[3rem] bg-slate-200 bg-cover bg-center dark:bg-slate-800"
                        style="background-image: url('https://placehold.co/600x400/slate/white?text=rooftop');"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- === INVENTORY + FILTER + SEARCH + SIDEBAR DETAIL (dynamic) === -->
    <section class="py-32" id="inventory">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-16 flex flex-col items-end justify-between gap-6 md:flex-row">
                <div>
                    <h2 class="text-5xl font-black tracking-tighter">Live map <span class="text-indigo-600">·</span>
                        inventory</h2>
                    <p class="mt-2 font-medium text-slate-500"><span
                            x-text="units.filter(u=>u.status==='Available').length"></span> available units</p>
                </div>
                <!-- filter pills + search -->
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex rounded-2xl bg-slate-100 p-1.5 dark:bg-slate-900">
                        <button class="rounded-xl px-5 py-2 text-sm font-bold transition" @click="filter='all'"
                            :class="filter === 'all' ? 'bg-white dark:bg-slate-800 shadow-md text-indigo-600' : 'text-slate-500'">All</button>
                        <button class="rounded-xl px-5 py-2 text-sm font-bold transition" @click="filter='shop'"
                            :class="filter === 'shop' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500'">Shops</button>
                        <button class="rounded-xl px-5 py-2 text-sm font-bold transition" @click="filter='house'"
                            :class="filter === 'house' ? 'bg-violet-600 text-white shadow-lg' : 'text-slate-500'">House</button>
                    </div>
                    <input
                        class="rounded-full border border-slate-200 px-4 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                        type="text" placeholder="🔍 Search unit (e.g. A-101)" x-model="searchQuery">
                </div>
            </div>

            <!-- unit cards grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template
                    x-for="unit in units.filter(u => (filter==='all' || u.type===filter) && (u.no.toLowerCase().includes(searchQuery.toLowerCase()) || u.price.toString().includes(searchQuery)))"
                    :key="unit.id">
                    <div class="group relative cursor-pointer overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 transition-all hover:border-indigo-500 hover:shadow-2xl dark:border-slate-800 dark:bg-slate-900/50"
                        @click="unit.status === 'Available' ? selectedUnit = unit : (toastMessage='⛔ Occupied or unavailable'; showToast=true; setTimeout(()=>showToast=false,2000))"
                        :class="unit.status === 'Occupied' ? 'grayscale opacity-60' : ''">
                        <div class="mb-5 flex items-start justify-between">
                            <span class="text-3xl font-black tracking-tighter" x-text="unit.no"></span>
                            <div class="h-3 w-3 rounded-full"
                                :class="unit.status === 'Available' ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'">
                            </div>
                        </div>
                        <p class="mb-1 text-xs font-black uppercase tracking-widest text-slate-400"
                            x-text="unit.type + ' · ' + unit.floor"></p>
                        <p class="mb-4 text-3xl font-bold tracking-tight">৳<span
                                x-text="unit.price.toLocaleString()"></span><span
                                class="text-sm font-medium text-slate-400">/mo</span></p>
                        <div
                            class="flex flex-wrap gap-2 text-xs font-bold uppercase text-slate-500 dark:text-slate-400">
                            <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800"
                                x-text="unit.size"></span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800"
                                x-text="unit.bedrooms+' BD'"></span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800"
                                x-text="unit.bathrooms+' BA'"></span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-1">
                            <template x-for="feat in unit.features.slice(0,2)" :key="feat">
                                <span class="text-[10px] font-medium text-indigo-500"
                                    x-text="'#'+feat.toLowerCase().replace(' ','')"></span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- side drawer / unit detail -->
        <div class="fixed inset-0 z-[200] flex items-center justify-end" x-show="selectedUnit" x-cloak>
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-md" x-show="selectedUnit" x-transition.opacity
                @click="selectedUnit = null"></div>
            <div class="relative h-full w-full max-w-lg overflow-y-auto bg-white p-10 shadow-2xl dark:bg-slate-950"
                x-show="selectedUnit" x-transition:enter="transition duration-300 transform"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0">
                <button class="mb-8 flex items-center gap-2 text-slate-400 transition hover:text-slate-600"
                    @click="selectedUnit = null">← Back</button>
                <template x-if="selectedUnit">
                    <div>
                        <h2 class="mb-1 text-7xl font-black" x-text="selectedUnit.no"></h2>
                        <p class="mb-6 text-xl font-medium text-slate-500"
                            x-text="selectedUnit.type + ' on ' + selectedUnit.floor"></p>
                        <div class="mb-8 grid grid-cols-3 gap-2 rounded-3xl bg-slate-100 p-4 dark:bg-slate-900">
                            <div class="text-center"><span class="block text-2xl font-bold"
                                    x-text="selectedUnit.size.replace('sqf','')"></span><span
                                    class="text-xs">sqf</span></div>
                            <div class="text-center"><span class="block text-2xl font-bold"
                                    x-text="selectedUnit.bedrooms"></span><span class="text-xs">beds</span></div>
                            <div class="text-center"><span class="block text-2xl font-bold"
                                    x-text="selectedUnit.bathrooms"></span><span class="text-xs">baths</span></div>
                        </div>
                        <ul class="mb-8 space-y-2 text-sm">
                            <template x-for="feat in selectedUnit.features" :key="feat">
                                <li class="flex items-center gap-2">✓ <span x-text="feat"></span></li>
                            </template>
                        </ul>
                        <button
                            class="w-full rounded-3xl bg-indigo-600 py-6 text-xl font-black text-white shadow-xl shadow-indigo-500/30 transition hover:scale-[1.02] hover:bg-indigo-700"
                            @click="window.open(`https://wa.me/${waNumber}?text=Hi%2C%20I%20want%20to%20book%20unit%20${selectedUnit.no}%20(UrbanSpace)`,'_blank'); selectedUnit=null">
                            📲 Reserve on WhatsApp
                        </button>
                        <p class="mt-4 text-center text-xs text-slate-400">or call +880 1234-567890</p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- === REVIEWS (carousel-like / marquee) === -->
    <section class="bg-slate-50 py-32 dark:bg-slate-900/50" id="reviews">
        <div class="mx-auto max-w-7xl px-6">
            <h2 class="mb-16 text-center text-4xl font-black italic tracking-tighter">"Trusted by residents &
                entrepreneurs"</h2>
            <div class="hide-scrollbar flex snap-x gap-6 overflow-x-auto pb-6">
                <div
                    class="min-w-[300px] flex-1 snap-start rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    ⭐️⭐️⭐️⭐️⭐️<p class="mb-6 mt-2 font-medium text-slate-600 dark:text-slate-400">"The best management
                        in the area. Booking was smooth."</p>
                    <p class="font-bold">- Ariful Islam, shop owner</p>
                </div>
                <div
                    class="min-w-[300px] flex-1 snap-start rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    ⭐️⭐️⭐️⭐️⭐️<p class="mb-6 mt-2 font-medium text-slate-600 dark:text-slate-400">"Quiet, secure,
                        rooftop is a huge plus for my family."</p>
                    <p class="font-bold">- Sarah Akter, resident</p>
                </div>
                <div
                    class="min-w-[300px] flex-1 snap-start rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    ⭐️⭐️⭐️⭐️⭐️<p class="mb-6 mt-2 font-medium text-slate-600 dark:text-slate-400">"Commercial shops
                        have the best road visibility."</p>
                    <p class="font-bold">- Tanvir Ahmed, retailer</p>
                </div>
                <div
                    class="min-w-[300px] flex-1 snap-start rounded-[2rem] border border-slate-100 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    ⭐️⭐️⭐️⭐️<p class="mb-6 mt-2 font-medium text-slate-600 dark:text-slate-400">"Modern finishing,
                        elevator, worth every taka."</p>
                    <p class="font-bold">- Nusrat Jahan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- === FAQ (collapsible) === -->
    <section class="py-20" id="faq">
        <div class="mx-auto max-w-3xl px-6">
            <h2 class="mb-10 text-center text-4xl font-black">Frequently asked questions</h2>
            <div class="space-y-3">
                <template
                    x-for="(faq, idx) in [{q:'Payment terms?',a:'Monthly rent, 2 months security deposit.'},{q:'Pets allowed?',a:'Only in specific residential units.'},{q:'Parking?',a:'Underground parking for residents.'},{q:'Maintenance charge?',a:'Included in rent for most units.'}]"
                    :key="idx">
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800">
                        <button class="flex w-full items-center justify-between p-5 text-left font-bold"
                            @click="faqOpen = (faqOpen === idx ? null : idx)">
                            <span x-text="faq.q"></span>
                            <span x-text="faqOpen === idx ? '−' : '+'"></span>
                        </button>
                        <div class="px-5 pb-5 text-slate-500" x-show="faqOpen === idx" x-cloak x-transition
                            x-text="faq.a"></div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- === CONTACT (with validation & toast) === -->
    <section class="py-20" id="contact">
        <div
            class="relative mx-auto max-w-5xl overflow-hidden rounded-[4rem] bg-indigo-600 p-12 px-6 text-center text-white md:p-20">
            <div class="relative z-10">
                <h2 class="mb-8 text-4xl font-black md:text-6xl">Ready to move in?</h2>
                <p class="mx-auto mb-12 max-w-lg text-lg text-indigo-100">Leave your email or call us. We'll help you
                    find the perfect floor plan.</p>
                <form class="flex flex-col justify-center gap-4 sm:flex-row"
                    @submit.prevent="if(emailInput.includes('@') && emailInput.includes('.')) { toastMessage='📩 Thanks! We’ll reply shortly.'; showToast=true; emailInput=''; setTimeout(()=>showToast=false,3000); } else { toastMessage='❌ valid email required'; showToast=true; setTimeout(()=>showToast=false,2000); }">
                    <input
                        class="rounded-3xl border border-white/20 bg-white/10 px-8 py-5 text-white outline-none placeholder:text-indigo-200 focus:bg-white/20 sm:w-80"
                        type="email" placeholder="your@email.com" x-model="emailInput" required>
                    <button
                        class="rounded-3xl bg-white px-10 py-5 font-black text-indigo-600 transition hover:bg-slate-100"
                        type="submit">Notify me</button>
                </form>
                <p class="mt-8 font-bold text-indigo-100">📞 +880 1234-567890 · 📍 42 Gulshan Ave, Dhaka</p>
            </div>
        </div>
    </section>

    <!-- toast notification -->
    <div class="fixed bottom-8 left-1/2 z-[300] -translate-x-1/2 rounded-full bg-slate-900 px-6 py-3 text-sm font-medium text-white shadow-2xl transition-opacity dark:bg-white dark:text-slate-900"
        x-show="showToast" x-transition x-cloak x-text="toastMessage"></div>

    <!-- footer -->
    <footer
        class="border-t border-slate-200 py-8 text-center text-sm font-medium text-slate-400 dark:border-slate-800">
        © 2026 UrbanSpace — personal property project. All rights reserved.
        <span class="ml-4 inline-flex gap-2"><a class="hover:text-indigo-500" href="#">IG</a> <a
                class="hover:text-indigo-500" href="#">FB</a></span>
    </footer>
</body>

</html>
