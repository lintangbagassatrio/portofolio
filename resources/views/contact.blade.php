@extends('layouts.app')

@section('title', 'Hubungi Saya - Kontak')

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <h5 class="hero-subtitle text-gradient">Hubungi Saya</h5>
            <h2>Kirim Pesan & Tetap Terhubung</h2>
        </div>

        <div class="contact-grid">
            <!-- Contact Details Column -->
            <div class="contact-info-list" style="text-align: left;">
                <!-- WhatsApp Card -->
                @if(\App\Models\Setting::get('social_whatsapp'))
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-brands fa-whatsapp text-gradient"></i></div>
                        <div class="contact-info-content">
                            <h4>WhatsApp</h4>
                            <p><a href="https://wa.me/{{ \App\Models\Setting::get('social_whatsapp') }}" target="_blank" class="portfolio-link">+{{ \App\Models\Setting::get('social_whatsapp') }}</a></p>
                        </div>
                    </div>
                @endif

                <!-- Email Card -->
                @if(\App\Models\Setting::get('social_email'))
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-solid fa-envelope text-gradient"></i></div>
                        <div class="contact-info-content">
                            <h4>Email Profesional</h4>
                            <p><a href="mailto:{{ \App\Models\Setting::get('social_email') }}" class="portfolio-link">{{ \App\Models\Setting::get('social_email') }}</a></p>
                        </div>
                    </div>
                @endif

                <!-- LinkedIn Card -->
                @if(\App\Models\Setting::get('social_linkedin'))
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-brands fa-linkedin text-gradient"></i></div>
                        <div class="contact-info-content">
                            <h4>LinkedIn</h4>
                            <p><a href="{{ \App\Models\Setting::get('social_linkedin') }}" target="_blank" class="portfolio-link">Lihat Profil LinkedIn</a></p>
                        </div>
                    </div>
                @endif

                <!-- GitHub Card -->
                @if(\App\Models\Setting::get('social_github'))
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-brands fa-github text-gradient"></i></div>
                        <div class="contact-info-content">
                            <h4>GitHub</h4>
                            <p><a href="{{ \App\Models\Setting::get('social_github') }}" target="_blank" class="portfolio-link">Lihat Repositori</a></p>
                        </div>
                    </div>
                @endif

                <!-- Instagram Card -->
                @if(\App\Models\Setting::get('social_instagram'))
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="fa-brands fa-instagram text-gradient"></i></div>
                        <div class="contact-info-content">
                            <h4>Instagram</h4>
                            <p><a href="{{ \App\Models\Setting::get('social_instagram') }}" target="_blank" class="portfolio-link">Ikuti di Instagram</a></p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Contact Form Card -->
            <div class="contact-form-card" style="text-align: left;">
                <h3 style="font-size: 1.3rem; margin-bottom: 24px;">Kirim Formulir Kontak</h3>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Anda" value="{{ old('name') }}" required>
                        @error('name')
                            <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Alamat Email *</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="email@contoh.com" value="{{ old('email') }}" required>
                        @error('email')
                            <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subject">Subjek *</label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="Judul Pesan Anda" value="{{ old('subject') }}" required>
                        @error('subject')
                            <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Pesan *</label>
                        <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Tuliskan pesan lengkap Anda..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Kirim Pesan <i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
        </div>

        <!-- Google Maps Section -->
        <div class="map-container">
            <!-- Dynamic map iframe centered in Jakarta / Indonesia. Looks highly professional! -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126920.24083758362!2d106.78919011782299!3d-6.229746487677598!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e845e28275%3A0x7d6f51f49673cc6e!2sJakarta%20Pusat%2C%20Kota%20Jakarta%20Pusat%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
@endsection
