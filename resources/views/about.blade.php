@extends('layouts.app')

@section('title', 'About Me - ' . ($profile ? $profile->full_name : 'John Doe'))

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h5 class="hero-subtitle text-gradient">Tentang Saya</h5>
            <h2>Profil Lengkap & Karir</h2>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 48px; margin-bottom: 60px; align-items: start;">
            <!-- Profile Info Card -->
            <div class="contact-info-list" style="position: sticky; top: 100px;">
                <div class="timeline-content" style="text-align: center; padding: 32px 24px;">
                    <div style="position: relative; width: 140px; height: 140px; margin: 0 auto 20px auto;">
                        @if($profile && $profile->photo)
                            <img src="{{ asset('storage/' . $profile->photo) }}" alt="Photo" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent);">
                        @else
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=140&h=140" alt="Photo" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent);">
                        @endif
                    </div>
                    <h3 style="font-size: 1.4rem; margin-bottom: 8px;">{{ $profile ? $profile->full_name : 'John Doe' }}</h3>
                    <p style="color: var(--accent); font-weight: 600; font-size: 0.9rem; margin-bottom: 24px; text-transform: uppercase;">{{ $profile ? $profile->education : 'Developer' }}</p>
                    
                    <div style="text-align: left; display: flex; flex-direction: column; gap: 14px; border-top: 1px solid var(--border-color); padding-top: 24px; font-size: 0.9rem;">
                        <div>
                            <strong style="display: block; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 2px;">Tempat, Tgl Lahir</strong>
                            <span>{{ $profile ? $profile->birth_place_date : '-' }}</span>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 2px;">Alamat</strong>
                            <span>{{ $profile ? $profile->address : '-' }}</span>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 2px;">Email</strong>
                            <a href="mailto:{{ $profile ? $profile->email : '' }}" class="text-gradient" style="font-weight: 500;">{{ $profile ? $profile->email : '-' }}</a>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 2px;">Nomor Telepon</strong>
                            <span>{{ $profile ? $profile->phone : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biography & Details -->
            <div style="display: flex; flex-direction: column; gap: 32px;">
                <div class="timeline-content" style="text-align: left;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 16px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;"><i class="fa-solid fa-user text-gradient"></i> Latar Belakang</h3>
                    <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.7;">{!! nl2br(e($profile ? $profile->background : 'Latar belakang belum diisi.')) !!}</p>
                </div>

                <div class="timeline-content" style="text-align: left;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 16px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;"><i class="fa-solid fa-briefcase text-gradient"></i> Perjalanan Karir</h3>
                    <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.7;">{!! nl2br(e($profile ? $profile->career : 'Informasi karir belum diisi.')) !!}</p>
                </div>

                <div class="timeline-content" style="text-align: left;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 16px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;"><i class="fa-solid fa-compass text-gradient"></i> Minat & Passion</h3>
                    <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.7;">{!! nl2br(e($profile ? $profile->interests : 'Minat belum diisi.')) !!}</p>
                </div>

                <div class="timeline-content" style="text-align: left;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 16px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;"><i class="fa-solid fa-bullseye text-gradient"></i> Tujuan Profesional</h3>
                    <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.7;">{!! nl2br(e($profile ? $profile->goals : 'Tujuan belum diisi.')) !!}</p>
                </div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 60px 0;">

        <!-- Work Experience Timeline -->
        <div style="margin-bottom: 80px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h5 class="hero-subtitle text-gradient">Riwayat Karir</h5>
                <h2>Pengalaman Kerja & Magang</h2>
            </div>
            
            @if($workExperiences->count() > 0)
                <div class="timeline">
                    @foreach($workExperiences as $exp)
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <span class="timeline-date">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - {{ $exp->is_current ? 'Present' : \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}</span>
                                <h3 class="timeline-title">{{ $exp->title }}</h3>
                                <div class="timeline-subtitle">{{ $exp->company }} <span style="font-size: 0.8rem; padding: 2px 8px; border-radius: 10px; background-color: var(--accent-light); color: var(--accent); margin-left: 8px; text-transform: uppercase;">{{ $exp->type }}</span></div>
                                <p class="timeline-desc">{!! nl2br(e($exp->description)) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="text-align: center; color: var(--text-secondary);">Belum ada riwayat pengalaman.</p>
            @endif
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 60px 0;">

        <!-- Education Timeline -->
        <div style="margin-bottom: 80px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h5 class="hero-subtitle text-gradient">Riwayat Akademis</h5>
                <h2>Pendidikan & Organisasi</h2>
            </div>
            
            @if($educationTimeline->count() > 0)
                <div class="timeline">
                    @foreach($educationTimeline as $edu)
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <span class="timeline-date">{{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} - {{ $edu->is_current ? 'Present' : \Carbon\Carbon::parse($edu->end_date)->format('M Y') }}</span>
                                <h3 class="timeline-title">{{ $edu->title }}</h3>
                                <div class="timeline-subtitle">{{ $edu->company }}</div>
                                <p class="timeline-desc">{!! nl2br(e($edu->description)) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="text-align: center; color: var(--text-secondary);">Belum ada riwayat pendidikan/organisasi.</p>
            @endif
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 60px 0;">

        <!-- Certifications List -->
        <div>
            <div style="text-align: center; margin-bottom: 40px;">
                <h5 class="hero-subtitle text-gradient">Prestasi</h5>
                <h2>Daftar Sertifikasi Lengkap</h2>
            </div>

            @if($certificates->count() > 0)
                <div class="portfolio-grid">
                    @foreach($certificates as $cert)
                        <div class="portfolio-card">
                            <div class="portfolio-thumbnail" style="height: 180px;">
                                @if($cert->thumbnail)
                                    <img src="{{ asset('storage/' . $cert->thumbnail) }}" alt="{{ $cert->name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1589330694653-ded6df53f7ec?auto=format&fit=crop&q=80&w=400&h=250" alt="Cert Default">
                                @endif
                                <span class="portfolio-category-badge" style="background-color: var(--accent-light); color: var(--accent);">{{ $cert->year }}</span>
                            </div>
                            <div class="portfolio-info" style="gap: 8px;">
                                <h4 class="portfolio-card-title" style="font-size: 1.15rem;">{{ $cert->name }}</h4>
                                <div style="font-size: 0.85rem; color: var(--accent); font-weight: 600;">{{ $cert->publisher }}</div>
                                <p class="portfolio-card-desc" style="font-size: 0.85rem;">{{ $cert->description }}</p>
                            </div>
                            @if($cert->file_path)
                                <div class="portfolio-links">
                                    <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" class="portfolio-link"><i class="fa-solid fa-eye"></i> Lihat Sertifikat</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p style="text-align: center; color: var(--text-secondary);">Belum ada sertifikasi.</p>
            @endif
        </div>
    </div>
</section>
@endsection
