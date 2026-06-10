<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin Dashboard') - Admin Area</title>
    
    <!-- Favicon -->
    @if(\App\Models\Setting::get('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\Setting::get('site_favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @endif

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
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
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('home') }}" target="_blank" class="logo">
                    <span class="text-gradient">Admin Panel</span>
                </a>
            </div>
            
            <nav class="admin-sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="admin-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.profile') }}" class="admin-menu-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i> Kelola Profil
                </a>
                <a href="{{ route('admin.portfolios.index') }}" class="admin-menu-item {{ request()->routeIs('admin.portfolios.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-briefcase"></i> Kelola Portofolio
                </a>
                <a href="{{ route('admin.skills.index') }}" class="admin-menu-item {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-brain"></i> Kelola Skill
                </a>
                <a href="{{ route('admin.experiences.index') }}" class="admin-menu-item {{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-timeline"></i> Kelola Pengalaman
                </a>
                <a href="{{ route('admin.certificates.index') }}" class="admin-menu-item {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-award"></i> Kelola Sertifikat
                </a>
                <a href="{{ route('admin.blogs.index') }}" class="admin-menu-item {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper"></i> Kelola Blog
                </a>
                <a href="{{ route('admin.messages.index') }}" class="admin-menu-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope"></i> Kelola Pesan
                </a>
                
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.settings.index') }}" class="admin-menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders"></i> Pengaturan Web
                    </a>
                @endif
            </nav>
            
            <div class="admin-sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="width: 100%; justify-content: flex-start; gap: 10px;">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <div class="admin-content">
            <!-- Navbar -->
            <header class="admin-navbar">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <button class="menu-toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h2 style="font-size: 1.25rem;">@yield('title', 'Dashboard')</h2>
                </div>
                
                <div class="admin-user-menu">
                    <!-- Theme Toggle -->
                    <button class="btn btn-secondary btn-icon" id="themeToggle" title="Toggle Dark/Light Mode" aria-label="Toggle theme">
                        <i class="fa-solid fa-moon dark-icon"></i>
                        <i class="fa-solid fa-sun light-icon" style="display: none;"></i>
                    </button>
                    
                    <div style="display: flex; align-items: center; gap: 12px;">
                        @if(Auth::user()->profile && Auth::user()->profile->photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile->photo) }}" alt="Avatar" class="admin-user-avatar">
                        @else
                            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=80&h=80" alt="Avatar Default" class="admin-user-avatar">
                        @endif
                        <div class="admin-user-info">
                            <div class="admin-user-name">{{ Auth::user()->name }}</div>
                            <div class="admin-user-role">{{ Auth::user()->role }}</div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="admin-main">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Container -->
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
                <span>Gagal menyimpan data. Cek input Anda.</span>
            </div>
        @endif
    </div>

    <!-- Core Scripts -->
    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                adminSidebar.classList.toggle('active');
            });
        }

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

        // Reusable Image Preview Helper (Upload Gambar dengan Preview)
        function initImagePreview(inputId, previewImgId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewImgId);
            
            if (input && preview) {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                            
                            // Hide icon if present in parent
                            const icon = preview.parentElement.querySelector('i');
                            if (icon) icon.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        // Autohide Toasts
        setTimeout(() => {
            document.querySelectorAll('.toast').forEach(toast => {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            });
        }, 4000);
    </script>
    @yield('scripts')
</body>
</html>
