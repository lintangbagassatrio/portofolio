<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>@yield('title', \App\Models\Setting::get('meta_title', 'My Portfolio'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('meta_description', 'Professional personal portfolio website.'))">
    <meta name="keywords" content="@yield('meta_keywords', \App\Models\Setting::get('meta_keywords', 'portfolio, developer'))">
    
    <!-- Favicon -->
    @if(\App\Models\Setting::get('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\Setting::get('site_favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Theme Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @yield('styles')
</head>
<body>
    <!-- Page Loading Animation -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="loader"></div>
    </div>

    <!-- Animated Background Glow Blobs -->
    <div class="bg-glow blob-1"></div>
    <div class="bg-glow blob-2"></div>
    <div class="bg-glow blob-3"></div>

    <!-- Header Navigation -->
    <header class="header">
        <div class="container header-container">
            <a href="{{ route('home') }}" class="logo">
                @if(\App\Models\Setting::get('site_logo'))
                    <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}" alt="Logo" style="max-height: 40px;">
                @else
                    <span class="text-gradient">{{ \App\Models\Setting::get('site_name', 'My Portfolio') }}</span>
                @endif
            </a>

            <!-- Mobile Menu Toggle Button -->
            <button class="burger-menu" id="menuToggle" aria-label="Toggle Navigation">
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
            </button>

            <!-- Navigation Links -->
            <nav class="nav-menu" id="navMenu">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About Me</a>
                <a href="{{ route('portfolio') }}" class="nav-link {{ request()->routeIs('portfolio') ? 'active' : '' }}">Portfolio</a>
                <a href="{{ route('skills') }}" class="nav-link {{ request()->routeIs('skills') ? 'active' : '' }}">Skills</a>
                <a href="{{ route('experiences') }}" class="nav-link {{ request()->routeIs('experiences') ? 'active' : '' }}">Experience</a>
                <a href="{{ route('certificates') }}" class="nav-link {{ request()->routeIs('certificates') ? 'active' : '' }}">Certificates</a>
                <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog*') ? 'active' : '' }}">Blog</a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                
                <!-- Theme Toggle & Admin Button -->
                <div class="nav-actions">
                    <button class="btn btn-secondary btn-icon" id="themeToggle" title="Toggle Dark/Light Mode" aria-label="Toggle theme">
                        <i class="fa-solid fa-moon dark-icon"></i>
                        <i class="fa-solid fa-sun light-icon" style="display: none;"></i>
                    </button>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem;">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.85rem;"><i class="fa-solid fa-user-lock"></i> Login</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-socials">
                @if(\App\Models\Setting::get('social_whatsapp'))
                    <a href="https://wa.me/{{ \App\Models\Setting::get('social_whatsapp') }}" target="_blank" class="footer-social-link" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                @endif
                @if(\App\Models\Setting::get('social_email'))
                    <a href="mailto:{{ \App\Models\Setting::get('social_email') }}" class="footer-social-link" title="Email"><i class="fa-solid fa-envelope"></i></a>
                @endif
                @if(\App\Models\Setting::get('social_linkedin'))
                    <a href="{{ \App\Models\Setting::get('social_linkedin') }}" target="_blank" class="footer-social-link" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                @endif
                @if(\App\Models\Setting::get('social_github'))
                    <a href="{{ \App\Models\Setting::get('social_github') }}" target="_blank" class="footer-social-link" title="GitHub"><i class="fa-brands fa-github"></i></a>
                @endif
                @if(\App\Models\Setting::get('social_instagram'))
                    <a href="{{ \App\Models\Setting::get('social_instagram') }}" target="_blank" class="footer-social-link" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                @endif
            </div>
            <p>{{ \App\Models\Setting::get('footer_text', '© 2026 Portfolio. All rights reserved.') }}</p>
        </div>
    </footer>

    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast" style="border-left-color: #10b981;">
                <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="toast" style="border-left-color: #ef4444;">
                <i class="fa-solid fa-circle-xmark" style="color: #ef4444;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="toast" style="border-left-color: #ef4444;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i>
                <span>Please fix the form errors below.</span>
            </div>
        @endif
    </div>

    <!-- Core Script -->
    <script>
        // Page Loader Remove
        window.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('loaderOverlay');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    loader.style.visibility = 'hidden';
                }, 300);
            }
        });

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('themeToggle');
        const darkIcon = themeToggleBtn.querySelector('.dark-icon');
        const lightIcon = themeToggleBtn.querySelector('.light-icon');

        function updateToggleIcon() {
            if (document.documentElement.classList.contains('dark')) {
                darkIcon.style.display = 'none';
                lightIcon.style.display = 'inline-block';
            } else {
                darkIcon.style.display = 'inline-block';
                lightIcon.style.display = 'none';
            }
        }
        updateToggleIcon();

        themeToggleBtn.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateToggleIcon();
        });

        // Mobile Nav Drawer Toggle
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');
            
            // Animate burger menu
            const bars = menuToggle.querySelectorAll('.burger-bar');
            if (menuToggle.classList.contains('active')) {
                bars[0].style.transform = 'rotate(45deg) translate(5px, 6px)';
                bars[1].style.opacity = '0';
                bars[2].style.transform = 'rotate(-45deg) translate(5px, -6px)';
            } else {
                bars[0].style.transform = 'none';
                bars[1].style.opacity = '1';
                bars[2].style.transform = 'none';
            }
        });

        // Close mobile drawer when clicking links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
                const bars = menuToggle.querySelectorAll('.burger-bar');
                bars[0].style.transform = 'none';
                bars[1].style.opacity = '1';
                bars[2].style.transform = 'none';
            });
        });

        // Autohide Toasts after 4 seconds
        setTimeout(() => {
            document.querySelectorAll('.toast').forEach(toast => {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            });
        }, 4000);
    </script>
    
    @yield('scripts')
    
    <!-- Google Analytics Integration Placeholder -->
    {!! \App\Models\Setting::get('google_analytics') !!}
</body>
</html>
