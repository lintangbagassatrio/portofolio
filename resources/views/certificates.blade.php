@extends('layouts.app')

@section('title', 'Sertifikat & Penghargaan')

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h5 class="hero-subtitle text-gradient">Sertifikasi</h5>
            <h2>Daftar Sertifikat Lengkap</h2>
        </div>

        @if($certificates->count() > 0)
            <div class="portfolio-grid">
                @foreach($certificates as $cert)
                    <div class="portfolio-card">
                        <div class="portfolio-thumbnail" style="height: 200px;">
                            @if($cert->thumbnail)
                                <img src="{{ asset('storage/' . $cert->thumbnail) }}" alt="{{ $cert->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1589330694653-ded6df53f7ec?auto=format&fit=crop&q=80&w=400&h=250" alt="Certificate Default">
                            @endif
                            <span class="portfolio-category-badge" style="background-color: var(--accent-light); color: var(--accent);">{{ $cert->year }}</span>
                        </div>
                        <div class="portfolio-info" style="gap: 10px;">
                            <h4 class="portfolio-card-title">{{ $cert->name }}</h4>
                            <div style="font-size: 0.9rem; color: var(--accent); font-weight: 600;">{{ $cert->publisher }}</div>
                            <p class="portfolio-card-desc">{{ $cert->description }}</p>
                        </div>
                        @if($cert->file_path)
                            <div class="portfolio-links">
                                <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" class="portfolio-link"><i class="fa-solid fa-up-right-from-square"></i> Lihat Sertifikat</a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 60px 0; color: var(--text-secondary);">
                <i class="fa-solid fa-award" style="font-size: 3rem; margin-bottom: 16px; color: var(--border-color);"></i>
                <p>Belum ada sertifikat.</p>
            </div>
        @endif
    </div>
</section>
@endsection
