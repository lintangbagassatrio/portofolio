@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Tulis Artikel Baru</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Formulir untuk mempublikasikan tulisan, panduan, atau berita teknologi baru Anda.</p>
    </div>
    
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="admin-form-split">
        <!-- Left Column: Primary Fields -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-file-signature text-gradient"></i> Konten Artikel</h3>
                
                <div class="form-group">
                    <label for="title">Judul Artikel *</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Belajar Clean Architecture dengan Laravel" value="{{ old('title') }}" required>
                    @error('title')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="category_id">Kategori Artikel *</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tags">Tag Artikel (Pisahkan dengan koma)</label>
                        <input type="text" name="tags" id="tags" class="form-control" placeholder="Contoh: laravel, php, coding" value="{{ old('tags') }}">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label for="content">Isi Konten Artikel *</label>
                    <!-- Standard textarea; can be formatted with linebreaks or markdown. A rich text area placeholder is perfect. -->
                    <textarea name="content" id="content" rows="15" class="form-control" placeholder="Tulis artikel lengkap Anda di sini..." required>{{ old('content') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings & Media -->
        <div style="display: flex; flex-direction: column; gap: 24px; position: sticky; top: 100px;">
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 16px;">Pengaturan & Publikasi</h3>
                
                <div class="form-group">
                    <label for="status">Status Penerbitan *</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Langsung Terbitkan</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;"><i class="fa-solid fa-cloud-arrow-up"></i> Simpan Artikel</button>
            </div>

            <!-- Upload Cover Image -->
            <div class="admin-card" style="margin-bottom: 0; text-align: center;">
                <h3 style="margin-bottom: 16px; text-align: left;"><i class="fa-solid fa-image text-gradient"></i> Gambar Cover</h3>
                
                <div class="image-preview-box" id="thumbnailPreviewContainer" style="margin: 0 auto 16px auto; width: 100%; height: 180px;">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2.5rem; color: var(--border-color);"></i>
                    <img id="thumbnailPreview" src="" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <div class="form-group">
                    <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">File gambar JPG, PNG, WebP maksimal 2MB</small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        initImagePreview('thumbnailInput', 'thumbnailPreview');
    });
</script>
@endsection
