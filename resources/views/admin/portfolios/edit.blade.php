@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Edit Project</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Perbarui data atau detail project portofolio Anda.</p>
    </div>
    
    <a href="{{ route('admin.portfolios.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<form action="{{ route('admin.portfolios.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="admin-form-split">
        <!-- Left Column: Primary Fields -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-file-invoice text-gradient"></i> Detail Project</h3>
                
                <div class="form-group">
                    <label for="title">Judul Project *</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: E-Commerce Web Application" value="{{ old('title', $portfolio->title) }}" required>
                    @error('title')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="category_id">Kategori Project *</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $portfolio->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status Pengerjaan *</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="completed" {{ old('status', $portfolio->status) == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                            <option value="in-progress" {{ old('status', $portfolio->status) == 'in-progress' ? 'selected' : '' }}>Sedang Dikerjakan (In-Progress)</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="start_date">Tanggal Pengerjaan</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $portfolio->start_date ? $portfolio->start_date->format('Y-m-d') : '') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="technology_used">Teknologi / Stack (Pisahkan dengan koma) *</label>
                        <input type="text" name="technology_used" id="technology_used" class="form-control" placeholder="Contoh: Laravel, MySQL, AlpineJS, Tailwind" value="{{ old('technology_used', $techString) }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="demo_link">Link Demo Website (Optional)</label>
                        <input type="url" name="demo_link" id="demo_link" class="form-control" placeholder="https://demo.contoh.com" value="{{ old('demo_link', $portfolio->demo_link) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="github_link">Link Repositori GitHub (Optional)</label>
                        <input type="url" name="github_link" id="github_link" class="form-control" placeholder="https://github.com/username/project" value="{{ old('github_link', $portfolio->github_link) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label for="description">Deskripsi Lengkap Project *</label>
                    <textarea name="description" id="description" rows="8" class="form-control" placeholder="Jelaskan mengenai fitur, arsitektur, dan cara kerja project Anda..." required>{{ old('description', $portfolio->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings & Media -->
        <div style="display: flex; flex-direction: column; gap: 24px; position: sticky; top: 100px;">
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 16px;">Pengaturan & Tindakan</h3>
                
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; font-size: 0.95rem;">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" style="width: 18px; height: 18px; accent-color: var(--accent); cursor: pointer;" {{ old('is_featured', $portfolio->is_featured) ? 'checked' : '' }}>
                    <label for="is_featured" style="cursor: pointer; font-weight: 500;">Tampilkan sebagai Project Unggulan (Featured)</label>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
            </div>

            <!-- Upload Thumbnail -->
            <div class="admin-card" style="margin-bottom: 0; text-align: center;">
                <h3 style="margin-bottom: 16px; text-align: left;"><i class="fa-solid fa-image text-gradient"></i> Gambar Thumbnail</h3>
                
                <div class="image-preview-box" id="thumbnailPreviewContainer" style="margin: 0 auto 16px auto; width: 100%; height: 180px;">
                    @if($portfolio->thumbnail)
                        <img id="thumbnailPreview" src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="Preview" style="display: block; width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2.5rem; color: var(--border-color);"></i>
                        <img id="thumbnailPreview" src="" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                    @endif
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
        // Initialize the thumbnail upload preview
        initImagePreview('thumbnailInput', 'thumbnailPreview');
    });
</script>
@endsection
