@extends('layouts.app')

@section('title', 'Skills & Keahlian')

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h5 class="hero-subtitle text-gradient">Kemampuan</h5>
            <h2>Keahlian Teknis & Soft Skills</h2>
        </div>

        <div class="skills-grid">
            <!-- Technical Skills -->
            <div>
                <h3 class="skills-column-title"><i class="fa-solid fa-code text-gradient"></i> Technical Skills</h3>
                <div class="skills-list">
                    @forelse($technicalSkills as $skill)
                        <div class="skill-item">
                            <div class="skill-info">
                                <span>{{ $skill->name }}</span>
                                <span>{{ $skill->level }}%</span>
                            </div>
                            <div class="skill-progress-bg">
                                <div class="skill-progress-bar" style="width: {{ $skill->level }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <p style="color: var(--text-secondary);">Belum ada data skill teknis.</p>
                    @endforelse
                </div>
            </div>

            <!-- Soft Skills -->
            <div>
                <h3 class="skills-column-title"><i class="fa-solid fa-brain text-gradient"></i> Soft Skills</h3>
                <div class="skills-list">
                    @forelse($softSkills as $skill)
                        <div class="skill-item">
                            <div class="skill-info">
                                <span>{{ $skill->name }}</span>
                                <span>{{ $skill->level }}%</span>
                            </div>
                            <div class="skill-progress-bg">
                                <div class="skill-progress-bar" style="width: {{ $skill->level }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <p style="color: var(--text-secondary);">Belum ada data soft skill.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
