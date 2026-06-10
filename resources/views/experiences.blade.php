@extends('layouts.app')

@section('title', 'Riwayat Pengalaman & Karir')

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h5 class="hero-subtitle text-gradient">Karir & Organisasi</h5>
            <h2>Timeline Pengalaman Kerja</h2>
        </div>

        @if($experiences->count() > 0)
            <div class="timeline">
                @foreach($experiences as $exp)
                    <div class="timeline-item">
                        <div class="timeline-content" style="text-align: left;">
                            <span class="timeline-date">
                                {{ $exp->start_date->format('M Y') }} - 
                                {{ $exp->is_current ? 'Sekarang' : ($exp->end_date ? $exp->end_date->format('M Y') : '-') }}
                            </span>
                            
                            <h3 class="timeline-title" style="margin-top: 8px;">{{ $exp->title }}</h3>
                            
                            <div class="timeline-subtitle" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <span>{{ $exp->company }}</span>
                                <span style="font-size: 0.75rem; padding: 2px 10px; border-radius: 20px; font-weight: 700; text-transform: uppercase;
                                    @if($exp->type == 'work') background-color: rgba(99, 102, 241, 0.15); color: #818cf8;
                                    @elseif($exp->type == 'internship') background-color: rgba(16, 185, 129, 0.15); color: #34d399;
                                    @elseif($exp->type == 'freelance') background-color: rgba(245, 158, 11, 0.15); color: #fbbf24;
                                    @else background-color: rgba(107, 114, 128, 0.15); color: #9ca3af;
                                    @endif">
                                    {{ ucfirst($exp->type) }}
                                </span>
                            </div>
                            
                            <p class="timeline-desc" style="margin-top: 12px; font-size: 0.92rem; line-height: 1.6;">
                                {!! nl2br(e($exp->description)) !!}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 60px 0; color: var(--text-secondary);">
                <i class="fa-solid fa-briefcase" style="font-size: 3rem; margin-bottom: 16px; color: var(--border-color);"></i>
                <p>Belum ada riwayat pengalaman.</p>
            </div>
        @endif
    </div>
</section>
@endsection
