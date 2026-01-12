<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM System</title>
    <!-- Tailwind CSS + DaisyUI (Compiled) -->
    <link href="../dist/output.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
    </script>
    <style>
        /* Loading Screen Styles - Smooth Version */
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 1;
            backdrop-filter: blur(0px);
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                backdrop-filter 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Light theme loader */
        [data-theme="light"] #page-loader,
        [data-theme=""] #page-loader,
        :root:not([data-theme="dark"]) #page-loader {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        }

        /* Dark theme loader */
        [data-theme="dark"] #page-loader {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }

        .loader-content {
            text-align: center;
            transform: scale(1);
            opacity: 1;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .loader-spinner {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            border-radius: 50%;
            border: 4px solid transparent;
            animation: spin 0.8s cubic-bezier(0.4, 0, 0.2, 1) infinite,
                pulse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite,
                glow 2s ease-in-out infinite;
        }

        /* Light theme spinner */
        [data-theme="light"] .loader-spinner,
        [data-theme=""] .loader-spinner,
        :root:not([data-theme="dark"]) .loader-spinner {
            border-top-color: #570df8;
            border-right-color: #570df8;
            box-shadow: 0 0 25px rgba(87, 13, 248, 0.4);
        }

        /* Dark theme spinner */
        [data-theme="dark"] .loader-spinner {
            border-top-color: #661AE6;
            border-right-color: #661AE6;
            box-shadow: 0 0 25px rgba(102, 26, 230, 0.6);
        }

        .loader-text {
            margin-top: 24px;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            animation: textPulse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        /* Light theme text */
        [data-theme="light"] .loader-text,
        [data-theme=""] .loader-text,
        :root:not([data-theme="dark"]) .loader-text {
            color: #570df8;
        }

        /* Dark theme text */
        [data-theme="dark"] .loader-text {
            color: #a78bfa;
        }

        .loader-dots {
            display: inline-flex;
            gap: 6px;
            margin-left: 6px;
        }

        .loader-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: dotBounce 1.2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        [data-theme="light"] .loader-dots span,
        [data-theme=""] .loader-dots span,
        :root:not([data-theme="dark"]) .loader-dots span {
            background: #570df8;
        }

        [data-theme="dark"] .loader-dots span {
            background: #a78bfa;
        }

        .loader-dots span:nth-child(1) {
            animation-delay: 0s;
        }

        .loader-dots span:nth-child(2) {
            animation-delay: 0.15s;
        }

        .loader-dots span:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: rotate(0deg) scale(1);
            }

            50% {
                transform: rotate(180deg) scale(1.08);
            }
        }

        @keyframes glow {

            0%,
            100% {
                filter: brightness(1);
            }

            50% {
                filter: brightness(1.2);
            }
        }

        @keyframes textPulse {

            0%,
            100% {
                opacity: 0.7;
                transform: translateY(0);
            }

            50% {
                opacity: 1;
                transform: translateY(-2px);
            }
        }

        @keyframes dotBounce {

            0%,
            100% {
                transform: translateY(0) scale(1);
                opacity: 0.5;
            }

            50% {
                transform: translateY(-10px) scale(1.2);
                opacity: 1;
            }
        }

        /* Smooth hide animation */
        #page-loader.hiding {
            opacity: 0;
            backdrop-filter: blur(10px);
        }

        #page-loader.hiding .loader-content {
            transform: scale(0.9);
            opacity: 0;
        }

        #page-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* Page content smooth reveal */
        .drawer {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .drawer.loaded {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="bg-base-200 min-h-screen">
    <!-- Page Loader -->
    <div id="page-loader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <div class="loader-text">
                Memuat
                <span class="loader-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </div>
        </div>
    </div>

    <!-- Fixed Floating Theme Controller (Desktop Only) -->
    <div class="hidden lg:block fixed bottom-4 right-4 z-[9998]">
        <div class="dropdown dropdown-top dropdown-end">
            <div tabindex="0" role="button" class="btn btn-circle btn-primary shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300">
                <i class="fa-solid fa-palette text-lg"></i>
            </div>
            <ul tabindex="0" class="dropdown-content bg-base-100 rounded-box z-[9999] w-52 p-2 shadow-2xl mb-2 border border-base-300 max-h-96 overflow-y-auto">
                <li class="px-3 py-2 sticky top-0    bg-base-100">
                    <span class="text-xs font-bold uppercase opacity-60">Pilih Tema</span>
                </li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌞 Light" value="light" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌙 Dark" value="dark" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🧁 Cupcake" value="cupcake" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🐝 Bumblebee" value="bumblebee" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="💎 Emerald" value="emerald" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🏢 Corporate" value="corporate" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🎹 Synthwave" value="synthwave" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="📺 Retro" value="retro" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🤖 Cyberpunk" value="cyberpunk" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="💖 Valentine" value="valentine" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🎃 Halloween" value="halloween" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌷 Garden" value="garden" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌲 Forest" value="forest" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌊 Aqua" value="aqua" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🎵 Lofi" value="lofi" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🎨 Pastel" value="pastel" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🦄 Fantasy" value="fantasy" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="📐 Wireframe" value="wireframe" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="⬛ Black" value="black" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="👑 Luxury" value="luxury" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🧛 Dracula" value="dracula" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🖨️ CMYK" value="cmyk" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🍂 Autumn" value="autumn" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="💼 Business" value="business" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🧪 Acid" value="acid" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🍋 Lemonade" value="lemonade" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌃 Night" value="night" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="☕ Coffee" value="coffee" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="❄️ Winter" value="winter" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🔅 Dim" value="dim" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🏔️ Nord" value="nord" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌅 Sunset" value="sunset" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🍮 Caramellatte" value="caramellatte" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌌 Abyss" value="abyss" /></li>
                <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🧵 Silk" value="silk" /></li>
            </ul>
        </div>
    </div>

    <script>
        // Smooth page loader with seamless transition
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            const drawer = document.querySelector('.drawer');

            // Small delay for smoother feel
            setTimeout(function() {
                // Start hiding animation
                loader.classList.add('hiding');

                // Reveal page content with slight delay
                setTimeout(function() {
                    if (drawer) drawer.classList.add('loaded');
                }, 200);

                // Complete hide after transition
                setTimeout(function() {
                    loader.classList.add('hidden');
                    loader.classList.remove('hiding');

                    // Remove from DOM after animation
                    setTimeout(function() {
                        loader.style.display = 'none';
                    }, 100);
                }, 600);
            }, 200);
        });
    </script>
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col p-4 lg:p-8">
            <!-- Topbar -->
            <div class="flex justify-between items-center mb-4">
                <!-- Mobile menu button -->
                <label for="my-drawer-2" class="btn btn-ghost drawer-button lg:hidden">
                    <i class="fa-solid fa-bars"></i>
                </label>

                <!-- Title (mobile only) -->
                <h1 class="text-xl font-bold lg:hidden">CRM System</h1>

                <!-- Right side controls (Mobile Only) -->
                <div class="flex items-center gap-2 ml-auto lg:hidden">
                    <!-- Theme Controller Dropdown (Mobile) -->
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-sm gap-1">
                            <i class="fa-solid fa-palette"></i>
                            <span class="hidden sm:inline">Tema</span>
                            <svg width="12px" height="12px" class="h-2 w-2 fill-current opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
                                <path d="M1799 349l242 241-1017 1017L7 590l242-241 775 775 775-775z"></path>
                            </svg>
                        </div>
                        <ul tabindex="0" class="dropdown-content bg-base-200 rounded-box z-50 w-52 p-2 shadow-2xl mt-2 max-h-80 overflow-y-auto">
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌞 Light" value="light" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="� Dark" value="dark" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🧁 Cupcake" value="cupcake" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🐝 Bumblebee" value="bumblebee" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="💎 Emerald" value="emerald" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="� Corporate" value="corporate" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🎹 Synthwave" value="synthwave" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="📺 Retro" value="retro" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="� Cyberpunk" value="cyberpunk" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="💖 Valentine" value="valentine" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🎃 Halloween" value="halloween" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌷 Garden" value="garden" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="� Forest" value="forest" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌊 Aqua" value="aqua" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🎵 Lofi" value="lofi" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🎨 Pastel" value="pastel" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🦄 Fantasy" value="fantasy" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="� Wireframe" value="wireframe" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="⬛ Black" value="black" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="👑 Luxury" value="luxury" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="� Dracula" value="dracula" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🖨️ CMYK" value="cmyk" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🍂 Autumn" value="autumn" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="💼 Business" value="business" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🧪 Acid" value="acid" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🍋 Lemonade" value="lemonade" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌃 Night" value="night" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="☕ Coffee" value="coffee" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="❄️ Winter" value="winter" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🔅 Dim" value="dim" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🏔️ Nord" value="nord" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌅 Sunset" value="sunset" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="� Caramellatte" value="caramellatte" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🌌 Abyss" value="abyss" /></li>
                            <li><input type="radio" name="theme-dropdown" class="theme-controller btn btn-sm btn-block btn-ghost justify-start" aria-label="🧵 Silk" value="silk" /></li>
                        </ul>
                    </div>

                    <!-- Avatar (mobile only) -->
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama_lengkap']); ?>" />
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <?php displayFlash(); ?>


            <script>
                // Theme Controller - Set saved theme on radio buttons
                document.addEventListener('DOMContentLoaded', function() {
                    const savedTheme = localStorage.getItem('theme') || 'light';
                    const themeRadios = document.querySelectorAll('input[name="theme-dropdown"]');

                    // Set the saved theme as checked
                    themeRadios.forEach(radio => {
                        if (radio.value === savedTheme) {
                            radio.checked = true;
                        }

                        // Listen for changes
                        radio.addEventListener('change', function() {
                            localStorage.setItem('theme', this.value);
                        });
                    });
                });
            </script>