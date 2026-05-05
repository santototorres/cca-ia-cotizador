<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CCA IA Cotizador — Cotización inteligente de logística internacional para Caribberan Cargo Agency. Obtén tarifas estimadas de flete marítimo FCL y LCL con IA.">
    <meta name="keywords" content="cotizador logística, flete marítimo Colombia, FCL LCL, Caribberan Cargo Agency, carga internacional">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CCA IA Cotizador') — Caribberan Cargo Agency</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50:  '#e8edf5',
                            100: '#c5d0e6',
                            200: '#9fb0d4',
                            300: '#7890c2',
                            400: '#5a76b4',
                            500: '#3d5ca6',
                            600: '#304e9a',
                            700: '#203f8a',
                            800: '#12307a',
                            900: '#0A1628',
                            950: '#060e1a',
                        },
                        gold: {
                            50:  '#fef9ee',
                            100: '#fef0d0',
                            200: '#fddea0',
                            300: '#fbc660',
                            400: '#F5A623',
                            500: '#e8920d',
                            600: '#d07308',
                            700: '#ad5409',
                            800: '#8c420f',
                            900: '#733710',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                        'pulse-slow': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 1.5s linear infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #060e1a; }
        ::-webkit-scrollbar-thumb { background: #F5A623; border-radius: 3px; }

        /* Glassmorphism card */
        .glass-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(245,166,35,0.15);
        }

        /* Gold gradient text */
        .text-gold-gradient {
            background: linear-gradient(135deg, #F5A623, #fbc660);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animated border on focus */
        .input-field {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(245,166,35,0.25);
            color: #fff;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #F5A623;
            box-shadow: 0 0 0 3px rgba(245,166,35,0.15);
            background: rgba(255,255,255,0.08);
        }
        .input-field option {
            background: #0A1628;
            color: #fff;
        }

        /* Result card animations */
        .result-card {
            animation: slideUp 0.4s ease-out forwards;
        }
        .result-card:nth-child(1) { animation-delay: 0.05s; }
        .result-card:nth-child(2) { animation-delay: 0.15s; }
        .result-card:nth-child(3) { animation-delay: 0.25s; }
        .result-card:nth-child(4) { animation-delay: 0.35s; }
        .result-card:nth-child(5) { animation-delay: 0.45s; }
        .result-card:nth-child(6) { animation-delay: 0.55s; }

        /* CTA button shimmer */
        .btn-gold {
            background: linear-gradient(135deg, #F5A623, #e8920d);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-gold::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        .btn-gold:hover::before { left: 100%; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245,166,35,0.4); }

        /* Shipping lane animation background */
        .hero-bg {
            background:
                radial-gradient(ellipse at 20% 50%, rgba(245,166,35,0.06) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(245,166,35,0.04) 0%, transparent 60%),
                linear-gradient(135deg, #060e1a 0%, #0A1628 50%, #0d1f3c 100%);
        }

        /* Modal backdrop */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-navy-950 text-white font-sans min-h-screen flex flex-col">

    <!-- ═══ HEADER ═══ -->
    <header class="sticky top-0 z-50 border-b border-gold-400/10" style="background: rgba(6,14,26,0.95); backdrop-filter: blur(20px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <!-- Contenedor SVG Icon -->
                    <div class="relative">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:scale-105 transition-transform duration-300">
                            <rect width="40" height="40" rx="8" fill="rgba(245,166,35,0.1)"/>
                            <!-- Contenedor marítimo estilizado -->
                            <rect x="5" y="13" width="30" height="16" rx="2" stroke="#F5A623" stroke-width="1.5" fill="none"/>
                            <line x1="13" y1="13" x2="13" y2="29" stroke="#F5A623" stroke-width="1" opacity="0.6"/>
                            <line x1="20" y1="13" x2="20" y2="29" stroke="#F5A623" stroke-width="1" opacity="0.6"/>
                            <line x1="27" y1="13" x2="27" y2="29" stroke="#F5A623" stroke-width="1" opacity="0.6"/>
                            <!-- Ruedas/patas -->
                            <rect x="8" y="29" width="4" height="3" rx="1" fill="#F5A623" opacity="0.7"/>
                            <rect x="28" y="29" width="4" height="3" rx="1" fill="#F5A623" opacity="0.7"/>
                            <!-- Chispa/AI indicador -->
                            <circle cx="33" cy="10" r="4" fill="#F5A623"/>
                            <text x="33" y="13" text-anchor="middle" font-size="5" fill="#0A1628" font-weight="bold">AI</text>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-gold-gradient">CCA IA</span>
                        <span class="hidden sm:inline text-white font-semibold text-xl"> Cotizador</span>
                        <p class="text-xs text-gray-500 -mt-0.5 hidden sm:block">Caribberan Cargo Agency</p>
                    </div>
                </a>

                <!-- Nav Links -->
                <nav class="flex items-center gap-6">
                    <a href="https://ccargo.co" target="_blank" rel="noopener noreferrer"
                       class="text-sm text-gray-400 hover:text-gold-400 transition-colors duration-200 hidden sm:block">
                        ccargo.co ↗
                    </a>
                    <a href="mailto:ingeniero.ambiental@ccargo.co"
                       class="text-sm font-medium px-4 py-2 rounded-lg border border-gold-400/30 text-gold-400 hover:bg-gold-400/10 transition-all duration-200 hidden sm:flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contacto
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- ═══ MAIN CONTENT ═══ -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- ═══ FOOTER ═══ -->
    <footer class="border-t border-gold-400/10 bg-navy-950 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <svg width="28" height="28" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="8" fill="rgba(245,166,35,0.08)"/>
                            <rect x="5" y="13" width="30" height="16" rx="2" stroke="#F5A623" stroke-width="1.5" fill="none"/>
                            <line x1="13" y1="13" x2="13" y2="29" stroke="#F5A623" stroke-width="1" opacity="0.5"/>
                            <line x1="20" y1="13" x2="20" y2="29" stroke="#F5A623" stroke-width="1" opacity="0.5"/>
                            <line x1="27" y1="13" x2="27" y2="29" stroke="#F5A623" stroke-width="1" opacity="0.5"/>
                        </svg>
                        <span class="font-bold text-gold-400">CCA IA Cotizador</span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Cotización inteligente de logística internacional<br>
                        impulsada por IA para el comercio exterior colombiano.
                    </p>
                </div>

                <!-- Empresa -->
                <div>
                    <h3 class="text-sm font-semibold text-gold-400 uppercase tracking-wider mb-3">Caribberan Cargo Agency</h3>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Cartagena de Indias, Colombia
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2H5a2 2 0 00-2 2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:ingeniero.ambiental@ccargo.co" class="hover:text-gold-400 transition-colors">
                                ingeniero.ambiental@ccargo.co
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            <a href="https://ccargo.co" target="_blank" rel="noopener" class="hover:text-gold-400 transition-colors">
                                ccargo.co
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Servicios -->
                <div>
                    <h3 class="text-sm font-semibold text-gold-400 uppercase tracking-wider mb-3">Servicios</h3>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>🚢 Flete marítimo FCL y LCL</li>
                        <li>✈️ Carga aérea internacional</li>
                        <li>📋 Agenciamiento aduanero</li>
                        <li>📦 Almacenaje y distribución</li>
                        <li>🌐 Comercio exterior Colombia</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center gap-2">
                <p class="text-xs text-gray-600">
                    © {{ date('Y') }} Caribberan Cargo Agency. Todos los derechos reservados.
                </p>
                <p class="text-xs text-gray-700">
                    Las tarifas son estimativas. Contáctenos para cotización oficial.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
