@extends('layouts.admin')

@section('title', 'Tambah Pengalaman Baru')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Tambah Pengalaman Baru</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Formulir untuk mempublikasikan riwayat karir, magang, freelance, atau organisasi baru.</p>
    </div>
    
    <a href="{{ route('admin.experiences.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<form action="{{ route('admin.experiences.store') }}" method="POST">
    @csrf

    <div class="admin-form-split" style="grid-template-columns: 2fr 1fr;">
        <!-- Left Side: Fields -->
        <div class="admin-card">
            <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-timeline text-gradient"></i> Detail Pengalaman</h3>
            
            <div class="form-group">
                <label for="title">Jabatan / Posisi / Gelar *</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Senior Full-Stack Engineer" value="{{ old('title') }}" required>
                @error('title')
                    <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                <div class="form-group">
                    <label for="company">Nama Instansi / Perusahaan *</label>
                    <input type="text" name="company" id="company" class="form-control @error('company') is-invalid @enderror" placeholder="Contoh: Google Corp Indonesia" value="{{ old('company') }}" required>
                    @error('company')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="type">Kategori / Tipe Pengalaman *</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="work" {{ old('type') == 'work' ? 'selected' : '' }}>Pekerjaan (Work)</option>
                        <option value="internship" {{ old('type') == 'internship' ? 'selected' : '' }}>Magang (Internship)</option>
                        <option value="freelance" {{ old('type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="organization" {{ old('type') == 'organization' ? 'selected' : '' }}>Organisasi (Organization)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                <div class="form-group">
                    <label for="start_date">Tanggal Mulai *</label>
                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="end_date">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                    @error('end_date')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <label for="description">Keterangan / Job Description *</label>
                <textarea name="description" id="description" rows="6" class="form-control" placeholder="Jelaskan kontribusi, pencapaian, dan apa saja yang Anda kerjakan di posisi ini..." required>{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Right Side: Status and Save -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="admin-card">
                <h3 style="margin-bottom: 16px;">Status & Aksi</h3>
                
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; font-size: 0.95rem;">
                    <input type="checkbox" name="is_current" id="is_current" value="1" style="width: 18px; height: 18px; accent-color: var(--accent); cursor: pointer;" {{ old('is_current') ? 'checked' : '' }}>
                    <label for="is_current" style="cursor: pointer; font-weight: 500;">Masih Bekerja Di Sini (Current)</label>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengalaman</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const isCurrentCheckbox = document.getElementById('is_current');
        const endDateInput = document.getElementById('end_date');
        
        function toggleEndDate() {
            if (isCurrentCheckbox.checked) {
                endDateInput.disabled = true;
                endDateInput.value = '';
                endDateInput.required = false;
            } else {
                endDateInput.disabled = false;
            }
        }
        
        isCurrentCheckbox.addEventListener('change', toggleEndDate);
        toggleEndDate(); // run on load
    });
</script>
@endsection
