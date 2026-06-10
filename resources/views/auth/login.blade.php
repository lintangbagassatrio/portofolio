@extends('layouts.app')

@section('title', 'Login Admin - Dashboard')

@section('content')
<section class="section" style="padding-top: 140px; min-height: 80vh; display: flex; align-items: center;">
    <div class="container" style="display: flex; justify-content: center;">
        <div class="contact-form-card" style="width: 100%; max-width: 450px; text-align: left; padding: 40px;">
            <div style="text-align: center; margin-bottom: 32px;">
                <h5 class="hero-subtitle text-gradient" style="font-size: 0.85rem; letter-spacing: 2px;">Akses Dashboard</h5>
                <h2 style="font-size: 1.8rem; margin-top: 4px;">Login Admin</h2>
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 8px;">Silakan masuk untuk mengelola portofolio Anda.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error" style="padding: 12px; margin-bottom: 20px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="admin@portfolio.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group" style="margin-top: 16px;">
                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div style="display: flex; align-items: center; gap: 8px; margin-top: 20px; font-size: 0.9rem;">
                    <input type="checkbox" name="remember" id="remember" style="width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer;">
                    <label for="remember" style="cursor: pointer; color: var(--text-secondary); font-weight: 500;">Ingat Saya (Remember Me)</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 24px; padding: 14px;">Masuk Ke Dashboard <i class="fa-solid fa-right-to-bracket"></i></button>
            </form>
            
            <div style="text-align: center; margin-top: 24px; font-size: 0.85rem; color: var(--text-secondary);">
                <a href="{{ route('home') }}" class="portfolio-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</section>
@endsection
