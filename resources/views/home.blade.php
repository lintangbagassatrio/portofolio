@extends('layouts.app')

@section('title', $profile ? $profile->full_name . ' - Portfolio' : 'Welcome to My Portfolio')

@section('content')
<!-- Hero Section -->
<section class="section">
    <div class="container hero-wrapper">
        <div class="hero-content">
            <h5 class="hero-subtitle text-gradient">{{ $profile ? $profile->education : 'Software Engineer & Designer' }}</h5>
            <h1 class="hero-title">Hi, I'm <span class="text-gradient">{{ $profile ? $profile->full_name : 'John Doe' }}</span></h1>
            <p class="hero-desc">
                {{ $profile ? $profile->bio : 'I build modern, responsive, and secure web applications with high quality standards.' }}
            </p>
            <div class="hero-actions">
                <a href="{{ route('portfolio') }}" class="btn btn-primary">Lihat Portofolio <i class="fa-solid fa-arrow-right"></i></a>
                @if($profile && $profile->cv_file)
                    <a href="{{ asset('storage/' . $profile->cv_file) }}" class="btn btn-secondary" download>Download CV <i class="fa-solid fa-download"></i></a>
                @else
                    <a href="#" class="btn btn-secondary" onclick="alert('CV not uploaded yet.'); return false;">Download CV <i class="fa-solid fa-download"></i></a>
                @endif
                <a href="{{ route('contact') }}" class="btn btn-secondary">Hubungi Saya <i class="fa-solid fa-envelope"></i></a>
            </div>
        </div>
        <div class="hero-image-container">
            <div class="hero-image-bg"></div>
            @if($profile && $profile->photo)
                <img src="{{ asset('storage/' . $profile->photo) }}" alt="{{ $profile->full_name }}" class="hero-image">
            @else
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=320&h=320" alt="Profile Default" class="hero-image">
            @endif
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="section" style="background-color: var(--bg-tertiary); padding: 60px 0;">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="projects-count">{{ $stats['projects'] }}</div>
                <div class="stat-label">Jumlah Project</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="exp-count">{{ $stats['experience'] }}+</div>
                <div class="stat-label">Pengalaman Kerja</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="cert-count">{{ $stats['certificates'] }}</div>
                <div class="stat-label">Sertifikat</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="skills-count">{{ $stats['skills'] }}</div>
                <div class="stat-label">Skill Dikuasai</div>
            </div>
        </div>
    </div>
</section>

<!-- Skills Section Preview -->
<section class="section">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h5 class="hero-subtitle text-gradient">Kemampuan Saya</h5>
            <h2>Bahasa & Teknologi Unggulan</h2>
        </div>
        
        <div class="skills-grid" style="grid-template-columns: 1fr; max-width: 800px; margin: 0 auto;">
            <div class="skills-list" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                @foreach($skills as $skill)
                    <div class="skill-item">
                        <div class="skill-info">
                            <span>{{ $skill->name }}</span>
                            <span>{{ $skill->level }}%</span>
                        </div>
                        <div class="skill-progress-bg">
                            <div class="skill-progress-bar" style="width: {{ $skill->level }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('skills') }}" class="btn btn-secondary">Lihat Semua Skill <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- Highlights (Featured Projects & Experiences) -->
<section class="section" style="background-color: var(--bg-secondary);">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h5 class="hero-subtitle text-gradient">Highlight</h5>
            <h2>Project Unggulan & Pengalaman Terbaru</h2>
        </div>

        <div style="margin-bottom: 60px;">
            <h3 style="margin-bottom: 24px; display: flex; align-items: center; gap: 10px;"><i class="fa-solid fa-star text-gradient"></i> Project Unggulan</h3>
            @if($featuredPortfolios->count() > 0)
                <div class="portfolio-grid">
                    @foreach($featuredPortfolios as $portfolio)
                        <div class="portfolio-card">
                            <div class="portfolio-thumbnail">
                                @if($portfolio->thumbnail)
                                    <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&q=80&w=400&h=250" alt="Thumbnail Default">
                                @endif
                                <span class="portfolio-category-badge">{{ $portfolio->category->name }}</span>
                            </div>
                            <div class="portfolio-info">
                                <h4 class="portfolio-card-title">{{ $portfolio->title }}</h4>
                                <p class="portfolio-card-desc">{{ Str::limit($portfolio->description, 110) }}</p>
                                <div class="portfolio-tech">
                                    @if(is_array($portfolio->technology_used))
                                        @foreach($portfolio->technology_used as $tech)
                                            <span class="tech-badge">{{ $tech }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="portfolio-links">
                                @if($portfolio->demo_link)
                                    <a href="{{ $portfolio->demo_link }}" target="_blank" class="portfolio-link"><i class="fa-solid fa-up-right-from-square"></i> Demo</a>
                                @endif
                                @if($portfolio->github_link)
                                    <a href="{{ $portfolio->github_link }}" target="_blank" class="portfolio-link"><i class="fa-brands fa-github"></i> GitHub</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-secondary);">Belum ada project unggulan.</p>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <!-- Latest Experience Highlight -->
            <div>
                <h3 style="margin-bottom: 24px; display: flex; align-items: center; gap: 10px;"><i class="fa-solid fa-briefcase text-gradient"></i> Pengalaman Terbaru</h3>
                @if($latestExperience)
                    <div class="timeline-content" style="text-align: left;">
                        <span class="timeline-date">{{ $latestExperience->start_date->format('M Y') }} - {{ $latestExperience->is_current ? 'Present' : $latestExperience->end_date->format('M Y') }}</span>
                        <h4 class="timeline-title">{{ $latestExperience->title }}</h4>
                        <div class="timeline-subtitle">{{ $latestExperience->company }} ({{ ucfirst($latestExperience->type) }})</div>
                        <p class="timeline-desc">{{ Str::limit($latestExperience->description, 200) }}</p>
                        <a href="{{ route('experiences') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem; margin-top: 14px; display: inline-flex;">Selengkapnya</a>
                    </div>
                @else
                    <p style="color: var(--text-secondary);">Belum ada riwayat pengalaman.</p>
                @endif
            </div>

            <!-- Latest Blog Highlight -->
            <div>
                <h3 style="margin-bottom: 24px; display: flex; align-items: center; gap: 10px;"><i class="fa-solid fa-newspaper text-gradient"></i> Artikel Terbaru</h3>
                @if($latestBlog)
                    <div class="blog-card" style="text-align: left;">
                        <div class="blog-info">
                            <span class="blog-date">{{ $latestBlog->created_at->format('d M Y') }} • {{ $latestBlog->category->name }}</span>
                            <h4 class="blog-card-title"><a href="{{ route('blog.detail', $latestBlog->slug) }}">{{ $latestBlog->title }}</a></h4>
                            <p class="blog-card-desc">{{ Str::limit(strip_tags($latestBlog->content), 120) }}</p>
                            <a href="{{ route('blog.detail', $latestBlog->slug) }}" class="text-gradient" style="font-weight: 600; font-size: 0.9rem; align-self: flex-start; margin-top: 10px; display: inline-flex; align-items: center; gap: 6px;">Baca Artikel <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                @else
                    <p style="color: var(--text-secondary);">Belum ada artikel terbaru.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
