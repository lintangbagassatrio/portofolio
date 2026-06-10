@extends('layouts.admin')

@section('title', 'Kelola Profil')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Kelola Profil & CV</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Perbarui biodata diri, riwayat hidup singkat, foto profil, dan berkas CV Anda.</p>
    </div>
</div>

<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="admin-form-split">
        <!-- Left Side: Text Fields & Bios -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-id-card text-gradient"></i> Informasi Dasar</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="full_name">Nama Lengkap *</label>
                        <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name', $profile->full_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="birth_place_date">Tempat, Tanggal Lahir</label>
                        <input type="text" name="birth_place_date" id="birth_place_date" class="form-control" placeholder="Contoh: Jakarta, 15 Mei 1999" value="{{ old('birth_place_date', $profile->birth_place_date) }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="education">Pendidikan / Gelar</label>
                        <input type="text" name="education" id="education" class="form-control" placeholder="Contoh: S1 Teknik Informatika" value="{{ old('education', $profile->education) }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat Tempat Tinggal</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="Contoh: Jakarta, Indonesia" value="{{ old('address', $profile->address) }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                    <div class="form-group">
                        <label for="email">Email Kontak</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $profile->email) }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Contoh: +62 812-3456-7890" value="{{ old('phone', $profile->phone) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label for="bio">Deskripsi Singkat Profesional *</label>
                    <textarea name="bio" id="bio" rows="4" class="form-control" placeholder="Jelaskan mengenai keahlian utama dan fokus profesional Anda..." required>{{ old('bio', $profile->bio) }}</textarea>
                </div>
            </div>

            <!-- Detailed Biography Columns -->
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-user-tie text-gradient"></i> Informasi Biografi & Karir</h3>
                
                <div class="form-group">
                    <label for="background">Latar Belakang Pendidikan & Awal Mula Belajar</label>
                    <textarea name="background" id="background" rows="4" class="form-control" placeholder="Tulis latar belakang Anda...">{{ old('background', $profile->background) }}</textarea>
                </div>

                <div class="form-group" style="margin-top: 16px;">
                    <label for="career">Perjalanan Karir Professional</label>
                    <textarea name="career" id="career" rows="4" class="form-control" placeholder="Tulis rincian karir Anda...">{{ old('career', $profile->career) }}</textarea>
                </div>

                <div class="form-group" style="margin-top: 16px;">
                    <label for="interests">Minat & Bidang Ketertarikan Khusus</label>
                    <textarea name="interests" id="interests" rows="3" class="form-control" placeholder="Tulis bidang yang menarik minat Anda...">{{ old('interests', $profile->interests) }}</textarea>
                </div>

                <div class="form-group" style="margin-top: 16px;">
                    <label for="goals">Tujuan Profesional Masa Depan</label>
                    <textarea name="goals" id="goals" rows="3" class="form-control" placeholder="Tulis resolusi/target karir Anda ke depan...">{{ old('goals', $profile->goals) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Side: Files Upload & Actions -->
        <div style="display: flex; flex-direction: column; gap: 24px; position: sticky; top: 100px;">
            <!-- Actions Card -->
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 16px;">Tindakan</h3>
                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
            </div>

            <!-- Upload Photo Card -->
            <div class="admin-card" style="margin-bottom: 0; text-align: center;">
                <h3 style="margin-bottom: 16px; text-align: left;"><i class="fa-solid fa-image text-gradient"></i> Foto Profil</h3>
                
                <div class="image-preview-box" style="margin: 0 auto 16px auto; width: 150px; height: 150px; border-radius: 50%;">
                    @if($profile->photo)
                        <img id="photoPreview" src="{{ asset('storage/' . $profile->photo) }}" alt="Preview" style="display: block;">
                    @else
                        <img id="photoPreview" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150&h=150" alt="Preview Default" style="display: block;">
                    @endif
                </div>
                
                <div class="form-group">
                    <input type="file" name="photo" id="photoInput" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">Maksimal file 2MB (JPG, PNG, WebP)</small>
                </div>
            </div>

            <!-- Upload CV Card -->
            <div class="admin-card" style="margin-bottom: 0;">
                <h3 style="margin-bottom: 16px;"><i class="fa-solid fa-file-pdf text-gradient"></i> Berkas CV (Curriculum Vitae)</h3>
                
                @if($profile->cv_file)
                    <div style="display: flex; align-items: center; gap: 10px; background-color: var(--bg-tertiary); padding: 12px; border-radius: var(--border-radius-sm); margin-bottom: 16px;">
                        <i class="fa-solid fa-file-pdf" style="font-size: 1.5rem; color: #ef4444;"></i>
                        <div style="text-align: left; flex-grow: 1; min-width: 0;">
                            <div style="font-size: 0.85rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">CV Aktif</div>
                            <a href="{{ asset('storage/' . $profile->cv_file) }}" target="_blank" style="font-size: 0.75rem; color: var(--accent); font-weight: 500;">Lihat/Unduh Dokumen</a>
                        </div>
                    </div>
                @endif
                
                <div class="form-group">
                    <input type="file" name="cv_file" id="cvInput" class="form-control" accept="application/pdf">
                    <small style="color: var(--text-secondary); margin-top: 4px; display: block;">Format PDF saja, Maksimal 5MB.</small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize the photo preview
        initImagePreview('photoInput', 'photoPreview');
    });
</script>
@endsection
