@extends('layouts.admin')

@section('title', 'Tambah Sertifikat')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Tambah Sertifikat Baru</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Formulir untuk mendokumentasikan sertifikasi kredensial baru.</p>
    </div>
    
    <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<form action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="admin-form-split" style="grid-template-columns: 2fr 1fr;">
        <!-- Left Column: Fields -->
        <div class="admin-card">
            <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-award text-gradient"></i> Detail Sertifikasi</h3>
            
            <div class="form-group">
                <label for="name">Nama Sertifikasi / Lisensi *</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Certified Web Developer" value="{{ old('name') }}" required>
                @error('name')
                    <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                <div class="form-group">
                    <label for="publisher">Penerbit (Publisher) *</label>
                    <input type="text" name="publisher" id="publisher" class="form-control @error('publisher') is-invalid @enderror" placeholder="Contoh: Cisco, Dicoding, Google" value="{{ old('publisher') }}" required>
                    @error('publisher')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="year">Tahun Perolehan *</label>
                    <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror" placeholder="Contoh: 2025" min="2000" max="{{ date('Y') + 2 }}" value="{{ old('year', date('Y')) }}" required>
                    @error('year')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <label for="description">Deskripsi Singkat / Kompetensi Kunci</label>
                <textarea name="description" id="description" rows="5" class="form-control" placeholder="Tulis rincian singkat mengenai kemampuan utama yang diuji dalam sertifikasi ini...">{{ old('description') }}</textarea>
            </div>
            
            <!-- Submit -->
            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px;"><i class="fa-solid fa-floppy-disk"></i> Simpan Sertifikat</button>
            </div>
        </div>

        <!-- Right Column: Media Upload -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Thumbnail preview -->
            <div class="admin-card" style="text-align: center;">
                <h3 style="margin-bottom: 16px; text-align: left;"><i class="fa-solid fa-image text-gradient"></i> Gambar Thumbnail</h3>
                
                <div class="image-preview-box" id="thumbnailPreviewContainer" style="margin: 0 auto 16px auto; width: 100%; height: 160px;">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2.5rem; color: var(--border-color);"></i>
                    <img id="thumbnailPreview" src="" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <div class="form-group">
                    <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">File JPG, PNG, WebP maksimal 2MB</small>
                </div>
            </div>

            <!-- PDF Certificate File -->
            <div class="admin-card">
                <h3 style="margin-bottom: 16px;"><i class="fa-solid fa-file-pdf text-gradient"></i> File Kredensial (PDF/Image)</h3>
                
                <div class="form-group">
                    <input type="file" name="file_path" class="form-control" accept="application/pdf,image/jpg,image/jpeg,image/png">
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">Format PDF atau Gambar, maksimal 5MB.</small>
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
