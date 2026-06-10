@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Pengaturan Website</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Kelola identitas website, optimasi SEO meta tag, integrasi analitik, dan tautan sosial media Anda.</p>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="admin-form-split" style="grid-template-columns: 1.3fr 0.7fr;">
        <!-- Left Column: Forms -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- General Settings -->
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-circle-info text-gradient"></i> Identitas & Konfigurasi Dasar</h3>
                
                <div class="form-group">
                    <label for="site_name">Nama Website / Portofolio *</label>
                    <input type="text" name="site_name" id="site_name" class="form-control" value="{{ old('site_name', $settings['site_name']) }}" required>
                </div>
                
                <div class="form-group" style="margin-top: 16px;">
                    <label for="footer_text">Teks Hak Cipta di Footer (Footer Text)</label>
                    <input type="text" name="footer_text" id="footer_text" class="form-control" value="{{ old('footer_text', $settings['footer_text']) }}">
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-magnifying-glass text-gradient"></i> Optimasi SEO & Meta Tag</h3>
                
                <div class="form-group">
                    <label for="meta_title">Meta Title Tag SEO (Optimal 60 Karakter)</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $settings['meta_title']) }}">
                </div>
                
                <div class="form-group" style="margin-top: 16px;">
                    <label for="meta_keywords">Meta Keywords SEO (Pisahkan dengan koma)</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="Contoh: portfolio, developer, laravel" value="{{ old('meta_keywords', $settings['meta_keywords']) }}">
                </div>

                <div class="form-group" style="margin-top: 16px;">
                    <label for="meta_description">Meta Description SEO (Maksimal 160 Karakter)</label>
                    <textarea name="meta_description" id="meta_description" rows="3" class="form-control" placeholder="Tulis ringkasan website Anda untuk mesin pencari...">{{ old('meta_description', $settings['meta_description']) }}</textarea>
                </div>
            </div>

            <!-- Social Media Settings -->
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-share-nodes text-gradient"></i> Sosial Media & Kontak</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="social_whatsapp"><i class="fa-brands fa-whatsapp" style="color: #25d366;"></i> WhatsApp (Format: 628xx...)</label>
                        <input type="text" name="social_whatsapp" id="social_whatsapp" class="form-control" placeholder="Contoh: 628123456789" value="{{ old('social_whatsapp', $settings['social_whatsapp']) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="social_email"><i class="fa-solid fa-envelope" style="color: #ea4335;"></i> Email Publik</label>
                        <input type="email" name="social_email" id="social_email" class="form-control" placeholder="Contoh: nama@email.com" value="{{ old('social_email', $settings['social_email']) }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="social_linkedin"><i class="fa-brands fa-linkedin" style="color: #0077b5;"></i> URL LinkedIn</label>
                        <input type="url" name="social_linkedin" id="social_linkedin" class="form-control" placeholder="https://linkedin.com/in/username" value="{{ old('social_linkedin', $settings['social_linkedin']) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="social_github"><i class="fa-brands fa-github" style="color: var(--text-primary);"></i> URL GitHub</label>
                        <input type="url" name="social_github" id="social_github" class="form-control" placeholder="https://github.com/username" value="{{ old('social_github', $settings['social_github']) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 16px; width: 50%;">
                    <label for="social_instagram"><i class="fa-brands fa-instagram" style="color: #e1306c;"></i> URL Instagram</label>
                    <input type="url" name="social_instagram" id="social_instagram" class="form-control" placeholder="https://instagram.com/username" value="{{ old('social_instagram', $settings['social_instagram']) }}">
                </div>
            </div>

            <!-- Third-Party Integration -->
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-chart-line text-gradient"></i> Integrasi Google Analytics</h3>
                
                <div class="form-group">
                    <label for="google_analytics">Google Analytics Tracking Script (HTML Script Tag)</label>
                    <textarea name="google_analytics" id="google_analytics" rows="5" class="form-control" placeholder="Tempel script analytics global (gtag.js) Anda di sini...">{{ old('google_analytics', $settings['google_analytics']) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Media Uploads & Save -->
        <div style="display: flex; flex-direction: column; gap: 24px; position: sticky; top: 100px;">
            <!-- Actions Card -->
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 16px;">Simpan</h3>
                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan</button>
            </div>

            <!-- Logo Upload -->
            <div class="admin-card" style="margin-bottom: 0; text-align: center;">
                <h3 style="margin-bottom: 16px; text-align: left;"><i class="fa-solid fa-circle-user text-gradient"></i> Logo Website</h3>
                
                <div class="image-preview-box" id="logoPreviewContainer" style="margin: 0 auto 16px auto; width: 100%; height: 80px;">
                    @if($settings['site_logo'])
                        <img id="logoPreview" src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Preview" style="display: block; object-fit: contain;">
                    @else
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2rem; color: var(--border-color);"></i>
                        <img id="logoPreview" src="" alt="Preview" style="display: none; object-fit: contain;">
                    @endif
                </div>
                
                <div class="form-group">
                    <input type="file" name="site_logo" id="logoInput" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp,image/svg+xml">
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">SVG/PNG/JPG maksimal 1MB</small>
                </div>
            </div>

            <!-- Favicon Upload -->
            <div class="admin-card" style="margin-bottom: 0; text-align: center;">
                <h3 style="margin-bottom: 16px; text-align: left;"><i class="fa-solid fa-shapes text-gradient"></i> Favicon (Kecil)</h3>
                
                <div class="image-preview-box" id="faviconPreviewContainer" style="margin: 0 auto 16px auto; width: 60px; height: 60px;">
                    @if($settings['site_favicon'])
                        <img id="faviconPreview" src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Preview" style="display: block;">
                    @else
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 1.5rem; color: var(--border-color);"></i>
                        <img id="faviconPreview" src="" alt="Preview" style="display: none;">
                    @endif
                </div>
                
                <div class="form-group">
                    <input type="file" name="site_favicon" id="faviconInput" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp,image/x-icon">
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">ICO/PNG/JPG maksimal 512KB</small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        initImagePreview('logoInput', 'logoPreview');
        initImagePreview('faviconInput', 'faviconPreview');
    });
</script>
@endsection
